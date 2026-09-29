<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the language the visitor picked.
 *
 * The choice is remembered in the session, so signing in is never needed to
 * read the store in English, and it can also be asked for in the address as
 * ?dil=en. The address wins, because it is the only way English can be
 * reached without a session: a shared link, a search engine, or a page
 * running inside someone else's site, where the browser will not hand over a
 * SameSite=Lax cookie at all.
 */
class SetLocale
{
    /** @var array<int, string> */
    public const SUPPORTED = ['tr', 'en'];

    /** The query parameter a language can be asked for in, named like the routes. */
    public const QUERY = 'dil';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->fromQuery($request) ?? $request->session()->get('locale');

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }

    /**
     * The language asked for in the address, or null when none was.
     *
     * It is written back to the session so that the links on the page, which
     * carry no parameter of their own, stay in the language that was asked
     * for. Where no session survives the request that write is simply lost,
     * which costs nothing: the parameter still applied to this page.
     */
    private function fromQuery(Request $request): ?string
    {
        $locale = $request->query(self::QUERY);

        if (! is_string($locale) || ! in_array($locale, self::SUPPORTED, true)) {
            return null;
        }

        if ($request->hasSession() && $request->session()->get('locale') !== $locale) {
            $request->session()->put('locale', $locale);
        }

        return $locale;
    }
}
