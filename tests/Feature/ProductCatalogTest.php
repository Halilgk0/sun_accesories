<?php

use App\Models\Product;

it('shows featured products on the home page', function () {
    $featured = Product::factory()->create(['is_featured' => true, 'name' => 'Gün Doğumu Kolye']);
    Product::factory()->create(['is_featured' => false, 'name' => 'Gizli Parça']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($featured->name)
        ->assertDontSee('Gizli Parça');
});

it('lists every product in the collection', function () {
    $products = Product::factory()->count(5)->create();

    $response = $this->get(route('products.index'))->assertOk();

    foreach ($products as $product) {
        $response->assertSee($product->name);
    }
});

it('filters the collection by category', function () {
    $ring = Product::factory()->create(['category' => 'ring', 'name' => 'Lale Yüzük']);
    Product::factory()->create(['category' => 'necklace', 'name' => 'Güneş Kolye']);

    $this->get(route('products.index', ['kategori' => 'ring']))
        ->assertOk()
        ->assertSee($ring->name)
        ->assertDontSee('Güneş Kolye');
});

it('searches products by name', function () {
    Product::factory()->create(['name' => 'Papatya Küpe']);
    Product::factory()->create(['name' => 'Kelebek Halhal']);

    $this->get(route('products.index', ['arama' => 'Papatya']))
        ->assertOk()
        ->assertSee('Papatya Küpe')
        ->assertDontSee('Kelebek Halhal');
});

it('sorts products from the cheapest to the most expensive', function () {
    Product::factory()->create(['name' => 'Pahalı', 'price' => 1900]);
    Product::factory()->create(['name' => 'Ucuz', 'price' => 400]);

    $response = $this->get(route('products.index', ['sirala' => 'artan']))->assertOk();

    expect(strpos($response->getContent(), 'Ucuz'))
        ->toBeLessThan(strpos($response->getContent(), 'Pahalı'));
});

it('resolves a product page by its slug', function () {
    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'name' => 'Lale Yüzük']);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($product->material);
});

it('returns 404 for an unknown product slug', function () {
    $this->get('/urun/olmayan-taki')->assertNotFound();
});
