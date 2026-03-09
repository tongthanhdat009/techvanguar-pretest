<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Authentication' }} – Flashcard Learning Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/auth/auth.css'])
    @stack('styles')
</head>
<body class="auth-container {{ $type ?? 'client' }}">

    @php
        $authType = $type ?? 'client';
        $typingTexts = $authType === 'admin'
            ? '["Đăng nhập admin.", "Vào khu vực quản trị."]'
            : '["Quay lại nhịp học.", "Tiếp tục học tập.", "Đúng tiến độ của bạn."]';
        $showcase = $authType === 'admin'
            ? [
                'badge' => 'Admin access',
                'title' => '<span class="typing-text" data-typing=\'' . $typingTexts . '\'></span>',
                'copy' => 'Truy cập nhanh phần kiểm duyệt, người dùng và nội dung.',
                'panelKicker' => 'Admin portal',
                'panelCopy' => 'Đăng nhập để tiếp tục quản trị hệ thống.',
            ]
            : [
                'badge' => 'Client access',
                'title' => '<span class="typing-text" data-typing=\'' . $typingTexts . '\'></span>',
                'copy' => 'Mở lại deck, lịch ôn tập và tiến độ đang có chỉ trong vài giây.',
                'panelKicker' => 'Learning portal',
                'panelCopy' => 'Đăng nhập hoặc tạo tài khoản để tiếp tục học.',
            ];
    @endphp

    <div class="auth-stage auth-stage-{{ $type ?? 'client' }}">
        <section class="auth-showcase {{ $type ?? 'client' }}">
            <div class="auth-showcase-topline">
                <div class="auth-showcase-badge">{{ $showcase['badge'] }}</div>
                <span class="auth-showcase-brand">Flashcard Learning Hub</span>
            </div>

            <div class="auth-floating-logo">
                <img src="{{ asset('assets/icon-logo.svg') }}" alt="Flashcard Learning Hub" class="floating-logo">
                <div class="floating-particles">
                    <span class="particle particle-1"></span>
                    <span class="particle particle-2"></span>
                    <span class="particle particle-3"></span>
                </div>
            </div>

            <h2 class="auth-showcase-title">{!! $showcase['title'] !!}</h2>
            <p class="auth-showcase-copy">{{ $showcase['copy'] }}</p>

            <div class="auth-showcase-footer">
                <a href="{{ route('home') }}">Trang chủ</a>
                <a href="{{ route('home') }}#demo">Xem demo</a>
                @if($authType !== 'admin')
                    <a href="{{ route('admin.login') }}">Admin login</a>
                @endif
            </div>
        </section>

        <section class="auth-panel {{ $type ?? 'client' }}">
            <div class="auth-panel-shell {{ $type ?? 'client' }}">
                <div class="auth-panel-heading">
                    <span class="auth-panel-kicker">{{ $showcase['panelKicker'] }}</span>
                    <p class="auth-panel-copy">{{ $showcase['panelCopy'] }}</p>
                </div>

                <div class="auth-box {{ $type ?? 'client' }}">
                    @yield('content')

                    <p class="auth-back-link {{ $type ?? 'client' }}">
                        <a href="{{ route('home') }}">← Về trang chủ</a>
                    </p>
                </div>
            </div>
        </section>
    </div>

    {{-- Scripts --}}
    @vite(['resources/js/auth/auth.js'])

    @stack('scripts')

</body>
</html>
