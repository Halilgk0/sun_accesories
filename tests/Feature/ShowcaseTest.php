<?php

use App\Models\Product;
use App\Support\Instagram;

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

it('accepts no submissions from a visitor but the language switch', function () {
    // The catalogue editor posts, of course, but it sits behind a password on
    // a path no visitor is given. This is about what the shop itself asks for.
    $posts = collect(app('router')->getRoutes())
        ->filter(fn ($route) => in_array('POST', $route->methods(), true))
        ->map->getName()
        ->reject(fn (?string $name) => str_starts_with((string) $name, 'admin.'))
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

it('reads the account however it was written down', function () {
    // Whoever sets this is likely to paste a handle or the profile URL, and
    // Instagram's own share link carries tracking on the end of it.
    foreach ([
        'sun_accessoriess',
        '@sun_accessoriess',
        'https://www.instagram.com/sun_accessoriess',
        'https://www.instagram.com/sun_accessoriess?utm_source=ig_web_button_share_sheet&stkn=abc',
    ] as $written) {
        config(['contact.instagram' => $written]);

        expect(Instagram::username())->toBe('sun_accessoriess');
        expect(Instagram::dmLink())->toBe('https://ig.me/m/sun_accessoriess');
        expect(Instagram::handle())->toBe('@sun_accessoriess');
    }

    config(['contact.instagram' => null]);
    expect(Instagram::dmLink())->toBeNull();
});

it('prints the handle in the footer', function () {
    config(['contact.instagram' => 'sun_accessoriess']);

    $this->get(route('home'))->assertOk()->assertSee('@sun_accessoriess');
});

it('points a product at Instagram instead of a basket', function () {
    config(['contact.instagram' => 'sun_accessoriess']);

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

it('opens the Instagram chat and hands over the piece to paste', function () {
    config(['contact.instagram' => 'sun_accessoriess']);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk', 'name' => 'Lale Yüzük']);

    $content = $this->get(route('products.show', $product))->assertOk()->getContent();

    expect($content)->toContain('https://ig.me/m/sun_accessoriess');

    // Instagram cannot be given the message, so it rides on the element the
    // click copies from, naming the piece and linking to the page.
    $expected = e(__('shop.product.enquiry_message', [
        'name' => 'Lale Yüzük',
        'url' => route('products.show', $product),
    ]));
    expect($content)->toContain('data-copy="'.$expected.'"');
});

it('offers the shop page as well as the inbox', function () {
    config(['contact.instagram' => 'sun_accessoriess']);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);

    // Writing and looking round are different intentions, so each gets a link.
    foreach ([route('products.show', $product), route('home')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        expect($content)->toContain('https://ig.me/m/sun_accessoriess');
        expect($content)->toContain('https://www.instagram.com/sun_accessoriess');
    }
});

it('offers Instagram from the header and the footer too', function () {
    config(['contact.instagram' => 'sun_accessoriess']);

    $content = $this->get(route('home'))->assertOk()->getContent();

    expect(substr_count($content, 'https://ig.me/m/sun_accessoriess'))->toBeGreaterThan(1);
});

it('falls back to the atelier when no account is set', function () {
    config(['contact.instagram' => null]);

    $product = Product::factory()->create(['slug' => 'lale-yuzuk']);

    // A missing account must never leave a dead button behind.
    foreach ([route('products.show', $product), route('home')] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        expect($content)->not->toContain('ig.me');
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

it('keeps the questions on the atelier page', function () {
    // They moved here when the contact page went; the address and opening
    // hours that came with them have since been taken off the site.
    $this->get(route('about'))
        ->assertOk()
        ->assertSee(__('pages.about.faq_title'))
        ->assertSee(__('pages.about.faq_6_q'));
});

it('writes prices the way each language writes numbers', function () {
    // The price stays in lira either way, but 1.150,00 reads as a fraction to
    // someone on the English pages.
    Product::factory()->create(['slug' => 'lale-yuzuk', 'price' => 1150, 'compare_at_price' => null]);

    $this->post(route('locale.update', 'tr'));
    $this->get(route('products.show', 'lale-yuzuk'))->assertOk()->assertSee('1.150,00 ₺');

    $this->post(route('locale.update', 'en'));
    $this->get(route('products.show', 'lale-yuzuk'))->assertOk()->assertSee('1,150.00 ₺');
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

it('says nowhere where the atelier is', function () {
    Product::factory()->create(['slug' => 'ornek-parca']);

    // The workshop is not a shop to walk into, and the address was a real
    // one. Nothing on the site should place it on a map.
    foreach (['tr', 'en'] as $locale) {
        $this->post(route('locale.update', $locale));

        foreach ([route('home'), route('products.index'), route('products.show', 'ornek-parca'), route('about')] as $url) {
            $content = $this->get($url)->assertOk()->getContent();

            foreach (['Alsancak', 'Papatya Sok', 'Konak', 'İzmir', 'Izmir'] as $place) {
                expect($content)->not->toContain($place);
            }
        }
    }
});

it('invites nobody to visit', function () {
    // An invitation without an address only prompts the question it cannot
    // answer, so the whole framing went with it.
    $content = $this->get(route('about'))->assertOk()->getContent();

    foreach (['Randevu al', 'Çalışma saatleri', 'kapımız açık', 'Book a visit', 'Opening hours'] as $phrase) {
        expect($content)->not->toContain($phrase);
    }
});
