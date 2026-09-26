@props(['size' => '18rem', 'smSize' => null, 'rays' => true])

{{-- The store's recurring ornament: a glowing sun with slowly turning rays. --}}
<div {{ $attributes->merge(['class' => 'pointer-events-none absolute']) }}
     style="--sun-size: {{ $size }}; --sun-size-sm: {{ $smSize ?? $size }}"
     aria-hidden="true">

    <div class="relative size-(--sun-size) sm:size-(--sun-size-sm)">
        @if ($rays)
            <div class="sun-rays absolute inset-0 rounded-full"></div>
        @endif

        <div class="glow absolute inset-[12%] rounded-full"></div>

        <div class="absolute inset-[34%] rounded-full bg-linear-to-br from-[#FFE9A8] via-sun to-sun-deep opacity-90 blur-[2px]"></div>
    </div>
</div>
