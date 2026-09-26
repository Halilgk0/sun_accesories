<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

it('registers a new shopper and signs them in', function () {
    $this->post(route('register.store'), [
        'name' => 'Defne Güneş',
        'email' => 'defne@ornek.com',
        'phone' => '0532 111 22 33',
        'password' => 'sifre1234',
        'password_confirmation' => 'sifre1234',
    ])->assertRedirect(route('account.index'));

    $this->assertAuthenticated();
    expect(User::where('email', 'defne@ornek.com')->exists())->toBeTrue();
});

it('rejects a duplicate email address', function () {
    User::factory()->create(['email' => 'defne@ornek.com']);

    $this->post(route('register.store'), [
        'name' => 'Başkası',
        'email' => 'defne@ornek.com',
        'password' => 'sifre1234',
        'password_confirmation' => 'sifre1234',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('signs in with the right password', function () {
    $user = User::factory()->create(['password' => 'sifre1234']);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'sifre1234',
    ])->assertRedirect(route('account.index'));

    $this->assertAuthenticatedAs($user);
});

it('refuses a wrong password', function () {
    $user = User::factory()->create(['password' => 'sifre1234']);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'yanlis-sifre',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('signs the shopper out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

it('keeps account pages behind the login', function () {
    foreach ([route('account.index'), route('account.orders'), route('account.favorites')] as $url) {
        $this->get($url)->assertRedirect(route('login'));
    }
});

it('updates the profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->patch(route('account.profile.update'), [
        'name' => 'Defne Güneş',
        'email' => 'yeni@ornek.com',
        'phone' => '0532 000 00 00',
        'city' => 'İzmir',
        'address' => 'Alsancak Mah. No:7',
    ])->assertSessionHas('status');

    expect($user->fresh()->email)->toBe('yeni@ornek.com')
        ->and($user->fresh()->city)->toBe('İzmir');
});

it('changes the password when the current one is right', function () {
    $user = User::factory()->create(['password' => 'sifre1234']);

    $this->actingAs($user)->patch(route('account.password.update'), [
        'current_password' => 'sifre1234',
        'password' => 'yenisifre5678',
        'password_confirmation' => 'yenisifre5678',
    ])->assertSessionHas('status');

    expect(Hash::check('yenisifre5678', $user->fresh()->password))->toBeTrue();
});

it('refuses a password change with the wrong current password', function () {
    $user = User::factory()->create(['password' => 'sifre1234']);

    $this->actingAs($user)->patch(route('account.password.update'), [
        'current_password' => 'yanlis',
        'password' => 'yenisifre5678',
        'password_confirmation' => 'yenisifre5678',
    ])->assertSessionHasErrors('current_password');

    expect(Hash::check('sifre1234', $user->fresh()->password))->toBeTrue();
});

it('hides another shopper order detail', function () {
    $owner = User::factory()->create();
    $order = Order::create([
        'order_number' => 'GN-TEST-00001',
        'user_id' => $owner->id,
        'customer_name' => 'Defne',
        'email' => 'defne@ornek.com',
        'phone' => '0532 111 22 33',
        'city' => 'İzmir',
        'district' => 'Konak',
        'address' => 'Alsancak Mah. No:7',
        'subtotal' => 100,
        'total' => 100,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('account.order', $order))
        ->assertForbidden();
});

it('accepts a contact message', function () {
    Product::factory()->create();

    $this->post(route('contact.send'), [
        'name' => 'Defne Güneş',
        'email' => 'defne@ornek.com',
        'subject' => 'Sipariş takibi',
        'message' => 'Siparişim ne zaman kargoya verilir?',
    ])->assertSessionHas('status');
});
