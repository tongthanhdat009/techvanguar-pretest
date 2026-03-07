<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin Dashboard' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell font-sans antialiased bg-slate-50">
        <div class="flex h-screen overflow-hidden relative">
            <!-- Mobile Sidebar Overlay -->
            <div id="sidebar-overlay"
                 onclick="closeSidebar()"
                 class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"
                 aria-hidden="true"></div>
            <!-- Sidebar -->
            <aside id="sidebar" class="w-64 bg-slate-950 text-white flex flex-col transition-all duration-300 ease-in-out">
                <!-- Logo & Toggle -->
                <div class="p-4 border-b border-slate-800 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.overview') }}"
                       class="sidebar-logo flex-1 min-w-0 text-lg font-bold tracking-tight text-white whitespace-nowrap overflow-hidden text-ellipsis"
                       title="Admin Panel">
                        <span class="sidebar-text">Admin Panel</span>
                        <span class="sidebar-text-short hidden">A</span>
                    </a>
                    <button type="button"
                            onclick="closeSidebar()"
                            class="lg:hidden p-2 rounded-lg hover:bg-slate-800 transition flex-shrink-0 ml-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                    <a href="{{ route('admin.overview') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.overview') ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                       title="Overview">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Overview</span>
                    </a>

                    <a href="{{ route('admin.users') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users*') ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                       title="Users">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Users</span>
                    </a>

                    <a href="{{ route('admin.decks') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.decks*') ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                       title="Decks">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Decks</span>
                    </a>

                    <a href="{{ route('admin.reviews') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reviews*') ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                       title="Reviews">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Reviews</span>
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="p-4 border-t border-slate-800">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex-1 min-w-0 sidebar-user">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <span class="pill bg-sky-400/20 text-sky-100 text-xs flex-shrink-0">{{ auth()->user()->role }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
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
            <main class="flex-1 overflow-y-auto flex flex-col">
                <!-- Breadcrumb Header -->
                <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sticky top-0 z-10 flex items-center gap-4">
                    <!-- Desktop Toggle Button -->
                    <button type="button"
                            onclick="toggleSidebar()"
                            class="hidden lg:flex p-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition"
                            title="Toggle sidebar">
                        <svg id="toggle-icon" class="w-5 h-5 text-slate-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Mobile Toggle Button (Hamburger) -->
                    <button type="button"
                            onclick="toggleSidebar()"
                            class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition"
                            title="Open menu">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="flex-1 min-w-0">
                        <x-admin-breadcrumb :items="$breadcrumb ?? [['label' => 'Dashboard', 'url' => route('admin.overview')]]" />
                    </div>
                </header>

                <!-- Content Area -->
                <div class="flex-1 p-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>

        <script>
            let sidebarOpen = localStorage.getItem('sidebarOpen') !== 'false';

            function toggleSidebar() {
                sidebarOpen = !sidebarOpen;
                localStorage.setItem('sidebarOpen', sidebarOpen);
                updateSidebar();
            }

            function closeSidebar() {
                sidebarOpen = false;
                localStorage.setItem('sidebarOpen', 'false');
                updateSidebar();
            }

            function updateSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const toggleIcon = document.getElementById('toggle-icon');

                if (sidebarOpen) {
                    sidebar.classList.remove('-translate-x-full', 'lg:w-20');
                    sidebar.classList.add('w-64');
                    // Show overlay on mobile
                    if (overlay) overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('lg:w-20');
                    sidebar.classList.remove('w-64');
                    // Hide overlay on mobile
                    if (overlay) overlay.classList.add('hidden');
                }

                // Update icon rotation
                if (toggleIcon) {
                    toggleIcon.style.transform = sidebarOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateSidebar();
            });
        </script>

        <style>
            /* Hide sidebar text when collapsed on desktop */
            @media (min-width: 1024px) {
                aside.lg\:w-20 .sidebar-text,
                aside.lg\:w-20 .sidebar-user {
                    display: none;
                }

                aside.lg\:w-20 .sidebar-text-short {
                    display: inline;
                }

                aside.lg\:w-20 nav a {
                    justify-content: center;
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                }

                aside.lg\:w-20 .p-3,
                aside.lg\:w-20 .p-4 {
                    padding: 0.75rem;
                }

                /* Center logo when collapsed */
                aside.lg\:w-20 .sidebar-logo {
                    text-align: center;
                }
            }

            /* Mobile sidebar */
            @media (max-width: 1023px) {
                #sidebar {
                    position: fixed;
                    inset: 0;
                    z-index: 50;
                    transform: translateX(-100%);
                }

                #sidebar:not(.-translate-x-full) {
                    transform: translateX(0);
                }
            }

            /* Hide short text when expanded */
            @media (min-width: 1024px) {
                aside:not(.lg\:w-20) .sidebar-text-short {
                    display: none;
                }
            }
        </style>
    </body>
</html>
