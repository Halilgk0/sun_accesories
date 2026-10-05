{{-- The editor is a workroom, not a shop window: no ticker, no petals, no
     footer, nothing that gets between the work and the person doing it. --}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', __('admin.title')) · {{ __('site.brand') }}</title>

    <link rel="icon" href="data:image/svg+xml,{{ rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><circle cx="16" cy="16" r="8" fill="%23FFB300"/></svg>') }}">

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="min-h-screen bg-paper-warm text-ink antialiased">

    <header class="border-b-2 border-paper-deep bg-paper">
        <div class="wrap flex flex-wrap items-center justify-between gap-3 py-4">
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5">
                <span class="grid size-9 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-base font-black text-white">S</span>
                <span>
                    <span class="block font-display text-lg leading-none">{{ __('site.brand') }}</span>
                    <span class="block text-xs text-ink-soft">{{ __('admin.title') }}</span>
                </span>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank" rel="noopener"
                   class="rounded-full border-2 border-paper-deep px-4 py-2 text-sm font-semibold transition hover:border-sun">
                    Siteyi gör
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full border-2 border-paper-deep px-4 py-2 text-sm font-semibold transition hover:border-bole hover:text-bole">
                        {{ __('admin.sign_out') }}
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="wrap py-7 sm:py-10">
        @if (session('status'))
            <p class="mb-5 rounded-2xl border-2 border-yaprak bg-white px-4 py-3 text-sm font-semibold text-yaprak sm:mb-7">
                {{ session('status') }}
            </p>
        @endif

        @yield('content')
    </main>

</body>
</html>
