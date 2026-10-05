@extends('admin.layout')

@section('title', __('admin.categories'))

@section('content')

<div class="mb-6 flex flex-wrap items-end justify-between gap-3 sm:mb-8">
    <div>
        <h1 class="font-display text-2xl sm:text-3xl">{{ __('admin.categories') }}</h1>
        <p class="mt-1 text-sm text-ink-soft">{{ __('admin.categories_lead') }}</p>
    </div>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-sun px-6 py-3">
        <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
        {{ __('admin.category_new') }}
    </a>
</div>

@error('category')
    <p class="mb-5 rounded-2xl border-2 border-bole bg-white px-4 py-3 text-sm font-semibold text-bole">{{ $message }}</p>
@enderror

@if ($categories->isEmpty())
    <p class="tile p-8 text-center text-ink-soft">{{ __('admin.categories_empty') }}</p>
@else
    <div class="space-y-3">
        @foreach ($categories as $category)
            <article class="tile flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:p-5">
                <div class="min-w-0 flex-1">
                    <h2 class="font-display text-lg sm:text-xl">{{ $category->name }}</h2>
                    <p class="mt-1 text-sm text-ink-soft">
                        {{ $category->name_en ?: '—' }} · /{{ $category->slug }}
                    </p>
                    <p class="mt-1.5 text-sm {{ $category->products_count > 0 ? 'text-yaprak' : 'text-ink-faint' }}">
                        {{ __('admin.category_product_count', ['count' => $category->products_count]) }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 sm:shrink-0">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="rounded-full border-2 border-ink bg-ink px-4 py-2 text-sm font-semibold text-paper-warm transition hover:bg-transparent hover:text-ink">
                        {{ __('admin.edit') }}
                    </a>

                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                          onsubmit="return confirm(@js(__('admin.category_delete_confirm', ['name' => $category->name])))">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                @disabled($category->products_count > 0)
                                class="rounded-full border-2 border-paper-deep px-4 py-2 text-sm font-semibold text-bole transition hover:border-bole hover:bg-bole hover:text-white disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-paper-deep disabled:hover:bg-transparent disabled:hover:text-bole">
                            {{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endif

@endsection
