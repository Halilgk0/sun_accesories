<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    public function index(): View
    {
        // Attaching a fresh database leaves the panel as the only way in, and
        // there is no release step on Vercel to migrate it, so an unprepared
        // database is shown as something to fix rather than as a crash.
        try {
            $products = Product::orderBy('id')->get();
        } catch (QueryException) {
            // Nothing to list because the table is not there yet.
            return view('admin.setup');
        }

        // An empty catalogue is a legitimate state — everything may simply have
        // been deleted — so it is listed as empty rather than treated as a
        // broken installation.
        return view('admin.index', ['products' => $products]);
    }

    /**
     * Brings the schema up to date and, on an empty catalogue, puts the five
     * pieces back. Both steps are safe to repeat: migrations are tracked and
     * the seeder works by updateOrCreate.
     */
    public function setup(): RedirectResponse
    {
        Artisan::call('migrate', ['--force' => true]);

        if (Product::query()->count() === 0) {
            Artisan::call('db:seed', ['--force' => true]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('status', __('admin.setup_done'));
    }

    public function create(): View
    {
        return view('admin.form', [
            'product' => new Product([
                'color_hex' => '#F2A007',
                'stock' => 1,
                'rating' => 5.0,
                'review_count' => 0,
            ]),
            'images' => $this->availableImages(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('status', __('admin.created', ['name' => $product->name]));
    }

    public function edit(Product $product): View
    {
        return view('admin.form', [
            'product' => $product,
            'images' => $this->availableImages(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('status', __('admin.updated', ['name' => $product->name]));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', __('admin.deleted', ['name' => $name]));
    }

    /**
     * The photographs already shipped with the site, offered as a picker so a
     * path never has to be typed by hand.
     *
     * @return array<int, string>
     */
    private function availableImages(): array
    {
        $files = glob(public_path('images/products/*.{jpg,jpeg,png,webp,avif}'), GLOB_BRACE) ?: [];

        return array_map(
            fn (string $file) => 'images/products/'.basename($file),
            $files,
        );
    }
}
