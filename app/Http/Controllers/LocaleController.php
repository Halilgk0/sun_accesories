<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class LocaleController extends Controller
{
    public function update(Request $request, string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, SetLocale::SUPPORTED, true), 404);

        $request->session()->put('locale', $locale);

        // The choice now lives in the session, so a ?dil= still sitting in the
        // address the visitor came from would outrank it on arrival and undo
        // the switch. Drop it on the way back and let the session speak.
        return redirect()->to(self::withoutLocaleQuery(URL::previous()));
    }

    /**
     * The same address with the language parameter removed.
     *
     * Fragments never reach the server in a Referer header, so splitting on
     * the first "?" is enough here.
     */
    private static function withoutLocaleQuery(string $url): string
    {
        [$path, $queryString] = array_pad(explode('?', $url, 2), 2, '');

        parse_str($queryString, $query);
        unset($query[SetLocale::QUERY]);

        return $query === [] ? $path : $path.'?'.http_build_query($query);
    }
}
