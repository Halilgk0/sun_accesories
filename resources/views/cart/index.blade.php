@extends('layouts.app')

@section('title', __('shop.cart.title'))

@section('content')

<x-page-header :eyebrow="__('shop.cart.title')" :title="__('shop.cart.title')" />

<section class="wrap -mt-6 pb-14 sm:-mt-8 sm:pb-24">

    @if ($items->isEmpty())
        <div class="tile tile-raised relative overflow-hidden px-5 py-12 text-center sm:px-6 sm:py-20">
            <x-sun class="-top-20 left-1/2 -translate-x-1/2 opacity-40" size="14rem" sm-size="22rem" />

            <div class="relative">
                <span class="mx-auto mb-4 grid size-16 place-items-center rounded-full bg-paper-warm text-3xl animate-float sm:mb-6 sm:size-24 sm:text-5xl">🧺</span>
                <h2 class="display-md">{{ __('shop.cart.empty_title') }}</h2>
                <p class="mx-auto mt-3 max-w-[44ch] text-sm text-ink-soft sm:mt-4 sm:text-base">
                    {{ __('shop.cart.empty_text') }}
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-sun mt-6 sm:mt-8">{{ __('site.actions.browse') }}</a>
            </div>
        </div>
    @else

        {{-- Free shipping meter --}}
        <div class="tile mb-4 p-3.5 sm:mb-6 sm:p-5">
            @if ($untilFreeShipping > 0)
                <p class="mb-2.5 text-xs font-semibold sm:mb-3 sm:text-sm">
                    {!! __('shop.cart.free_shipping_left', ['amount' => '<span class="text-bole">'.number_format($untilFreeShipping, 2, ',', '.').' ₺</span>']) !!}
                </p>
            @else
                <p class="mb-2.5 flex items-center gap-2 text-xs font-semibold text-yaprak sm:mb-3 sm:text-sm">
                    <svg class="size-3.5 sm:size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                    {{ __('shop.cart.free_shipping_reached') }}
                </p>
            @endif

            <div class="h-2 overflow-hidden rounded-full bg-paper-deep sm:h-2.5">
                <div class="h-full rounded-full bg-linear-to-r from-sun to-bole transition-[width] duration-700"
                     style="width: {{ min(100, ($subtotal / \App\Services\CartService::FREE_SHIPPING_THRESHOLD) * 100) }}%"></div>
            </div>
        </div>

        <div class="grid gap-4 sm:gap-6 lg:grid-cols-[1.6fr_1fr] lg:items-start">

            {{-- Lines --}}
            <div class="space-y-3 sm:space-y-4">
                @foreach ($items as $item)
                    {{-- On a phone the controls drop to their own full-width row so
                         nothing has to squeeze in beside the thumbnail. --}}
                    <article class="tile reveal p-3 sm:flex sm:gap-4 sm:p-4"
                             style="--card-accent: {{ $item->product->color_hex }}">

                        <div class="flex gap-3 sm:contents">
                            <a href="{{ route('products.show', $item->product) }}" class="shrink-0">
                                <img src="{{ asset($item->product->image_path) }}"
                                     alt="{{ $item->product->translated('name') }}"
                                     width="160" height="160"
                                     class="size-20 rounded-xl object-cover transition duration-500 hover:scale-105 sm:size-32 sm:rounded-2xl">
                            </a>

                            <div class="min-w-0 flex-1 sm:self-center">
                                <span class="text-[0.65rem] font-bold sm:text-xs" style="color: {{ $item->product->color_hex }}">{{ $item->product->categoryLabel() }}</span>
                                <h2 class="mt-0.5 font-display text-base leading-tight sm:text-2xl">
                                    <a href="{{ route('products.show', $item->product) }}" class="transition hover:text-bole">{{ $item->product->translated('name') }}</a>
                                </h2>
                                <p class="mt-0.5 hidden text-sm text-ink-soft sm:block">{{ $item->product->translated('material') }} · {{ $item->product->translated('stone') }}</p>
                                <p class="mt-1 text-[0.7rem] font-semibold text-ink-soft tabular-nums sm:mt-2 sm:text-sm">
                                    {{ __('shop.cart.unit', ['price' => number_format((float) $item->product->price, 2, ',', '.').' ₺']) }}
                                </p>
                                <p class="mt-1.5 font-display text-lg whitespace-nowrap tabular-nums sm:hidden">{{ number_format($item->lineTotal(), 2, ',', '.') }} ₺</p>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-3 border-t-2 border-paper-deep pt-3 sm:mt-0 sm:flex-col sm:items-end sm:justify-center sm:gap-2.5 sm:border-0 sm:pt-0">
                            <form method="POST" action="{{ route('cart.update', $item) }}" data-quantity data-quantity-submit
                                  class="flex items-center gap-0.5 rounded-full border-2 border-paper-deep p-0.5 sm:gap-1 sm:p-1">
                                @csrf
                                @method('PATCH')
                                <button type="button" data-step="-1" class="grid size-8 place-items-center rounded-full font-bold transition hover:bg-paper-warm" aria-label="{{ __('shop.product.decrease') }}">−</button>
                                <label for="adet-{{ $item->id }}" class="sr-only">{{ __('shop.cart.quantity_of', ['name' => $item->product->translated('name')]) }}</label>
                                <input id="adet-{{ $item->id }}" type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="99"
                                       class="w-9 bg-transparent text-center text-sm font-bold tabular-nums focus:outline-none sm:w-10 sm:text-base [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" data-step="1" class="grid size-8 place-items-center rounded-full font-bold transition hover:bg-paper-warm" aria-label="{{ __('shop.product.increase') }}">+</button>
                                <noscript><button type="submit" class="px-2 text-xs font-bold underline">{{ __('shop.cart.update') }}</button></noscript>
                            </form>

                            <p class="hidden font-display text-2xl whitespace-nowrap tabular-nums sm:block">{{ number_format($item->lineTotal(), 2, ',', '.') }} ₺</p>

                            <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-ink-faint transition hover:text-bole">
                                    {{ __('shop.cart.remove') }}
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach

                <div class="flex flex-wrap items-center justify-between gap-3 pt-1 sm:pt-2">
                    <a href="{{ route('products.index') }}" class="link-sun text-xs font-semibold sm:text-sm">← {{ __('site.actions.continue_shopping') }}</a>

                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-ink-faint transition hover:text-bole sm:text-sm">{{ __('shop.cart.clear') }}</button>
                    </form>
                </div>
            </div>

            {{-- Summary --}}
            <aside class="tile tile-raised overflow-hidden lg:sticky lg:top-28">
                <div class="bg-ink p-4 text-paper-warm sm:p-6">
                    <h2 class="font-display text-lg sm:text-2xl">{{ __('shop.cart.summary') }}</h2>
                </div>

                <dl class="space-y-3 p-4 text-sm sm:space-y-3.5 sm:p-6">
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">{{ __('shop.cart.subtotal') }}</dt>
                        <dd class="font-semibold tabular-nums">{{ number_format($subtotal, 2, ',', '.') }} ₺</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">{{ __('shop.cart.shipping') }}</dt>
                        <dd class="font-semibold tabular-nums {{ $shippingFee === 0.0 ? 'text-yaprak' : '' }}">
                            {{ $shippingFee === 0.0 ? __('shop.cart.free') : number_format($shippingFee, 2, ',', '.').' ₺' }}
                        </dd>
                    </div>

                    <div class="flex items-baseline justify-between border-t-2 border-paper-deep pt-3.5 sm:pt-4">
                        <dt class="font-display text-base sm:text-xl">{{ __('shop.cart.total') }}</dt>
                        <dd class="font-display text-2xl tabular-nums sm:text-3xl">{{ number_format($total, 2, ',', '.') }} ₺</dd>
                    </div>
                </dl>

                <div class="px-4 pb-4 sm:px-6 sm:pb-6">
                    <a href="{{ route('checkout.index') }}" class="btn btn-sun w-full py-3.5 sm:py-4">{{ __('shop.cart.checkout') }}</a>

                    <p class="mt-3 flex items-center justify-center gap-1.5 text-[0.7rem] text-ink-soft sm:mt-4 sm:gap-2 sm:text-xs">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        {{ __('shop.cart.secure') }}
                    </p>
                </div>
            </aside>
        </div>
    @endif
</section>

@endsection
