@extends('layouts.app')

@section('title', $product->translated('name'))
@section('description', $product->translated('tagline').' — '.Str::limit(strip_tags($product->translated('description')), 120))

@section('content')

<section class="relative overflow-hidden pt-6 pb-12 sm:pt-10 sm:pb-20">
    <x-sun class="-top-24 -right-28 opacity-50 sm:-top-36 sm:-right-40" size="20rem" sm-size="34rem" />

    <div class="wrap relative">

        <nav class="mb-5 flex flex-wrap items-center gap-1.5 text-xs text-ink-soft sm:mb-8 sm:gap-2 sm:text-sm" aria-label="{{ __('site.nav.aria') }}">
            <a href="{{ route('home') }}" class="transition hover:text-bole">{{ __('site.breadcrumb.home') }}</a>
            <span class="text-ink-faint">/</span>
            <a href="{{ route('products.index', ['kategori' => $product->category]) }}" class="transition hover:text-bole">{{ $product->categoryLabel() }}</a>
            <span class="text-ink-faint">/</span>
            <span class="font-semibold text-ink">{{ $product->translated('name') }}</span>
        </nav>

        <div class="grid gap-8 sm:gap-12 lg:grid-cols-[1.05fr_1fr] lg:gap-16">

            {{-- Gallery --}}
            <div>
                <div data-zoom class="arch-sm relative overflow-hidden border-4 border-paper shadow-[0_45px_85px_-50px_rgba(43,27,61,.6)] sm:cursor-zoom-in">
                    <img src="{{ asset($product->image_path) }}"
                         alt="{{ $product->translated('name') }} — {{ $product->translated('tagline') }}"
                         width="1080" height="1080"
                         class="aspect-square w-full object-cover transition-transform duration-300 ease-out">

                    {{-- Along the straight lower edge, clear of the arch's curve --}}
                    @if ($product->isOnSale())
                        <span class="badge absolute bottom-4 left-4 bg-bole text-white shadow-lg sm:bottom-5 sm:left-5">
                            {{ __('shop.card.discount', ['percent' => $product->discountPercentage()]) }}
                        </span>
                    @endif
                </div>

                <p class="mt-2.5 hidden text-center text-xs text-ink-faint sm:block">{{ __('shop.product.zoom_hint') }}</p>

                {{-- Detail chips --}}
                <dl class="mt-5 grid grid-cols-3 gap-2 sm:mt-7 sm:gap-3">
                    @foreach ([
                        [__('shop.product.material'), $product->translated('material')],
                        [__('shop.product.stone'), $product->translated('stone')],
                        [__('shop.product.category'), $product->categoryLabel()],
                    ] as [$label, $value])
                        <div class="tile p-2.5 text-center sm:p-4">
                            <dt class="text-[0.65rem] text-ink-soft sm:text-xs">{{ $label }}</dt>
                            <dd class="mt-0.5 text-xs font-semibold sm:mt-1 sm:text-sm">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Buy box --}}
            <div class="lg:pt-4">
                @if ($product->badge)
                    <span class="badge mb-3 bg-ink text-paper-warm sm:mb-4">{{ $product->badgeLabel() }}</span>
                @endif

                <h1 class="display-lg">{{ $product->translated('name') }}</h1>
                <p class="mt-2 font-display text-base text-ink-soft italic sm:mt-3 sm:text-xl">{{ $product->translated('tagline') }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 sm:mt-6 sm:gap-5">
                    <span class="flex items-center gap-1.5">
                        <span class="flex gap-0.5 text-sun">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="size-3.5 sm:size-4" viewBox="0 0 24 24" fill="{{ $i < round($product->rating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="m12 2 2.9 6.3 6.8.8-5 4.7 1.3 6.8L12 17.3 6 20.6l1.3-6.8-5-4.7 6.8-.8L12 2Z"/></svg>
                            @endfor
                        </span>
                        <span class="text-xs font-semibold sm:text-sm">{{ number_format((float) $product->rating, 1, ',', '') }}</span>
                        <span class="text-xs text-ink-soft sm:text-sm">{{ __('shop.product.reviews', ['count' => $product->review_count]) }}</span>
                    </span>

                    @if ($product->isInStock())
                        <span class="flex items-center gap-1.5 text-xs font-semibold text-yaprak sm:text-sm">
                            <span class="size-2 rounded-full bg-yaprak"></span>
                            {{ __('shop.product.in_stock', ['count' => $product->stock]) }}
                        </span>
                    @else
                        <span class="text-xs font-semibold text-bole sm:text-sm">{{ __('shop.product.out_of_stock') }}</span>
                    @endif
                </div>

                <x-price :product="$product" size="lg" class="mt-5 sm:mt-7" />
                <p class="mt-1 text-xs text-ink-soft sm:mt-1.5 sm:text-sm">{{ __('shop.product.tax_note') }}</p>

                <p class="mt-5 max-w-[56ch] text-sm leading-relaxed text-ink-soft sm:mt-7 sm:text-lg">{{ $product->translated('description') }}</p>

                {{-- Add to cart --}}
                @if ($product->isInStock())
                    <div class="mt-6 flex flex-wrap items-center gap-2.5 sm:mt-9 sm:gap-3">
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="flex flex-1 flex-wrap items-center gap-2.5 sm:gap-3">
                            @csrf

                            <div data-quantity class="flex items-center gap-0.5 rounded-full border-2 border-paper-deep p-1 sm:gap-1 sm:p-1.5">
                                <button type="button" data-step="-1" class="grid size-9 place-items-center rounded-full text-lg font-bold transition hover:bg-paper-warm sm:size-10 sm:text-xl" aria-label="{{ __('shop.product.decrease') }}">−</button>
                                <label for="adet" class="sr-only">{{ __('shop.product.quantity') }}</label>
                                <input id="adet" type="number" name="quantity" value="1" min="1" max="10"
                                       class="w-10 bg-transparent text-center text-base font-bold tabular-nums focus:outline-none sm:w-12 sm:text-lg [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" data-step="1" class="grid size-9 place-items-center rounded-full text-lg font-bold transition hover:bg-paper-warm sm:size-10 sm:text-xl" aria-label="{{ __('shop.product.increase') }}">+</button>
                            </div>

                            <button type="submit" class="btn btn-sun flex-1 py-3.5 sm:flex-none sm:px-10 sm:py-4">
                                {{ __('shop.card.add') }}
                            </button>
                        </form>

                        @auth
                            <form method="POST" action="{{ route('favorites.store', $product) }}" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="btn btn-outline w-full py-3.5 sm:w-auto sm:py-4" aria-label="{{ __('shop.card.favorite_add', ['name' => $product->translated('name')]) }}">
                                    <svg class="size-4.5 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 20.5s-7.5-4.6-7.5-9.6a4.3 4.3 0 0 1 7.5-2.8 4.3 4.3 0 0 1 7.5 2.8c0 5-7.5 9.6-7.5 9.6Z"/></svg>
                                    {{ __('shop.product.favorite') }}
                                </button>
                            </form>
                        @endauth
                    </div>
                @else
                    <p class="tile mt-6 p-4 text-sm text-ink-soft sm:mt-9 sm:p-5">
                        {{ __('shop.product.sold_out_text') }}
                        <a href="{{ route('contact') }}" class="link-sun font-semibold text-bole">{{ __('shop.product.sold_out_link') }}</a>.
                    </p>
                @endif

                {{-- Care & shipping --}}
                <div class="mt-7 space-y-2.5 sm:mt-10 sm:space-y-3">
                    @foreach (['shipping', 'care', 'returns'] as $index => $key)
                        <details class="tile group overflow-hidden p-0" @if($index === 0) open @endif>
                            <summary class="flex cursor-pointer items-center justify-between gap-3 p-4 text-sm font-semibold transition hover:bg-paper-warm sm:gap-4 sm:p-5 sm:text-base">
                                {{ __('shop.product.'.$key.'_title') }}
                                <span class="grid size-6 shrink-0 place-items-center rounded-full bg-paper-warm transition-transform duration-300 group-open:rotate-45 sm:size-7">
                                    <svg class="size-3 sm:size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                </span>
                            </summary>
                            <p class="px-4 pb-4 text-xs leading-relaxed text-ink-soft sm:px-5 sm:pb-5 sm:text-sm">{{ __('shop.product.'.$key.'_text') }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Related --}}
@if ($related->isNotEmpty())
    <section class="relative bg-paper-warm py-12 sm:py-20">
        <div class="wrap">
            <h2 class="display-md mb-6 sm:mb-10">{{ __('shop.product.related') }}</h2>

            <div class="grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-4">
                @foreach ($related as $index => $item)
                    <x-product-card :product="$item" :delay="$index * 90" compact />
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
