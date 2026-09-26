@extends('layouts.app')

@section('title', __('site.actions.login'))

@section('content')

<section class="relative overflow-hidden py-10 sm:py-16">
    <x-sun class="-top-28 -left-28 opacity-60 sm:-top-40 sm:-left-40" size="22rem" sm-size="38rem" />

    <div class="wrap relative grid items-center gap-9 sm:gap-14 lg:grid-cols-[1fr_1fr]">

        {{-- Form --}}
        <div class="tile tile-raised order-2 mx-auto w-full max-w-lg p-5 sm:p-10 lg:order-1">
            <h1 class="display-md">{{ __('account.auth.login_title') }}</h1>
            <p class="mt-2.5 text-sm text-ink-soft sm:mt-3 sm:text-base">{{ __('account.auth.login_lead') }}</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4 sm:mt-8 sm:space-y-5">
                @csrf

                <div>
                    <label for="email" class="label">{{ __('account.auth.email') }}</label>
                    <input id="email" name="email" type="email" required autofocus autocomplete="email"
                           value="{{ old('email') }}" class="field" @if($errors->has('email')) aria-invalid="true" @endif>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">{{ __('account.auth.password') }}</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="field" @if($errors->has('password')) aria-invalid="true" @endif>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <label class="flex cursor-pointer items-center gap-2.5 text-sm">
                    <input type="checkbox" name="remember" value="1" class="size-4.5 rounded border-2 border-paper-deep accent-sun-deep sm:size-5 sm:rounded-md">
                    <span class="text-ink-soft">{{ __('account.auth.remember') }}</span>
                </label>

                <button type="submit" class="btn btn-sun w-full py-3.5 sm:py-4">{{ __('site.actions.login') }}</button>
            </form>

            <p class="mt-6 text-center text-sm text-ink-soft sm:mt-7">
                {{ __('account.auth.no_account') }}
                <a href="{{ route('register') }}" class="link-sun font-semibold text-ink">{{ __('account.auth.create_now') }}</a>
            </p>

            {{-- Demo hint, because this is a showcase store --}}
            <div class="mt-6 rounded-xl border-2 border-dashed border-sun/50 bg-sun-pale/30 p-3.5 text-xs sm:mt-7 sm:rounded-2xl sm:p-4 sm:text-sm">
                <p class="font-semibold">{{ __('account.auth.demo_title') }}</p>
                <p class="mt-1 break-all text-ink-soft tabular-nums">demo@sunaccesories.com · sifre1234</p>
            </div>
        </div>

        {{-- Illustration --}}
        <div class="order-1 mx-auto w-full max-w-xs sm:max-w-sm lg:order-2 lg:max-w-none">
            <div class="arch relative overflow-hidden border-4 border-paper shadow-[0_45px_85px_-50px_rgba(43,27,61,.6)]">
                <img src="{{ asset('images/atolye.jpg') }}" alt=""
                     width="1600" height="1200" class="aspect-3/4 w-full object-cover">
                <div class="absolute inset-0 bg-linear-to-t from-ink/55 to-transparent"></div>
                <blockquote class="absolute inset-x-4 bottom-5 font-display text-base leading-snug text-white sm:inset-x-6 sm:bottom-8 sm:text-2xl">
                    {{ __('account.auth.quote') }}
                </blockquote>
            </div>
        </div>
    </div>
</section>

@endsection
