<?php

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

/**
 * Answers "has this shopper hearted that product?" without re-querying for
 * every card on the page — the id list is fetched once per request.
 */
class FavoriteService
{
    /** @var array<int, int>|null */
    private ?array $ids = null;

    public function has(int $productId): bool
    {
        return in_array($productId, $this->ids(), true);
    }

    /** @return array<int, int> */
    public function ids(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return $this->ids ??= Favorite::where('user_id', Auth::id())
            ->pluck('product_id')
            ->map(fn ($id): int => (int) $id)
            ->all();
    }
}
