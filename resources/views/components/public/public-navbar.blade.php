{{-- Public Navbar Component --}}
<header class="public-navbar">
    <div class="nav-container">
        <nav class="nav-content">
            {{-- Logo + Brand --}}
            <x-common.app-logo variant="public" size="md" />

            {{-- Nav actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('client.login') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white hero-gradient hover:opacity-90 rounded-lg shadow-sm transition-opacity">
                    Đăng ký
                </a>
            </div>
        </nav>
    </div>
</header>
