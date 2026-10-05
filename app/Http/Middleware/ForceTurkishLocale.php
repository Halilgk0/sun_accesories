<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pins the catalogue editor to Turkish.
 *
 * The editor is read by the atelier alone and is written in Turkish only, so
 * it must not follow the language a visitor picked for the shop: there is no
 * English copy of its translation file to fall back to, and its pages would
 * render their own key names instead of words.
 */
class ForceTurkishLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale('tr');

        return $next($request);
    }
}
