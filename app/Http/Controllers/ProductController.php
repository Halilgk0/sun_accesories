<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\WhatsApp;
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

                // SQLite's LIKE ignores case, Postgres' does not, so ask for a
                // case-insensitive match explicitly on the drivers that need it.
                $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

                // Search both language columns so a term works whichever language is showing.
                $query->where(function ($inner) use ($term, $operator) {
                    foreach (['name', 'name_en', 'tagline', 'tagline_en', 'stone', 'stone_en'] as $column) {
                        $inner->orWhere($column, $operator, "%{$term}%");
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
        // The enquiry opens WhatsApp with this already typed out, naming the
        // piece and linking back to the page the visitor is standing on.
        $enquiry = __('shop.product.whatsapp_message', [
            'name' => $product->translated('name'),
            'url' => route('products.show', $product),
        ]);

        return view('products.show', [
            'product' => $product,
            'related' => Product::where('id', '!=', $product->id)->inRandomOrder()->take(4)->get(),
            'whatsappUrl' => WhatsApp::link($enquiry),
        ]);
    }
}
