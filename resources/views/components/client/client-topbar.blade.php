@php
    $user = auth()->user();
    $level = $user->level();
    $levelProgress = $user->levelProgress();
    $currentLevelXp = $levelProgress['progress_within_level'];
    $nextLevelXp = $levelProgress['xp_per_level'];
    $xpProgress = $levelProgress['percent'];
    $dailyStreak = $user->daily_streak ?? 0;
    $dueCount = app(\App\Services\StudyScheduler::class)->dueTodayCount($user);
@endphp

<header class="client-topbar">
    <!-- Left Section: Mobile Menu Toggle & Search -->
    <div class="topbar-left">
        <button class="mobile-menu-btn" data-mobile-menu-toggle aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Search Bar -->
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text"
                   class="search-input"
                   placeholder="Search decks..."
                   id="deckSearch"
                   autocomplete="off">
        </div>
    </div>

    <!-- Right Section: Gamification Stats & User Menu -->
    <div class="topbar-right">
        <!-- Study Reminder (if cards due) -->
        @if($dueCount > 0)
            <a href="{{ route('client.study.all') }}" class="study-reminder">
                <span class="reminder-icon">📚</span>
                <span class="reminder-text">{{ $dueCount }} cards due!</span>
            </a>
        @endif

        <!-- Level Badge with XP Progress -->
        <div class="level-badge-container">
            <div class="level-badge">
                <span class="level-icon">⭐</span>
                <span class="level-number">Lvl {{ $level }}</span>
            </div>
            <!-- XP Progress Tooltip -->
            <div class="xp-progress-tooltip">
                <div class="xp-info">
                    <span class="xp-current">{{ number_format($currentLevelXp) }}</span>
                    <span class="xp-separator">/</span>
                    <span class="xp-required">{{ number_format($nextLevelXp) }} XP</span>
                </div>
                <div class="xp-progress-bar">
                    <div class="xp-progress-fill" style="width: {{ $xpProgress }}%"></div>
                </div>
                <div class="xp-percentage">{{ $xpProgress }}%</div>
            </div>
        </div>

        <!-- Daily Streak -->
        <div class="streak-badge @if($dailyStreak >= 7) streak-fire @endif">
            <span class="streak-icon">🔥</span>
            <span class="streak-count">{{ $dailyStreak }}</span>
            <span class="streak-label">day{{ $dailyStreak > 1 ? 's' : '' }}</span>
        </div>

        <!-- User Dropdown -->
        <div class="user-dropdown" data-user-dropdown>
            <button class="user-dropdown-btn" data-dropdown-toggle aria-expanded="false">
                <div class="user-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <span class="user-name">{{ $user->name }}</span>
                <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">{{ substr($user->name, 0, 1) }}</div>
                    <div class="dropdown-user-info">
                        <div class="dropdown-user-name">{{ $user->name }}</div>
                        <div class="dropdown-user-email">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="dropdown-stats">
                    <div class="dropdown-stat">
                        <span class="dropdown-stat-label">Level</span>
                        <span class="dropdown-stat-value">{{ $level }}</span>
                    </div>
                    <div class="dropdown-stat">
                        <span class="dropdown-stat-label">Streak</span>
                        <span class="dropdown-stat-value">{{ $dailyStreak }} 🔥</span>
                    </div>
                    <div class="dropdown-stat">
                        <span class="dropdown-stat-label">XP</span>
                        <span class="dropdown-stat-value">{{ number_format($user->experience_points ?? 0) }}</span>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a href="{{ route('client.profile') }}" class="dropdown-item">
                    <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Profile</span>
                </a>
                <a href="{{ route('client.dashboard') }}" class="dropdown-item">
                    <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST" class="dropdown-logout-form">
                    @csrf
                    <button type="submit" class="dropdown-item dropdown-item-danger">
                        <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
