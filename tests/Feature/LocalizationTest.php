<?php

use App\Models\Product;

it('serves the store in Turkish by default', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Koleksiyon')
        ->assertSee('Anasayfa');
});

it('lets a visitor switch to English without signing in', function () {
    $this->post(route('locale.update', 'en'))->assertRedirect();

    $this->assertGuest();
    expect(session('locale'))->toBe('en');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Collection')
        ->assertSee('Home');
});

it('switches back to Turkish', function () {
    $this->post(route('locale.update', 'en'));
    $this->post(route('locale.update', 'tr'));

    $this->get(route('home'))->assertOk()->assertSee('Koleksiyon');
});

it('rejects a language it does not serve', function () {
    $this->post(route('locale.update', 'de'))->assertNotFound();

    expect(session('locale'))->toBeNull();
});

it('shows English product copy once English is picked', function () {
    $product = Product::factory()->create([
        'slug' => 'test-parca',
        'name' => 'Lale Yüzük',
        'name_en' => 'Tulip Ring',
        'description' => 'Türkçe açıklama',
        'description_en' => 'English description',
    ]);

    $this->post(route('locale.update', 'en'));

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Tulip Ring')
        ->assertSee('English description')
        ->assertDontSee('Türkçe açıklama');
});

it('falls back to the Turkish copy when a translation is missing', function () {
    $product = Product::factory()->create([
        'slug' => 'ceviri-yok',
        'name' => 'Çevirisiz Parça',
        'name_en' => null,
    ]);

    $this->post(route('locale.update', 'en'));

    $this->get(route('products.show', $product))->assertOk()->assertSee('Çevirisiz Parça');
});

it('translates category labels', function () {
    Product::factory()->create(['category' => 'ring', 'name' => 'Lale Yüzük']);

    $this->get(route('products.index', ['kategori' => 'ring']))->assertOk()->assertSee('Yüzük');

    $this->post(route('locale.update', 'en'));

    $this->get(route('products.index', ['kategori' => 'ring']))->assertOk()->assertSee('Rings');
});

it('finds a product by its English name while browsing in English', function () {
    Product::factory()->create(['name' => 'Papatya Küpe', 'name_en' => 'Daisy Studs']);
    Product::factory()->create(['name' => 'Kelebek Halhal', 'name_en' => 'Butterfly Anklet']);

    $this->post(route('locale.update', 'en'));

    $this->get(route('products.index', ['arama' => 'Daisy']))
        ->assertOk()
        ->assertSee('Daisy Studs')
        ->assertDontSee('Butterfly Anklet');
});

it('keeps seasonal copy out of the store', function () {
    Product::factory()->count(3)->create();

    foreach ([route('home'), route('products.index'), route('about')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        foreach (['Bahar', 'bahar', 'Hıdırellez', 'ilkbahar'] as $seasonalWord) {
            expect($content)->not->toContain($seasonalWord);
        }
    }
});
