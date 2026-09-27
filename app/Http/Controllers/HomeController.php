<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

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
}
