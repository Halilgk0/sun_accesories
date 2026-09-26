@extends('layouts.app')

@section('title', __('account.orders.title'))

@section('content')

<x-account-shell :title="__('account.orders.title')" :lead="__('account.orders.lead')">

    @if ($orders->isEmpty())
        <div class="tile p-9 text-center sm:p-14">
            <span class="mx-auto mb-4 grid size-14 place-items-center rounded-full bg-paper-warm text-2xl animate-float sm:mb-6 sm:size-20 sm:text-4xl">📦</span>
            <h2 class="display-md">{{ __('account.orders.empty_title') }}</h2>
            <p class="mx-auto mt-2.5 max-w-[42ch] text-sm text-ink-soft sm:mt-3 sm:text-base">{{ __('account.orders.empty_text') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-sun mt-5 sm:mt-7">{{ __('site.actions.browse') }}</a>
        </div>
    @else
        <div class="space-y-4 sm:space-y-5">
            @foreach ($orders as $order)
                @php
                    $steps = ['hazirlaniyor', 'kargoda', 'teslim_edildi'];
                    $currentStep = array_search($order->status, $steps, true);
                    $currentStep = $currentStep === false ? 0 : $currentStep;
                @endphp

                <article class="tile reveal overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-paper-deep p-3.5 sm:gap-4 sm:p-5">
                        <div>
                            <p class="font-display text-lg sm:text-2xl">{{ $order->order_number }}</p>
                            <p class="mt-0.5 text-xs text-ink-soft sm:text-sm">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>

                        <div class="flex items-center gap-3 sm:gap-4">
                            <span class="badge {{ $order->status === 'teslim_edildi' ? 'bg-yaprak-pale text-yaprak' : 'bg-sun-pale text-sun-deep' }}">
                                {{ $order->statusLabel() }}
                            </span>
                            <span class="font-display text-lg tabular-nums sm:text-2xl">{{ number_format((float) $order->total, 2, ',', '.') }} ₺</span>
                        </div>
                    </div>

                    {{-- Progress --}}
                    @if ($order->status !== 'iptal')
                        <div class="flex items-center gap-1.5 px-3.5 pt-4 sm:gap-2 sm:px-5 sm:pt-5">
                            @foreach ([__('shop.status.hazirlaniyor'), __('shop.status.kargoda'), __('shop.status.teslim_edildi')] as $index => $label)
                                <div class="flex flex-1 items-center gap-1.5 sm:gap-2">
                                    <span class="grid size-6 shrink-0 place-items-center rounded-full text-[0.65rem] font-bold sm:size-7 sm:text-xs {{ $index <= $currentStep ? 'bg-sun text-ink' : 'border-2 border-paper-deep text-ink-faint' }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="hidden text-xs font-semibold sm:block {{ $index <= $currentStep ? '' : 'text-ink-faint' }}">{{ $label }}</span>
                                    @unless ($loop->last)
                                        <span class="h-1 flex-1 rounded-full {{ $index < $currentStep ? 'bg-sun' : 'bg-paper-deep' }}"></span>
                                    @endunless
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Thumbnails --}}
                    <div class="flex flex-wrap items-center gap-2.5 p-3.5 sm:gap-3 sm:p-5">
                        @foreach ($order->items as $item)
                            <span class="relative">
                                <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}"
                                     width="56" height="56" class="size-11 rounded-xl object-cover sm:size-14 sm:rounded-2xl">
                                <span class="absolute -top-1.5 -right-1.5 grid size-4.5 place-items-center rounded-full bg-ink text-[0.6rem] font-bold text-white sm:size-5 sm:text-[0.65rem]">{{ $item->quantity }}</span>
                            </span>
                        @endforeach

                        <a href="{{ route('account.order', $order) }}" class="btn btn-quiet ml-auto">{{ __('account.orders.detail') }}</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

</x-account-shell>

@endsection
