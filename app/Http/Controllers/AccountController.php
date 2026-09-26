<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('account.index', [
            'user' => $user,
            'orders' => $user->orders()->withCount('items')->latest()->take(3)->get(),
            'orderCount' => $user->orders()->count(),
            'favoriteCount' => $user->favorites()->count(),
            'spent' => (float) $user->orders()->sum('total'),
        ]);
    }

    public function orders(): View
    {
        return view('account.orders', [
            'orders' => Auth::user()->orders()->with('items')->latest()->get(),
        ]);
    }

    public function showOrder(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);

        return view('account.order', ['order' => $order->load('items')]);
    }

    public function favorites(): View
    {
        return view('account.favorites', [
            'favorites' => Auth::user()->favorites()->with('product')->latest()->get(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:25'],
            'city' => ['nullable', 'string', 'max:60'],
            'address' => ['nullable', 'string', 'max:500'],
        ], attributes: [
            'name' => __('fields.name'),
            'email' => __('fields.email'),
            'phone' => __('fields.phone'),
            'city' => __('fields.city'),
            'address' => __('fields.address'),
        ]);

        $user->update($validated);

        return back()->with('status', __('account.auth.profile_saved'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], attributes: [
            'current_password' => __('fields.current_password'),
            'password' => __('fields.new_password'),
        ]);

        Auth::user()->update(['password' => $validated['password']]);

        return back()->with('status', __('account.auth.password_saved'));
    }
}
