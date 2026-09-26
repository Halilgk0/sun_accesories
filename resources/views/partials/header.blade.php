@php
    $nav = [
        ['route' => 'home', 'label' => __('site.nav.home')],
        ['route' => 'products.index', 'label' => __('site.nav.collection')],
        ['route' => 'about', 'label' => __('site.nav.atelier')],
        ['route' => 'contact', 'label' => __('site.nav.contact')],
    ];
    $ticker = [
        ['☀', 'text-sun', __('site.ticker.shipping')],
        ['✿', 'text-blush', __('site.ticker.handmade')],
        ['✦', 'text-turkuaz-bright', __('site.ticker.returns')],
        ['✧', 'text-sun', __('site.ticker.restock')],
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

            @auth
                <a href="{{ route('account.index') }}"
                   class="hidden items-center gap-2.5 rounded-full border-2 border-paper-deep py-1.5 pr-4 pl-1.5 transition hover:border-sun hover:bg-paper-warm lg:flex">
                    <span class="grid size-8 place-items-center rounded-full bg-linear-to-br from-turkuaz to-cobalt text-xs font-bold text-white">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="text-sm font-semibold">{{ Str::before(auth()->user()->name, ' ') }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-quiet hidden lg:inline-flex">{{ __('site.actions.login') }}</a>
            @endauth

            <a href="{{ route('cart.index') }}"
               class="group relative grid size-10 place-items-center rounded-full border-2 border-paper-deep transition hover:border-sun hover:bg-paper-warm sm:size-11"
               aria-label="{{ __('site.actions.cart_aria', ['count' => $cartCount]) }}">
                <svg class="size-4.5 transition-transform duration-500 group-hover:-rotate-12 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 7h16l-1.4 11.2A2 2 0 0 1 16.6 20H7.4a2 2 0 0 1-2-1.8L4 7Z"/>
                    <path d="M9 10V6a3 3 0 0 1 6 0v4"/>
                </svg>
                @if ($cartCount > 0)
                    <span data-cart-count="{{ $cartCount }}"
                          class="absolute -top-1 -right-1 grid min-w-5 place-items-center rounded-full bg-bole px-1.5 py-0.5 text-[0.65rem] font-bold text-white shadow-[0_4px_12px_-4px_rgba(209,58,36,.9)] sm:-top-1.5 sm:-right-1.5 sm:min-w-6 sm:text-[0.7rem]">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

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

            <hr class="my-2.5 border-paper-deep">

            @auth
                <a href="{{ route('account.index') }}" class="rounded-xl px-3 py-2.5 text-sm font-semibold hover:bg-paper-warm">{{ __('site.actions.account') }}</a>
                <a href="{{ route('account.orders') }}" class="rounded-xl px-3 py-2.5 text-sm font-semibold hover:bg-paper-warm">{{ __('site.actions.orders') }}</a>
                <a href="{{ route('account.favorites') }}" class="rounded-xl px-3 py-2.5 text-sm font-semibold hover:bg-paper-warm">{{ __('site.actions.favorites') }}</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-ink mt-1 py-3 text-sm">{{ __('site.actions.login') }}</a>
                <a href="{{ route('register') }}" class="btn btn-outline mt-2 py-3 text-sm">{{ __('site.actions.register') }}</a>
            @endauth

        </nav>
    </div>
</header>
