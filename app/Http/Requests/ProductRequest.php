<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /** The panel's own middleware decides who may be here. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:120'],
            'slug' => [
                'required', 'string', 'max:140', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($product),
            ],
            'category' => ['required', 'string', Rule::in(Product::CATEGORIES)],
            'badge' => ['nullable', 'string', Rule::in(Product::BADGES)],

            'tagline' => ['required', 'string', 'max:160'],
            'tagline_en' => ['nullable', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],

            'price' => ['required', 'numeric', 'min:0', 'max:9999999'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0', 'max:9999999', 'gt:price'],

            'image_path' => ['required', 'string', 'max:500'],
            'material' => ['required', 'string', 'max:120'],
            'material_en' => ['nullable', 'string', 'max:120'],
            'stone' => ['required', 'string', 'max:120'],
            'stone_en' => ['nullable', 'string', 'max:120'],
            'color_hex' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],

            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['required', 'integer', 'min:0', 'max:1000000'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return __('admin.fields');
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.regex' => __('admin.slug_format'),
            'color_hex.regex' => __('admin.colour_format'),
            'compare_at_price.gt' => __('admin.compare_price_must_be_higher'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            // An empty box means "no old price", not "zero lira".
            'compare_at_price' => $this->filled('compare_at_price') ? $this->input('compare_at_price') : null,
        ]);
    }
}
