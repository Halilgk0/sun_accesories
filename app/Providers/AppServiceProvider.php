<?php

namespace App\Providers;

use App\Support\WhatsApp;
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
        // Every "get in touch" on the site opens WhatsApp, so the views that
        // offer one need the link. It is built per request rather than shared
        // once, because the message follows the visitor's chosen language.
        // The product page passes its own, naming the piece being asked about.
        ViewFacade::composer(
            ['partials.header', 'partials.footer', 'home', 'pages.about'],
            fn (View $view) => $view
                ->with('whatsappUrl', WhatsApp::link(__('pages.about.whatsapp_message')))
                ->with('whatsappNumber', WhatsApp::display()),
        );
    }
}
