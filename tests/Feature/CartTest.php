<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;

it('adds a product to a guest basket', function () {
    $product = Product::factory()->create(['stock' => 5]);

    $this->post(route('cart.store', $product))
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(CartItem::where('product_id', $product->id)->sum('quantity'))->toBe(1);
});

it('adds the requested quantity', function () {
    $product = Product::factory()->create(['stock' => 10]);

    $this->post(route('cart.store', $product), ['quantity' => 3]);

    expect(CartItem::where('product_id', $product->id)->value('quantity'))->toBe(3);
});

it('stacks repeat additions onto the same basket line', function () {
    $product = Product::factory()->create(['stock' => 10]);

    $this->post(route('cart.store', $product), ['quantity' => 2]);
    $this->post(route('cart.store', $product), ['quantity' => 3]);

    expect(CartItem::where('product_id', $product->id)->count())->toBe(1)
        ->and(CartItem::where('product_id', $product->id)->value('quantity'))->toBe(5);
});

it('refuses to add a product that is out of stock', function () {
    $product = Product::factory()->outOfStock()->create();

    $this->post(route('cart.store', $product))->assertSessionHas('error');

    expect(CartItem::count())->toBe(0);
});

it('updates the quantity of a basket line', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $this->post(route('cart.store', $product));
    $item = CartItem::first();

    $this->patch(route('cart.update', $item), ['quantity' => 4]);

    expect($item->fresh()->quantity)->toBe(4);
});

it('removes the line when the quantity drops to zero', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $this->post(route('cart.store', $product));
    $item = CartItem::first();

    $this->patch(route('cart.update', $item), ['quantity' => 0]);

    expect(CartItem::count())->toBe(0);
});

it('empties the whole basket', function () {
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));
    $this->post(route('cart.store', Product::factory()->create(['stock' => 5])));

    $this->delete(route('cart.clear'));

    expect(CartItem::count())->toBe(0);
});

it('will not let a shopper change someone else basket line', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $stranger = CartItem::create([
        'user_id' => User::factory()->create()->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $this->patch(route('cart.update', $stranger), ['quantity' => 9])->assertForbidden();

    expect($stranger->fresh()->quantity)->toBe(1);
});

it('charges shipping below the free threshold and drops it above', function () {
    $cart = app(CartService::class);

    $cheap = Product::factory()->create(['price' => 500, 'stock' => 10]);
    $this->post(route('cart.store', $cheap));
    expect($cart->shippingFee())->toBe(CartService::SHIPPING_FEE);

    $this->post(route('cart.store', $cheap), ['quantity' => 3]);
    expect($cart->subtotal())->toBeGreaterThanOrEqual(CartService::FREE_SHIPPING_THRESHOLD)
        ->and($cart->shippingFee())->toBe(0.0);
});

it('moves a guest basket onto the account after signing in', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $user = User::factory()->create(['password' => 'sifre1234']);

    $this->post(route('cart.store', $product), ['quantity' => 2]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'sifre1234',
    ])->assertRedirect();

    $item = CartItem::firstOrFail();
    expect($item->user_id)->toBe($user->id)
        ->and($item->session_token)->toBeNull()
        ->and($item->quantity)->toBe(2);
});

it('shows an empty basket message when there is nothing in it', function () {
    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('Sepetin henüz boş');
});
