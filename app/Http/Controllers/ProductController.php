<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('kategori')->toString();
        $sort = $request->string('sirala')->toString();

        $products = Product::query()
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($request->filled('arama'), function ($query) use ($request) {
                $term = $request->string('arama')->toString();
                // Search both language columns so a term works whichever language is showing.
                $query->where(function ($inner) use ($term) {
                    foreach (['name', 'name_en', 'tagline', 'tagline_en', 'stone', 'stone_en'] as $column) {
                        $inner->orWhere($column, 'like', "%{$term}%");
                    }
                });
            })
            ->when($sort === 'artan', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'azalan', fn ($query) => $query->orderByDesc('price'))
            ->when($sort === 'puan', fn ($query) => $query->orderByDesc('rating'))
            ->when(! in_array($sort, ['artan', 'azalan', 'puan'], true), fn ($query) => $query->orderBy('id'))
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => Product::query()->select('category')->distinct()->orderBy('category')->pluck('category'),
            'activeCategory' => $category,
            'activeSort' => $sort,
        ]);
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product,
            'related' => Product::where('id', '!=', $product->id)->inRandomOrder()->take(4)->get(),
        ]);
    }
}
