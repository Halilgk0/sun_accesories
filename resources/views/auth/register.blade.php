@extends('layouts.app')

@section('title', __('site.actions.register'))

@section('content')

<section class="relative overflow-hidden py-10 sm:py-16">
    <x-sun class="-top-28 -right-28 opacity-60 sm:-top-40 sm:-right-40" size="22rem" sm-size="38rem" />

    <div class="wrap relative grid items-center gap-9 sm:gap-14 lg:grid-cols-[1fr_1fr]">

        {{-- Benefits --}}
        <div>
            <h1 class="display-lg">{{ __('account.auth.register_title') }}</h1>
            <p class="mt-4 max-w-[46ch] text-sm leading-relaxed text-ink-soft sm:mt-5 sm:text-lg">
                {{ __('account.auth.register_lead') }}
            </p>

            <ul class="mt-7 space-y-4 sm:mt-9 sm:space-y-5">
                @foreach ([1, 2, 3] as $index => $i)
                    <li class="reveal flex gap-3 sm:gap-4" style="--reveal-delay: {{ $index * 120 }}ms">
                        <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-linear-to-br from-sun to-bole text-white sm:size-11 sm:rounded-2xl">
                            <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                        </span>
                        <span>
                            <strong class="block font-display text-base sm:text-xl">{{ __("account.auth.benefit_{$i}_title") }}</strong>
                            <span class="mt-0.5 block text-xs leading-relaxed text-ink-soft sm:text-sm">{{ __("account.auth.benefit_{$i}_text") }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Form --}}
        <div class="tile tile-raised mx-auto w-full max-w-lg p-5 sm:p-10">
            <h2 class="font-display text-2xl sm:text-3xl">{{ __('account.auth.register_heading') }}</h2>

            <form method="POST" action="{{ route('register.store') }}" class="mt-5 space-y-4 sm:mt-7 sm:space-y-5">
                @csrf

                <div>
                    <label for="name" class="label">{{ __('account.auth.name') }}</label>
                    <input id="name" name="name" type="text" required autofocus autocomplete="name"
                           value="{{ old('name') }}" class="field" @if($errors->has('name')) aria-invalid="true" @endif>
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="label">{{ __('account.auth.email') }}</label>
                    <input id="email" name="email" type="email" required autocomplete="email"
                           value="{{ old('email') }}" class="field" @if($errors->has('email')) aria-invalid="true" @endif>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="label">{{ __('account.auth.phone') }} <span class="font-normal text-ink-faint">{{ __('shop.checkout.optional') }}</span></label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="0500 000 00 00"
                           value="{{ old('phone') }}" class="field">
                    @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">{{ __('account.auth.password') }}</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="field" @if($errors->has('password')) aria-invalid="true" @endif>
                    <p class="mt-1 text-xs text-ink-faint sm:mt-1.5">{{ __('account.auth.password_hint') }}</p>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">{{ __('account.auth.password_confirm') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="field">
                </div>

                <button type="submit" class="btn btn-sun w-full py-3.5 sm:py-4">{{ __('account.auth.register_submit') }}</button>
            </form>

            <p class="mt-6 text-center text-sm text-ink-soft sm:mt-7">
                {{ __('account.auth.have_account') }}
                <a href="{{ route('login') }}" class="link-sun font-semibold text-ink">{{ __('site.actions.login') }}</a>
            </p>
        </div>
    </div>
</section>

@endsection
