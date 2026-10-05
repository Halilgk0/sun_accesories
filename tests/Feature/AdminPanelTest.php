<?php

use App\Http\Middleware\RequireAdminPassword;
use App\Models\Product;

/**
 * The catalogue editor. It is hidden behind an unguessable path and a
 * password, and it is the one place on the site that writes anything, so
 * these tests cover both who may reach it and what it does once reached.
 */
/**
 * The path is registered as routes are built, long before a test runs, so it
 * comes from phpunit.xml rather than being set here.
 */
function adminUrl(string $path = ''): string
{
    return rtrim('/'.config('admin.path').'/'.ltrim($path, '/'), '/');
}

function signedIn(): void
{
    test()->withSession([RequireAdminPassword::SESSION_KEY => true]);
}

it('is not linked from anywhere a visitor can see', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    foreach ([route('home'), route('products.index'), route('products.show', 'ornek-parca'), route('about')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        expect($content)->not->toContain(config('admin.path'));
    }
});

it('keeps the editor shut until the password is given', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    foreach ([
        ['get', adminUrl()],
        ['get', adminUrl('yeni')],
        ['get', adminUrl('ornek-parca/duzenle')],
    ] as [$method, $url]) {
        $this->{$method}($url)->assertRedirect(adminUrl('giris'));
    }
});

it('refuses a wrong password and accepts the right one', function () {
    $this->post(adminUrl('giris'), ['password' => 'tahmin'])
        ->assertSessionHasErrors('password');

    $this->post(adminUrl('giris'), ['password' => 'cok-gizli-sifre'])
        ->assertRedirect(adminUrl());

    $this->get(adminUrl())->assertOk();
});

it('stays shut when no password is configured', function () {
    // A deployment that forgot the password must lock, not stand open.
    config(['admin.password' => null]);

    $this->post(adminUrl('giris'), ['password' => ''])->assertSessionHasErrors('password');
    $this->post(adminUrl('giris'), ['password' => 'anything'])->assertSessionHasErrors('password');

    $this->get(adminUrl())->assertRedirect(adminUrl('giris'));
});

it('creates a piece and puts it straight on the site', function () {
    signedIn();

    $this->post(adminUrl(), [
        'name' => 'Menekşe Broş',
        'slug' => 'menekse-bros',
        'category' => 'ring',
        'tagline' => 'Yakaya bir bahar',
        'description' => 'Elde boyanmış mine yapraklar ve altın gövde.',
        'price' => '1250.00',
        'image_path' => 'images/products/lale-yuzuk.jpg',
        'material' => '18 ayar altın kaplama',
        'stone' => 'Ametist',
        'color_hex' => '#7B4FA0',
        'stock' => 3,
        'rating' => 4.8,
        'review_count' => 12,
    ])->assertRedirect(adminUrl());

    $product = Product::where('slug', 'menekse-bros')->firstOrFail();
    expect($product->name)->toBe('Menekşe Broş');

    $this->get(route('products.show', $product))->assertOk()->assertSee('Menekşe Broş');
});

it('edits a piece', function () {
    signedIn();
    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'name' => 'Lale Yüzük', 'price' => 1000]);

    $this->put(adminUrl($product->slug), [
        ...$product->only(['slug', 'category', 'tagline', 'description', 'image_path', 'material', 'stone', 'color_hex', 'stock', 'rating', 'review_count']),
        'name' => 'Lale Yüzük — yeni',
        'price' => '1399.50',
    ])->assertRedirect(adminUrl());

    expect($product->fresh()->name)->toBe('Lale Yüzük — yeni')
        ->and((float) $product->fresh()->price)->toBe(1399.50);
});

it('deletes a piece', function () {
    signedIn();
    $product = Product::factory()->create(['slug' => 'gider']);

    $this->delete(adminUrl($product->slug))->assertRedirect(adminUrl());

    expect(Product::where('slug', 'gider')->exists())->toBeFalse();
    $this->get('/urun/gider')->assertNotFound();
});

it('will not let one piece take another address', function () {
    signedIn();
    Product::factory()->create(['slug' => 'alinmis']);
    $product = Product::factory()->create(['slug' => 'benim']);

    $this->put(adminUrl($product->slug), [...$product->toArray(), 'slug' => 'alinmis'])
        ->assertSessionHasErrors('slug');

    // Its own address is not a clash with itself.
    $this->put(adminUrl($product->slug), [...$product->toArray(), 'slug' => 'benim'])
        ->assertSessionHasNoErrors();
});

it('rejects values the shop pages could not render', function () {
    signedIn();
    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);
    $valid = $product->toArray();

    foreach ([
        ['slug' => 'Büyük Harf Ve Boşluk'],
        ['category' => 'tiara'],
        ['badge' => 'uydurma'],
        ['color_hex' => 'turuncu'],
        ['price' => -5],
        ['rating' => 9],
        ['compare_at_price' => 1],
    ] as $bad) {
        $this->put(adminUrl($product->slug), [...$valid, 'price' => 100, ...$bad])
            ->assertSessionHasErrors(array_key_first($bad));
    }
});

it('does not exist at all when no path is configured', function () {
    // Routes are registered at boot, so this proves the guard in the route
    // file rather than the behaviour of a request.
    expect(filled(config('admin.path')))->toBeTrue();

    config(['admin.path' => null]);
    expect(filled(config('admin.path')))->toBeFalse();
});

it('puts the sample pieces back on an empty catalogue', function () {
    signedIn();

    expect(Product::query()->count())->toBe(0);
    $this->get(adminUrl())->assertOk()->assertSee(__('admin.seed_samples'));

    $this->post(adminUrl('kurulum'))->assertRedirect(adminUrl());

    expect(Product::query()->count())->toBeGreaterThan(0);
});

it('does not hand the setup step to a stranger', function () {
    $this->post(adminUrl('kurulum'))->assertRedirect(adminUrl('giris'));

    expect(Product::query()->count())->toBe(0);
});

it('renders every page of the editor', function () {
    signedIn();
    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'name' => 'Lale Yüzük']);

    // The forms were only ever exercised by posting to them, so a page that
    // threw while rendering went unnoticed until it was live.
    $this->get(adminUrl())->assertOk()->assertSee('Lale Yüzük');
    $this->get(adminUrl('yeni'))->assertOk()->assertSee(__('admin.form_new'));
    $this->get(adminUrl($product->slug.'/duzenle'))->assertOk()->assertSee($product->name);
});

it('offers the photographs that ship with the site', function () {
    signedIn();

    $content = $this->get(adminUrl('yeni'))->assertOk()->getContent();

    expect($content)->toContain('images/products/lale-yuzuk.jpg');
});
