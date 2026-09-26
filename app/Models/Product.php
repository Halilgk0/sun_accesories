<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return HasMany<CartItem, $this> */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /** @return HasMany<Favorite, $this> */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Returns the English copy while the store is read in English, falling back
     * to the Turkish original whenever a translation is missing.
     */
    public function translated(string $field): string
    {
        if (App::getLocale() === 'en') {
            $english = $this->getAttribute($field.'_en');

            if (filled($english)) {
                return $english;
            }
        }

        return (string) $this->getAttribute($field);
    }

    public function categoryLabel(): string
    {
        return __('shop.categories.'.$this->category);
    }

    public function badgeLabel(): ?string
    {
        return $this->badge ? __('shop.badges.'.$this->badge) : null;
    }

    public function isOnSale(): bool
    {
        return $this->compare_at_price !== null && $this->compare_at_price > $this->price;
    }

    public function discountPercentage(): int
    {
        if (! $this->isOnSale()) {
            return 0;
        }

        return (int) round((1 - ($this->price / $this->compare_at_price)) * 100);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
