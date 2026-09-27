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

                {{-- This is a catalogue, not a checkout: the piece is bought by
                     talking to the atelier, so the page hands the visitor over
                     with the product already named. --}}
                <div class="tile mt-6 border-sun bg-sun-pale/30 p-4 sm:mt-9 sm:p-6">
                    <p class="font-display text-lg sm:text-2xl">{{ __('shop.product.enquire_title') }}</p>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-soft sm:mt-2">
                        {{ $product->isInStock() ? __('shop.product.enquire_text') : __('shop.product.enquire_text_sold_out') }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2.5 sm:mt-5 sm:gap-3">
                        <a href="{{ route('contact', ['urun' => $product->translated('name')]) }}" class="btn btn-sun flex-1 py-3.5 sm:flex-none sm:px-8 sm:py-4">
                            <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18v12H3zM3 7l9 6 9-6"/></svg>
                            {{ __('shop.product.enquire_cta') }}
                        </a>
                        <a href="tel:+902320000000" class="btn btn-outline flex-1 py-3.5 sm:flex-none sm:px-8 sm:py-4">
                            <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1.1 1A16 16 0 0 1 4 5.1 1 1 0 0 1 5 4Z"/></svg>
                            {{ __('shop.product.call_cta') }}
                        </a>
                    </div>
                </div>

                {{-- Care & shipping --}}
                <div class="mt-7 space-y-2.5 sm:mt-10 sm:space-y-3">
                    @foreach (['howto', 'care', 'warranty'] as $index => $key)
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
