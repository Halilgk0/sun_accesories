<?php

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/** @return array<string, string> */
function validOrderPayload(array $overrides = []): array
{
    return array_merge([
        'customer_name' => 'Defne Güneş',
        'email' => 'defne@ornek.com',
        'phone' => '0532 111 22 33',
        'city' => 'İzmir',
        'district' => 'Konak',
        'address' => 'Alsancak Mah. Papatya Sok. No:7 D:3',
        'payment_method' => 'kredi_karti',
        'card_name' => 'Defne Gunes',
        'card_number' => '4242 4242 4242 4242',
        'card_expiry' => '04/29',
        'card_cvv' => '123',
        'terms' => '1',
    ], $overrides);
}

it('sends a shopper with an empty basket back to the cart', function () {
    $this->get(route('checkout.index'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('error');
});

it('shows the checkout form once the basket has something in it', function () {
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->get(route('checkout.index'))
        ->assertOk()
        ->assertSee('Teslimat bilgileri');
});

it('creates an order and empties the basket', function () {
    $product = Product::factory()->create(['price' => 1150, 'stock' => 10]);
    $this->post(route('cart.store', $product), ['quantity' => 2]);

    $this->post(route('checkout.store'), validOrderPayload())->assertRedirect();

    $order = Order::firstOrFail();

    expect($order->customer_name)->toBe('Defne Güneş')
        ->and((float) $order->subtotal)->toBe(2300.0)
        ->and((float) $order->shipping_fee)->toBe(0.0)
        ->and((float) $order->total)->toBe(2300.0)
        ->and($order->items)->toHaveCount(1)
        ->and($order->items->first()->quantity)->toBe(2)
        ->and(CartItem::count())->toBe(0);
});

it('stores only the last four digits of the card', function () {
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->post(route('checkout.store'), validOrderPayload());

    expect(Order::firstOrFail()->card_last_four)->toBe('4242');
});

it('keeps no card digits for a bank transfer order', function () {
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->post(route('checkout.store'), validOrderPayload([
        'payment_method' => 'havale',
        'card_name' => null,
        'card_number' => null,
        'card_expiry' => null,
        'card_cvv' => null,
    ]))->assertRedirect();

    expect(Order::firstOrFail()->card_last_four)->toBeNull();
});

it('requires the distance selling agreement', function () {
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->post(route('checkout.store'), validOrderPayload(['terms' => null]))
        ->assertSessionHasErrors('terms');

    expect(Order::count())->toBe(0);
});

it('requires card details when paying by card', function () {
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->post(route('checkout.store'), validOrderPayload([
        'card_number' => null,
        'card_expiry' => null,
        'card_cvv' => null,
    ]))->assertSessionHasErrors(['card_number', 'card_expiry', 'card_cvv']);
});

it('attaches the order to the signed-in shopper', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->post(route('checkout.store'), validOrderPayload());

    expect(Order::firstOrFail()->user_id)->toBe($user->id);
});

it('hides another shopper order confirmation', function () {
    $this->actingAs(User::factory()->create());
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));
    $this->post(route('checkout.store'), validOrderPayload());
    $order = Order::firstOrFail();

    $this->actingAs(User::factory()->create())
        ->get(route('checkout.success', $order))
        ->assertForbidden();
});

it('charges shipping when the basket is below the free threshold', function () {
    $product = Product::factory()->create(['price' => 649, 'stock' => 10]);
    $this->post(route('cart.store', $product));

    $this->post(route('checkout.store'), validOrderPayload());

    expect((float) Order::firstOrFail()->shipping_fee)->toBe(79.90);
});
