@extends('admin.layout')

@section('content')

<div class="mb-6 flex flex-wrap items-end justify-between gap-3 sm:mb-8">
    <div>
        <h1 class="font-display text-2xl sm:text-3xl">{{ __('admin.title') }}</h1>
        <p class="mt-1 text-sm text-ink-soft">{{ __('admin.count', ['count' => $products->count()]) }}</p>
    </div>

    <div class="flex flex-wrap gap-2.5">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline px-6 py-3">{{ __('admin.categories') }}</a>
    <a href="{{ route('admin.setup.show') }}" class="btn btn-quiet px-5 py-3">{{ __('admin.setup_run') }}</a>

    <a href="{{ route('admin.products.create') }}" class="btn btn-sun px-6 py-3">
        <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
        {{ __('admin.new') }}
    </a>
    </div>
</div>

@if ($products->isEmpty())
    <div class="tile p-8 text-center">
        <p class="text-ink-soft">{{ __('admin.empty') }}</p>

        <form method="POST" action="{{ route('admin.setup') }}" class="mt-5">
            @csrf
            <button type="submit" class="btn btn-outline px-6 py-3">{{ __('admin.seed_samples') }}</button>
        </form>
    </div>
@else
    <div class="space-y-3">
        @foreach ($products as $product)
            <article class="tile flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:p-5">

                <img src="{{ asset($product->image_path) }}" alt=""
                     width="96" height="96" loading="lazy"
                     class="size-20 shrink-0 rounded-2xl object-cover sm:size-24">

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                        <h2 class="font-display text-lg sm:text-xl">{{ $product->name }}</h2>
                        @if ($product->badge)
                            <span class="badge bg-ink text-paper-warm">{{ $product->badgeLabel() }}</span>
                        @endif
                        @if ($product->is_featured)
                            <span class="badge bg-sun text-ink">Öne çıkan</span>
                        @endif
                    </div>

                    <p class="mt-1 text-sm text-ink-soft">
                        {{ $product->categoryLabel() }} · /{{ $product->slug }}
                    </p>

                    <p class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                        <span class="font-semibold tabular-nums">{{ \App\Support\Format::price((float) $product->price) }}</span>

                        @if ($product->isInStock())
                            <span class="text-yaprak">Stok: {{ $product->stock }}</span>
                        @else
                            <span class="font-semibold text-bole">Tezgâhta yok</span>
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 sm:shrink-0">
                    <a href="{{ route('products.show', $product) }}" target="_blank" rel="noopener"
                       class="rounded-full border-2 border-paper-deep px-4 py-2 text-sm font-semibold transition hover:border-sun">
                        {{ __('admin.view') }}
                    </a>

                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="rounded-full border-2 border-ink bg-ink px-4 py-2 text-sm font-semibold text-paper-warm transition hover:bg-transparent hover:text-ink">
                        {{ __('admin.edit') }}
                    </a>

                    {{-- Deleting cannot be undone, so the browser asks first. --}}
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                          onsubmit="return confirm(@js(__('admin.delete_confirm', ['name' => $product->name])))">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="rounded-full border-2 border-paper-deep px-4 py-2 text-sm font-semibold text-bole transition hover:border-bole hover:bg-bole hover:text-white">
                            {{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endif

@endsection
