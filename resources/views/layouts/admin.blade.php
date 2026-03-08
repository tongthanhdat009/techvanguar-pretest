<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Admin Portal' }} – Flashcard Learning Hub</title>
    @vite(['resources/css/app.css', 'resources/css/admin/admin.css'])
</head>
<body class="bg-slate-900 min-h-screen font-sans antialiased">

    {{-- Admin Sidebar --}}
    @if (isset($sidebar) && $sidebar !== false)
        @include('components.admin.admin-sidebar', ['isOpen' => true])
        {{-- Mobile overlay --}}
        <div data-admin-sidebar-overlay class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>
    @endif

    {{-- Toast Notifications --}}
    <x-admin.admin-toast :messages="$flashMessages ?? []" />

    {{-- Confirm Modal --}}
    <x-admin.admin-confirm-modal />

    {{-- Main wrapper --}}
    <div class="{{ (isset($sidebar) && $sidebar !== false) ? 'lg:ml-[260px]' : '' }}">

        {{-- Top Navbar --}}
        <header class="admin-topnav">
            @if (isset($sidebar) && $sidebar !== false)
                <button data-admin-sidebar-toggle class="admin-topnav-toggle lg:hidden" aria-label="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            @endif
            <span class="text-white font-semibold text-sm">{{ $title ?? 'Admin Portal' }}</span>
            <div class="flex items-center gap-3 ml-auto">
                <span class="text-slate-400 text-sm hidden sm:block">{{ auth('admin')->user()?->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-white text-sm transition-colors">Logout</button>
                </form>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="{{ $mainClass ?? 'p-6' }}">
            {{-- Breadcrumb --}}
            @if (isset($breadcrumb) && $breadcrumb !== false)
                @include('components.admin.admin-breadcrumb', $breadcrumb)
            @endif

            {{-- Page Header --}}
            @if (isset($header))
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-white">{{ $header['title'] ?? '' }}</h1>
                    @if (isset($header['subtitle']))
                        <p class="text-slate-400 mt-1">{{ $header['subtitle'] }}</p>
                    @endif
                </div>
            @endif

            {{-- Content --}}
            @yield('content')
        </main>
    </div>

    {{-- Scripts --}}
    @vite(['resources/js/admin/admin.js'])

    @stack('scripts')

</body>
</html>
