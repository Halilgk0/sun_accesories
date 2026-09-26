<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, CartService $cart): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], attributes: [
            'name' => __('fields.name'),
            'email' => __('fields.email'),
            'phone' => __('fields.phone'),
            'password' => __('fields.password'),
        ]);

        $user = User::create($validated);

        Auth::login($user, remember: true);
        $cart->mergeGuestCartIntoAccount($user->id);
        $request->session()->regenerate();

        return redirect()->route('account.index')
            ->with('status', __('account.auth.welcome', ['name' => $user->name]));
    }
}
