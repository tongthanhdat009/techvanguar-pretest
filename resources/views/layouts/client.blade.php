<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Dashboard' }} – Flashcard Learning Hub</title>
    @vite(['resources/css/app.css', 'resources/css/client/client.css'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    {{-- Client Header --}}
    @include('components.client.client-header')

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    {{-- Scripts --}}
    @vite(['resources/js/client/client.js'])

    @stack('scripts')

</body>
</html>
