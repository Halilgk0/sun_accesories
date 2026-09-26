<?php

namespace App\Providers;

use App\Services\CartService;
use App\Services\FavoriteService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class);
        $this->app->singleton(FavoriteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Number::useLocale('tr');

        // The header renders on every page and needs the live basket count.
        view()->composer('layouts.app', function (View $view): void {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
