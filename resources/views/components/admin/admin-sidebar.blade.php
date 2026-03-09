{{-- Admin Sidebar Component --}}
<div admin-sidebar-open="{{ $isOpen ?? 'false' }}" id="admin-sidebar" class="admin-sidebar">
    <div class="flex flex-col h-full">
        <div class="p-6 border-b border-slate-700">
            <a href="{{ route('admin.overview') }}" class="flex items-center gap-3">
                <span class="w-10 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" class="w-full h-full">
                        <defs>
                            <linearGradient id="adminLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#22D3EE" />
                                <stop offset="100%" stop-color="#F97316" />
                            </linearGradient>
                        </defs>
                        <rect x="10" y="10" width="100" height="100" rx="22" fill="#FFF7ED" />
                        <rect x="31" y="21" width="58" height="78" rx="8" fill="#FFFFFF" stroke="url(#adminLogoGradient)" stroke-width="4" />
                        <line x1="41" y1="38" x2="79" y2="38" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round"/>
                        <line x1="41" y1="52" x2="67" y2="52" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round"/>
                        <path d="M66 58 L48 75 L58 75 L53 93 L73 69 L63 69 Z" fill="url(#adminLogoGradient)" />
                    </svg>
                </span>
                <div>
                    <span class="text-lg font-bold text-white">Admin Console</span>
                    <span class="text-xs text-slate-400 block">Flashcard operations</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.overview') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.overview') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Overview
            </a>

            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Users
            </a>

            <a href="{{ route('admin.decks') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.decks*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Decks
            </a>

            <a href="{{ route('admin.reviews') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.reviews*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Reviews
            </a>
        </nav>

        {{-- Back to landing --}}
        <div class="p-4 border-t border-slate-700">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to landing
            </a>
        </div>
    </div>
</div>
