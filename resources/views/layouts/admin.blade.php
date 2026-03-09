<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Admin Portal' }} – Flashcard Learning Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin/admin.css'])
    @stack('styles')
</head>
<body class="admin-shell">

    {{-- Admin Sidebar --}}
    @if (isset($sidebar) && $sidebar !== false)
        @include('components.admin.admin-sidebar', ['isOpen' => true])
        {{-- Overlay for mobile/sidebar closed state --}}
        <div data-admin-sidebar-overlay class="fixed inset-0 bg-black/50 z-40 hidden"></div>
    @endif

    {{-- Toast Notifications --}}
    <x-admin.admin-toast :messages="$flashMessages ?? []" />

    {{-- Confirm Modal --}}
    <x-admin.admin-confirm-modal />

    {{-- Main wrapper --}}
    <div id="admin-main-wrapper" class="admin-main-wrapper">

        {{-- Top Navbar --}}
        <header class="admin-topnav">
            @if (isset($sidebar) && $sidebar !== false)
                <button data-admin-sidebar-toggle class="admin-topnav-toggle" aria-label="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            @endif
            <div class="admin-topnav-title-group">
                <span class="admin-topnav-eyebrow">Operations</span>
                <span class="admin-topnav-title">{{ $title ?? 'Admin Portal' }}</span>
            </div>
            {{-- User Dropdown --}}
            <div class="flex items-center gap-3 ml-auto" x-data="{ open: false }" @click.outside="open = false">
                <div class="relative">
                    <button
                        @click="open = !open"
                        class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 transition-colors"
                        :class="{ 'bg-slate-800': open }"
                    >
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(auth('admin')->user()?->name ?? '', 0, 1)) }}
                        </span>
                        <span class="hidden sm:block text-white font-medium">{{ auth('admin')->user()?->name }}</span>
                        <svg
                            class="w-4 h-4 text-slate-500 transition-transform"
                            :class="{ 'rotate-180': open }"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute top-full right-0 mt-2 w-56 bg-slate-800 border border-slate-700 rounded-xl shadow-lg overflow-hidden z-50"
                    >
                        <div class="p-1">
                            {{-- User Info --}}
                            <div class="px-3 py-2 border-b border-slate-700 mb-1">
                                <p class="text-sm font-medium text-white">{{ auth('admin')->user()?->name }}</p>
                                <p class="text-xs text-slate-500">{{ auth('admin')->user()?->email }}</p>
                            </div>

                            {{-- Hồ sơ --}}
                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Hồ sơ
                            </a>

                            {{-- Tài khoản --}}
                            <a href="{{ route('admin.account') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Tài khoản
                            </a>

                            {{-- Cài đặt --}}
                            <a href="{{ route('admin.settings') }}"
                                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Cài đặt
                            </a>

                            <div class="border-t border-slate-700 my-1"></div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('admin.logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- Main Content --}}
        <main class="{{ $mainClass ?? 'p-4 sm:p-6' }}">
            {{-- Breadcrumb --}}
            @if (isset($breadcrumb) && $breadcrumb !== false)
                @include('components.admin.admin-breadcrumb', $breadcrumb)
            @endif

            {{-- Page Header --}}
            @if (isset($header))
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-white">{{ $header['title'] ?? '' }}</h1>
                    @if (isset($header['subtitle']))
                        <p class="text-slate-400 mt-1">{{ $header['subtitle'] }}</p>
                    @endif
                </div>
            @endif

            {{-- Content --}}
            @yield('content')
        </main>
    </div>

    {{-- Scripts --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/js/admin/admin.js'])

    @stack('scripts')

</body>
</html>
