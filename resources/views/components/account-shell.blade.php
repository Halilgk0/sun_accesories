@props(['title', 'lead' => null])

@php
    $links = [
        ['route' => 'account.index', 'label' => __('account.panel'), 'icon' => 'M4 4h7v7H4zM13 4h7v4h-7zM13 10h7v10h-7zM4 13h7v7H4z'],
        ['route' => 'account.orders', 'label' => __('site.actions.orders'), 'icon' => 'M4 7h16l-1.4 11.2A2 2 0 0 1 16.6 20H7.4a2 2 0 0 1-2-1.8L4 7ZM9 10V6a3 3 0 0 1 6 0v4'],
        ['route' => 'account.favorites', 'label' => __('site.actions.favorites'), 'icon' => 'M12 20.5s-7.5-4.6-7.5-9.6a4.3 4.3 0 0 1 7.5-2.8 4.3 4.3 0 0 1 7.5 2.8c0 5-7.5 9.6-7.5 9.6Z'],
    ];
@endphp

<section class="relative overflow-hidden bg-paper-warm pt-9 pb-10 sm:pt-14 sm:pb-16">
    <x-sun class="-top-28 -right-24 opacity-50 sm:-top-40 sm:-right-32" size="18rem" sm-size="30rem" />

    <div class="wrap relative flex flex-wrap items-end justify-between gap-4 sm:gap-6">
        <div>
            <p class="mb-1.5 text-xs font-semibold text-ink-soft sm:mb-2 sm:text-sm">{{ __('account.greeting', ['name' => Str::before(auth()->user()->name, ' ')]) }}</p>
            <h1 class="display-lg">{{ $title }}</h1>
            @if ($lead)
                <p class="mt-3 max-w-[52ch] text-sm text-ink-soft sm:mt-4 sm:text-lg">{{ $lead }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-quiet">{{ __('site.actions.logout') }}</button>
        </form>
    </div>
</section>

<section class="wrap grid gap-5 py-8 sm:gap-8 sm:py-12 lg:grid-cols-[16rem_1fr] lg:items-start">

    {{-- On a phone the menu is a scrollable strip rather than a stacked column --}}
    <nav class="tile overflow-hidden p-1.5 lg:sticky lg:top-28 lg:p-2" aria-label="{{ __('account.menu') }}">
        <div class="no-scrollbar flex gap-1 overflow-x-auto lg:flex-col lg:overflow-visible">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   @if (request()->routeIs($link['route'])) aria-current="page" @endif
                   class="flex shrink-0 items-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition hover:bg-paper-warm sm:text-sm lg:gap-3 lg:rounded-2xl lg:px-4 lg:py-3.5 {{ request()->routeIs($link['route']) ? 'bg-sun-pale text-ink' : 'text-ink-soft' }}">
                    <svg class="size-4 shrink-0 sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $link['icon'] }}"/></svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    <div class="min-w-0">
        {{ $slot }}
    </div>
</section>
