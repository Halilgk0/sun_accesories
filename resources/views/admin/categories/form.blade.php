@extends('admin.layout')

@section('title', $category->exists ? $category->name : __('admin.category_new'))

@section('content')

@php
    $saving = $category->exists
        ? ['method' => 'PUT', 'action' => route('admin.categories.update', $category)]
        : ['method' => 'POST', 'action' => route('admin.categories.store')];
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="font-display text-2xl sm:text-3xl">
        {{ $category->exists ? __('admin.category_edit', ['name' => $category->name]) : __('admin.category_new') }}
    </h1>

    <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-ink-soft underline-offset-4 hover:underline">
        ← {{ __('admin.categories') }}
    </a>
</div>

<form method="POST" action="{{ $saving['action'] }}" class="tile max-w-2xl p-5 sm:p-7">
    @csrf
    @method($saving['method'])

    <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
        <div>
            <label for="name" class="label">{{ __('admin.category_fields.name') }}</label>
            <input id="name" name="name" type="text" required maxlength="80" data-slug-source
                   value="{{ old('name', $category->name) }}" class="field"
                   @if ($errors->has('name')) aria-invalid="true" @endif>
            @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="name_en" class="label">{{ __('admin.category_fields.name_en') }}</label>
            <input id="name_en" name="name_en" type="text" maxlength="80"
                   value="{{ old('name_en', $category->name_en) }}" class="field"
                   @if ($errors->has('name_en')) aria-invalid="true" @endif>
            @error('name_en') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="slug" class="label">{{ __('admin.category_fields.slug') }}</label>
            <input id="slug" name="slug" type="text" required maxlength="80" data-slug-target
                   value="{{ old('slug', $category->slug) }}" class="field"
                   @if ($errors->has('slug')) aria-invalid="true" @endif>
            <p class="mt-1.5 text-xs text-ink-soft">{{ __('admin.category_slug_hint') }}</p>
            @error('slug') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="position" class="label">{{ __('admin.category_fields.position') }}</label>
            <input id="position" name="position" type="number" required min="0" max="999"
                   value="{{ old('position', $category->position ?? 0) }}" class="field"
                   @if ($errors->has('position')) aria-invalid="true" @endif>
            <p class="mt-1.5 text-xs text-ink-soft">{{ __('admin.category_position_hint') }}</p>
            @error('position') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <button type="submit" class="btn btn-sun px-8 py-3.5">{{ __('admin.save') }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline px-8 py-3.5">{{ __('admin.cancel') }}</a>
    </div>
</form>

@endsection
