<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin Login' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell font-sans antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
            <!-- Admin Header -->
            <header class="mb-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <div class="rounded-xl bg-slate-900 p-3 ring-1 ring-slate-700">
                        <svg class="h-8 w-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h1 class="text-2xl font-bold text-white">Admin Portal</h1>
                        <p class="text-sm text-slate-400">Flashcard Learning Hub</p>
                    </div>
                </a>
            </header>

            <!-- Status Messages -->
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-center">
                    <p class="text-sm font-medium text-emerald-400">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-rose-500/10 border border-rose-500/20 px-4 py-3">
                    <ul class="list-disc space-y-1 pl-5 text-sm text-rose-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Content -->
            {{ $slot }}

            <!-- Footer -->
            <footer class="mt-12 text-center text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} Flashcard Learning Hub. All rights reserved.</p>
                <div class="mt-2 flex justify-center gap-4">
                    <a href="{{ route('home') }}" class="hover:text-slate-300 transition">Back to Home</a>
                    <a href="{{ route('client.login') }}" class="hover:text-slate-300 transition">Client Portal</a>
                </div>
            </footer>
        </div>
    </body>
</html>
