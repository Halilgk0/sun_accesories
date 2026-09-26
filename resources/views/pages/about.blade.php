@extends('layouts.app')

@section('title', __('pages.about.meta_title'))

@section('content')

<x-page-header
    :eyebrow="__('site.nav.atelier')"
    :title="__('pages.about.title')"
    :lead="__('pages.about.lead')" />

{{-- Story --}}
<section class="wrap grid items-center gap-9 py-12 sm:gap-14 sm:py-20 lg:grid-cols-2">
    <div class="reveal relative mx-auto w-full max-w-md lg:max-w-none">
        <div class="arch-sm overflow-hidden border-4 border-paper shadow-[0_45px_85px_-50px_rgba(43,27,61,.6)]">
            <img src="{{ asset('images/atolye.jpg') }}"
                 alt="{{ __('pages.about.title') }}"
                 width="1600" height="1200" class="aspect-4/3 w-full object-cover">
        </div>

        <div class="absolute -right-2 -bottom-5 rotate-3 rounded-xl border-2 border-ink bg-sun px-3.5 py-2 shadow-[4px_4px_0_var(--color-ink)] sm:-right-4 sm:-bottom-8 sm:rounded-2xl sm:px-5 sm:py-3 sm:shadow-[5px_5px_0_var(--color-ink)]">
            <p class="font-display text-base text-ink sm:text-xl">{{ __('pages.about.location') }}</p>
        </div>
    </div>

    <div class="reveal" style="--reveal-delay: 140ms">
        <h2 class="display-md">{{ __('pages.about.why_title') }}</h2>
        <p class="mt-5 text-sm leading-relaxed text-ink-soft sm:mt-6 sm:text-lg">
            {{ __('pages.about.why_text_1') }}
        </p>
        <p class="mt-4 text-sm leading-relaxed text-ink-soft sm:mt-5 sm:text-lg">
            {{ __('pages.about.why_text_2') }}
        </p>
    </div>
</section>

{{-- Process — a genuine sequence, so numbering earns its place --}}
<section class="relative bg-ink py-12 text-paper-warm sm:py-20">
    <div class="scallop-top absolute inset-x-0 top-0 h-6 bg-paper sm:h-10" aria-hidden="true"></div>

    <div class="wrap relative pt-5 sm:pt-8">
        <h2 class="display-md mb-8 max-w-[20ch] sm:mb-14">{{ __('pages.about.process_title') }}</h2>

        <ol class="grid gap-3.5 sm:gap-6 md:grid-cols-4">
            @foreach ([1, 2, 3, 4] as $index => $step)
                <li class="reveal relative rounded-2xl border-2 border-white/10 bg-white/5 p-4 sm:rounded-3xl sm:p-7"
                    style="--reveal-delay: {{ $index * 130 }}ms">
                    <span class="mb-3 grid size-9 place-items-center rounded-full bg-linear-to-br from-sun to-bole font-display text-base font-bold text-white sm:mb-5 sm:size-12 sm:text-xl">
                        {{ $step }}
                    </span>
                    <h3 class="font-display text-lg text-sun sm:text-2xl">{{ __("pages.about.step_{$step}_title") }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-paper-warm/75 sm:mt-3 sm:text-sm">{{ __("pages.about.step_{$step}_text") }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Values --}}
<section class="wrap py-14 sm:py-24">
    <h2 class="display-md mb-7 max-w-[20ch] sm:mb-12">{{ __('pages.about.values_title') }}</h2>

    <div class="grid gap-3.5 sm:gap-6 md:grid-cols-3">
        @foreach ([[1, '#0E8D87'], [2, '#D13A24'], [3, '#FFB300']] as $index => [$value, $color])
            <article class="tile reveal p-5 sm:p-7" style="--reveal-delay: {{ $index * 120 }}ms">
                <span class="mb-3.5 block h-1.5 w-11 rounded-full sm:mb-5 sm:w-14" style="background: {{ $color }}"></span>
                <h3 class="font-display text-lg sm:text-2xl">{{ __("pages.about.value_{$value}_title") }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink-soft sm:mt-3 sm:text-base">{{ __("pages.about.value_{$value}_text") }}</p>
            </article>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="wrap pb-14 sm:pb-24">
    <div class="relative overflow-hidden rounded-3xl bg-paper-warm px-5 py-10 text-center sm:rounded-[2.5rem] sm:px-8 sm:py-16 md:px-16">
        <x-sun class="-top-28 left-1/2 -translate-x-1/2 opacity-60 sm:-top-40" size="18rem" sm-size="32rem" />

        <div class="relative">
            <h2 class="display-md mx-auto max-w-[20ch]">{{ __('pages.about.visit_title') }}</h2>
            <p class="mx-auto mt-4 max-w-[48ch] text-sm leading-relaxed text-ink-soft sm:mt-5 sm:text-lg">
                {{ __('pages.about.visit_text') }}
            </p>
            <div class="mt-7 flex flex-wrap justify-center gap-2.5 sm:mt-9 sm:gap-3">
                <a href="{{ route('contact') }}" class="btn btn-sun">{{ __('pages.about.visit_cta') }}</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline">{{ __('site.actions.browse') }}</a>
            </div>
        </div>
    </div>
</section>

@endsection
