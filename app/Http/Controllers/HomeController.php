<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'featured' => Product::where('is_featured', true)->orderBy('id')->get(),
            'newest' => Product::latest('id')->take(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], attributes: [
            'name' => __('fields.name'),
            'email' => __('fields.email'),
            'subject' => __('fields.subject'),
            'message' => __('fields.message'),
        ]);

        return back()->with('status', __('pages.contact.sent'));
    }
}
