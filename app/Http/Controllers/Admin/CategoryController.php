<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::ordered()->withCount('products')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['position' => Category::max('position') + 1]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', __('admin.category_created', ['name' => $category->name]));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $previousSlug = $category->slug;

        $category->update($request->validated());

        // Products store the slug, so renaming the address has to carry them
        // along or they would be left pointing at a category that is gone.
        if ($previousSlug !== $category->slug) {
            $category->newQuery()->getConnection()
                ->table('products')
                ->where('category', $previousSlug)
                ->update(['category' => $category->slug]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('status', __('admin.category_updated', ['name' => $category->name]));
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Deleting one that is in use would leave those pieces uncategorised
        // and their links broken, so it is refused with a count rather than
        // quietly taking the products with it.
        $inUse = $category->products()->count();

        if ($inUse > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->withErrors(['category' => __('admin.category_in_use', [
                    'name' => $category->name,
                    'count' => $inUse,
                ])]);
        }

        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', __('admin.category_deleted', ['name' => $name]));
    }
}
