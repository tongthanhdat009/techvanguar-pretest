<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Admin Portal' }} – Flashcard Learning Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin/admin.css'])
    @stack('styles')
</head>
<body class="admin-shell">

    {{-- Admin Sidebar --}}
    @if (isset($sidebar) && $sidebar !== false)
        @include('components.admin.admin-sidebar', ['isOpen' => true])
        {{-- Overlay for mobile/sidebar closed state --}}
        <div data-admin-sidebar-overlay class="fixed inset-0 bg-black/50 z-40 hidden"></div>
    @endif

    {{-- Toast Notifications --}}
    <x-admin.admin-toast :messages="$flashMessages ?? []" />

    {{-- Confirm Modal --}}
    <x-admin.admin-confirm-modal />

    {{-- Main wrapper --}}
    <div id="admin-main-wrapper" class="admin-main-wrapper">

        {{-- Top Navbar --}}
        @include('components.admin.admin-topbar')

        {{-- Main Content --}}
        <main class="{{ $mainClass ?? 'p-4 sm:p-6' }}">
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/js/admin/admin.js'])

    @stack('scripts')

</body>
</html>
