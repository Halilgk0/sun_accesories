<?php

use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;

it('adds a product to favourites', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('favorites.store', $product))
        ->assertSessionHas('status');

    expect(Favorite::where('user_id', $user->id)->where('product_id', $product->id)->exists())->toBeTrue();
});

it('removes a product that is already favourited', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    Favorite::create(['user_id' => $user->id, 'product_id' => $product->id]);

    $this->actingAs($user)->post(route('favorites.store', $product));

    expect(Favorite::count())->toBe(0);
});

it('keeps favourites behind the login', function () {
    $product = Product::factory()->create();

    $this->post(route('favorites.store', $product))->assertRedirect(route('login'));

    expect(Favorite::count())->toBe(0);
});

it('lists the favourited products', function () {
    $user = User::factory()->create();
    $favourited = Product::factory()->create(['name' => 'Lale Yüzük']);
    Product::factory()->create(['name' => 'Kelebek Halhal']);
    Favorite::create(['user_id' => $user->id, 'product_id' => $favourited->id]);

    $this->actingAs($user)
        ->get(route('account.favorites'))
        ->assertOk()
        ->assertSee('Lale Yüzük')
        ->assertDontSee('Kelebek Halhal');
});
