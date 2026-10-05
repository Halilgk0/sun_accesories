<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Image;
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
            'categories' => Category::ordered()->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::create($this->withPhoto($request));

        return redirect()
            ->route('admin.products.index')
            ->with('status', __('admin.created', ['name' => $product->name]));
    }

    public function edit(Product $product): View
    {
        return view('admin.form', [
            'product' => $product,
            'images' => $this->availableImages(),
            'categories' => Category::ordered()->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->withPhoto($request, $product));

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
     * Puts an uploaded photograph in the database and points the product at
     * it. Without an upload the path the form carried is kept, so editing a
     * piece does not require re-uploading its picture.
     *
     * @return array<string, mixed>
     */
    private function withPhoto(ProductRequest $request, ?Product $product = null): array
    {
        $fields = $request->validated();
        unset($fields['photo']);

        if ($request->hasFile('photo')) {
            $fields['image_path'] = Image::store($request->file('photo'))->path();
        } elseif (blank($fields['image_path'] ?? null)) {
            $fields['image_path'] = $product?->image_path ?? '';
        }

        return $fields;
    }

    /**
     * Everything that can be chosen as a photograph: the ones shipped with the
     * site and everything uploaded since.
     *
     * @return array<int, string>
     */
    private function availableImages(): array
    {
        // One glob per extension rather than GLOB_BRACE: braces are a GNU
        // extension that musl does not implement, so on the Alpine image the
        // flag raises instead of matching and took the whole page down.
        $files = [];

        foreach (['jpg', 'jpeg', 'png', 'webp', 'avif'] as $extension) {
            $files = [...$files, ...(glob(public_path('images/products/*.'.$extension)) ?: [])];
        }

        sort($files);

        $shipped = array_map(
            fn (string $file) => 'images/products/'.basename($file),
            $files,
        );

        $uploaded = Image::latest('id')->take(40)->get()
            ->map(fn (Image $image) => $image->path())
            ->all();

        return [...$uploaded, ...$shipped];
    }
}
