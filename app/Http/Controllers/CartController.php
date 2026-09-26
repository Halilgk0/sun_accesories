<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'shippingFee' => $this->cart->shippingFee(),
            'total' => $this->cart->total(),
            'untilFreeShipping' => $this->cart->amountUntilFreeShipping(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        if (! $product->isInStock()) {
            return back()->with('error', __('shop.flash.out_of_stock', ['name' => $product->translated('name')]));
        }

        $this->cart->add($product, $validated['quantity'] ?? 1);

        return back()->with('status', __('shop.flash.added', ['name' => $product->translated('name')]));
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->ensureOwnership($cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $this->cart->updateQuantity($cartItem, $validated['quantity']);

        return back()->with('status', __('shop.flash.updated'));
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->ensureOwnership($cartItem);

        $name = $cartItem->product->translated('name');
        $this->cart->remove($cartItem);

        return back()->with('status', __('shop.flash.removed', ['name' => $name]));
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back()->with('status', __('shop.flash.cleared'));
    }

    private function ensureOwnership(CartItem $cartItem): void
    {
        if (! $this->cart->owns($cartItem)) {
            throw new AccessDeniedHttpException('This basket line does not belong to you.');
        }
    }
}
