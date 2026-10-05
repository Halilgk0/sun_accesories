<?php

namespace App\Providers;

use App\Models\Category;
use App\Support\Instagram;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Every "get in touch" on the site opens the Instagram inbox, so the
        // views that offer one need the link and the handle to print.
        ViewFacade::composer(
            ['partials.header', 'partials.footer', 'home', 'pages.about'],
            fn (View $view) => $view
                ->with('instagramUrl', Instagram::dmLink())
                ->with('instagramProfile', Instagram::profileLink())
                ->with('instagramHandle', Instagram::handle())
                // The footer and the home page both list categories, and they
                // are rows the atelier edits rather than a fixed set.
                ->with('navCategories', Category::ordered()->get()),
        );
    }
}
