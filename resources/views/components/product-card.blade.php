@props(['product', 'delay' => 0, 'compact' => false, 'isFavorite' => false])

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

            @if ($product->isOnSale())
                <span class="badge bg-bole text-white shadow-[0_8px_20px_-10px_rgba(209,58,36,.9)]">
                    {{ __('shop.card.discount', ['percent' => $product->discountPercentage()]) }}
                </span>
            @endif

            @unless ($product->isInStock())
                <span class="badge bg-ink-soft text-white">{{ __('shop.card.sold_out') }}</span>
            @endunless
        </div>

        {{-- Quick add, rises over the badges on hover --}}
        @if ($product->isInStock())
            <form method="POST" action="{{ route('cart.store', $product) }}" class="card-quick z-3 hidden sm:block">
                @csrf
                <button type="submit" class="btn btn-sun w-full py-2.5 text-sm">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    {{ __('shop.card.add') }}
                </button>
            </form>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-3.5 sm:p-5">
        <div class="mb-1.5 flex items-center justify-between gap-2 sm:mb-2 sm:gap-3">
            <span class="text-[0.7rem] font-bold tracking-wide sm:text-xs" style="color: {{ $product->color_hex }}">{{ $product->categoryLabel() }}</span>

            <span class="flex items-center gap-2">
                <span class="flex items-center gap-1 text-[0.7rem] font-semibold text-ink-soft sm:text-xs">
                    <svg class="size-3 text-sun sm:size-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.3 6.8.8-5 4.7 1.3 6.8L12 17.3 6 20.6l1.3-6.8-5-4.7 6.8-.8L12 2Z"/></svg>
                    {{ number_format((float) $product->rating, 1, ',', '') }}
                    <span class="hidden font-normal text-ink-faint sm:inline">({{ $product->review_count }})</span>
                </span>

                @auth
                    <form method="POST" action="{{ route('favorites.store', $product) }}" class="relative z-3 -my-1 flex">
                        @csrf
                        <button type="submit"
                                class="grid size-7 place-items-center rounded-full transition hover:scale-120 {{ $isFavorite ? 'text-bole' : 'text-ink-faint hover:text-bole' }}"
                                aria-label="{{ $isFavorite ? __('shop.card.favorite_remove', ['name' => $product->translated('name')]) : __('shop.card.favorite_add', ['name' => $product->translated('name')]) }}">
                            <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M12 20.5s-7.5-4.6-7.5-9.6a4.3 4.3 0 0 1 7.5-2.8 4.3 4.3 0 0 1 7.5 2.8c0 5-7.5 9.6-7.5 9.6Z"/>
                            </svg>
                        </button>
                    </form>
                @endauth
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

            @if ($product->stock > 0 && $product->stock <= 10)
                <span class="text-[0.7rem] font-semibold text-bole sm:text-xs">{{ __('shop.card.last_units', ['count' => $product->stock]) }}</span>
            @endif
        </div>
    </div>
</article>
