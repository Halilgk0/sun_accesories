<?php

use App\Models\Product;

/**
 * This site shows the collection and hands the visitor to the atelier; it does
 * not take orders. These tests hold that line, so a checkout cannot creep back
 * in without someone deciding to put it there.
 */
it('serves every page of the site', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    foreach (['home', 'products.index', 'about', 'contact'] as $name) {
        $this->get(route($name))->assertOk();
    }

    $this->get(route('products.show', 'ornek-parca'))->assertOk();
});

it('has no basket, checkout, account or sign-in routes', function () {
    foreach (['/sepet', '/odeme', '/hesabim', '/giris', '/kayit', '/siparis/GN-000000-AAAAA'] as $path) {
        $this->get($path)->assertNotFound();
    }
});

it('points a product at the atelier instead of a basket', function () {
    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'stock' => 5]);

    $response = $this->get(route('products.show', $product))->assertOk();

    $response->assertSee(__('shop.product.enquire_cta'));
    $response->assertSee(route('contact', ['urun' => $product->translated('name')]));
    $response->assertDontSee('name="quantity"', escape: false);
    $response->assertDontSee('<form method="POST" action="'.url('/sepet'), escape: false);
});

it('never invites a phone call', function () {
    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);

    foreach ([route('products.show', $product), route('contact'), route('home')] as $url) {
        $this->get($url)->assertOk()->assertDontSee('href="tel:', escape: false);
    }
});

it('opens WhatsApp with the piece and its link already written out', function () {
    config(['contact.whatsapp' => '+90 532 111 22 33']);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'name' => 'Lale Yüzük']);

    $content = $this->get(route('products.show', $product))->assertOk()->getContent();

    // Digits only, exactly as wa.me wants the number.
    expect($content)->toContain('https://wa.me/905321112233?text=');

    // Both the piece and the page it was seen on travel with the message.
    $expected = rawurlencode(__('shop.product.whatsapp_message', [
        'name' => 'Lale Yüzük',
        'url' => route('products.show', $product),
    ]));
    expect($content)->toContain($expected);
});

it('falls back to the contact form when no WhatsApp number is set', function () {
    config(['contact.whatsapp' => null]);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);

    $content = $this->get(route('products.show', $product))->assertOk()->getContent();

    expect($content)->not->toContain('wa.me');
    expect($content)->toContain(route('contact', ['urun' => $product->translated('name')]));
});

it('offers the atelier for a piece that is not on the bench', function () {
    $product = Product::factory()->outOfStock()->create(['slug' => 'tukenmis']);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee(__('shop.product.enquire_text_sold_out'))
        ->assertSee(__('shop.product.out_of_stock'));
});

it('carries the product name into the contact form', function () {
    Product::factory()->create(['slug' => 'papatya-kupe', 'name' => 'Papatya Küpe']);

    $this->get(route('contact', ['urun' => 'Papatya Küpe']))
        ->assertOk()
        ->assertSee('value="Papatya Küpe"', escape: false);
});

it('never leaves a raw translation key on the page', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    $urls = [
        route('home'),
        route('products.index'),
        route('products.show', 'ornek-parca'),
        route('about'),
        route('contact'),
    ];

    foreach (['tr', 'en'] as $locale) {
        $this->post(route('locale.update', $locale));

        foreach ($urls as $url) {
            $content = $this->get($url)->assertOk()->getContent();

            // A key that was never translated renders as its own dotted name.
            expect($content)->not->toMatch('/\b(site|shop|pages|account)\.[a-z_]+\.[a-z_]+/');
        }
    }
});

it('makes no promise about shipping, returns or payment', function () {
    Product::factory()->count(3)->create();

    foreach ([route('home'), route('products.index'), route('about')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        foreach (['Sepete ekle', 'Ödemeye geç', 'ücretsiz kargo', 'koşulsuz iade', 'indirim'] as $claim) {
            expect($content)->not->toContain($claim);
        }
    }
});
