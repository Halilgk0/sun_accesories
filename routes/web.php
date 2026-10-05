<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SessionController as AdminSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\ForceTurkishLocale;
use App\Http\Middleware\RequireAdminPassword;
use Illuminate\Support\Facades\Route;

Route::post('/dil/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hakkimizda', [HomeController::class, 'about'])->name('about');

Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/urun/{product}', [ProductController::class, 'show'])->name('products.show');

/*
 * The catalogue editor. Its path comes from the environment and is never
 * linked to from the site, so with no ADMIN_PATH set these routes do not
 * exist at all — which is what should happen anywhere it has not been
 * deliberately switched on.
 */
if (filled($adminPath = config('admin.path'))) {
    Route::prefix($adminPath)
        ->name('admin.')
        ->middleware(ForceTurkishLocale::class)
        ->group(function () {
            Route::get('/giris', [AdminSessionController::class, 'create'])->name('login');

            // Five tries a minute: enough for a slip, far too few to guess with.
            Route::post('/giris', [AdminSessionController::class, 'store'])
                ->middleware('throttle:5,1')
                ->name('login.store');

            Route::middleware(RequireAdminPassword::class)->group(function () {
                Route::post('/cikis', [AdminSessionController::class, 'destroy'])->name('logout');

                Route::get('/', [AdminProductController::class, 'index'])->name('products.index');
                Route::post('/kurulum', [AdminProductController::class, 'setup'])->name('setup');
                Route::get('/yeni', [AdminProductController::class, 'create'])->name('products.create');
                Route::post('/', [AdminProductController::class, 'store'])->name('products.store');
                Route::get('/{product}/duzenle', [AdminProductController::class, 'edit'])->name('products.edit');
                Route::put('/{product}', [AdminProductController::class, 'update'])->name('products.update');
                Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
            });
        });
}
