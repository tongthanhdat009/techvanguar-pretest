@props([
    'activeRoute' => null
])

@php
    $user = auth()->user();
    $dueCount = app(\App\Services\StudyScheduler::class)->dueTodayCount($user);
@endphp

<aside class="client-sidebar">
    <div class="sidebar-header">
        <a href="{{ route('client.dashboard') }}" class="sidebar-logo" aria-label="Đi tới dashboard client">
            <img src="{{ asset('assets/icon-logo.svg') }}" alt="Flashcard Learning Hub" class="logo-icon">
            <span>
                <span class="logo-text">Flashcard Hub</span>
                <span class="sidebar-logo-subtitle">Không gian học tập</span>
            </span>
        </a>
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Thu gọn hoặc mở rộng sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Workspace</div>
        <a href="{{ route('client.dashboard') }}"
           class="nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="nav-text">Tổng quan</span>
        </a>

        <a href="{{ route('client.my-decks') }}"
           class="nav-item {{ request()->routeIs('client.my-decks') ? 'active' : '' }}">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44z" />
            </svg>
            <span class="nav-text">Deck của tôi</span>
        </a>

        <a href="{{ route('client.decks.create') }}"
           class="nav-item {{ request()->routeIs('client.decks.create') ? 'active' : '' }}">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span class="nav-text">Tạo deck</span>
        </a>

        <a href="{{ route('client.study.all') }}"
           class="nav-item {{ request()->routeIs('client.study.*') ? 'active' : '' }} nav-item-primary">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <span class="nav-text">Ôn ngay</span>
            @if($dueCount > 0)
                <span class="nav-badge">{{ $dueCount }}</span>
            @endif
        </a>

        <a href="{{ route('client.community') }}"
           class="nav-item {{ request()->routeIs('client.community') ? 'active' : '' }}">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 5.471 5.971 5.971 0 0 0-.941 3.197m0 0H6" />
            </svg>
            <span class="nav-text">Cộng đồng</span>
        </a>

        <div class="nav-divider"></div>

        <div class="sidebar-section-label">Tài khoản</div>
        <a href="{{ route('client.profile') }}"
           class="nav-item {{ request()->routeIs('client.profile') ? 'active' : '' }}">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span class="nav-text">Hồ sơ</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-focus-card">
            <span class="sidebar-focus-label">Hàng chờ hôm nay</span>
            <strong>{{ $dueCount }}</strong>
            <p>{{ $dueCount > 0 ? 'Thẻ đang chờ được ôn tập.' : 'Hiện chưa có thẻ đến hạn.' }}</p>
        </div>
        <div class="user-stats-mini">
            <div class="stat-item">
                <span class="stat-icon">🔥</span>
                <span class="stat-value">{{ $user->daily_streak ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-icon">⭐</span>
                <span class="stat-value">{{ $user->level() }}</span>
            </div>
        </div>
        <div class="sidebar-copyright">
            &copy; {{ date('Y') }} Flashcard Learning Hub
        </div>
    </div>
</aside>
