<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Flashcard Learning Hub' }} – Ghi nhớ mọi thứ dễ dàng hơn</title>
    @if(isset($description))
        <meta name="description" content="{{ $description }}" />
    @endif
    @vite(['resources/css/app.css', 'resources/css/public/public.css'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    {{-- Navbar --}}
    @include('components.public.public-navbar')

    {{-- Main Content --}}
    {{ $slot ?? '' }}

    {{-- Footer --}}
    @include('components.public.public-footer')

    {{-- Scripts --}}
    @vite(['resources/js/public/public.js'])

    @stack('scripts')

</body>
</html>
