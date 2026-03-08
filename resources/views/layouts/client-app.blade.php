<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Dashboard' }} – FlashMaster</title>
    @vite(['resources/css/app.css', 'resources/css/client/client.css'])
    @stack('styles')
</head>
<body class="client-app-body">

    {{-- Client App Layout --}}
    <div class="client-app-layout" data-client-app>

        {{-- Sidebar --}}
        @include('components.client.client-sidebar')

        {{-- Main Content Area --}}
        <div class="client-main-wrapper">

            {{-- Topbar --}}
            @include('components.client.client-topbar')

            {{-- Page Content --}}
            <main class="client-main-content">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="client-footer">
                <div class="footer-content">
                    <p>&copy; {{ date('Y') }} FlashMaster. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">About</a>
                        <a href="#">Help</a>
                        <a href="#">Privacy</a>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    {{-- Toasts Container --}}
    <div class="toasts-container" data-toasts-container></div>

    {{-- Modals Container --}}
    <div class="modals-container" data-modals-container>
        @stack('modals')
    </div>

    {{-- Scripts --}}
    @vite(['resources/js/client/client.js'])
    @stack('scripts')

</body>
</html>
