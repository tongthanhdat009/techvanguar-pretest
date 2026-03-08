@extends('layouts.admin', [
    'title' => 'Dashboard',
    'sidebar' => true,
])

@section('content')

{{-- Page heading --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Dashboard</h1>
    <p class="text-slate-400 mt-1 text-sm">Tổng quan hệ thống · {{ now()->format('d/m/Y') }}</p>
</div>

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">

    {{-- Total Users --}}
    <div class="admin-stat-card bg-gradient-to-br from-indigo-600 to-indigo-800">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['users']) }}</div>
        <div class="stat-label">Tổng người dùng</div>
        <div class="stat-sub">{{ $stats['admins'] }} admin &middot; {{ $stats['clients'] }} client</div>
    </div>

    {{-- Total Decks --}}
    <div class="admin-stat-card bg-gradient-to-br from-purple-600 to-purple-800">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['decks']) }}</div>
        <div class="stat-label">Tổng bộ thẻ</div>
        <div class="stat-sub">{{ $stats['public_decks'] }} công khai</div>
    </div>

    {{-- Total Flashcards --}}
    <div class="admin-stat-card bg-gradient-to-br from-emerald-600 to-emerald-800">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['flashcards']) }}</div>
        <div class="stat-label">Tổng flashcard</div>
        <div class="stat-sub">{{ number_format($stats['mastered']) }} đã thành thạo</div>
    </div>

    {{-- Total Reviews --}}
    <div class="admin-stat-card bg-gradient-to-br from-amber-500 to-orange-600">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['reviews']) }}</div>
        <div class="stat-label">Tổng đánh giá</div>
        <div class="stat-sub">Từ người dùng</div>
    </div>

</div>

{{-- ── Quick Actions ── --}}
<div class="mb-8">
    <h2 class="text-lg font-semibold text-white mb-4">Truy cập nhanh</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <a href="{{ route('admin.users') }}"
           class="flex items-center gap-4 p-5 rounded-xl bg-slate-800 border border-slate-700 hover:border-indigo-500/50 hover:bg-slate-700 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center group-hover:bg-indigo-600/40 transition-colors flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="text-white font-semibold text-sm">Quản lý người dùng</div>
                <div class="text-slate-400 text-xs mt-0.5">{{ $stats['users'] }} tài khoản</div>
            </div>
            <svg class="w-4 h-4 text-slate-600 ml-auto group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <a href="{{ route('admin.decks') }}"
           class="flex items-center gap-4 p-5 rounded-xl bg-slate-800 border border-slate-700 hover:border-purple-500/50 hover:bg-slate-700 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center group-hover:bg-purple-600/40 transition-colors flex-shrink-0">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="text-white font-semibold text-sm">Quản lý bộ thẻ</div>
                <div class="text-slate-400 text-xs mt-0.5">{{ $stats['decks'] }} bộ thẻ</div>
            </div>
            <svg class="w-4 h-4 text-slate-600 ml-auto group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <a href="{{ route('admin.reviews') }}"
           class="flex items-center gap-4 p-5 rounded-xl bg-slate-800 border border-slate-700 hover:border-amber-500/50 hover:bg-slate-700 transition-all group">
            <div  class="w-10 h-10 rounded-lg bg-amber-500/20 flex items-center justify-center group-hover:bg-amber-500/40 transition-colors flex-shrink-0">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="text-white font-semibold text-sm">Quản lý đánh giá</div>
                <div class="text-slate-400 text-xs mt-0.5">{{ $stats['reviews'] }} đánh giá</div>
            </div>
            <svg class="w-4 h-4 text-slate-600 ml-auto group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

    </div>
</div>

@endsection
