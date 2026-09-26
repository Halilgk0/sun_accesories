@extends('layouts.app')

@section('title', __('account.favorites.title'))

@section('content')

<x-account-shell :title="__('account.favorites.title')" :lead="__('account.favorites.lead')">

    @if ($favorites->isEmpty())
        <div class="tile p-9 text-center sm:p-14">
            <span class="mx-auto mb-4 grid size-14 place-items-center rounded-full bg-paper-warm text-2xl animate-float sm:mb-6 sm:size-20 sm:text-4xl">💛</span>
            <h2 class="display-md">{{ __('account.favorites.empty_title') }}</h2>
            <p class="mx-auto mt-2.5 max-w-[42ch] text-sm text-ink-soft sm:mt-3 sm:text-base">
                {{ __('account.favorites.empty_text') }}
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-sun mt-5 sm:mt-7">{{ __('site.actions.browse') }}</a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-3 sm:gap-6 xl:grid-cols-3">
            @foreach ($favorites as $index => $favorite)
                <x-product-card :product="$favorite->product" :delay="$index * 90" compact />
            @endforeach
        </div>
    @endif

</x-account-shell>

@endsection
