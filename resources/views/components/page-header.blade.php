@props(['eyebrow' => null, 'title', 'lead' => null])

<section class="relative overflow-hidden bg-paper-warm pt-10 pb-14 sm:pt-16 sm:pb-20">
    <x-sun class="-top-24 -right-16 opacity-60 sm:-top-32 sm:-right-24" size="16rem" sm-size="26rem" />

    <div class="pointer-events-none absolute -bottom-20 -left-16 size-48 bg-turkuaz-pale/70 animate-blob sm:size-72" aria-hidden="true"></div>

    <div class="wrap relative">
        <nav class="mb-4 flex items-center gap-2 text-xs text-ink-soft sm:mb-6 sm:text-sm" aria-label="{{ __('site.nav.aria') }}">
            <a href="{{ route('home') }}" class="transition hover:text-bole">{{ __('site.breadcrumb.home') }}</a>
            <span class="text-ink-faint">/</span>
            <span class="font-semibold text-ink">{{ $eyebrow ?? $title }}</span>
        </nav>

        <h1 class="display-lg max-w-[16ch]">{{ $title }}</h1>

        @if ($lead)
            <p class="mt-4 max-w-[52ch] text-base leading-relaxed text-ink-soft sm:mt-5 sm:text-lg">{{ $lead }}</p>
        @endif

        {{ $slot }}
    </div>
</section>
