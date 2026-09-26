@extends('layouts.app')

@section('title', $order->order_number)

@section('content')

<x-account-shell :title="$order->order_number">

    <a href="{{ route('account.orders') }}" class="link-sun mb-4 inline-block text-xs font-semibold sm:mb-6 sm:text-sm">← {{ __('account.orders.all_orders') }}</a>

    <div class="grid gap-4 sm:gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-start">

        <div class="tile overflow-hidden">
            <div class="flex items-center justify-between gap-3 border-b-2 border-paper-deep p-4 sm:gap-4 sm:p-6">
                <h2 class="font-display text-lg sm:text-2xl">{{ __('account.orders.contents') }}</h2>
                <span class="badge bg-sun-pale text-sun-deep">{{ $order->statusLabel() }}</span>
            </div>

            <ul class="divide-y-2 divide-paper-deep">
                @foreach ($order->items as $item)
                    <li class="flex items-center gap-3 p-3.5 sm:gap-4 sm:p-5">
                        <img src="{{ asset($item->product_image) }}" alt="" width="72" height="72" class="size-14 shrink-0 rounded-xl object-cover sm:size-18 sm:rounded-2xl">
                        <span class="min-w-0 flex-1">
                            <span class="block font-display text-base leading-tight sm:text-xl">{{ $item->product_name }}</span>
                            <span class="mt-0.5 block text-xs text-ink-soft tabular-nums sm:mt-1 sm:text-sm">
                                {{ __('account.orders.units', ['count' => $item->quantity, 'price' => number_format((float) $item->unit_price, 2, ',', '.').' ₺']) }}
                            </span>
                        </span>
                        <span class="text-sm font-semibold tabular-nums sm:text-base">{{ number_format((float) $item->line_total, 2, ',', '.') }} ₺</span>
                    </li>
                @endforeach
            </ul>

            <dl class="space-y-2.5 border-t-2 border-paper-deep bg-paper-warm p-4 text-sm sm:space-y-3 sm:p-6">
                <div class="flex justify-between">
                    <dt class="text-ink-soft">{{ __('shop.cart.subtotal') }}</dt>
                    <dd class="font-semibold tabular-nums">{{ number_format((float) $order->subtotal, 2, ',', '.') }} ₺</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-soft">{{ __('shop.cart.shipping') }}</dt>
                    <dd class="font-semibold tabular-nums">
                        {{ (float) $order->shipping_fee === 0.0 ? __('shop.cart.free') : number_format((float) $order->shipping_fee, 2, ',', '.').' ₺' }}
                    </dd>
                </div>
                <div class="flex items-baseline justify-between border-t-2 border-paper-deep pt-3.5 sm:pt-4">
                    <dt class="font-display text-base sm:text-xl">{{ __('shop.cart.total') }}</dt>
                    <dd class="font-display text-2xl tabular-nums sm:text-3xl">{{ number_format((float) $order->total, 2, ',', '.') }} ₺</dd>
                </div>
            </dl>
        </div>

        <div class="space-y-4 sm:space-y-6">
            <div class="tile p-4 sm:p-6">
                <h2 class="mb-2.5 font-display text-base sm:mb-3 sm:text-xl">{{ __('account.orders.delivery') }}</h2>
                <p class="text-xs leading-relaxed text-ink-soft sm:text-sm">
                    {{ $order->customer_name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->district }} / {{ $order->city }}<br>
                    {{ $order->phone }}<br>
                    {{ $order->email }}
                </p>
            </div>

            <div class="tile p-4 sm:p-6">
                <h2 class="mb-2.5 font-display text-base sm:mb-3 sm:text-xl">{{ __('account.orders.payment') }}</h2>
                <p class="text-xs leading-relaxed text-ink-soft sm:text-sm">
                    {{ $order->paymentLabel() }}<br>
                    {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                </p>
            </div>

            @if ($order->note)
                <div class="tile border-sun bg-sun-pale/40 p-4 sm:p-6">
                    <h2 class="mb-1.5 font-display text-base sm:mb-2 sm:text-xl">{{ __('account.orders.note') }}</h2>
                    <p class="text-xs leading-relaxed text-ink-soft sm:text-sm">{{ $order->note }}</p>
                </div>
            @endif

            <a href="{{ route('contact') }}" class="btn btn-outline w-full">{{ __('account.orders.ask') }}</a>
        </div>
    </div>

</x-account-shell>

@endsection
