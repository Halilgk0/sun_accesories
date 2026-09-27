@props(['product', 'size' => 'md'])

@php
    $sizes = [
        'md' => 'text-base sm:text-xl',
        'lg' => 'text-3xl sm:text-4xl',
    ];
@endphp

<p {{ $attributes->merge(['class' => 'flex flex-wrap items-baseline gap-x-2 gap-y-0.5']) }}>
    <span class="font-display {{ $sizes[$size] }} font-semibold tabular-nums">
        {{ \App\Support\Format::price((float) $product->price) }}
    </span>

    @if ($product->isOnSale())
        <span class="text-xs text-ink-faint line-through tabular-nums sm:text-sm">
            {{ \App\Support\Format::price((float) $product->compare_at_price) }}
        </span>
    @endif
</p>
