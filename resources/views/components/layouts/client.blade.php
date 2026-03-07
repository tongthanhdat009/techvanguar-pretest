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
            <!-- Client-only Header -->
            <header class="mb-8 flex flex-col gap-4 rounded-3xl bg-gradient-to-r from-indigo-950 via-violet-950 to-slate-950 px-6 py-5 text-white shadow-2xl shadow-indigo-950/30 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a href="{{ route('client.dashboard') }}" class="text-2xl font-bold tracking-tight text-white">Flashcard Learning Hub</a>
                    <p class="mt-2 text-sm text-indigo-200">Evidence-based learning platform with spaced repetition and active recall.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    @auth('client')
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="font-semibold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-indigo-300">Level {{ auth()->user()->level() }} · XP {{ auth()->user()->experience_points }}</p>
                            </div>
                            <span class="pill bg-violet-400/20 text-violet-100">{{ auth()->user()->role }}</span>
                        </div>
                        <div class="w-px h-8 bg-white/20"></div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('client.dashboard') }}" class="rounded-full border border-white/20 px-3 py-2 font-medium text-white transition hover:bg-white/10">Dashboard</a>
                            <a href="{{ route('client.portal') }}" class="rounded-full border border-white/20 px-3 py-2 font-medium text-white transition hover:bg-white/10">Study</a>
                            <a href="{{ route('client.profile') }}" class="rounded-full border border-white/20 px-3 py-2 font-medium text-white transition hover:bg-white/10">Profile</a>
                        </div>
                        <form action="{{ route('client.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-full bg-white/10 px-4 py-2 font-semibold text-white transition hover:bg-white/20 border border-white/20">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('client.login') }}" class="rounded-full bg-white px-4 py-2 font-semibold text-slate-950 transition hover:bg-indigo-50">Scholar Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-amber-300 px-4 py-2 font-semibold text-slate-950 transition hover:bg-amber-200">Create Account</a>
                    @endauth
                </div>
            </header>

            <!-- Quick Stats Bar for Logged-in Clients -->
            @auth('client')
                @if(auth()->user()->isClient())
                    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="rounded-2xl bg-gradient-to-br from-indigo-50 to-violet-50 border border-indigo-100 px-4 py-3 text-center">
                            <p class="text-2xl font-bold text-indigo-950">{{ auth()->user()->level() }}</p>
                            <p class="text-xs text-indigo-600">Level</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 px-4 py-3 text-center">
                            <p class="text-2xl font-bold text-amber-950">{{ auth()->user()->daily_streak }}</p>
                            <p class="text-xs text-amber-600">Day Streak</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 px-4 py-3 text-center">
                            <p class="text-2xl font-bold text-emerald-950">{{ auth()->user()->experience_points }}</p>
                            <p class="text-xs text-emerald-600">XP Points</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-cyan-50 border border-sky-100 px-4 py-3 text-center">
                            <p class="text-2xl font-bold text-sky-950">{{ \App\Models\StudyProgress::where('user_id', auth()->id())->where('status', 'mastered')->count() }}</p>
                            <p class="text-xs text-sky-600">Mastered</p>
                        </div>
                    </div>
                @endif
            @endauth

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

            <x-footer />
        </div>
    </body>
</html>
