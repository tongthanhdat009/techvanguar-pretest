@extends('layouts.admin', [
    'title' => 'Dashboard',
    'sidebar' => true,
])

@section('content')
<section class="admin-overview-hero">
    <div>
        <span class="admin-overview-kicker">System snapshot · {{ now()->format('d/m/Y') }}</span>
        <h1>Flashcard platform operations at a glance.</h1>
        <p>Theo dõi lượng người dùng, nội dung học, mức độ mastery và tín hiệu tương tác trong một dashboard gọn, rõ và đủ ngữ cảnh.</p>
    </div>
    <div class="admin-overview-hero-panel">
        <div>
            <span>Active clients</span>
            <strong>{{ number_format($stats['clients']) }}</strong>
        </div>
        <div>
            <span>Public deck ratio</span>
            <strong>{{ $stats['decks'] > 0 ? round(($stats['public_decks'] / $stats['decks']) * 100) : 0 }}%</strong>
        </div>
    </div>
</section>

<section class="admin-overview-grid">
    <article class="admin-stat-card bg-gradient-to-br from-sky-500 to-blue-700">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['users']) }}</div>
        <div class="stat-label">Total users</div>
        <div class="stat-sub">{{ $stats['admins'] }} admin · {{ $stats['clients'] }} client</div>
    </article>

    <article class="admin-stat-card bg-gradient-to-br from-violet-500 to-indigo-700">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['decks']) }}</div>
        <div class="stat-label">Deck inventory</div>
        <div class="stat-sub">{{ $stats['public_decks'] }} public decks</div>
    </article>

    <article class="admin-stat-card bg-gradient-to-br from-emerald-500 to-emerald-700">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['flashcards']) }}</div>
        <div class="stat-label">Flashcards</div>
        <div class="stat-sub">{{ number_format($stats['mastered']) }} mastered records</div>
    </article>

    <article class="admin-stat-card bg-gradient-to-br from-orange-500 to-rose-600">
        <div class="stat-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['reviews']) }}</div>
        <div class="stat-label">Reviews</div>
        <div class="stat-sub">Community quality signal</div>
    </article>
</section>

<section class="admin-overview-panels">
    <article class="admin-insight-card">
        <div class="admin-card-header">
            <div>
                <span class="admin-panel-kicker">Health check</span>
                <h2>Platform balance</h2>
            </div>
        </div>
        <div class="admin-insight-list">
            <div class="admin-insight-row">
                <span>Admins to clients</span>
                <strong>{{ $stats['admins'] }} : {{ max(1, $stats['clients']) }}</strong>
            </div>
            <div class="admin-insight-row">
                <span>Decks per client</span>
                <strong>{{ $stats['clients'] > 0 ? number_format($stats['decks'] / $stats['clients'], 1) : '0.0' }}</strong>
            </div>
            <div class="admin-insight-row">
                <span>Flashcards per deck</span>
                <strong>{{ $stats['decks'] > 0 ? number_format($stats['flashcards'] / $stats['decks'], 1) : '0.0' }}</strong>
            </div>
            <div class="admin-insight-row">
                <span>Reviews per public deck</span>
                <strong>{{ $stats['public_decks'] > 0 ? number_format($stats['reviews'] / $stats['public_decks'], 1) : '0.0' }}</strong>
            </div>
        </div>
    </article>

    <article class="admin-insight-card">
        <div class="admin-card-header">
            <div>
                <span class="admin-panel-kicker">Quick actions</span>
                <h2>Move directly to core tasks</h2>
            </div>
        </div>
        <div class="admin-action-grid">
            <a href="{{ route('admin.users') }}" class="admin-action-card">
                <strong>Users</strong>
                <span>Review accounts, roles and status.</span>
            </a>
            <a href="{{ route('admin.decks') }}" class="admin-action-card">
                <strong>Decks</strong>
                <span>Inspect content volume and ownership.</span>
            </a>
            <a href="{{ route('admin.reviews') }}" class="admin-action-card">
                <strong>Reviews</strong>
                <span>Monitor public feedback and quality.</span>
            </a>
        </div>
    </article>
</section>

@endsection
