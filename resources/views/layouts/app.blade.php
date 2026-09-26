<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', __('site.meta_description'))">
    <title>@yield('title', __('site.brand')) · {{ __('site.brand') }}</title>

    <link rel="icon" href="data:image/svg+xml,{{ rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><circle cx="16" cy="16" r="8" fill="%23FFB300"/><g stroke="%23EF8A00" stroke-width="2.4" stroke-linecap="round"><path d="M16 1v4M16 27v4M1 16h4M27 16h4M5.5 5.5l2.8 2.8M23.7 23.7l2.8 2.8M26.5 5.5l-2.8 2.8M8.3 23.7l-2.8 2.8"/></g></svg>') }}">

    {{-- Reveal animations only hide content when JavaScript is there to bring it back. --}}
    <script>document.documentElement.classList.add('js');</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper text-ink antialiased">

    {{-- Sunbeam reading progress --}}
    <div class="fixed inset-x-0 top-0 z-60 h-0.5 origin-left scale-x-0 bg-linear-to-r from-sun via-bole to-cobalt sm:h-1"
         data-scroll-progress aria-hidden="true"></div>

    @include('partials.header')

    <main id="icerik" class="relative z-10">
        @include('partials.flash')
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>
