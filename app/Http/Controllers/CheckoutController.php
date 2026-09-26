<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View|RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('shop.flash.cart_required'));
        }

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $this->cart->subtotal(),
            'shippingFee' => $this->cart->shippingFee(),
            'total' => $this->cart->total(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('shop.flash.cart_empty'));
        }

        $data = $request->safe();
        $subtotal = $this->cart->subtotal();
        $shippingFee = $this->cart->shippingFee();

        $order = DB::transaction(function () use ($items, $data, $subtotal, $shippingFee): Order {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => 'hazirlaniyor',
                'customer_name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'district' => $data['district'],
                'address' => $data['address'],
                'note' => $data['note'] ?? null,
                'payment_method' => $data['payment_method'],
                'card_last_four' => $this->cardLastFour($data['card_number'] ?? null),
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
            ]);

            $order->items()->createMany($items->map(fn (CartItem $item): array => [
                'product_id' => $item->product_id,
                'product_name' => $item->product->translated('name'),
                'product_image' => $item->product->image_path,
                'unit_price' => $item->product->price,
                'quantity' => $item->quantity,
                'line_total' => $item->lineTotal(),
            ])->all());

            $this->cart->clear();

            return $order;
        });

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order): View
    {
        abort_unless(
            $order->user_id === null || $order->user_id === Auth::id(),
            403,
        );

        return view('checkout.success', [
            'order' => $order->load('items'),
        ]);
    }

    private function generateOrderNumber(): string
    {
        return 'GN-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
    }

    private function cardLastFour(?string $cardNumber): ?string
    {
        if (! $cardNumber) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $cardNumber) ?? '';

        return $digits === '' ? null : substr($digits, -4);
    }
}
