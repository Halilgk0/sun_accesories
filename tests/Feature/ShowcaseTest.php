<?php

use App\Models\Product;
use App\Support\WhatsApp;

/**
 * This site shows the collection and hands the visitor to the atelier; it does
 * not take orders, and it no longer offers a form that delivers nowhere. These
 * tests hold that line, so neither a checkout nor a dead form can creep back in
 * without someone deciding to put it there.
 */
it('serves every page of the site', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    foreach (['home', 'products.index', 'about'] as $name) {
        $this->get(route($name))->assertOk();
    }

    $this->get(route('products.show', 'ornek-parca'))->assertOk();
});

it('has no basket, checkout, account, sign-in or contact routes', function () {
    foreach (['/sepet', '/odeme', '/hesabim', '/giris', '/kayit', '/siparis/GN-000000-AAAAA', '/iletisim'] as $path) {
        $this->get($path)->assertNotFound();
    }
});

it('accepts no submissions but the language switch', function () {
    $posts = collect(app('router')->getRoutes())
        ->filter(fn ($route) => in_array('POST', $route->methods(), true))
        ->map->getName()
        ->values()
        ->all();

    expect($posts)->toBe(['locale.update']);
});

it('asks the visitor for nothing, anywhere', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    // No message box, no sign-up box: the site would only be throwing whatever
    // it collected away, which is why the contact and newsletter forms went.
    foreach ([route('home'), route('products.index'), route('products.show', 'ornek-parca'), route('about')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        expect($content)->not->toContain('type="email"');
        expect($content)->not->toContain('<textarea');
        expect($content)->not->toContain('onsubmit');
    }
});

it('prints the number the way a person reads it', function () {
    config(['contact.whatsapp' => '+90 545 922 99 43']);

    $this->get(route('home'))->assertOk()->assertSee('+90 545 922 99 43');

    // A number from anywhere else is left alone rather than grouped by guesswork.
    config(['contact.whatsapp' => '44 20 7946 0000']);
    expect(WhatsApp::display())->toBe('+442079460000');

    config(['contact.whatsapp' => null]);
    expect(WhatsApp::display())->toBeNull();
});

it('points a product at WhatsApp instead of a basket', function () {
    config(['contact.whatsapp' => '+90 532 111 22 33']);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'stock' => 5]);

    $response = $this->get(route('products.show', $product))->assertOk();

    $response->assertSee(__('shop.product.enquire_cta'));
    $response->assertDontSee('name="quantity"', escape: false);
    $response->assertDontSee('<form method="POST" action="'.url('/sepet'), escape: false);
});

it('never invites a phone call', function () {
    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);

    foreach ([route('products.show', $product), route('about'), route('home')] as $url) {
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

it('offers WhatsApp from the header and the footer too', function () {
    config(['contact.whatsapp' => '+90 532 111 22 33']);

    $content = $this->get(route('home'))->assertOk()->getContent();

    expect(substr_count($content, 'https://wa.me/905321112233'))->toBeGreaterThan(1);
});

it('falls back to the atelier when no WhatsApp number is set', function () {
    config(['contact.whatsapp' => null]);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);

    // A missing number must never leave a dead button behind.
    foreach ([route('products.show', $product), route('home')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        expect($content)->not->toContain('wa.me');
        expect($content)->toContain(route('about'));
    }
});

it('offers the atelier for a piece that is not on the bench', function () {
    $product = Product::factory()->outOfStock()->create(['slug' => 'tukenmis']);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee(__('shop.product.enquire_text_sold_out'))
        ->assertSee(__('shop.product.out_of_stock'));
});

it('keeps the address, opening hours and questions on the atelier page', function () {
    // These used to live on the contact page; removing that page must not have
    // taken them with it.
    $this->get(route('about'))
        ->assertOk()
        ->assertSee(__('pages.about.address'))
        ->assertSee(__('pages.about.hours_title'))
        ->assertSee(__('pages.about.faq_title'))
        ->assertSee(__('pages.about.faq_6_q'));
});

it('never leaves a raw translation key on the page', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    $urls = [
        route('home'),
        route('products.index'),
        route('products.show', 'ornek-parca'),
        route('about'),
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
