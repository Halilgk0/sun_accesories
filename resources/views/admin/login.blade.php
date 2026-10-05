{{-- Stands alone: whoever reaches this has not signed in, so it shares none
     of the editor's chrome. --}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('admin.sign_in_title') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-paper-warm px-5 py-10 text-ink antialiased">

    <main class="w-full max-w-sm">
        <div class="mb-7 text-center">
            <span class="mx-auto mb-4 grid size-12 place-items-center rounded-full bg-linear-to-br from-sun to-bole text-lg font-black text-white">S</span>
            <h1 class="font-display text-2xl">{{ __('admin.sign_in_title') }}</h1>
            <p class="mt-1.5 text-sm text-ink-soft">{{ __('admin.sign_in_lead') }}</p>
        </div>

        @if (session('status'))
            <p class="mb-5 rounded-2xl border-2 border-paper-deep bg-paper px-4 py-3 text-center text-sm font-semibold">
                {{ session('status') }}
            </p>
        @endif

        @if (blank(config('admin.password')))
            {{-- Refusing to open is the safe failure: a deployment missing its
                 password must not leave the catalogue editable by anyone. --}}
            <p class="rounded-2xl border-2 border-bole bg-paper px-4 py-3 text-sm font-semibold text-bole">
                {{ __('admin.locked') }}
            </p>
        @else
            <form method="POST" action="{{ route('admin.login.store') }}" class="tile tile-raised p-6">
                @csrf

                <label for="password" class="label">{{ __('admin.password') }}</label>
                <input id="password" name="password" type="password" required autofocus
                       autocomplete="current-password" class="field"
                       @if ($errors->has('password')) aria-invalid="true" @endif>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror

                <button type="submit" class="btn btn-sun mt-5 w-full py-3.5">{{ __('admin.sign_in') }}</button>
            </form>
        @endif
    </main>

</body>
</html>
