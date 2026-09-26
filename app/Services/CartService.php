<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Keeps one basket per shopper, whether they are signed in or browsing as a guest.
 *
 * A guest basket is keyed by a random token stored in the session; when that shopper
 * signs in or registers, the guest rows are merged onto their account.
 */
class CartService
{
    public const FREE_SHIPPING_THRESHOLD = 1500.00;

    public const SHIPPING_FEE = 79.90;

    /** @return Collection<int, CartItem> */
    public function items(): Collection
    {
        return $this->baseQuery()->with('product')->latest()->get();
    }

    public function add(Product $product, int $quantity = 1): CartItem
    {
        $item = $this->baseQuery()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);

            return $item->refresh();
        }

        return CartItem::create([
            ...$this->owner(),
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        if ($quantity < 1) {
            $item->delete();

            return;
        }

        $item->update(['quantity' => min($quantity, 99)]);
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(): void
    {
        $this->baseQuery()->delete();
    }

    public function count(): int
    {
        return (int) $this->baseQuery()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum(fn (CartItem $item): float => $item->lineTotal());
    }

    public function shippingFee(): float
    {
        $subtotal = $this->subtotal();

        if ($subtotal <= 0 || $subtotal >= self::FREE_SHIPPING_THRESHOLD) {
            return 0.0;
        }

        return self::SHIPPING_FEE;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->shippingFee();
    }

    public function amountUntilFreeShipping(): float
    {
        return max(0, self::FREE_SHIPPING_THRESHOLD - $this->subtotal());
    }

    /**
     * Moves a guest basket onto the account that just signed in.
     */
    public function mergeGuestCartIntoAccount(int $userId): void
    {
        $token = Session::get('cart_token');

        if (! $token) {
            return;
        }

        $guestItems = CartItem::where('session_token', $token)->get();

        foreach ($guestItems as $guestItem) {
            $existing = CartItem::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
                $guestItem->delete();

                continue;
            }

            $guestItem->update(['user_id' => $userId, 'session_token' => null]);
        }

        Session::forget('cart_token');
    }

    /**
     * Confirms the item belongs to the basket of whoever is browsing right now.
     */
    public function owns(CartItem $item): bool
    {
        $owner = $this->owner();

        return $item->user_id === ($owner['user_id'] ?? null)
            && $item->session_token === ($owner['session_token'] ?? null);
    }

    /** @return array{user_id: int|null, session_token: string|null} */
    private function owner(): array
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id(), 'session_token' => null];
        }

        return ['user_id' => null, 'session_token' => $this->sessionToken()];
    }

    /** @return Builder<CartItem> */
    private function baseQuery(): Builder
    {
        $owner = $this->owner();

        return CartItem::query()
            ->when($owner['user_id'], fn ($query) => $query->where('user_id', $owner['user_id']))
            ->when($owner['session_token'], fn ($query) => $query->where('session_token', $owner['session_token']));
    }

    private function sessionToken(): string
    {
        if (! Session::has('cart_token')) {
            Session::put('cart_token', (string) Str::uuid());
        }

        return Session::get('cart_token');
    }
}
