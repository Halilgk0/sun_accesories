<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SessionController as AdminSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\ForceTurkishLocale;
use App\Http\Middleware\RequireAdminPassword;
use App\Http\Middleware\SetLocale;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::post('/dil/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hakkimizda', [HomeController::class, 'about'])->name('about');

/*
 * Photographs the atelier uploaded. They live in the database, so this is the
 * only way to reach them.
 *
 * Deliberately outside the session middleware: those add a Set-Cookie to every
 * response, and no CDN will cache a response that carries one — which would
 * have left the long cache lifetime below doing nothing and woken PHP for
 * every thumbnail on the page. Model binding is kept; nothing else is needed,
 * since the bytes are public and the route reads no session and takes no
 * input.
 */
Route::get('/gorsel/{image}', [ImageController::class, 'show'])
    ->withoutMiddleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        PreventRequestForgery::class,
        SetLocale::class,
    ])
    ->name('images.show');

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
                Route::get('/kurulum', [AdminProductController::class, 'setupPage'])->name('setup.show');
                Route::post('/kurulum', [AdminProductController::class, 'setup'])->name('setup');
                Route::get('/yeni', [AdminProductController::class, 'create'])->name('products.create');
                Route::post('/', [AdminProductController::class, 'store'])->name('products.store');
                Route::get('/kategoriler', [AdminCategoryController::class, 'index'])->name('categories.index');
                Route::get('/kategoriler/yeni', [AdminCategoryController::class, 'create'])->name('categories.create');
                Route::post('/kategoriler', [AdminCategoryController::class, 'store'])->name('categories.store');
                Route::get('/kategoriler/{category}/duzenle', [AdminCategoryController::class, 'edit'])->name('categories.edit');
                Route::put('/kategoriler/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
                Route::delete('/kategoriler/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

                Route::get('/{product}/duzenle', [AdminProductController::class, 'edit'])->name('products.edit');
                Route::put('/{product}', [AdminProductController::class, 'update'])->name('products.update');
                Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
            });
        });
}
