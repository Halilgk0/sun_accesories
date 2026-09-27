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
                @if ($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-sun">
                        <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.8 14.2c-.2.7-1.4 1.3-2 1.4-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5-4.5-.2-.2-1.2-1.6-1.2-3s.8-2.1 1-2.4c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .7.5l.9 2.2c.1.2.1.4 0 .6l-.4.5-.3.4c-.1.1-.2.3 0 .6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6 0l.9-1c.2-.2.4-.2.6-.1l2.1 1c.3.1.5.2.5.4.1.1.1.6-.1 1.3Z"/></svg>
                        {{ __('site.actions.whatsapp') }}
                    </a>
                @endif
                <a href="{{ route('products.index') }}" class="btn btn-outline">{{ __('site.actions.browse') }}</a>
            </div>
        </div>
    </div>
</section>

{{-- Where we are, and when the door is open --}}
<section class="wrap pb-14 sm:pb-24">
    <div class="grid gap-3.5 sm:gap-5 lg:grid-cols-2">

        <div class="space-y-3.5 sm:space-y-5">
            @foreach ([
                [__('pages.about.location_title'), __('pages.about.address'), 'M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z'],
                [__('pages.about.email_label'), 'merhaba@sunaccesories.com', 'M3 6h18v12H3zM3 7l9 6 9-6'],
            ] as $index => [$label, $value, $path])
                <div class="tile reveal flex items-start gap-3 p-4 sm:gap-4 sm:p-6" style="--reveal-delay: {{ $index * 110 }}ms">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-sun-pale text-sun-deep sm:size-11 sm:rounded-2xl">
                        <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $path }}"/></svg>
                    </span>
                    <span class="min-w-0">
                        <strong class="block text-xs text-ink-soft sm:text-sm">{{ $label }}</strong>
                        <span class="mt-0.5 block text-sm font-semibold break-words sm:text-base">{{ $value }}</span>
                    </span>
                </div>
            @endforeach
        </div>

        <div class="tile overflow-hidden">
            <div class="border-b-2 border-paper-deep p-4 sm:p-6">
                <h2 class="font-display text-lg sm:text-2xl">{{ __('pages.about.hours_title') }}</h2>
            </div>
            <dl class="divide-y-2 divide-paper-deep text-sm">
                @foreach ([[__('pages.about.weekdays'), '09.00 – 18.00'], [__('pages.about.saturday'), '11.00 – 16.00'], [__('pages.about.sunday'), __('pages.about.closed')]] as [$day, $hours])
                    <div class="flex justify-between p-3.5 sm:p-4">
                        <dt class="text-ink-soft">{{ $day }}</dt>
                        <dd class="font-semibold tabular-nums {{ $hours === __('pages.about.closed') ? 'text-bole' : '' }}">{{ $hours }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section id="sss" class="wrap scroll-mt-28 pb-14 sm:pb-24">
    <h2 class="display-md mb-6 max-w-[20ch] sm:mb-10">{{ __('pages.about.faq_title') }}</h2>

    <div class="grid gap-2.5 sm:gap-3 md:grid-cols-2">
        @foreach ([1, 2, 3, 4, 5, 6] as $i)
            <details class="tile group overflow-hidden p-0">
                <summary class="flex cursor-pointer items-center justify-between gap-3 p-4 text-sm font-semibold transition hover:bg-paper-warm sm:gap-4 sm:p-5 sm:text-base">
                    {{ __("pages.about.faq_{$i}_q") }}
                    <span class="grid size-6 shrink-0 place-items-center rounded-full bg-paper-warm transition-transform duration-300 group-open:rotate-45 sm:size-7">
                        <svg class="size-3 sm:size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    </span>
                </summary>
                <p class="px-4 pb-4 text-xs leading-relaxed text-ink-soft sm:px-5 sm:pb-5 sm:text-sm">{{ __("pages.about.faq_{$i}_a") }}</p>
            </details>
        @endforeach
    </div>
</section>

@endsection
