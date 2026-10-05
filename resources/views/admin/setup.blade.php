@extends('admin.layout')

@section('title', __('admin.setup_title'))

@section('content')

<div class="mx-auto max-w-xl text-center">
    <h1 class="font-display text-2xl sm:text-3xl">{{ __('admin.setup_title') }}</h1>

    <p class="mt-3 text-sm leading-relaxed text-ink-soft sm:text-base">
        {{ __('admin.setup_missing') }}
    </p>

    <form method="POST" action="{{ route('admin.setup') }}" class="mt-7">
        @csrf
        <button type="submit" class="btn btn-sun px-8 py-3.5">{{ __('admin.setup_run') }}</button>
    </form>

    <p class="mt-5 text-xs text-ink-soft">{{ __('admin.setup_safe') }}</p>
</div>

@endsection
