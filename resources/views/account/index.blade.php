@extends('layouts.app')

@section('title', __('account.dashboard.title'))

@section('content')

<x-account-shell :title="__('account.dashboard.title')">

    {{-- Stats --}}
    <div class="mb-5 grid grid-cols-2 gap-2.5 sm:mb-8 sm:gap-4 md:grid-cols-3">
        @foreach ([
            [__('account.dashboard.orders'), $orderCount, 'from-sun to-sun-deep', 'M4 7h16l-1.4 11.2A2 2 0 0 1 16.6 20H7.4a2 2 0 0 1-2-1.8L4 7ZM9 10V6a3 3 0 0 1 6 0v4'],
            [__('account.dashboard.favorites'), $favoriteCount, 'from-blush to-bole', 'M12 20.5s-7.5-4.6-7.5-9.6a4.3 4.3 0 0 1 7.5-2.8 4.3 4.3 0 0 1 7.5 2.8c0 5-7.5 9.6-7.5 9.6Z'],
            [__('account.dashboard.spent'), number_format($spent, 0, ',', '.').' ₺', 'from-turkuaz-bright to-turkuaz', 'M12 3v18M16.5 7.5c0-1.9-2-3-4.5-3s-4.5 1.1-4.5 3 2 2.8 4.5 3.4 4.5 1.5 4.5 3.4-2 3-4.5 3-4.5-1.1-4.5-3'],
        ] as $index => [$label, $value, $gradient, $icon])
            <div class="tile reveal overflow-hidden p-3.5 last:col-span-2 sm:p-6 md:last:col-span-1" style="--reveal-delay: {{ $index * 110 }}ms">
                <span class="mb-2.5 grid size-9 place-items-center rounded-xl bg-linear-to-br {{ $gradient }} text-white sm:mb-4 sm:size-11 sm:rounded-2xl">
                    <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icon }}"/></svg>
                </span>
                <p class="font-display text-2xl tabular-nums sm:text-4xl">{{ $value }}</p>
                <p class="mt-0.5 text-xs text-ink-soft sm:mt-1 sm:text-sm">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    {{-- Recent orders --}}
    <div class="tile mb-5 overflow-hidden sm:mb-8">
        <div class="flex items-center justify-between gap-3 border-b-2 border-paper-deep p-4 sm:gap-4 sm:p-6">
            <h2 class="font-display text-lg sm:text-2xl">{{ __('account.dashboard.recent') }}</h2>
            @if ($orders->isNotEmpty())
                <a href="{{ route('account.orders') }}" class="link-sun text-xs font-semibold sm:text-sm">{{ __('account.dashboard.all') }}</a>
            @endif
        </div>

        @if ($orders->isEmpty())
            <div class="p-7 text-center sm:p-10">
                <p class="text-sm text-ink-soft sm:text-base">{{ __('account.dashboard.none') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-sun mt-4 sm:mt-5">{{ __('account.dashboard.first_order') }}</a>
            </div>
        @else
            <ul class="divide-y-2 divide-paper-deep">
                @foreach ($orders as $order)
                    <li>
                        <a href="{{ route('account.order', $order) }}" class="flex flex-wrap items-center gap-3 p-3.5 transition hover:bg-paper-warm sm:gap-4 sm:p-5">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-sun-pale text-sun-deep sm:size-12 sm:rounded-2xl">
                                <svg class="size-4 sm:size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16l-1.4 11.2A2 2 0 0 1 16.6 20H7.4a2 2 0 0 1-2-1.8L4 7Z"/></svg>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold">{{ $order->order_number }}</span>
                                <span class="block text-xs text-ink-soft sm:text-sm">
                                    {{ $order->created_at->translatedFormat('d F Y') }} · {{ __('shop.checkout.piece_count', ['count' => $order->items_count]) }}
                                </span>
                            </span>
                            <span class="badge bg-paper-warm text-ink-soft">{{ $order->statusLabel() }}</span>
                            <span class="font-display text-base tabular-nums sm:text-xl">{{ number_format((float) $order->total, 2, ',', '.') }} ₺</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="grid gap-4 sm:gap-6 xl:grid-cols-2">

        {{-- Profile --}}
        <form method="POST" action="{{ route('account.profile.update') }}" class="tile p-4 sm:p-6">
            @csrf
            @method('PATCH')

            <h2 class="mb-4 font-display text-lg sm:mb-5 sm:text-2xl">{{ __('account.dashboard.profile') }}</h2>

            <div class="space-y-3.5 sm:space-y-4">
                <div>
                    <label for="name" class="label">{{ __('account.auth.name') }}</label>
                    <input id="name" name="name" type="text" required value="{{ old('name', $user->name) }}" class="field">
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="label">{{ __('account.auth.email') }}</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" class="field">
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-3.5 sm:grid-cols-2 sm:gap-4">
                    <div>
                        <label for="phone" class="label">{{ __('account.auth.phone') }}</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="field">
                        @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="label">{{ __('shop.checkout.city') }}</label>
                        <input id="city" name="city" type="text" value="{{ old('city', $user->city) }}" class="field">
                        @error('city') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="label">{{ __('account.dashboard.default_address') }}</label>
                    <textarea id="address" name="address" rows="3" class="field resize-y">{{ old('address', $user->address) }}</textarea>
                    @error('address') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-ink mt-5 w-full sm:mt-6">{{ __('account.dashboard.save') }}</button>
        </form>

        {{-- Password --}}
        <form method="POST" action="{{ route('account.password.update') }}" class="tile h-fit p-4 sm:p-6">
            @csrf
            @method('PATCH')

            <h2 class="mb-4 font-display text-lg sm:mb-5 sm:text-2xl">{{ __('account.dashboard.password') }}</h2>

            <div class="space-y-3.5 sm:space-y-4">
                <div>
                    <label for="current_password" class="label">{{ __('account.dashboard.current_password') }}</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="field">
                    @error('current_password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new_password" class="label">{{ __('account.dashboard.new_password') }}</label>
                    <input id="new_password" name="password" type="password" autocomplete="new-password" class="field">
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">{{ __('account.dashboard.new_password_confirm') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="field">
                </div>
            </div>

            <button type="submit" class="btn btn-outline mt-5 w-full sm:mt-6">{{ __('account.dashboard.update_password') }}</button>
        </form>
    </div>

</x-account-shell>

@endsection
