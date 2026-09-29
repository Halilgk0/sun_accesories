<footer class="relative z-10 mt-16 overflow-hidden bg-ink text-paper-warm sm:mt-24">

    {{-- Scalloped petal edge, cut out of the footer itself --}}
    <div class="scallop-top absolute inset-x-0 top-0 h-6 bg-paper sm:h-10" aria-hidden="true"></div>

    {{-- Sun setting behind the footer --}}
    <div class="pointer-events-none absolute -bottom-40 left-1/2 size-[24rem] -translate-x-1/2 rounded-full bg-radial-[at_50%_50%] from-sun/35 to-transparent to-70% blur-2xl sm:size-[34rem]" aria-hidden="true"></div>

    <div class="wrap relative grid gap-11 pt-14 pb-10 sm:gap-12 sm:pt-20 sm:pb-8 md:grid-cols-[1.4fr_1fr_1fr_1.2fr]">

        <div>
            <div class="mb-4 flex items-center gap-2.5 sm:mb-5 sm:gap-3">
                <span class="grid size-9 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-base font-black text-white animate-float sm:size-11 sm:text-lg">S</span>
                <span class="font-display text-xl sm:text-2xl">{{ __('site.brand') }}</span>
            </div>
            <p class="max-w-[42ch] text-sm leading-relaxed text-paper-warm/70">
                {{ __('site.footer.about') }}
            </p>

            <div class="mt-7 flex gap-2.5 sm:mt-6">
                @foreach ([['Instagram', 'M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.3 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c0 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2 0-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c0-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2Zm0 5.3a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Zm0 7.4a2.9 2.9 0 1 1 0-5.8 2.9 2.9 0 0 1 0 5.8Zm5.7-7.6a1.05 1.05 0 1 1-2.1 0 1.05 1.05 0 0 1 2.1 0Z'], ['Pinterest', 'M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 0-2.9l1.2-5.1s-.3-.6-.3-1.5c0-1.4.8-2.5 1.9-2.5.9 0 1.3.7 1.3 1.5 0 .9-.6 2.2-.9 3.5-.2 1 .5 1.9 1.6 1.9 1.9 0 3.2-2.4 3.2-5.3 0-2.2-1.5-3.8-4.1-3.8-3 0-4.9 2.2-4.9 4.7 0 .9.3 1.5.7 2 .2.2.2.3.1.6l-.2.8c-.1.2-.2.3-.5.2-1.4-.6-2-2.1-2-3.8 0-2.8 2.4-6.2 7-6.2 3.7 0 6.2 2.7 6.2 5.6 0 3.8-2.1 6.7-5.3 6.7-1 0-2-.6-2.4-1.2l-.6 2.5c-.2.8-.7 1.8-1.1 2.4A10 10 0 1 0 12 2Z']] as [$label, $path])
                    <a href="{{ $label === 'Instagram' ? ($instagramUrl ?: '#') : '#' }}"
                       @if ($label === 'Instagram') target="_blank" rel="noopener" @endif
                       aria-label="{{ $label }}"
                       class="grid size-11 place-items-center rounded-full border border-paper-warm/20 transition hover:-translate-y-1 hover:border-sun hover:bg-sun hover:text-ink sm:size-10">
                        <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $path }}"/></svg>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <h3 class="mb-4 font-display text-base text-sun sm:text-lg">{{ __('site.footer.shop') }}</h3>
            <ul class="space-y-1 text-sm text-paper-warm/75 sm:space-y-1.5">
                <li><a href="{{ route('products.index') }}" class="inline-block py-1.5 transition hover:text-sun sm:py-1">{{ __('site.footer.all_products') }}</a></li>
                @foreach (['necklace', 'earrings', 'ring'] as $category)
                    <li><a href="{{ route('products.index', ['kategori' => $category]) }}" class="inline-block py-1.5 transition hover:text-sun sm:py-1">{{ __('shop.categories.'.$category) }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="mb-4 font-display text-base text-sun sm:text-lg">{{ __('site.footer.help') }}</h3>
            <ul class="space-y-1 text-sm text-paper-warm/75 sm:space-y-1.5">
                <li><a href="{{ route('about') }}" class="inline-block py-1.5 transition hover:text-sun sm:py-1">{{ __('site.footer.atelier') }}</a></li>
                <li><a href="{{ route('about') }}#sss" class="inline-block py-1.5 transition hover:text-sun sm:py-1">{{ __('site.footer.faq') }}</a></li>
                @if ($instagramUrl)
                    <li><a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="inline-block py-1.5 transition hover:text-sun sm:py-1">{{ __('site.actions.instagram') }}</a></li>
                @endif
            </ul>
        </div>

        {{-- No sign-up box here: the site collects nothing, so the only thing
             worth offering is the way to reach a person. --}}
        <div>
            <h3 class="mb-4 font-display text-base text-sun sm:mb-3 sm:text-lg">{{ __('site.footer.ask_title') }}</h3>
            <p class="mb-5 text-sm leading-relaxed text-paper-warm/70">
                {{ __('site.footer.ask_text') }}
            </p>

            @if ($instagramUrl)
                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full bg-sun px-6 py-3 text-sm font-bold text-ink transition hover:scale-105 hover:bg-white sm:px-5 sm:py-2.5">
                    <x-instagram-icon class="size-4.5" />
                    {{ __('site.actions.instagram') }}
                </a>

                @if ($instagramHandle)
                    <p class="mt-4 font-display text-lg text-paper-warm sm:text-xl">{{ $instagramHandle }}</p>
                @endif
            @endif

            <p class="mt-2.5 text-xs leading-relaxed text-paper-warm/55">{{ __('site.footer.ask_hours') }}</p>
        </div>
    </div>

    <div class="wrap relative flex flex-col gap-3.5 border-t border-paper-warm/15 py-7 text-xs text-paper-warm/55 sm:flex-row sm:items-center sm:justify-between sm:gap-3 sm:py-6">
        <p>{{ __('site.footer.rights', ['year' => date('Y')]) }}</p>
        <p class="flex flex-wrap items-center gap-x-5 gap-y-2.5">
            <span>{{ __('site.footer.terms') }}</span>
            <span>{{ __('site.footer.privacy') }}</span>
            <span>{{ __('site.footer.cookies') }}</span>
        </p>
    </div>
</footer>
