<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/dil/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hakkimizda', [HomeController::class, 'about'])->name('about');
Route::get('/iletisim', [HomeController::class, 'contact'])->name('contact');
Route::post('/iletisim', [HomeController::class, 'sendContact'])->name('contact.send');

Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/urun/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/sepet', [CartController::class, 'index'])->name('cart.index');
Route::post('/sepet/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/sepet/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/sepet/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/sepet', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/odeme', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/odeme', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/siparis/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware('guest')->group(function () {
    Route::get('/kayit', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/kayit', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/giris', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/giris', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/cikis', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/hesabim', [AccountController::class, 'index'])->name('account.index');
    Route::get('/hesabim/siparislerim', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/hesabim/siparis/{order}', [AccountController::class, 'showOrder'])->name('account.order');
    Route::get('/hesabim/favorilerim', [AccountController::class, 'favorites'])->name('account.favorites');
    Route::patch('/hesabim/profil', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::patch('/hesabim/sifre', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::post('/favori/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
});
