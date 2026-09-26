@extends('layouts.app')

@section('title', __('shop.checkout.title'))

@section('content')

<x-page-header :eyebrow="__('shop.cart.checkout')" :title="__('shop.checkout.title')" :lead="__('shop.checkout.lead')" />

<section class="wrap -mt-6 pb-14 sm:-mt-8 sm:pb-24">

    {{-- Steps --}}
    <ol class="mb-5 flex flex-wrap items-center gap-2 text-xs font-semibold sm:mb-8 sm:gap-3 sm:text-sm">
        @foreach ([[__('shop.checkout.step_cart'), true], [__('shop.checkout.step_details'), true], [__('shop.checkout.step_done'), false]] as $index => [$label, $done])
            <li class="flex items-center gap-1.5 sm:gap-2">
                <span class="grid size-6 place-items-center rounded-full sm:size-7 {{ $done ? 'bg-sun text-ink' : 'border-2 border-paper-deep text-ink-faint' }}">{{ $index + 1 }}</span>
                <span class="{{ $done ? '' : 'text-ink-faint' }}">{{ $label }}</span>
                @unless ($loop->last)
                    <span class="ml-0.5 h-0.5 w-5 bg-paper-deep sm:ml-1 sm:w-8"></span>
                @endunless
            </li>
        @endforeach
    </ol>

    @if ($errors->any())
        <div class="tile mb-4 border-bole bg-bole-pale p-4 sm:mb-6 sm:p-5" role="alert">
            <p class="font-display text-base text-bole sm:text-xl">{{ __('shop.checkout.errors_title') }}</p>
            <ul class="mt-2.5 space-y-1 text-xs sm:mt-3 sm:space-y-1.5 sm:text-sm">
                @foreach ($errors->all() as $error)
                    <li class="flex gap-2"><span class="text-bole">•</span> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-4 sm:gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-start">
        @csrf

        <div class="space-y-4 sm:space-y-6">

            {{-- Delivery --}}
            <fieldset class="tile p-4 sm:p-6">
                <legend class="mb-4 flex items-center gap-2.5 font-display text-lg sm:mb-5 sm:gap-3 sm:text-2xl">
                    <span class="grid size-8 place-items-center rounded-full bg-sun-pale text-sun-deep sm:size-9">
                        <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                    </span>
                    {{ __('shop.checkout.delivery') }}
                </legend>

                <div class="grid gap-3.5 sm:grid-cols-2 sm:gap-5">
                    <div class="sm:col-span-2">
                        <label for="customer_name" class="label">{{ __('shop.checkout.name') }}</label>
                        <input id="customer_name" name="customer_name" type="text" required autocomplete="name"
                               value="{{ old('customer_name', auth()->user()?->name) }}"
                               class="field" @if($errors->has('customer_name')) aria-invalid="true" @endif>
                        @error('customer_name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="label">{{ __('shop.checkout.email') }}</label>
                        <input id="email" name="email" type="email" required autocomplete="email"
                               value="{{ old('email', auth()->user()?->email) }}"
                               class="field" @if($errors->has('email')) aria-invalid="true" @endif>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="label">{{ __('shop.checkout.phone') }}</label>
                        <input id="phone" name="phone" type="tel" required autocomplete="tel" placeholder="0500 000 00 00"
                               value="{{ old('phone', auth()->user()?->phone) }}"
                               class="field" @if($errors->has('phone')) aria-invalid="true" @endif>
                        @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="city" class="label">{{ __('shop.checkout.city') }}</label>
                        <input id="city" name="city" type="text" required autocomplete="address-level1"
                               value="{{ old('city', auth()->user()?->city) }}"
                               class="field" @if($errors->has('city')) aria-invalid="true" @endif>
                        @error('city') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="district" class="label">{{ __('shop.checkout.district') }}</label>
                        <input id="district" name="district" type="text" required autocomplete="address-level2"
                               value="{{ old('district') }}"
                               class="field" @if($errors->has('district')) aria-invalid="true" @endif>
                        @error('district') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address" class="label">{{ __('shop.checkout.address') }}</label>
                        <textarea id="address" name="address" rows="3" required autocomplete="street-address"
                                  placeholder="{{ __('shop.checkout.address_placeholder') }}"
                                  class="field resize-y" @if($errors->has('address')) aria-invalid="true" @endif>{{ old('address', auth()->user()?->address) }}</textarea>
                        @error('address') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="note" class="label">{{ __('shop.checkout.note') }} <span class="font-normal text-ink-faint">{{ __('shop.checkout.optional') }}</span></label>
                        <textarea id="note" name="note" rows="2" placeholder="{{ __('shop.checkout.note_placeholder') }}"
                                  class="field resize-y">{{ old('note') }}</textarea>
                        @error('note') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Payment --}}
            <fieldset class="tile p-4 sm:p-6">
                <legend class="mb-4 flex items-center gap-2.5 font-display text-lg sm:mb-5 sm:gap-3 sm:text-2xl">
                    <span class="grid size-8 place-items-center rounded-full bg-sun-pale text-sun-deep sm:size-9">
                        <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2.5" y="5" width="19" height="14" rx="2.5"/><path d="M2.5 10h19"/></svg>
                    </span>
                    {{ __('shop.checkout.payment') }}
                </legend>

                <div class="grid gap-2 sm:grid-cols-3 sm:gap-3">
                    @foreach ([
                        ['kredi_karti', __('shop.checkout.card'), __('shop.checkout.card_hint')],
                        ['havale', __('shop.checkout.transfer'), __('shop.checkout.transfer_hint')],
                        ['kapida_odeme', __('shop.checkout.cod'), __('shop.checkout.cod_hint')],
                    ] as [$value, $title, $hint])
                        <label class="relative cursor-pointer rounded-xl border-2 border-paper-deep p-3 pr-9 transition hover:border-sun has-checked:border-sun has-checked:bg-sun-pale/50 sm:rounded-2xl sm:p-4 sm:pr-10">
                            <input type="radio" name="payment_method" value="{{ $value }}" data-payment-option
                                   class="peer sr-only" @checked(old('payment_method', 'kredi_karti') === $value)>
                            <span class="block text-sm font-semibold sm:text-base">{{ $title }}</span>
                            <span class="mt-0.5 block text-[0.7rem] text-ink-soft sm:text-xs">{{ $hint }}</span>
                            <span class="absolute top-3 right-3 grid size-4.5 place-items-center rounded-full border-2 border-paper-deep transition peer-checked:border-sun-deep peer-checked:bg-sun-deep peer-checked:*:opacity-100 sm:top-3.5 sm:right-3.5 sm:size-5">
                                <span class="size-1.5 rounded-full bg-white opacity-0 transition sm:size-2"></span>
                            </span>
                        </label>
                    @endforeach
                </div>

                {{-- Card panel --}}
                <div data-payment-panel="kredi_karti" class="mt-5 grid gap-5 sm:mt-6 lg:grid-cols-[1fr_auto] lg:items-start">
                    <div class="grid grid-cols-2 gap-3.5 sm:gap-5">
                        <div class="col-span-2">
                            <label for="card_name" class="label">{{ __('shop.checkout.card_name') }}</label>
                            <input id="card_name" name="card_name" type="text" data-card-name autocomplete="cc-name"
                                   value="{{ old('card_name') }}" class="field">
                            @error('card_name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="card_number" class="label">{{ __('shop.checkout.card_number') }}</label>
                            <input id="card_number" name="card_number" type="text" inputmode="numeric" data-card-number
                                   autocomplete="cc-number" placeholder="0000 0000 0000 0000" maxlength="19"
                                   value="{{ old('card_number') }}" class="field tabular-nums">
                            @error('card_number') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="card_expiry" class="label">{{ __('shop.checkout.card_expiry') }}</label>
                            <input id="card_expiry" name="card_expiry" type="text" inputmode="numeric" data-card-expiry
                                   autocomplete="cc-exp" placeholder="{{ __('shop.checkout.expiry_placeholder') }}" maxlength="5"
                                   value="{{ old('card_expiry') }}" class="field tabular-nums">
                            @error('card_expiry') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="card_cvv" class="label">{{ __('shop.checkout.card_cvv') }}</label>
                            <input id="card_cvv" name="card_cvv" type="text" inputmode="numeric" autocomplete="cc-csc"
                                   placeholder="123" maxlength="4" value="{{ old('card_cvv') }}" class="field tabular-nums">
                            @error('card_cvv') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Live card preview --}}
                    <div class="relative mx-auto aspect-8/5 w-full max-w-64 overflow-hidden rounded-2xl bg-linear-to-br from-ink via-[#4a2f63] to-bole p-4 text-white shadow-[0_28px_50px_-26px_rgba(43,27,61,.8)] sm:max-w-80 sm:rounded-3xl sm:p-6"
                         data-tilt="9" aria-hidden="true">
                        <div class="absolute -top-10 -right-8 size-32 rounded-full bg-sun/30 blur-2xl animate-halo sm:size-40"></div>

                        <div class="relative flex h-full flex-col justify-between">
                            <div class="flex items-start justify-between">
                                <span class="h-6 w-9 rounded bg-linear-to-br from-sun-pale to-sun sm:h-8 sm:w-11 sm:rounded-md"></span>
                                <span class="font-display text-sm sm:text-lg">{{ __('site.brand_mark') }}</span>
                            </div>

                            <p data-card-preview-number class="font-display text-sm tracking-widest tabular-nums sm:text-xl">•••• •••• •••• ••••</p>

                            <div class="flex items-end justify-between text-[0.65rem] sm:text-xs">
                                <span data-card-preview-name class="font-semibold tracking-wide">{{ __('shop.checkout.card_holder_placeholder') }}</span>
                                <span data-card-preview-expiry class="tabular-nums">{{ __('shop.checkout.expiry_placeholder') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-payment-panel="havale" hidden class="mt-5 rounded-xl bg-paper-warm p-4 text-xs leading-relaxed sm:mt-6 sm:rounded-2xl sm:p-5 sm:text-sm">
                    <p class="mb-1.5 font-semibold sm:mb-2">{{ __('shop.checkout.transfer_title') }}</p>
                    <p class="text-ink-soft">{{ __('shop.checkout.transfer_text') }}</p>
                </div>

                <div data-payment-panel="kapida_odeme" hidden class="mt-5 rounded-xl bg-paper-warm p-4 text-xs leading-relaxed sm:mt-6 sm:rounded-2xl sm:p-5 sm:text-sm">
                    <p class="mb-1.5 font-semibold sm:mb-2">{{ __('shop.checkout.cod_title') }}</p>
                    <p class="text-ink-soft">{{ __('shop.checkout.cod_text') }}</p>
                </div>
            </fieldset>
        </div>

        {{-- Summary --}}
        <aside class="tile tile-raised overflow-hidden lg:sticky lg:top-28">
            <div class="bg-ink p-4 text-paper-warm sm:p-6">
                <h2 class="font-display text-lg sm:text-2xl">{{ __('shop.checkout.your_order') }}</h2>
                <p class="mt-0.5 text-xs text-paper-warm/65 sm:mt-1 sm:text-sm">{{ __('shop.checkout.piece_count', ['count' => $items->sum('quantity')]) }}</p>
            </div>

            <ul class="divide-y-2 divide-paper-deep">
                @foreach ($items as $item)
                    <li class="flex items-center gap-2.5 p-3 sm:gap-3 sm:p-4">
                        <span class="relative shrink-0">
                            <img src="{{ asset($item->product->image_path) }}" alt="" width="56" height="56" class="size-11 rounded-lg object-cover sm:size-14 sm:rounded-xl">
                            <span class="absolute -top-1.5 -right-1.5 grid size-5 place-items-center rounded-full bg-ink text-[0.65rem] font-bold text-white sm:-top-2 sm:-right-2 sm:size-6 sm:text-xs">{{ $item->quantity }}</span>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-semibold">{{ $item->product->translated('name') }}</span>
                            <span class="block text-[0.7rem] text-ink-soft sm:text-xs">{{ $item->product->categoryLabel() }}</span>
                        </span>
                        <span class="text-xs font-semibold tabular-nums sm:text-sm">{{ number_format($item->lineTotal(), 2, ',', '.') }} ₺</span>
                    </li>
                @endforeach
            </ul>

            <dl class="space-y-2.5 border-t-2 border-paper-deep p-4 text-sm sm:space-y-3 sm:p-6">
                <div class="flex justify-between">
                    <dt class="text-ink-soft">{{ __('shop.cart.subtotal') }}</dt>
                    <dd class="font-semibold tabular-nums">{{ number_format($subtotal, 2, ',', '.') }} ₺</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-soft">{{ __('shop.cart.shipping') }}</dt>
                    <dd class="font-semibold tabular-nums {{ $shippingFee === 0.0 ? 'text-yaprak' : '' }}">
                        {{ $shippingFee === 0.0 ? __('shop.cart.free') : number_format($shippingFee, 2, ',', '.').' ₺' }}
                    </dd>
                </div>
                <div class="flex items-baseline justify-between border-t-2 border-paper-deep pt-3.5 sm:pt-4">
                    <dt class="font-display text-base sm:text-xl">{{ __('shop.cart.total') }}</dt>
                    <dd class="font-display text-2xl tabular-nums sm:text-3xl">{{ number_format($total, 2, ',', '.') }} ₺</dd>
                </div>
            </dl>

            <div class="px-4 pb-4 sm:px-6 sm:pb-6">
                <label class="mb-4 flex cursor-pointer items-start gap-2.5 text-xs sm:mb-5 sm:gap-3 sm:text-sm">
                    <input type="checkbox" name="terms" value="1" @checked(old('terms'))
                           class="mt-0.5 size-4.5 shrink-0 rounded border-2 border-paper-deep accent-sun-deep sm:size-5 sm:rounded-md">
                    <span class="text-ink-soft">
                        {{ __('shop.checkout.terms_before') }}
                        <a href="#" class="link-sun font-semibold text-ink">{{ __('site.footer.terms') }}</a>
                        {{ __('shop.checkout.terms_middle') }}
                        <a href="#" class="link-sun font-semibold text-ink">{{ __('shop.checkout.terms_form') }}</a>{{ __('shop.checkout.terms_after') }}
                    </span>
                </label>
                @error('terms') <p class="field-error -mt-3 mb-3.5">{{ $message }}</p> @enderror

                <button type="submit" class="btn btn-sun w-full py-3.5 sm:py-4">
                    {{ __('shop.checkout.submit') }}
                </button>

                <p class="mt-3 text-center text-[0.7rem] text-ink-soft sm:mt-4 sm:text-xs">
                    {{ __('shop.checkout.demo_note') }}
                </p>
            </div>
        </aside>
    </form>
</section>

@endsection
