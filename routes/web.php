<?php

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
