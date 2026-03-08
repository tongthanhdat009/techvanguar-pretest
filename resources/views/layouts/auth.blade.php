<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Authentication' }} – Flashcard Learning Hub</title>
    @vite(['resources/css/app.css', 'resources/css/auth/auth.css'])
</head>
<body class="auth-container {{ $type ?? 'client' }}">

    <div class="auth-box {{ $type ?? 'client' }}">
        {{-- Logo --}}
        @include('components.auth.auth-logo', ['type' => $type ?? 'client'])

        {{-- Content --}}
        <div class="content">
            @yield('content')
        </div>

        {{-- Back to Home --}}
        <p class="auth-back-link {{ $type ?? 'client' }}">
            <a href="{{ route('home') }}" class="hover:text-gray-600 transition-colors">← Về trang chủ</a>
        </p>
    </div>

    {{-- Scripts --}}
    @vite(['resources/js/auth/auth.js'])

    @stack('scripts')

</body>
</html>
