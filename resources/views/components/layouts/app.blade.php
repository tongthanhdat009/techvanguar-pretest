<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Flashcard Learning Hub' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell font-sans antialiased">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Public Header -->
            <header class="mb-8 flex flex-col gap-4 rounded-3xl bg-slate-950 px-6 py-5 text-white shadow-2xl shadow-slate-950/30 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight text-white">Flashcard Learning Hub</a>
                    <p class="mt-2 text-sm text-slate-300">Evidence-based learning platform with spaced repetition and active recall.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    @guest
                        <a href="{{ route('client.login') }}" class="rounded-full bg-white px-4 py-2 font-semibold text-slate-950 transition hover:bg-sky-100">Scholar Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-amber-300 px-4 py-2 font-semibold text-slate-950 transition hover:bg-amber-200">Create Account</a>
                    @else
                        <!-- Redirect authenticated users to appropriate portal -->
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.overview') }}" class="rounded-full bg-white px-4 py-2 font-semibold text-slate-950 transition hover:bg-sky-100">Admin Panel</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="rounded-full bg-white px-4 py-2 font-semibold text-slate-950 transition hover:bg-sky-100">Go to Learning Portal</a>
                        @endif
                    @endguest
                </div>
            </header>

            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </body>
</html>
