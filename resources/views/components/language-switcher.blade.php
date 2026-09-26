@php
    $locales = ['tr', 'en'];
    $current = app()->getLocale();
@endphp

{{-- Two small buttons rather than a dropdown: one tap, no menu to open. --}}
<div {{ $attributes->merge(['class' => 'flex items-center gap-0.5 rounded-full border-2 border-paper-deep p-0.5']) }}
     role="group" aria-label="{{ __('site.language.label') }}">
    @foreach ($locales as $locale)
        <form method="POST" action="{{ route('locale.update', $locale) }}" class="contents">
            @csrf
            <button type="submit"
                    @if ($locale === $current) aria-current="true" @endif
                    aria-label="{{ __('site.language.switch_to', ['language' => __('site.language.'.$locale)]) }}"
                    class="rounded-full px-2.5 py-1 text-xs font-bold uppercase transition {{ $locale === $current ? 'bg-ink text-paper-warm' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                {{ $locale }}
            </button>
        </form>
    @endforeach
</div>
