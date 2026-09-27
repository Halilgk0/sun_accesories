@php
    $nav = [
        ['route' => 'home', 'label' => __('site.nav.home')],
        ['route' => 'products.index', 'label' => __('site.nav.collection')],
        ['route' => 'about', 'label' => __('site.nav.atelier')],
    ];
    $ticker = [
        ['☀', 'text-sun', __('site.ticker.handmade')],
        ['✿', 'text-blush', __('site.ticker.atelier')],
        ['✦', 'text-turkuaz-bright', __('site.ticker.restock')],
        ['✧', 'text-sun', __('site.ticker.enquire')],
    ];
@endphp

<a href="#icerik" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-70 focus:rounded-full focus:bg-ink focus:px-5 focus:py-3 focus:text-paper-warm">
    {{ __('site.skip_to_content') }}
</a>

{{-- Announcement ticker --}}
<div class="marquee relative overflow-hidden bg-ink py-1.5 text-paper-warm sm:py-2.5">
    <div class="marquee-track gap-6 text-[0.7rem] font-medium tracking-wide sm:gap-10 sm:text-sm">
        @for ($i = 0; $i < 2; $i++)
            <span class="flex shrink-0 items-center gap-6 pr-6 sm:gap-10 sm:pr-10" @if($i === 1) aria-hidden="true" @endif>
                @foreach ($ticker as [$glyph, $color, $text])
                    <span class="flex items-center gap-1.5 sm:gap-2"><span class="{{ $color }}">{{ $glyph }}</span> {{ $text }}</span>
                @endforeach
            </span>
        @endfor
    </div>
</div>

<header data-header
        class="sticky top-0 z-50 border-b-2 border-paper-deep bg-paper/85 backdrop-blur-xl transition-shadow duration-300 [&.is-stuck]:shadow-[0_10px_40px_-28px_rgba(43,27,61,.5)]">
    <div class="wrap flex items-center justify-between gap-3 py-2.5 sm:py-3.5">

        {{-- Wordmark --}}
        <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-2 sm:gap-3">
            <span class="relative grid size-9 place-items-center sm:size-11">
                <span class="sun-rays absolute inset-[-8px] rounded-full opacity-70 transition-opacity group-hover:opacity-100 sm:inset-[-10px]" aria-hidden="true"></span>
                <span class="relative grid size-9 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-base font-black text-white shadow-[0_6px_18px_-6px_rgba(239,138,0,.9)] transition-transform duration-500 group-hover:rotate-[22deg] sm:size-11 sm:text-lg">
                    S
                </span>
            </span>
            <span class="leading-none">
                <span class="block font-display text-lg font-semibold tracking-tight sm:text-xl">{{ __('site.brand_mark') }}</span>
                <span class="block text-[0.6rem] font-semibold tracking-[0.22em] text-ink-soft sm:text-[0.7rem] sm:tracking-[0.28em]">{{ __('site.brand_sub') }}</span>
            </span>
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden items-center gap-7 lg:flex" aria-label="{{ __('site.nav.aria') }}">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   @if (request()->routeIs($item['route'])) aria-current="page" @endif
                   class="link-sun text-[0.95rem] font-semibold text-ink hover:text-bole">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Actions --}}
        <div class="flex items-center gap-1.5 sm:gap-2">

            <x-language-switcher />

            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-quiet hidden lg:inline-flex">
                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.8 14.2c-.2.7-1.4 1.3-2 1.4-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5-4.5-.2-.2-1.2-1.6-1.2-3s.8-2.1 1-2.4c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .7.5l.9 2.2c.1.2.1.4 0 .6l-.4.5-.3.4c-.1.1-.2.3 0 .6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6 0l.9-1c.2-.2.4-.2.6-.1l2.1 1c.3.1.5.2.5.4.1.1.1.6-.1 1.3Z"/></svg>
                    {{ __('site.actions.whatsapp') }}
                </a>
            @endif

            <button type="button" data-nav-toggle aria-expanded="false" aria-controls="mobil-menu"
                    class="grid size-10 place-items-center rounded-full border-2 border-paper-deep transition hover:border-sun sm:size-11 lg:hidden">
                <span class="sr-only">{{ __('site.nav.open') }}</span>
                <svg class="size-4.5 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile panel --}}
    <div id="mobil-menu" data-nav-panel hidden class="max-h-[calc(100dvh-7rem)] overflow-y-auto border-t-2 border-paper-deep bg-paper lg:hidden">
        <nav class="wrap flex flex-col gap-0.5 py-4" aria-label="{{ __('site.nav.mobile_aria') }}">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="rounded-xl px-3 py-2.5 font-display text-lg transition hover:bg-paper-warm {{ request()->routeIs($item['route']) ? 'text-bole' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-ink mt-3 py-3 text-sm">
                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.8 14.2c-.2.7-1.4 1.3-2 1.4-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5-4.5-.2-.2-1.2-1.6-1.2-3s.8-2.1 1-2.4c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .7.5l.9 2.2c.1.2.1.4 0 .6l-.4.5-.3.4c-.1.1-.2.3 0 .6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6 0l.9-1c.2-.2.4-.2.6-.1l2.1 1c.3.1.5.2.5.4.1.1.1.6-.1 1.3Z"/></svg>
                    {{ __('site.actions.whatsapp') }}
                </a>
            @endif
        </nav>
    </div>
</header>
