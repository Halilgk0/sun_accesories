@extends('admin.layout')

@section('title', $product->exists ? __('admin.form_edit', ['name' => $product->name]) : __('admin.form_new'))

@section('content')

@php
    $saving = $product->exists
        ? ['method' => 'PUT', 'action' => route('admin.products.update', $product)]
        : ['method' => 'POST', 'action' => route('admin.products.store')];

    /** Fills a field from what was typed last, falling back to the stored value. */
    $value = fn (string $field, $fallback = null) => old($field, $product->getAttribute($field) ?? $fallback);
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="font-display text-2xl sm:text-3xl">
        {{ $product->exists ? __('admin.form_edit', ['name' => $product->name]) : __('admin.form_new') }}
    </h1>

    <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-ink-soft underline-offset-4 hover:underline">
        ← {{ __('admin.back') }}
    </a>
</div>

@if ($errors->any())
    <p class="mb-5 rounded-2xl border-2 border-bole bg-white px-4 py-3 text-sm font-semibold text-bole">
        {{ __('admin.errors') }}
    </p>
@endif

<form method="POST" action="{{ $saving['action'] }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method($saving['method'])

    {{-- Basics ---------------------------------------------------------- --}}
    <section class="tile p-5 sm:p-7">
        <h2 class="font-display text-xl">{{ __('admin.section_basics') }}</h2>
        <p class="mt-1 mb-5 text-sm text-ink-soft">{{ __('admin.section_basics_hint') }}</p>

        <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
            <div>
                <label for="name" class="label">{{ __('admin.fields.name') }}</label>
                <input id="name" name="name" type="text" required maxlength="120" data-slug-source
                       value="{{ $value('name') }}" class="field" @if ($errors->has('name')) aria-invalid="true" @endif>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name_en" class="label">{{ __('admin.fields.name_en') }}</label>
                <input id="name_en" name="name_en" type="text" maxlength="120"
                       value="{{ $value('name_en') }}" class="field" @if ($errors->has('name_en')) aria-invalid="true" @endif>
                @error('name_en') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="label">{{ __('admin.fields.slug') }}</label>
                <input id="slug" name="slug" type="text" required maxlength="140" data-slug-target
                       value="{{ $value('slug') }}" class="field" @if ($errors->has('slug')) aria-invalid="true" @endif>
                <p class="mt-1.5 text-xs text-ink-soft">{{ __('admin.hints.slug') }}</p>
                @error('slug') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="label">{{ __('admin.fields.category') }}</label>
                    <select id="category" name="category" required class="field" @if ($errors->has('category')) aria-invalid="true" @endif>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected($value('category') === $category->slug)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="badge" class="label">{{ __('admin.fields.badge') }}</label>
                    <select id="badge" name="badge" class="field" @if ($errors->has('badge')) aria-invalid="true" @endif>
                        <option value="">{{ __('admin.no_badge') }}</option>
                        @foreach (\App\Models\Product::BADGES as $badge)
                            <option value="{{ $badge }}" @selected($value('badge') === $badge)>
                                {{ __('shop.badges.'.$badge) }}
                            </option>
                        @endforeach
                    </select>
                    @error('badge') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </section>

    {{-- Copy ------------------------------------------------------------ --}}
    <section class="tile p-5 sm:p-7">
        <h2 class="font-display text-xl">{{ __('admin.section_copy') }}</h2>
        <p class="mt-1 mb-5 text-sm text-ink-soft">{{ __('admin.section_copy_hint') }}</p>

        <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
            @foreach ([
                ['tagline', 'tagline_en', false],
                ['description', 'description_en', true],
                ['material', 'material_en', false],
                ['stone', 'stone_en', false],
            ] as [$tr, $en, $long])
                @foreach ([$tr, $en] as $field)
                    <div @class(['sm:col-span-2' => $long && false])>
                        <label for="{{ $field }}" class="label">{{ __('admin.fields.'.$field) }}</label>
                        @if ($long)
                            <textarea id="{{ $field }}" name="{{ $field }}" rows="5" maxlength="2000"
                                      @required($field === $tr)
                                      class="field resize-y" @if ($errors->has($field)) aria-invalid="true" @endif>{{ $value($field) }}</textarea>
                        @else
                            <input id="{{ $field }}" name="{{ $field }}" type="text" maxlength="160"
                                   @required($field === $tr)
                                   value="{{ $value($field) }}" class="field" @if ($errors->has($field)) aria-invalid="true" @endif>
                        @endif
                        @error($field) <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            @endforeach
        </div>
    </section>

    {{-- Price and stock -------------------------------------------------- --}}
    <section class="tile p-5 sm:p-7">
        <h2 class="font-display text-xl">{{ __('admin.section_price') }}</h2>
        <p class="mt-1 mb-5 text-sm text-ink-soft">{{ __('admin.section_price_hint') }}</p>

        <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
            @foreach ([
                ['price', 'number', ['step' => '0.01', 'min' => '0', 'required' => true]],
                ['compare_at_price', 'number', ['step' => '0.01', 'min' => '0']],
                ['stock', 'number', ['step' => '1', 'min' => '0', 'required' => true]],
                ['rating', 'number', ['step' => '0.1', 'min' => '0', 'max' => '5', 'required' => true]],
                ['review_count', 'number', ['step' => '1', 'min' => '0', 'required' => true]],
            ] as [$field, $type, $attrs])
                <div>
                    <label for="{{ $field }}" class="label">{{ __('admin.fields.'.$field) }}</label>
                    <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}"
                           @foreach ($attrs as $attr => $attrValue) {{ $attr }}="{{ $attrValue }}" @endforeach
                           value="{{ $value($field) }}" class="field" @if ($errors->has($field)) aria-invalid="true" @endif>
                    @if (__('admin.hints.'.$field) !== 'admin.hints.'.$field)
                        <p class="mt-1.5 text-xs text-ink-soft">{{ __('admin.hints.'.$field) }}</p>
                    @endif
                    @error($field) <p class="field-error">{{ $message }}</p> @enderror
                </div>
            @endforeach

            <label class="flex items-center gap-3 self-end rounded-2xl border-2 border-paper-deep p-3.5">
                <input name="is_featured" type="checkbox" value="1" @checked($value('is_featured'))
                       class="size-5 shrink-0 accent-[var(--color-sun)]">
                <span class="text-sm font-semibold">{{ __('admin.fields.is_featured') }}</span>
            </label>
        </div>
    </section>

    {{-- Look ------------------------------------------------------------ --}}
    <section class="tile p-5 sm:p-7">
        <h2 class="font-display text-xl">{{ __('admin.section_look') }}</h2>
        <p class="mt-1 mb-5 text-sm text-ink-soft">{{ __('admin.section_look_hint') }}</p>

        <label for="photo" class="label">{{ __('admin.fields.photo') }}</label>
        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp,image/avif"
               data-photo-input
               class="field cursor-pointer file:mr-3 file:rounded-full file:border-0 file:bg-ink file:px-4 file:py-1.5 file:text-sm file:font-semibold file:text-paper-warm"
               @if ($errors->has('photo')) aria-invalid="true" @endif>
        <p class="mt-1.5 text-xs text-ink-soft">{{ __('admin.hints.photo') }}</p>
        @error('photo') <p class="field-error">{{ $message }}</p> @enderror

        <p class="mt-6 mb-3 text-xs font-semibold tracking-wide text-ink-soft uppercase">{{ __('admin.image_or_choose') }}</p>

        <label for="image_path" class="sr-only">{{ __('admin.fields.image_path') }}</label>

        @if ($images)
            <div class="mb-4 flex flex-wrap gap-2.5">
                @foreach ($images as $image)
                    <button type="button" data-pick-image="{{ $image }}"
                            class="overflow-hidden rounded-2xl border-2 border-paper-deep transition hover:border-sun focus:border-sun focus:outline-none">
                        <img src="{{ asset($image) }}" alt="" width="80" height="80" loading="lazy" class="size-20 object-cover">
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Not required in the markup: a photograph may be uploaded instead,
             and the server enforces that one of the two is given. --}}
        <input id="image_path" name="image_path" type="text" maxlength="500" data-image-input
               value="{{ $value('image_path') }}" placeholder="images/products/ornek.jpg"
               class="field" @if ($errors->has('image_path')) aria-invalid="true" @endif>
        @error('image_path') <p class="field-error">{{ $message }}</p> @enderror

        <div class="mt-5 grid gap-5 sm:grid-cols-[auto_1fr] sm:items-start">
            <div>
                <span class="label">{{ __('admin.image_preview') }}</span>
                {{-- An empty src draws a broken-image icon, so it stays hidden
                     until there is something to show. --}}
                <img data-image-preview
                     @if ($value('image_path')) src="{{ asset($value('image_path')) }}" @endif
                     alt=""
                     class="size-32 rounded-2xl border-2 border-paper-deep object-cover @unless ($value('image_path')) hidden @endunless">
            </div>

            <div>
                <label for="color_hex" class="label">{{ __('admin.fields.color_hex') }}</label>
                <div class="flex items-center gap-3">
                    <input type="color" value="{{ $value('color_hex', '#F2A007') }}" data-colour-picker
                           class="size-11 shrink-0 cursor-pointer rounded-xl border-2 border-paper-deep bg-paper p-1">
                    <input id="color_hex" name="color_hex" type="text" required maxlength="7" data-colour-text
                           value="{{ $value('color_hex', '#F2A007') }}" class="field font-mono"
                           @if ($errors->has('color_hex')) aria-invalid="true" @endif>
                </div>
                @error('color_hex') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn btn-sun px-8 py-3.5">{{ __('admin.save') }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline px-8 py-3.5">{{ __('admin.cancel') }}</a>
    </div>
</form>

@endsection
