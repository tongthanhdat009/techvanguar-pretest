<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Tổng Quan' }} – Flashcard Learning Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/client/client.css'])
    @stack('styles')
</head>
<body class="client-app-body">

    {{-- Client App Layout --}}
    <div class="client-app-layout" data-client-app>

        {{-- Sidebar --}}
        @include('components.client.client-sidebar')

        <div class="client-sidebar-overlay" data-sidebar-overlay aria-hidden="true"></div>

        {{-- Main Content Area --}}
        <div class="client-main-wrapper">

            {{-- Topbar --}}
            @include('components.client.client-topbar')

            {{-- Page Content --}}
            <main class="client-main-content">
                @yield('content')
            </main>

        </div>

    </div>

    {{-- Toasts Container --}}
    <div class="toasts-container" data-toasts-container></div>
    @if(session('status'))
        <div data-flash-toast
             data-flash-toast-type="success"
             data-flash-toast-message="{{ session('status') }}"
             hidden></div>
    @endif
    @if($errors->any())
        <div data-flash-toast
             data-flash-toast-type="error"
             data-flash-toast-message="{{ $errors->first() }}"
             hidden></div>
    @endif

    {{-- Modals Container --}}
    <div class="modals-container" data-modals-container>
        @stack('modals')
    </div>

    {{-- Scripts --}}
    @vite(['resources/js/client/client.js'])
    @stack('scripts')

</body>
</html>
