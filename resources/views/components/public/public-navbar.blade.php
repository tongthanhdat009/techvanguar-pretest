{{-- Public Navbar Component --}}
<header class="public-navbar">
    <div class="nav-container">
        <nav class="nav-content">
            <a href="{{ route('home') }}" class="public-brand">
                <span class="public-brand-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" class="w-full h-full">
                        <defs>
                            <linearGradient id="navbarGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#0F766E" />
                                <stop offset="100%" stop-color="#F97316" />
                            </linearGradient>
                        </defs>
                        <rect x="10" y="10" width="100" height="100" rx="22" fill="#FFF7ED" />
                        <rect x="31" y="21" width="58" height="78" rx="8" fill="#FFFFFF" stroke="url(#navbarGradient)" stroke-width="4" />
                        <line x1="41" y1="38" x2="79" y2="38" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round"/>
                        <line x1="41" y1="52" x2="67" y2="52" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round"/>
                        <path d="M66 58 L48 75 L58 75 L53 93 L73 69 L63 69 Z" fill="url(#navbarGradient)" />
                    </svg>
                </span>
                <span>
                    <span class="public-brand-name">Flashcard Hub</span>
                    <span class="public-brand-subtitle">Spaced learning platform</span>
                </span>
            </a>

            <div class="public-nav-links hidden lg:flex">
                <a href="#features">Tính năng</a>
                <a href="#demo">Demo</a>
                <a href="#community-decks">Bộ thẻ cộng đồng</a>
            </div>

            @auth
                <div class="public-nav-actions desktop-only">
                    <div class="public-user-chip">
                        <span class="public-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span>
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ auth()->user()->role === 'admin' ? 'Admin portal' : 'Learning portal' }}</small>
                        </span>
                    </div>
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.overview') : route('client.dashboard') }}" class="public-btn public-btn-primary">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="public-btn public-btn-ghost">Đăng xuất</button>
                    </form>
                </div>
            @else
                <div class="public-nav-actions desktop-only">
                    <a href="{{ route('admin.login') }}" class="public-btn public-btn-ghost public-btn-ghost-muted">Admin</a>
                    <a href="{{ route('client.login') }}" class="public-btn public-btn-ghost">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="public-btn public-btn-primary">Dùng miễn phí</a>
                </div>
            @endauth

            <button
                type="button"
                class="public-mobile-toggle lg:hidden"
                data-mobile-menu-toggle
                aria-controls="public-mobile-menu"
                aria-expanded="false"
                aria-label="Mở điều hướng"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>
                </svg>
            </button>
        </nav>

        <div id="public-mobile-menu" class="public-mobile-menu lg:hidden" data-mobile-menu>
            <div class="public-mobile-menu-inner">
                <div class="public-mobile-links">
                    <a href="#features" class="public-mobile-link">Tính năng</a>
                    <a href="#demo" class="public-mobile-link">Demo</a>
                    <a href="#community-decks" class="public-mobile-link">Bộ thẻ cộng đồng</a>
                </div>

                @auth
                    <div class="public-mobile-actions">
                        <div class="public-user-chip">
                            <span class="public-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span>
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ auth()->user()->role === 'admin' ? 'Admin portal' : 'Learning portal' }}</small>
                            </span>
                        </div>
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.overview') : route('client.dashboard') }}" class="public-btn public-btn-primary">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="public-btn public-btn-ghost">Đăng xuất</button>
                        </form>
                    </div>
                @else
                    <div class="public-mobile-actions">
                        <a href="{{ route('client.login') }}" class="public-btn public-btn-ghost">Đăng nhập</a>
                        <a href="{{ route('admin.login') }}" class="public-btn public-btn-ghost public-btn-ghost-muted">Admin</a>
                        <a href="{{ route('register') }}" class="public-btn public-btn-primary">Dùng miễn phí</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
