<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Flashcard Learning Hub' }} – Ghi nhớ mọi thứ dễ dàng hơn</title>
    @if(isset($description))
        <meta name="description" content="{{ $description }}" />
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/public/public.css'])
    @stack('styles')
</head>
<body class="public-shell">

    {{-- Navbar --}}
    @include('components.public.public-navbar')

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('components.public.public-footer')

    {{-- Scripts --}}
    @vite(['resources/js/public/public.js'])

    @stack('scripts')

</body>
</html>
