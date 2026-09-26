@if (session('status') || session('error'))
    @php
        $isError = (bool) session('error');
    @endphp

    <div class="pointer-events-none fixed inset-x-0 top-20 z-65 flex justify-center px-3 sm:top-24 sm:px-4">
        <div data-toast
             role="status"
             class="toast pointer-events-auto flex w-full max-w-md items-center gap-2.5 rounded-2xl border-2 py-2.5 pr-2 pl-3.5 shadow-[0_22px_50px_-26px_rgba(43,27,61,.7)] sm:w-auto sm:gap-3 sm:rounded-full sm:py-3 sm:pr-3 sm:pl-5 {{ $isError ? 'border-bole bg-bole-pale text-ink' : 'border-sun bg-paper text-ink' }}">
            <span class="grid size-7 shrink-0 place-items-center rounded-full sm:size-8 {{ $isError ? 'bg-bole text-white' : 'bg-linear-to-br from-sun to-sun-deep text-white' }}">
                @if ($isError)
                    <svg class="size-3.5 sm:size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="M12 8v5M12 17h.01"/></svg>
                @else
                    <svg class="size-3.5 sm:size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                @endif
            </span>

            <p class="min-w-0 flex-1 text-[0.8rem] leading-snug font-semibold sm:text-sm">{{ session('error') ?? session('status') }}</p>

            <button type="button" data-toast-close class="grid size-7 shrink-0 place-items-center rounded-full transition hover:bg-paper-deep sm:size-8">
                <span class="sr-only">{{ __('site.actions.close') }}</span>
                <svg class="size-3.5 sm:size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
            </button>
        </div>
    </div>
@endif
