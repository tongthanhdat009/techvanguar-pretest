<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin Dashboard' }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon-logo.svg') }}">
        <script>
            (function () {
                try {
                    document.documentElement.dataset.adminSidebarDesktop = localStorage.getItem('admin-sidebar-desktop') === 'collapsed'
                        ? 'collapsed'
                        : 'expanded';
                } catch (error) {
                    document.documentElement.dataset.adminSidebarDesktop = 'expanded';
                }

                document.documentElement.dataset.adminSidebarMobile = 'closed';
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell font-sans antialiased bg-slate-50">
        @php
            $toasts = [];

            if (session('status')) {
                $toasts[] = ['type' => 'success', 'message' => session('status')];
            }

            foreach ($errors->all() as $error) {
                $toasts[] = ['type' => 'error', 'message' => $error];
            }

            $sidebarLinks = [
                [
                    'label' => 'Overview',
                    'route' => 'admin.overview',
                    'active' => ['admin.overview'],
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                ],
                [
                    'label' => 'Users',
                    'route' => 'admin.users',
                    'active' => ['admin.users*'],
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                ],
                [
                    'label' => 'Decks',
                    'route' => 'admin.decks',
                    'active' => ['admin.decks*'],
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                ],
                [
                    'label' => 'Reviews',
                    'route' => 'admin.reviews',
                    'active' => ['admin.reviews*'],
                    'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                ],
            ];
        @endphp

        <div class="relative min-h-screen overflow-hidden">
            <!-- Mobile Sidebar Overlay -->
            <div id="sidebar-overlay"
                 data-sidebar-close
                 class="fixed inset-0 z-40 hidden bg-slate-950/60 lg:hidden"
                 aria-hidden="true"></div>
            <!-- Sidebar -->
            <aside id="sidebar" data-admin-sidebar class="fixed inset-y-0 left-0 z-50 flex h-screen w-[17rem] max-w-[85vw] -translate-x-full flex-col bg-slate-950 text-white shadow-2xl transition-all duration-300 ease-in-out lg:w-64 lg:max-w-none lg:translate-x-0 lg:shadow-none">
                <!-- Logo & Toggle -->
                <div class="p-4 border-b border-slate-800 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.overview') }}"
                       data-admin-navigate
                       class="sidebar-logo flex-1 min-w-0 text-lg font-bold tracking-tight text-white whitespace-nowrap overflow-hidden text-ellipsis"
                       title="Admin Panel">
                        <span class="sidebar-text">Admin Panel</span>
                        <span class="sidebar-text-short hidden">A</span>
                    </a>
                    <button type="button"
                            data-sidebar-close
                            class="lg:hidden p-2 rounded-lg hover:bg-slate-800 transition flex-shrink-0 ml-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-3 space-y-1 overflow-y-auto" data-admin-sidebar-nav>
                    @foreach ($sidebarLinks as $link)
                        @php
                            $isActive = collect($link['active'])->contains(fn (string $pattern) => request()->routeIs($pattern));
                        @endphp
                                <a href="{{ route($link['route']) }}"
                                    data-admin-navigate
                           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium {{ $isActive ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                           title="{{ $link['label'] }}"
                           @if ($isActive) aria-current="page" @endif>
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                            </svg>
                            <span class="sidebar-text whitespace-nowrap">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <!-- User Info & Logout -->
                <div class="mt-auto p-4 border-t border-slate-800 sticky bg-slate-950">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex-1 min-w-0 sidebar-user">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->guard('admin')->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->guard('admin')->user()->email }}</p>
                        </div>
                        <span class="pill bg-sky-400/20 text-sky-100 text-xs flex-shrink-0">{{ auth()->guard('admin')->user()->role }}</span>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-300 border border-slate-700 rounded-lg hover:bg-slate-800 hover:text-white transition"
                                title="Logout">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="sidebar-text whitespace-nowrap">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main id="admin-main" class="flex min-h-screen min-w-0 w-full flex-1 flex-col overflow-y-auto transition-[padding-left] duration-300 ease-in-out lg:pl-64">
                <!-- Breadcrumb Header -->
                <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sticky top-0 z-10 flex items-center gap-4">
                    <!-- Desktop Toggle Button -->
                    <button type="button"
                            data-sidebar-toggle
                            data-sidebar-toggle-mode="desktop"
                            class="hidden lg:flex p-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition"
                            aria-controls="sidebar"
                            aria-expanded="true"
                            title="Toggle sidebar">
                        <svg id="toggle-icon" class="w-5 h-5 text-slate-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Mobile Toggle Button (Hamburger) -->
                    <button type="button"
                            data-sidebar-toggle
                            data-sidebar-toggle-mode="mobile"
                            class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition"
                            aria-controls="sidebar"
                            aria-expanded="false"
                            title="Open menu">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="flex-1 min-w-0" data-admin-breadcrumb>
                        <x-admin-breadcrumb :items="$breadcrumb ?? [['label' => 'Dashboard', 'url' => route('admin.overview')]]" />
                    </div>
                </header>

                <!-- Content Area -->
                <div class="flex-1 p-4 sm:p-6 lg:p-8" data-admin-page-content>
                    {{ $slot }}
                </div>
            </main>
        </div>

        <x-admin-toast :toasts="$toasts" />
        <x-admin-confirm-modal />

        <style>
            @media (min-width: 1024px) {
                html[data-admin-sidebar-desktop='collapsed'] #sidebar {
                    width: 5rem;
                }

                html[data-admin-sidebar-desktop='collapsed'] #admin-main {
                    padding-left: 5rem;
                }

                html[data-admin-sidebar-desktop='collapsed'] #sidebar .sidebar-text,
                html[data-admin-sidebar-desktop='collapsed'] #sidebar .sidebar-user {
                    display: none;
                }

                html[data-admin-sidebar-desktop='collapsed'] #sidebar .sidebar-text-short {
                    display: inline;
                }

                html[data-admin-sidebar-desktop='collapsed'] #sidebar nav a {
                    justify-content: center;
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                }

                html[data-admin-sidebar-desktop='collapsed'] #sidebar .p-3,
                html[data-admin-sidebar-desktop='collapsed'] #sidebar .p-4 {
                    padding: 0.75rem;
                }

                html[data-admin-sidebar-desktop='collapsed'] #sidebar .sidebar-logo {
                    text-align: center;
                }

                html[data-admin-sidebar-desktop='collapsed'] #toggle-icon {
                    transform: rotate(180deg);
                }

                html[data-admin-sidebar-desktop='expanded'] #admin-main {
                    padding-left: 16rem;
                }

                html[data-admin-sidebar-desktop='expanded'] #sidebar .sidebar-text-short {
                    display: none;
                }

                html[data-admin-sidebar-desktop='expanded'] #sidebar .sidebar-text,
                html[data-admin-sidebar-desktop='expanded'] #sidebar .sidebar-user {
                    display: block;
                }
            }

            @media (max-width: 1023px) {
                html[data-admin-sidebar-mobile='open'] #sidebar {
                    transform: translateX(0);
                }

                html[data-admin-sidebar-mobile='open'] #sidebar-overlay {
                    display: block;
                }
            }

            #sidebar .sidebar-text,
            #sidebar .sidebar-user {
                display: block;
            }
        </style>
    </body>
</html>
