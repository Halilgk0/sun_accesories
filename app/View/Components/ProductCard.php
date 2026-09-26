<?php

namespace App\View\Components;

use App\Models\Product;
use App\Services\FavoriteService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public bool $isFavorite;

    public function __construct(
        public Product $product,
        public int $delay = 0,
        public bool $compact = false,
        ?FavoriteService $favorites = null,
    ) {
        $this->isFavorite = ($favorites ?? app(FavoriteService::class))->has($product->id);
    }

    public function render(): View
    {
        return view('components.product-card');
    }
}
