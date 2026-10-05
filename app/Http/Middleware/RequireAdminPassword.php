<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps the catalogue editor shut until the password has been given.
 *
 * The unguessable path hides the panel; this is what locks it. Both are
 * required, and a missing password in the environment locks it for everyone
 * rather than letting a half-configured deployment stand open.
 */
class RequireAdminPassword
{
    public const SESSION_KEY = 'admin.signed_in';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get(self::SESSION_KEY)) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
