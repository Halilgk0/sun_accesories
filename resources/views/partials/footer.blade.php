<footer class="relative z-10 mt-16 overflow-hidden bg-ink text-paper-warm sm:mt-24">

    {{-- Scalloped petal edge, cut out of the footer itself --}}
    <div class="scallop-top absolute inset-x-0 top-0 h-6 bg-paper sm:h-10" aria-hidden="true"></div>

    {{-- Sun setting behind the footer --}}
    <div class="pointer-events-none absolute -bottom-40 left-1/2 size-[24rem] -translate-x-1/2 rounded-full bg-radial-[at_50%_50%] from-sun/35 to-transparent to-70% blur-2xl sm:size-[34rem]" aria-hidden="true"></div>

    <div class="wrap relative grid gap-9 pt-14 pb-8 sm:gap-12 sm:pt-20 md:grid-cols-[1.4fr_1fr_1fr_1.2fr]">

        <div>
            <div class="mb-4 flex items-center gap-2.5 sm:mb-5 sm:gap-3">
                <span class="grid size-9 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-base font-black text-white animate-float sm:size-11 sm:text-lg">S</span>
                <span class="font-display text-xl sm:text-2xl">{{ __('site.brand') }}</span>
            </div>
            <p class="max-w-[42ch] text-sm leading-relaxed text-paper-warm/70">
                {{ __('site.footer.about') }}
            </p>

            <div class="mt-5 flex gap-2 sm:mt-6 sm:gap-2.5">
                @foreach ([['Instagram', 'M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.3 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c0 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2 0-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c0-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2Zm0 5.3a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Zm0 7.4a2.9 2.9 0 1 1 0-5.8 2.9 2.9 0 0 1 0 5.8Zm5.7-7.6a1.05 1.05 0 1 1-2.1 0 1.05 1.05 0 0 1 2.1 0Z'], ['Pinterest', 'M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 0-2.9l1.2-5.1s-.3-.6-.3-1.5c0-1.4.8-2.5 1.9-2.5.9 0 1.3.7 1.3 1.5 0 .9-.6 2.2-.9 3.5-.2 1 .5 1.9 1.6 1.9 1.9 0 3.2-2.4 3.2-5.3 0-2.2-1.5-3.8-4.1-3.8-3 0-4.9 2.2-4.9 4.7 0 .9.3 1.5.7 2 .2.2.2.3.1.6l-.2.8c-.1.2-.2.3-.5.2-1.4-.6-2-2.1-2-3.8 0-2.8 2.4-6.2 7-6.2 3.7 0 6.2 2.7 6.2 5.6 0 3.8-2.1 6.7-5.3 6.7-1 0-2-.6-2.4-1.2l-.6 2.5c-.2.8-.7 1.8-1.1 2.4A10 10 0 1 0 12 2Z'], ['WhatsApp', 'M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.8 14.2c-.2.7-1.4 1.3-2 1.4-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5-4.5-.2-.2-1.2-1.6-1.2-3s.8-2.1 1-2.4c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .7.5l.9 2.2c.1.2.1.4 0 .6l-.4.5-.3.4c-.1.1-.2.3 0 .6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6 0l.9-1c.2-.2.4-.2.6-.1l2.1 1c.3.1.5.2.5.4.1.1.1.6-.1 1.3Z']] as [$label, $path])
                    <a href="{{ $label === 'WhatsApp' ? ($whatsappUrl ?: '#') : '#' }}"
                       @if ($label === 'WhatsApp') target="_blank" rel="noopener" @endif
                       aria-label="{{ $label }}"
                       class="grid size-9 place-items-center rounded-full border border-paper-warm/20 transition hover:-translate-y-1 hover:border-sun hover:bg-sun hover:text-ink sm:size-10">
                        <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $path }}"/></svg>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <h3 class="mb-3 font-display text-base text-sun sm:mb-4 sm:text-lg">{{ __('site.footer.shop') }}</h3>
            <ul class="space-y-2 text-sm text-paper-warm/75 sm:space-y-2.5">
                <li><a href="{{ route('products.index') }}" class="transition hover:text-sun">{{ __('site.footer.all_products') }}</a></li>
                @foreach (['necklace', 'earrings', 'ring'] as $category)
                    <li><a href="{{ route('products.index', ['kategori' => $category]) }}" class="transition hover:text-sun">{{ __('shop.categories.'.$category) }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="mb-3 font-display text-base text-sun sm:mb-4 sm:text-lg">{{ __('site.footer.help') }}</h3>
            <ul class="space-y-2 text-sm text-paper-warm/75 sm:space-y-2.5">
                <li><a href="{{ route('about') }}" class="transition hover:text-sun">{{ __('site.footer.atelier') }}</a></li>
                <li><a href="{{ route('about') }}#sss" class="transition hover:text-sun">{{ __('site.footer.faq') }}</a></li>
                @if ($whatsappUrl)
                    <li><a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="transition hover:text-sun">{{ __('site.actions.whatsapp') }}</a></li>
                @endif
            </ul>
        </div>

        <div>
            <h3 class="mb-2.5 font-display text-base text-sun sm:mb-3 sm:text-lg">{{ __('site.footer.newsletter_title') }}</h3>
            <p class="mb-4 text-sm leading-relaxed text-paper-warm/70">
                {{ __('site.footer.newsletter_text') }}
            </p>
            <form class="flex gap-2" onsubmit="event.preventDefault(); this.reset(); this.nextElementSibling.hidden = false;">
                <label for="bulten" class="sr-only">{{ __('site.footer.newsletter_label') }}</label>
                <input id="bulten" type="email" required placeholder="ornek@eposta.com"
                       class="min-w-0 flex-1 rounded-full border-2 border-paper-warm/20 bg-white/5 px-4 py-2.5 text-sm text-paper-warm placeholder:text-paper-warm/40 focus:border-sun focus:outline-none">
                <button type="submit" class="shrink-0 rounded-full bg-sun px-4 py-2.5 text-sm font-bold text-ink transition hover:scale-105 hover:bg-white sm:px-5">
                    {{ __('site.footer.newsletter_button') }}
                </button>
            </form>
            <p hidden class="mt-3 text-sm font-semibold text-sun">{{ __('site.footer.newsletter_done') }}</p>
        </div>
    </div>

    <div class="wrap relative flex flex-col gap-2.5 border-t border-paper-warm/15 py-5 text-xs text-paper-warm/55 sm:flex-row sm:items-center sm:justify-between sm:gap-3 sm:py-6">
        <p>{{ __('site.footer.rights', ['year' => date('Y')]) }}</p>
        <p class="flex flex-wrap items-center gap-x-4 gap-y-1.5">
            <span>{{ __('site.footer.terms') }}</span>
            <span>{{ __('site.footer.privacy') }}</span>
            <span>{{ __('site.footer.cookies') }}</span>
        </p>
    </div>
</footer>
