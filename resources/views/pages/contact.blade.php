@extends('layouts.app')

@section('title', __('pages.contact.meta_title'))

@section('content')

<x-page-header
    :eyebrow="__('site.nav.contact')"
    :title="__('pages.contact.title')"
    :lead="__('pages.contact.lead')" />

<section class="wrap -mt-5 grid gap-4 pb-12 sm:-mt-6 sm:gap-8 sm:pb-20 lg:grid-cols-[1.3fr_1fr] lg:items-start">

    {{-- Form --}}
    <form method="POST" action="{{ route('contact.send') }}" class="tile tile-raised p-5 sm:p-9">
        @csrf

        <h2 class="font-display text-2xl sm:text-3xl">{{ __('pages.contact.form_title') }}</h2>
        <p class="mt-1.5 text-sm text-ink-soft sm:mt-2 sm:text-base">{{ __('pages.contact.form_lead') }}</p>

        <div class="mt-5 grid gap-3.5 sm:mt-7 sm:grid-cols-2 sm:gap-5">
            <div>
                <label for="name" class="label">{{ __('pages.contact.name') }}</label>
                <input id="name" name="name" type="text" required autocomplete="name"
                       value="{{ old('name') }}" class="field"
                       @if($errors->has('name')) aria-invalid="true" @endif>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="label">{{ __('pages.contact.email') }}</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                       value="{{ old('email') }}" class="field"
                       @if($errors->has('email')) aria-invalid="true" @endif>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="subject" class="label">{{ __('pages.contact.subject') }}</label>
                <input id="subject" name="subject" type="text" required list="konular"
                       value="{{ old('subject', request()->string('urun')->toString()) }}" placeholder="{{ __('pages.contact.subject_placeholder') }}" class="field"
                       @if($errors->has('subject')) aria-invalid="true" @endif>
                <datalist id="konular">
                    @foreach (['tracking', 'returns', 'repair', 'product', 'visit'] as $key)
                        <option value="{{ __('pages.contact.subject_'.$key) }}"></option>
                    @endforeach
                </datalist>
                @error('subject') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="message" class="label">{{ __('pages.contact.message') }}</label>
                <textarea id="message" name="message" rows="5" required
                          placeholder="{{ __('pages.contact.message_placeholder') }}" class="field resize-y"
                          @if($errors->has('message')) aria-invalid="true" @endif>{{ old('message') }}</textarea>
                @error('message') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-sun mt-5 w-full py-3.5 sm:mt-7 sm:w-auto sm:px-12 sm:py-4">{{ __('pages.contact.submit') }}</button>
    </form>

    {{-- Details --}}
    <div class="space-y-3.5 sm:space-y-5">
        @foreach ([
            [__('pages.contact.atelier'), __('pages.contact.address'), 'M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z'],
            [__('pages.contact.email'), 'merhaba@sunaccesories.com', 'M3 6h18v12H3zM3 7l9 6 9-6'],
        ] as $index => [$label, $value, $path])
            <div class="tile reveal flex items-start gap-3 p-4 sm:gap-4 sm:p-6" style="--reveal-delay: {{ $index * 110 }}ms">
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-sun-pale text-sun-deep sm:size-11 sm:rounded-2xl">
                    <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $path }}"/></svg>
                </span>
                <span class="min-w-0">
                    <strong class="block text-xs text-ink-soft sm:text-sm">{{ $label }}</strong>
                    <span class="mt-0.5 block text-sm font-semibold break-words sm:text-base">{{ $value }}</span>
                </span>
            </div>
        @endforeach

        {{-- WhatsApp rather than a phone number: nobody here wants to be rung up. --}}
        @if ($whatsappUrl)
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
               class="tile reveal flex items-start gap-3 p-4 transition hover:border-sun sm:gap-4 sm:p-6" style="--reveal-delay: 330ms">
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-sun-pale text-sun-deep sm:size-11 sm:rounded-2xl">
                    <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.8 14.2c-.2.7-1.4 1.3-2 1.4-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5-4.5-.2-.2-1.2-1.6-1.2-3s.8-2.1 1-2.4c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .7.5l.9 2.2c.1.2.1.4 0 .6l-.4.5-.3.4c-.1.1-.2.3 0 .6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6 0l.9-1c.2-.2.4-.2.6-.1l2.1 1c.3.1.5.2.5.4.1.1.1.6-.1 1.3Z"/></svg>
                </span>
                <span class="min-w-0">
                    <strong class="block text-xs text-ink-soft sm:text-sm">WhatsApp</strong>
                    <span class="mt-0.5 block text-sm font-semibold sm:text-base">{{ __('pages.contact.whatsapp_cta') }}</span>
                </span>
            </a>
        @endif

        <div class="tile overflow-hidden">
            <div class="border-b-2 border-paper-deep p-4 sm:p-6">
                <h2 class="font-display text-lg sm:text-2xl">{{ __('pages.contact.hours_title') }}</h2>
            </div>
            <dl class="divide-y-2 divide-paper-deep text-sm">
                @foreach ([[__('pages.contact.weekdays'), '09.00 – 18.00'], [__('pages.contact.saturday'), '11.00 – 16.00'], [__('pages.contact.sunday'), __('pages.contact.closed')]] as [$day, $hours])
                    <div class="flex justify-between p-3.5 sm:p-4">
                        <dt class="text-ink-soft">{{ $day }}</dt>
                        <dd class="font-semibold tabular-nums {{ $hours === __('pages.contact.closed') ? 'text-bole' : '' }}">{{ $hours }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="wrap pb-14 sm:pb-24">
    <h2 class="display-md mb-6 max-w-[20ch] sm:mb-10">{{ __('pages.contact.faq_title') }}</h2>

    <div class="grid gap-2.5 sm:gap-3 md:grid-cols-2">
        @foreach ([1, 2, 3, 4, 5, 6] as $i)
            <details class="tile group overflow-hidden p-0">
                <summary class="flex cursor-pointer items-center justify-between gap-3 p-4 text-sm font-semibold transition hover:bg-paper-warm sm:gap-4 sm:p-5 sm:text-base">
                    {{ __("pages.contact.faq_{$i}_q") }}
                    <span class="grid size-6 shrink-0 place-items-center rounded-full bg-paper-warm transition-transform duration-300 group-open:rotate-45 sm:size-7">
                        <svg class="size-3 sm:size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    </span>
                </summary>
                <p class="px-4 pb-4 text-xs leading-relaxed text-ink-soft sm:px-5 sm:pb-5 sm:text-sm">{{ __("pages.contact.faq_{$i}_a") }}</p>
            </details>
        @endforeach
    </div>
</section>

@endsection
