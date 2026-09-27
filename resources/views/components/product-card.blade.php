@props(['product', 'delay' => 0, 'compact' => false])

<article {{ $attributes->merge(['class' => 'card reveal group']) }}
         style="--card-accent: {{ $product->color_hex }}; --reveal-delay: {{ $delay }}ms">

    <div class="card-media arch-top">
        <a href="{{ route('products.show', $product) }}" class="block" tabindex="-1" aria-hidden="true">
            <img src="{{ asset($product->image_path) }}"
                 alt=""
                 loading="lazy"
                 width="1080" height="1080"
                 class="aspect-square w-full object-cover">
        </a>

        {{-- Badges sit along the straight lower edge, clear of the arch --}}
        <div class="absolute bottom-2.5 left-3 z-2 flex flex-wrap items-center gap-1.5 sm:bottom-3.5 sm:left-4 sm:gap-2">
            @if ($product->badge)
                <span class="badge animate-wiggle bg-ink text-paper-warm shadow-[0_8px_20px_-10px_rgba(43,27,61,.9)]">
                    {{ $product->badgeLabel() }}
                </span>
            @endif

            @unless ($product->isInStock())
                <span class="badge bg-ink-soft text-white">{{ __('shop.card.sold_out') }}</span>
            @endunless
        </div>
    </div>

    <div class="flex flex-1 flex-col p-3.5 sm:p-5">
        <div class="mb-1.5 flex items-center justify-between gap-2 sm:mb-2 sm:gap-3">
            <span class="text-[0.7rem] font-bold tracking-wide sm:text-xs" style="color: {{ $product->color_hex }}">{{ $product->categoryLabel() }}</span>

            <span class="flex items-center gap-1 text-[0.7rem] font-semibold text-ink-soft sm:text-xs">
                <svg class="size-3 text-sun sm:size-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.3 6.8.8-5 4.7 1.3 6.8L12 17.3 6 20.6l1.3-6.8-5-4.7 6.8-.8L12 2Z"/></svg>
                {{ number_format((float) $product->rating, 1, ',', '') }}
                <span class="hidden font-normal text-ink-faint sm:inline">({{ $product->review_count }})</span>
            </span>
        </div>

        <h3 class="font-display text-lg leading-tight sm:text-2xl">
            <a href="{{ route('products.show', $product) }}" class="transition-colors hover:text-bole after:absolute after:inset-0 after:content-['']">
                {{ $product->translated('name') }}
            </a>
        </h3>

        @unless ($compact)
            <p class="mt-1 text-xs leading-snug text-ink-soft sm:mt-1.5 sm:text-sm">{{ $product->translated('tagline') }}</p>
        @endunless

        <div class="mt-auto flex flex-wrap items-end justify-between gap-x-3 gap-y-1 pt-3 sm:pt-5">
            <x-price :product="$product" />

            <span class="link-sun text-[0.7rem] font-semibold text-bole sm:text-xs">{{ __('shop.card.view') }} →</span>
        </div>
    </div>
</article>
