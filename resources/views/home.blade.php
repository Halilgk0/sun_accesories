@extends('layouts.app')

@section('title', __('pages.home.meta_title'))

@section('content')

{{-- ============================================================ HERO --}}
<section class="relative overflow-hidden bg-linear-to-b from-paper-warm via-paper to-paper pt-8 pb-14 sm:pt-12 sm:pb-20 lg:pt-16 lg:pb-24">

    <x-sun class="-top-28 -left-24 opacity-70 sm:-top-40 sm:-left-32" size="22rem" sm-size="42rem" />
    <div class="pointer-events-none absolute top-1/3 -right-32 size-72 rounded-full bg-turkuaz-pale/50 blur-3xl sm:-right-40 sm:size-[30rem]" aria-hidden="true"></div>

    <div class="wrap relative grid items-center gap-9 sm:gap-12 lg:grid-cols-[1.05fr_1fr] lg:gap-14">

        {{-- Copy --}}
        <div class="relative">
            <p class="mb-4 inline-flex items-center gap-2 rounded-full border-2 border-sun/40 bg-paper px-3 py-1.5 text-xs font-semibold text-ink sm:mb-5 sm:gap-2.5 sm:px-4 sm:py-2 sm:text-sm">
                <span class="relative flex size-2 sm:size-2.5">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-bole opacity-70"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-bole sm:size-2.5"></span>
                </span>
                {{ __('pages.home.eyebrow') }}
            </p>

            <h1 class="display-xl">
                {{ __('pages.home.title_1') }}
                <span class="relative inline-block">
                    <span class="sun-text">{{ __('pages.home.title_accent') }}</span>
                    <svg class="underline-draw absolute -bottom-1.5 left-0 w-full sm:-bottom-2" height="16" viewBox="0 0 300 16" fill="none" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M3 11C62 4 148 2 297 8" stroke="#D13A24" stroke-width="5" stroke-linecap="round"/>
                    </svg>
                </span>
                <br>{{ __('pages.home.title_2') }}
            </h1>

            <p class="mt-6 max-w-[46ch] text-base leading-relaxed text-ink-soft sm:mt-8 sm:text-lg">
                {{ __('pages.home.lead') }}
            </p>

            <div class="mt-7 flex flex-wrap items-center gap-2.5 sm:mt-9 sm:gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-sun">
                    {{ __('pages.home.cta_primary') }}
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h13m-5-6 6 6-6 6"/></svg>
                </a>
                <a href="{{ route('about') }}" class="btn btn-outline">{{ __('pages.home.cta_secondary') }}</a>
            </div>

            {{-- What the atelier actually promises — no shipping or returns
                 claims, because nothing is sold through this site. --}}
            <dl class="mt-9 grid max-w-md grid-cols-3 gap-4 border-t-2 border-paper-deep pt-5 sm:mt-12 sm:gap-6 sm:pt-7">
                @foreach ([
                    ['5', __('pages.home.trust_pieces')],
                    ['7', __('pages.home.trust_years')],
                    ['2', __('pages.home.trust_warranty')],
                ] as [$value, $label])
                    <div>
                        <dt class="font-display text-lg text-bole sm:text-2xl">{{ $value }}</dt>
                        <dd class="mt-0.5 text-[0.7rem] leading-snug text-ink-soft sm:mt-1 sm:text-xs">{{ $label }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        {{-- Arched hero image --}}
        <div class="relative mx-auto w-full max-w-sm lg:max-w-none">
            <div class="arch relative overflow-hidden border-4 border-paper shadow-[0_50px_90px_-50px_rgba(43,27,61,.65)]"
                 data-tilt="5">
                <img src="{{ asset('images/hero.jpg') }}"
                     alt="{{ __('pages.home.lead') }}"
                     width="1920" height="1080"
                     class="aspect-4/5 w-full object-cover object-[62%_center]">

                <div class="pointer-events-none absolute inset-0 bg-linear-to-t from-ink/25 via-transparent to-transparent"></div>
            </div>

            {{-- Floating product chip --}}
            @if ($featured->isNotEmpty())
                @php($chip = $featured->first())
                <a href="{{ route('products.show', $chip) }}"
                   class="absolute -bottom-5 -left-2 flex w-52 animate-float-slow items-center gap-2.5 rounded-2xl border-2 border-paper-deep bg-paper p-2 shadow-[0_28px_50px_-30px_rgba(43,27,61,.6)] transition hover:-translate-y-1.5 hover:border-sun sm:-bottom-6 sm:-left-6 sm:w-64 sm:gap-3 sm:rounded-3xl sm:p-3">
                    <img src="{{ asset($chip->image_path) }}" alt="" width="72" height="72" class="size-12 shrink-0 rounded-xl object-cover sm:size-16 sm:rounded-2xl">
                    <span class="min-w-0">
                        <span class="block font-display text-sm leading-tight sm:text-lg">{{ $chip->translated('name') }}</span>
                        <span class="block text-xs font-semibold text-bole sm:text-sm">{{ \App\Support\Format::price((float) $chip->price, 0) }}</span>
                    </span>
                </a>
            @endif

            {{-- Hand-drawn note --}}
            <div class="absolute -top-3 -right-1 rotate-6 animate-sway rounded-xl border-2 border-ink bg-sun px-2.5 py-1.5 text-xs font-bold text-ink shadow-[3px_3px_0_var(--color-ink)] sm:-top-4 sm:-right-2 sm:rounded-2xl sm:px-4 sm:py-2.5 sm:text-sm sm:shadow-[4px_4px_0_var(--color-ink)]">
                {{ __('pages.home.handmade_note') }}
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ CATEGORIES --}}
<section class="relative bg-ink py-12 text-paper-warm sm:py-20">
    <div class="scallop-top absolute inset-x-0 top-0 h-6 bg-paper sm:h-10" aria-hidden="true"></div>

    <div class="wrap relative pt-5 sm:pt-8">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-3 sm:mb-12 sm:gap-5">
            <h2 class="display-md max-w-[14ch]">{{ __('pages.home.categories_title') }}</h2>
            <a href="{{ route('products.index') }}" class="link-sun text-xs font-semibold text-sun sm:text-sm">{{ __('pages.home.categories_all') }}</a>
        </div>

        <div class="grid grid-cols-2 gap-2.5 sm:gap-4 md:grid-cols-5">
            @foreach ([
                ['necklace', '☀', 'from-sun to-sun-deep'],
                ['earrings', '✿', 'from-blush to-bole'],
                ['bracelet', '✦', 'from-sun-pale to-sun'],
                ['ring', '❀', 'from-bole-bright to-bole'],
                ['anklet', '✧', 'from-turkuaz-bright to-turkuaz'],
            ] as $index => [$key, $glyph, $gradient])
                <a href="{{ route('products.index', ['kategori' => $key]) }}"
                   class="reveal group relative overflow-hidden rounded-2xl border-2 border-white/10 bg-white/5 p-4 text-center transition duration-500 hover:-translate-y-2 hover:border-sun/60 hover:bg-white/10 last:col-span-2 sm:rounded-3xl sm:p-6 md:last:col-span-1"
                   style="--reveal-delay: {{ $index * 90 }}ms">
                    <span class="mx-auto mb-2.5 grid size-10 place-items-center rounded-full bg-linear-to-br {{ $gradient }} text-lg text-white transition-transform duration-500 group-hover:scale-115 group-hover:rotate-12 sm:mb-4 sm:size-14 sm:text-2xl">
                        {{ $glyph }}
                    </span>
                    <span class="block font-display text-base sm:text-xl">{{ __('shop.categories.'.$key) }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ PRODUCTS --}}
<section id="koleksiyon" class="relative overflow-hidden py-14 sm:py-24">
    <x-sun class="top-6 -right-32 opacity-40 sm:top-10 sm:-right-48" size="20rem" sm-size="34rem" />

    <div class="wrap relative">
        <div class="mb-8 max-w-2xl sm:mb-14">
            <h2 class="display-lg">{{ __('pages.home.products_title') }}</h2>
            <p class="mt-4 text-base leading-relaxed text-ink-soft sm:mt-5 sm:text-lg">
                {{ __('pages.home.products_lead') }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-3">
            @foreach ($featured as $index => $product)
                <x-product-card :product="$product" :delay="$index * 110" />
            @endforeach

            {{-- Fills the grid's sixth cell with an invitation rather than a gap --}}
            <a href="{{ route('products.index') }}"
               class="reveal group relative flex flex-col items-center justify-center gap-2.5 overflow-hidden rounded-[1.125rem] border-2 border-dashed border-sun/50 bg-paper-warm p-5 text-center transition hover:border-sun hover:bg-paper-deep/60 sm:min-h-72 sm:gap-4 sm:rounded-[1.75rem] sm:p-8"
               style="--reveal-delay: 560ms">
                <span class="grid size-11 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-lg text-white transition-transform duration-500 group-hover:rotate-[22deg] group-hover:scale-110 sm:size-16 sm:text-2xl">☀</span>
                <span class="font-display text-lg sm:text-2xl">{{ __('shop.card.see_all_title') }}</span>
                <span class="max-w-[24ch] text-xs text-ink-soft sm:text-sm">{{ __('shop.card.see_all_text') }}</span>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================ CAMPAIGN --}}
<section class="wrap">
    <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-bole via-bole-bright to-sun px-5 py-10 text-white sm:rounded-[2.5rem] sm:px-8 sm:py-16 md:px-16">

        <div class="pointer-events-none absolute -top-24 -right-16 size-56 rounded-full bg-white/15 blur-2xl animate-halo sm:size-80" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-10 size-44 bg-white/10 animate-blob sm:size-64" aria-hidden="true"></div>

        <div class="relative grid items-center gap-7 sm:gap-10 lg:grid-cols-[1.3fr_1fr]">
            <div>
                <p class="mb-2.5 text-xs font-bold tracking-wide text-white/80 sm:mb-4 sm:text-sm">{{ __('pages.home.visit_eyebrow') }}</p>
                <h2 class="display-md max-w-[18ch] text-white">{{ __('pages.home.visit_title') }}</h2>
                <p class="mt-3.5 max-w-[48ch] text-sm leading-relaxed text-white/85 sm:mt-5 sm:text-base">
                    {{ __('pages.home.visit_text') }}
                </p>
                <a href="{{ $whatsappUrl ?: route('about') }}" @if($whatsappUrl) target="_blank" rel="noopener" @endif class="btn mt-6 bg-white text-bole hover:scale-105 sm:mt-8">
                    {{ __('pages.home.visit_cta') }}
                </a>
            </div>

            {{-- Opening hours, in place of the old countdown --}}
            <dl class="grid grid-cols-1 gap-2 sm:gap-3">
                @foreach ([
                    [__('pages.about.weekdays'), '09.00 – 18.00'],
                    [__('pages.about.saturday'), '11.00 – 16.00'],
                    [__('pages.about.sunday'), __('pages.about.closed')],
                ] as [$day, $hours])
                    <div class="flex items-center justify-between rounded-xl border-2 border-white/25 bg-white/10 px-3.5 py-2.5 backdrop-blur-sm sm:rounded-2xl sm:px-5 sm:py-3.5">
                        <dt class="text-xs text-white/80 sm:text-sm">{{ $day }}</dt>
                        <dd class="font-display text-base tabular-nums sm:text-xl">{{ $hours }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>

{{-- ============================================================ ATELIER --}}
<section class="relative overflow-hidden py-14 sm:py-24">
    <div class="wrap grid items-center gap-9 sm:gap-14 lg:grid-cols-2">
        <div class="reveal relative mx-auto w-full max-w-md lg:max-w-none">
            <div class="arch-sm overflow-hidden border-4 border-paper shadow-[0_45px_80px_-50px_rgba(43,27,61,.6)]" data-parallax="-0.03">
                <img src="{{ asset('images/atolye.jpg') }}"
                     alt="{{ __('pages.about.title') }}"
                     width="1600" height="1200"
                     class="aspect-4/3 w-full object-cover">
            </div>

            <div class="absolute -right-1 -bottom-5 w-36 rotate-[-5deg] rounded-xl border-2 border-ink bg-paper p-3 shadow-[4px_4px_0_var(--color-ink)] sm:-right-3 sm:-bottom-7 sm:w-44 sm:rounded-2xl sm:p-4 sm:shadow-[5px_5px_0_var(--color-ink)]">
                <p class="font-display text-2xl leading-none text-bole sm:text-3xl">7</p>
                <p class="mt-1 text-[0.68rem] leading-snug text-ink-soft sm:text-xs">{{ __('pages.home.atelier_years') }}</p>
            </div>
        </div>

        <div class="reveal" style="--reveal-delay: 150ms">
            <h2 class="display-lg">{{ __('pages.home.atelier_title') }}</h2>
            <p class="mt-5 text-base leading-relaxed text-ink-soft sm:mt-6 sm:text-lg">
                {{ __('pages.home.atelier_text_1') }}
            </p>

            <ul class="mt-7 space-y-3.5 sm:mt-9 sm:space-y-4">
                @foreach ([1, 2, 3] as $i)
                    <li class="flex gap-3 sm:gap-4">
                        <span class="mt-0.5 grid size-6 shrink-0 place-items-center rounded-full bg-sun-pale text-sun-deep sm:mt-1 sm:size-7">
                            <svg class="size-3 sm:size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                        </span>
                        <span>
                            <strong class="block text-sm font-semibold sm:text-base">{{ __("pages.home.atelier_point_{$i}_title") }}</strong>
                            <span class="text-xs leading-relaxed text-ink-soft sm:text-sm">{{ __("pages.home.atelier_point_{$i}_text") }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>

            <a href="{{ route('about') }}" class="btn btn-ink mt-7 sm:mt-9">{{ __('pages.home.atelier_cta') }}</a>
        </div>
    </div>
</section>

{{-- ============================================================ REVIEWS --}}
<section class="relative overflow-hidden bg-paper-warm py-14 sm:py-24">
    <div class="wrap relative">
        <h2 class="display-md mb-7 max-w-[18ch] sm:mb-12">{{ __('pages.home.reviews_title') }}</h2>

        <div class="grid gap-3.5 sm:gap-6 md:grid-cols-3">
            @foreach ([
                [__('pages.home.review_1'), 'Elif K.', __('pages.home.review_1_piece'), '#F7E7B4'],
                [__('pages.home.review_2'), 'Merve T.', __('pages.home.review_2_piece'), '#F2A007'],
                [__('pages.home.review_3'), 'Zeynep A.', __('pages.home.review_3_piece'), '#4FC3C9'],
            ] as $index => [$quote, $name, $product, $color])
                <figure class="reveal tile tile-raised flex flex-col p-5 sm:p-7"
                        style="--reveal-delay: {{ $index * 120 }}ms">
                    <div class="mb-3 flex gap-0.5 text-sun sm:mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="size-3.5 sm:size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.3 6.8.8-5 4.7 1.3 6.8L12 17.3 6 20.6l1.3-6.8-5-4.7 6.8-.8L12 2Z"/></svg>
                        @endfor
                    </div>

                    <blockquote class="flex-1 font-display text-base leading-snug sm:text-xl">“{{ $quote }}”</blockquote>

                    <figcaption class="mt-4 flex items-center gap-2.5 border-t-2 border-paper-deep pt-4 sm:mt-6 sm:gap-3 sm:pt-5">
                        <span class="grid size-8 place-items-center rounded-full text-sm font-bold text-white sm:size-10" style="background: {{ $color }}">
                            {{ mb_substr($name, 0, 1) }}
                        </span>
                        <span>
                            <span class="block text-xs font-semibold sm:text-sm">{{ $name }}</span>
                            <span class="block text-[0.7rem] text-ink-soft sm:text-xs">{{ $product }}</span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ CTA --}}
<section class="relative overflow-hidden py-16 sm:py-28">
    <x-sun class="top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-50" size="24rem" sm-size="40rem" />

    <div class="wrap relative text-center">
        <h2 class="display-lg mx-auto max-w-[18ch]">{{ __('pages.home.cta_title') }}</h2>
        <p class="mx-auto mt-4 max-w-[48ch] text-base leading-relaxed text-ink-soft sm:mt-6 sm:text-lg">
            {{ __('pages.home.cta_text') }}
        </p>
        <a href="{{ route('products.index') }}" class="btn btn-sun mt-7 sm:mt-10 sm:px-10 sm:py-4 sm:text-lg">{{ __('pages.home.cta_button') }}</a>
    </div>
</section>

@endsection
