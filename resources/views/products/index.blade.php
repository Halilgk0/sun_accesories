@extends('layouts.app')

@section('title', __('site.nav.collection'))

@section('content')

<x-page-header
    :eyebrow="__('site.nav.collection')"
    :title="__('shop.catalog.title')"
    :lead="__('shop.catalog.lead')" />

<section class="wrap -mt-6 pb-14 sm:-mt-8 sm:pb-24">

    {{-- Filters. On a phone the whole bar collapses so it never eats the screen. --}}
    <form method="GET" action="{{ route('products.index') }}" class="mb-6 sm:mb-10">
        <div class="tile tile-raised p-3 sm:sticky sm:top-24 sm:z-30 sm:p-4">

            <div class="flex items-center gap-2 rounded-xl bg-paper-warm px-3 py-2 sm:gap-2.5 sm:rounded-2xl sm:px-4 sm:py-2.5">
                <svg class="size-4 shrink-0 text-ink-faint sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <label for="arama" class="sr-only">{{ __('shop.catalog.search_label') }}</label>
                <input id="arama" type="search" name="arama" value="{{ request('arama') }}"
                       placeholder="{{ __('shop.catalog.search_placeholder') }}"
                       class="w-full bg-transparent text-sm placeholder:text-ink-faint focus:outline-none sm:text-[0.95rem]">
            </div>

            <details class="group mt-2.5 sm:hidden" @if($activeCategory !== '' || $activeSort !== '') open @endif>
                <summary class="flex cursor-pointer items-center justify-between rounded-xl bg-paper-warm px-3 py-2 text-sm font-semibold">
                    {{ __('shop.catalog.filters') }}
                    <span class="grid size-5 place-items-center rounded-full bg-paper transition-transform duration-300 group-open:rotate-45">
                        <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    </span>
                </summary>

                <div class="mt-2.5 flex flex-wrap gap-1.5">
                    <a href="{{ route('products.index', array_filter(['sirala' => request('sirala')])) }}"
                       class="btn btn-quiet {{ $activeCategory === '' ? 'border-sun bg-sun-pale' : '' }}">
                        {{ __('shop.catalog.all') }}
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', array_filter(['kategori' => $category, 'sirala' => request('sirala')])) }}"
                           class="btn btn-quiet {{ $activeCategory === $category ? 'border-sun bg-sun-pale' : '' }}">
                            {{ __('shop.categories.'.$category) }}
                        </a>
                    @endforeach
                </div>

                <div class="mt-2.5">
                    <label for="sirala-mobil" class="sr-only">{{ __('shop.catalog.sort') }}</label>
                    <select id="sirala-mobil" name="sirala" onchange="this.form.submit()"
                            class="w-full rounded-xl border-2 border-paper-deep bg-paper px-3 py-2 text-sm font-semibold focus:border-sun focus:outline-none">
                        <option value="" @selected($activeSort === '')>{{ __('shop.catalog.sort_recommended') }}</option>
                        <option value="artan" @selected($activeSort === 'artan')>{{ __('shop.catalog.sort_asc') }}</option>
                        <option value="azalan" @selected($activeSort === 'azalan')>{{ __('shop.catalog.sort_desc') }}</option>
                        <option value="puan" @selected($activeSort === 'puan')>{{ __('shop.catalog.sort_rating') }}</option>
                    </select>
                </div>
            </details>

            <div class="mt-3 hidden items-center gap-3 sm:flex">
                <div class="flex flex-1 flex-wrap items-center gap-1.5">
                    <a href="{{ route('products.index', array_filter(['sirala' => request('sirala')])) }}"
                       class="btn btn-quiet {{ $activeCategory === '' ? 'border-sun bg-sun-pale' : '' }}">
                        {{ __('shop.catalog.all') }}
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', array_filter(['kategori' => $category, 'sirala' => request('sirala')])) }}"
                           class="btn btn-quiet {{ $activeCategory === $category ? 'border-sun bg-sun-pale' : '' }}">
                            {{ __('shop.categories.'.$category) }}
                        </a>
                    @endforeach
                </div>

                <label for="sirala" class="shrink-0 text-sm font-semibold text-ink-soft">{{ __('shop.catalog.sort') }}</label>
                <select id="sirala" name="sirala" onchange="this.form.submit()"
                        class="rounded-xl border-2 border-paper-deep bg-paper px-3 py-2 text-sm font-semibold focus:border-sun focus:outline-none">
                    <option value="" @selected($activeSort === '')>{{ __('shop.catalog.sort_recommended') }}</option>
                    <option value="artan" @selected($activeSort === 'artan')>{{ __('shop.catalog.sort_asc') }}</option>
                    <option value="azalan" @selected($activeSort === 'azalan')>{{ __('shop.catalog.sort_desc') }}</option>
                    <option value="puan" @selected($activeSort === 'puan')>{{ __('shop.catalog.sort_rating') }}</option>
                </select>
            </div>

            @if ($activeCategory !== '')
                <input type="hidden" name="kategori" value="{{ $activeCategory }}">
            @endif
        </div>
    </form>

    {{-- Results --}}
    @if ($products->isEmpty())
        <div class="tile flex flex-col items-center gap-4 px-5 py-14 text-center sm:gap-5 sm:px-6 sm:py-24">
            <span class="grid size-14 place-items-center rounded-full bg-paper-warm text-2xl sm:size-20 sm:text-4xl">🔍</span>
            <h2 class="display-md">{{ __('shop.catalog.empty_title') }}</h2>
            <p class="max-w-[42ch] text-sm text-ink-soft sm:text-base">
                {{ __('shop.catalog.empty_text') }}
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-sun mt-1 sm:mt-2">{{ __('shop.catalog.empty_action') }}</a>
        </div>
    @else
        <p class="mb-4 text-xs font-semibold text-ink-soft sm:mb-6 sm:text-sm">
            {{ __('shop.catalog.count', ['count' => $products->count()]) }}
            @if ($activeCategory !== '')
                · <span class="text-bole">{{ __('shop.categories.'.$activeCategory) }}</span>
            @endif
        </p>

        <div class="grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-3">
            @foreach ($products as $index => $product)
                <x-product-card :product="$product" :delay="$index * 90" />
            @endforeach
        </div>
    @endif
</section>

{{-- Reassurance strip --}}
<section class="wrap pb-14 sm:pb-24">
    <div class="grid gap-3 sm:gap-4 md:grid-cols-3">
        @foreach ([
            ['shipping', '<path d="M2.5 7.5h11v9h-11zM13.5 10.5h3.6l3.4 3.2v2.8h-7z"/><circle cx="7" cy="18.5" r="1.8"/><circle cx="17" cy="18.5" r="1.8"/>'],
            ['returns', '<path d="M20.5 12a8.5 8.5 0 1 1-2.6-6.1"/><path d="M20.5 3.5v5h-5"/>'],
            ['warranty', '<path d="M12 3 4.5 6v6c0 4.4 3.2 8.1 7.5 9 4.3-.9 7.5-4.6 7.5-9V6L12 3Z"/><path d="m9 12 2.2 2.2L15.5 10"/>'],
        ] as [$key, $icon])
            <div class="tile flex items-start gap-3 p-4 sm:gap-4 sm:p-6">
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-sun-pale text-sun-deep sm:size-11 sm:rounded-2xl">
                    <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg>
                </span>
                <span>
                    <strong class="block font-display text-base sm:text-xl">{{ __('shop.promises.'.$key.'_title') }}</strong>
                    <span class="mt-0.5 block text-xs leading-relaxed text-ink-soft sm:mt-1 sm:text-sm">{{ __('shop.promises.'.$key.'_text') }}</span>
                </span>
            </div>
        @endforeach
    </div>
</section>

@endsection
