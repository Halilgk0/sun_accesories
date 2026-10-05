<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RequireAdminPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (request()->session()->get(RequireAdminPassword::SESSION_KEY)) {
            return redirect()->route('admin.products.index');
        }

        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $expected = (string) config('admin.password');

        // hash_equals compares in constant time, so a wrong password cannot be
        // narrowed down a character at a time by watching how long it takes.
        if ($expected === '' || ! hash_equals($expected, $request->string('password')->toString())) {
            throw ValidationException::withMessages([
                'password' => __('admin.wrong_password'),
            ]);
        }

        // A new session id after signing in, so a session fixed beforehand by
        // someone else does not become an authenticated one.
        $request->session()->regenerate();
        $request->session()->put(RequireAdminPassword::SESSION_KEY, true);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(RequireAdminPassword::SESSION_KEY);
        $request->session()->regenerate();

        return redirect()->route('admin.login')->with('status', __('admin.signed_out'));
    }
}
