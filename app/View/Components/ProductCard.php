<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public function __construct(
        public Product $product,
        public int $delay = 0,
        public bool $compact = false,
    ) {}

    public function render(): View
    {
        return view('components.product-card');
    }
}
