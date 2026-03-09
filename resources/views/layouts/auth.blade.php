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
        $showcase = $authType === 'admin'
            ? [
                'badge' => 'Admin access',
                'title' => 'Bảng điều khiển quản trị rõ ràng, an toàn và tập trung.',
                'copy' => 'Theo dõi hoạt động người dùng, chất lượng nội dung và sức khỏe nền tảng trong một không gian làm việc gọn gàng.',
                'pills' => ['User oversight', 'Deck moderation', 'System visibility'],
                'cards' => [
                    ['label' => 'Control', 'body' => 'Điều phối người dùng, deck và review trong cùng một luồng quản trị.'],
                    ['label' => 'Visibility', 'body' => 'Nắm nhanh các chỉ số hệ thống và vùng cần can thiệp.'],
                ],
                'timeline' => [
                    'Đăng nhập bằng guard admin riêng biệt.',
                    'Kiểm tra nội dung, review và trạng thái người dùng.',
                    'Giữ hệ thống sạch, rõ và nhất quán.',
                ],
                'panelKicker' => 'Admin portal',
            ]
            : [
                'badge' => 'Client access',
                'title' => 'Biến việc ôn tập mỗi ngày thành một hệ thống học tập bền vững.',
                'copy' => 'Tạo deck nhanh, theo dõi streak, nhận lịch ôn tập thông minh và giữ nhịp học đều đặn trên mọi thiết bị.',
                'pills' => ['Spaced repetition', 'Public decks', 'Daily rhythm'],
                'cards' => [
                    ['label' => 'Focus', 'body' => 'Ưu tiên đúng thẻ cần học thay vì ôn tập dàn trải.'],
                    ['label' => 'Rhythm', 'body' => 'Duy trì streak, XP và tiến độ ghi nhớ bằng các nhịp học ngắn.'],
                ],
                'timeline' => [
                    'Tạo tài khoản học viên trong vài bước ngắn.',
                    'Bắt đầu từ deck riêng hoặc sao chép deck công khai.',
                    'Ôn theo lịch thay vì học theo cảm hứng.',
                ],
                'panelKicker' => 'Learning portal',
            ];
    @endphp

    <div class="auth-stage auth-stage-{{ $type ?? 'client' }}">
        <section class="auth-showcase {{ $type ?? 'client' }}">
            <div class="auth-showcase-topline">
                <div class="auth-showcase-badge">{{ $showcase['badge'] }}</div>
                <span class="auth-showcase-brand">Flashcard Learning Hub</span>
            </div>
            <h2 class="auth-showcase-title">{{ $showcase['title'] }}</h2>
            <p class="auth-showcase-copy">{{ $showcase['copy'] }}</p>

            <div class="auth-showcase-pills" aria-label="Điểm nhấn">
                @foreach($showcase['pills'] as $pill)
                    <span>{{ $pill }}</span>
                @endforeach
            </div>

            <div class="auth-showcase-grid">
                @foreach($showcase['cards'] as $card)
                    <div class="auth-showcase-card">
                        <span class="auth-showcase-metric">{{ $card['label'] }}</span>
                        <p>{{ $card['body'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="auth-showcase-timeline" aria-label="Quy trình">
                @foreach($showcase['timeline'] as $index => $item)
                    <div class="auth-showcase-step">
                        <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <p>{{ $item }}</p>
                    </div>
                @endforeach
            </div>

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
                    <p class="auth-panel-copy">Đăng nhập hoặc tạo tài khoản để tiếp tục trong cùng một không gian học tập được tổ chức rõ ràng.</p>
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
