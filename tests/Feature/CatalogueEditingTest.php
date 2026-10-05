<?php

use App\Http\Middleware\RequireAdminPassword;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

/**
 * Photographs and categories, the two things the atelier can now change
 * without a deployment.
 */
function adminPath(string $path = ''): string
{
    return rtrim('/'.config('admin.path').'/'.ltrim($path, '/'), '/');
}

function asAdmin(): void
{
    test()->withSession([RequireAdminPassword::SESSION_KEY => true]);
}

function aCategory(string $slug = 'yuzuk'): Category
{
    return Category::firstOrCreate(['slug' => $slug], ['name' => 'Yüzük', 'name_en' => 'Rings']);
}

/** The fields a product needs, so a test can vary one of them. */
function productFields(array $overrides = []): array
{
    return [
        'name' => 'Menekşe Broş',
        'slug' => 'menekse-bros',
        'category' => aCategory()->slug,
        'tagline' => 'Yakaya bir bahar',
        'description' => 'Elde boyanmış mine yapraklar.',
        'price' => '1250.00',
        'image_path' => 'images/products/lale-yuzuk.jpg',
        'material' => '18 ayar altın kaplama',
        'stone' => 'Ametist',
        'color_hex' => '#7B4FA0',
        'stock' => 3,
        'rating' => 4.8,
        'review_count' => 12,
        ...$overrides,
    ];
}

it('keeps an uploaded photograph where every instance can read it', function () {
    asAdmin();

    // The container's own filesystem is per-instance and discarded, so the
    // bytes have to land somewhere shared.
    $this->post(adminPath(), productFields([
        'image_path' => null,
        'photo' => UploadedFile::fake()->image('bros.jpg', 800, 800),
    ]))->assertRedirect(adminPath());

    $product = Product::where('slug', 'menekse-bros')->firstOrFail();
    $image = Image::firstOrFail();

    expect($product->image_path)->toBe('gorsel/'.$image->id)
        ->and($image->mime)->toStartWith('image/')
        ->and($image->decoded())->not->toBe('');
});

it('serves an uploaded photograph with a long cache life', function () {
    $image = Image::create([
        'filename' => 'bros.jpg',
        'mime' => 'image/jpeg',
        'bytes' => 3,
        'contents' => base64_encode('abc'),
    ]);

    $this->get('/'.$image->path())
        ->assertOk()
        ->assertHeader('Content-Type', 'image/jpeg')
        ->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');
});

it('keeps the old photograph when a piece is edited without a new one', function () {
    asAdmin();
    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'image_path' => 'gorsel/7']);

    $this->put(adminPath($product->slug), productFields([
        'slug' => 'lale-yuzuk',
        'image_path' => 'gorsel/7',
    ]))->assertRedirect(adminPath());

    expect($product->fresh()->image_path)->toBe('gorsel/7');
});

it('refuses anything that is not an image', function () {
    asAdmin();

    $this->post(adminPath(), productFields([
        'image_path' => null,
        'photo' => UploadedFile::fake()->create('fatura.pdf', 40, 'application/pdf'),
    ]))->assertSessionHasErrors('photo');

    expect(Image::count())->toBe(0);
});

it('adds, renames and removes a category', function () {
    asAdmin();

    $this->post(adminPath('kategoriler'), [
        'name' => 'Broş', 'name_en' => 'Brooches', 'slug' => 'bros', 'position' => 9,
    ])->assertRedirect(adminPath('kategoriler'));

    $category = Category::where('slug', 'bros')->firstOrFail();

    $this->put(adminPath('kategoriler/'.$category->slug), [
        'name' => 'Broşlar', 'name_en' => 'Brooches', 'slug' => 'bros', 'position' => 9,
    ])->assertRedirect(adminPath('kategoriler'));

    expect($category->fresh()->name)->toBe('Broşlar');

    $this->delete(adminPath('kategoriler/'.$category->slug))->assertRedirect(adminPath('kategoriler'));
    expect(Category::where('slug', 'bros')->exists())->toBeFalse();
});

it('carries the products along when a category is given a new address', function () {
    asAdmin();
    $category = aCategory('yuzuk');
    $product = Product::factory()->create(['category' => 'yuzuk']);

    $this->put(adminPath('kategoriler/'.$category->slug), [
        'name' => 'Yüzük', 'name_en' => 'Rings', 'slug' => 'yuzukler', 'position' => 0,
    ])->assertRedirect(adminPath('kategoriler'));

    // Otherwise the piece would point at a category that no longer exists.
    expect($product->fresh()->category)->toBe('yuzukler');
});

it('will not delete a category that still holds pieces', function () {
    asAdmin();
    $category = aCategory('yuzuk');
    Product::factory()->create(['category' => 'yuzuk']);

    $this->delete(adminPath('kategoriler/'.$category->slug))
        ->assertSessionHasErrors('category');

    expect(Category::where('slug', 'yuzuk')->exists())->toBeTrue();
});

it('offers only categories that exist', function () {
    asAdmin();
    aCategory();

    $this->post(adminPath(), productFields(['category' => 'uydurma']))
        ->assertSessionHasErrors('category');
});

it('shows a category on the collection page under its edited name', function () {
    $category = aCategory('yuzuk');
    Product::factory()->create(['category' => 'yuzuk']);

    $category->update(['name' => 'Yüzükler']);

    $this->get(route('products.index'))->assertOk()->assertSee('Yüzükler');
    $this->get(route('home'))->assertOk()->assertSee('Yüzükler');
});

it('can always reach the setup step, even with the schema behind', function () {
    asAdmin();

    // A migration added later leaves the catalogue unreadable; if the panel
    // only offered to fix that from a page which itself needs the new tables,
    // there would be no way back in.
    $this->get(adminPath('kurulum'))->assertOk()->assertSee(__('admin.setup_run'));
});
