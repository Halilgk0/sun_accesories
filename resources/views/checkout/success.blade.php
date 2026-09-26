@extends('layouts.app')

@section('title', __('shop.success.meta_title'))

@section('content')

<section class="relative overflow-hidden py-12 sm:py-20">
    <x-sun class="-top-36 left-1/2 -translate-x-1/2 opacity-70 sm:-top-52" size="26rem" sm-size="46rem" />

    <div class="wrap relative max-w-3xl">

        <div class="mb-7 text-center sm:mb-10">
            <span class="mx-auto mb-5 grid size-16 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-white shadow-[0_24px_50px_-20px_rgba(239,138,0,.9)] animate-pop sm:mb-7 sm:size-24">
                <svg class="size-8 sm:size-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
            </span>

            <h1 class="display-lg">{{ __('shop.success.title') }}</h1>
            <p class="mx-auto mt-4 max-w-[52ch] text-sm leading-relaxed text-ink-soft sm:mt-5 sm:text-lg">
                {!! __('shop.success.lead', [
                    'name' => e(Str::before($order->customer_name, ' ')),
                    'email' => '<strong class="text-ink">'.e($order->email).'</strong>',
                ]) !!}
            </p>
        </div>

        {{-- Order number --}}
        <div class="tile tile-raised mb-4 flex flex-wrap items-center justify-between gap-3 border-sun bg-sun-pale/40 p-4 sm:mb-6 sm:gap-4 sm:p-6">
            <div>
                <p class="text-xs text-ink-soft sm:text-sm">{{ __('shop.success.order_number') }}</p>
                <p class="font-display text-xl tracking-tight sm:text-3xl">{{ $order->order_number }}</p>
            </div>
            <span class="badge bg-ink text-paper-warm">{{ $order->statusLabel() }}</span>
        </div>

        {{-- Items --}}
        <div class="tile overflow-hidden">
            <ul class="divide-y-2 divide-paper-deep">
                @foreach ($order->items as $item)
                    <li class="flex items-center gap-3 p-3.5 sm:gap-4 sm:p-5">
                        <img src="{{ asset($item->product_image) }}" alt="" width="64" height="64" class="size-12 shrink-0 rounded-xl object-cover sm:size-16 sm:rounded-2xl">
                        <span class="min-w-0 flex-1">
                            <span class="block font-display text-base leading-tight sm:text-xl">{{ $item->product_name }}</span>
                            <span class="mt-0.5 block text-xs text-ink-soft sm:text-sm">{{ __('account.orders.units', ['count' => $item->quantity, 'price' => number_format((float) $item->unit_price, 2, ',', '.').' ₺']) }}</span>
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

        {{-- Delivery details --}}
        <div class="tile mt-4 grid gap-5 p-4 sm:mt-6 sm:gap-6 sm:p-6 md:grid-cols-2">
            <div>
                <h2 class="mb-1.5 font-display text-base sm:mb-2 sm:text-xl">{{ __('shop.success.delivery_address') }}</h2>
                <p class="text-xs leading-relaxed text-ink-soft sm:text-sm">
                    {{ $order->customer_name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->district }} / {{ $order->city }}<br>
                    {{ $order->phone }}
                </p>
            </div>
            <div>
                <h2 class="mb-1.5 font-display text-base sm:mb-2 sm:text-xl">{{ __('shop.success.payment') }}</h2>
                <p class="text-xs leading-relaxed text-ink-soft sm:text-sm">{{ $order->paymentLabel() }}</p>

                @if ($order->note)
                    <h2 class="mt-4 mb-1.5 font-display text-base sm:mt-5 sm:mb-2 sm:text-xl">{{ __('shop.success.your_note') }}</h2>
                    <p class="text-xs leading-relaxed text-ink-soft sm:text-sm">{{ $order->note }}</p>
                @endif
            </div>
        </div>

        <div class="mt-7 flex flex-wrap justify-center gap-2.5 sm:mt-10 sm:gap-3">
            <a href="{{ route('products.index') }}" class="btn btn-sun">{{ __('site.actions.continue_shopping') }}</a>
            @auth
                <a href="{{ route('account.orders') }}" class="btn btn-outline">{{ __('site.actions.orders') }}</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-outline">{{ __('shop.success.create_account') }}</a>
            @endauth
        </div>
    </div>
</section>

@endsection
