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
        @include('components.admin.admin-sidebar')
    @endif

    {{-- Toast Notifications --}}
    <x-admin-toast-stack :messages="$flashMessages ?? []" />

    {{-- Confirm Modal --}}
    <x-admin-confirm-modal />

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
        {{ $slot ?? '' }}
    </main>

    {{-- Scripts --}}
    @vite(['resources/js/admin/admin.js'])

    @stack('scripts')

</body>
</html>
