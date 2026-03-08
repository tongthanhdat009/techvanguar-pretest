@extends('layouts.client-app', ['title' => 'Profile'])

@section('content')
@php
    $user = auth()->user();
    $level = $user->level();
    $levelProgress = $user->levelProgress();
    $totalDecks = $user->decks()->count();
    $totalCards = $user->decks()->withCount('flashcards')->get()->pluck('flashcards_count')->sum();
    $masteredCards = $user->studyProgress()->where('status', 'mastered')->count();
    $studiedToday = $user->studyProgress()->whereDate('last_reviewed_at', today())->count();
    $recentDecks = $user->decks()->latest()->take(5)->get();
@endphp

<div class="profile-page">
    <div class="profile-header">
        <h1 class="profile-title">My Profile</h1>
        <p class="profile-subtitle">Manage your account and view your learning progress</p>
    </div>

    <div class="profile-grid">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar-section">
                <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <p class="profile-email">{{ $user->email }}</p>
                    @if($user->bio)
                        <p class="profile-bio">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>

            <!-- Level Badge -->
            <div class="level-display">
                <div class="level-badge-large">
                    <div class="level-icon">⭐</div>
                    <div class="level-info">
                        <span class="level-label">Level</span>
                        <span class="level-number">{{ $level }}</span>
                    </div>
                </div>
                <div class="level-progress">
                    <div class="level-progress-bar">
                        <div class="level-progress-fill" style="width: {{ $levelProgress['percent'] }}%"></div>
                    </div>
                    <div class="level-progress-text">
                        {{ number_format($levelProgress['progress_within_level']) }} / {{ number_format($levelProgress['xp_per_level']) }} XP
                    </div>
                </div>
            </div>

            <!-- Streak Display -->
            <div class="streak-display">
                <div class="streak-icon">🔥</div>
                <div class="streak-info">
                    <span class="streak-number">{{ $user->daily_streak ?? 0 }}</span>
                    <span class="streak-label">day streak</span>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="stats-card">
            <h3 class="stats-title">Learning Statistics</h3>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon stat-icon-decks">📚</div>
                    <div class="stat-details">
                        <span class="stat-value">{{ $totalDecks }}</span>
                        <span class="stat-label">Total Decks</span>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon stat-icon-cards">🃏</div>
                    <div class="stat-details">
                        <span class="stat-value">{{ $totalCards }}</span>
                        <span class="stat-label">Total Cards</span>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon stat-icon-mastered">✓</div>
                    <div class="stat-details">
                        <span class="stat-value">{{ $masteredCards }}</span>
                        <span class="stat-label">Mastered</span>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon stat-icon-today">📖</div>
                    <div class="stat-details">
                        <span class="stat-value">{{ $studiedToday }}</span>
                        <span class="stat-label">Studied Today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="edit-profile-section">
        <h3 class="section-title">Edit Profile</h3>

        <form action="{{ route('client.profile.update') }}" method="POST" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="form-input" required maxlength="255">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-input" required>
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="bio" class="form-label">Bio</label>
                <textarea id="bio" name="bio" rows="3" class="form-input"
                          placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-divider"></div>

            <h4 class="form-section-title">Change Password</h4>
            <p class="form-section-desc">Leave blank if you don't want to change your password</p>

            <div class="form-row">
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                           class="form-input" autocomplete="current-password">
                    @error('current_password') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password"
                           class="form-input" autocomplete="new-password">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-input" autocomplete="new-password">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Decks -->
    @if($recentDecks->count() > 0)
    <div class="recent-decks-section">
        <h3 class="section-title">Recent Decks</h3>

        <div class="decks-list">
            @foreach($recentDecks as $deck)
                <a href="{{ route('client.decks.show', $deck) }}" class="deck-item">
                    <div class="deck-item-icon">📚</div>
                    <div class="deck-item-info">
                        <h4 class="deck-item-title">{{ $deck->title }}</h4>
                        <p class="deck-item-meta">
                            {{ $deck->flashcards_count ?? $deck->flashcards->count() }} cards
                            @if($deck->visibility === 'public') · <span class="visibility-badge">Public</span> @endif
                        </p>
                    </div>
                    <svg class="deck-item-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @endforeach
        </div>

        @if($totalDecks > 5)
            <a href="{{ route('client.my-decks') }}" class="view-all-link">
                View all decks →
            </a>
        @endif
    </div>
    @endif
</div>

@push('styles')
<style>
/* Profile Page Styles */
.profile-page {
    max-width: 1100px;
    margin: 0 auto;
}

/* Header Section */
.profile-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
    border-radius: 1.5rem;
    padding: 3rem 2.5rem;
    margin-bottom: 2.5rem;
    text-align: center;
    box-shadow: 0 20px 40px -12px rgba(79, 70, 229, 0.5);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -15%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.profile-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(15deg); }
}

.profile-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.75rem;
    position: relative;
    z-index: 1;
    letter-spacing: -0.025em;
}

.profile-subtitle {
    color: rgba(255, 255, 255, 0.95);
    font-size: 1.0625rem;
    position: relative;
    z-index: 1;
    font-weight: 400;
}

/* Grid Layout */
.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 2.5rem;
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .profile-header {
        padding: 2.5rem 1.5rem;
    }

    .profile-title {
        font-size: 1.875rem;
    }
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.profile-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px dashed #e5e7eb;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
    position: relative;
}

.profile-avatar::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5, #a855f7);
    opacity: 0.3;
    animation: pulse-ring 2s ease-out infinite;
}

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.3; }
    100% { transform: scale(1.15); opacity: 0; }
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
    letter-spacing: -0.025em;
}

.profile-email {
    color: #6b7280;
    font-size: 0.9375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.profile-email::before {
    content: '✉';
    font-size: 0.875rem;
}

.profile-bio {
    color: #4b5563;
    font-size: 0.9375rem;
    margin-top: 0.75rem;
    line-height: 1.6;
    padding: 0.875rem 1rem;
    background: #f9fafb;
    border-radius: 0.75rem;
    font-style: italic;
}

/* Level Display */
.level-display {
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 50%, #ede9fe 100%);
    border-radius: 1.25rem;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    border: 1px solid #e9d5ff;
    position: relative;
    overflow: hidden;
}

.level-display::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #4f46e5, #a855f7, #4f46e5);
    background-size: 200% 100%;
    animation: shimmer-line 3s linear infinite;
}

@keyframes shimmer-line {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.level-badge-large {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1rem;
}

.level-badge-large .level-icon {
    font-size: 3rem;
    filter: drop-shadow(0 2px 8px rgba(79, 70, 229, 0.3));
}

.level-info {
    display: flex;
    flex-direction: column;
}

.level-label {
    font-size: 0.6875rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-weight: 700;
}

.level-number {
    font-size: 2.25rem;
    font-weight: 900;
    background: linear-gradient(135deg, #4f46e5 0%, #a855f7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}

.level-progress-bar {
    height: 12px;
    background: rgba(79, 70, 229, 0.15);
    border-radius: 9999px;
    overflow: hidden;
    margin-bottom: 0.75rem;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.level-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5 0%, #a855f7 50%, #c084fc 100%);
    border-radius: 9999px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 12px rgba(79, 70, 229, 0.5);
    position: relative;
}

.level-progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer-progress 2s infinite;
}

@keyframes shimmer-progress {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.level-progress-text {
    text-align: center;
    font-size: 0.875rem;
    color: #7c3aed;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.level-progress-text::before {
    content: '⚡';
}

/* Streak Display */
.streak-display {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fcd34d 100%);
    border-radius: 1.25rem;
    padding: 1.25rem 1.5rem;
    border: 2px solid #fbbf24;
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.25);
    position: relative;
    overflow: hidden;
}

.streak-display::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
    border-radius: 50%;
}

.streak-icon {
    font-size: 2.5rem;
    filter: drop-shadow(0 2px 6px rgba(217, 119, 6, 0.3));
    animation: flicker 1.5s ease-in-out infinite alternate;
}

@keyframes flicker {
    0% { transform: scale(1) rotate(-2deg); }
    100% { transform: scale(1.05) rotate(2deg); }
}

.streak-info {
    display: flex;
    flex-direction: column;
}

.streak-number {
    font-size: 1.75rem;
    font-weight: 900;
    color: #d97706;
    line-height: 1;
    text-shadow: 0 1px 2px rgba(217, 119, 6, 0.1);
}

.streak-label {
    color: #92400e;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Stats Card */
.stats-card {
    background: white;
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.stats-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.stats-title {
    font-size: 1.375rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stats-title::before {
    content: '📊';
    font-size: 1.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-color: transparent;
}

.stat-item:hover::before {
    opacity: 1;
}

.stat-item:nth-child(1)::before { background: linear-gradient(180deg, #3b82f6, #1d4ed8); }
.stat-item:nth-child(2)::before { background: linear-gradient(180deg, #ec4899, #be185d); }
.stat-item:nth-child(3)::before { background: linear-gradient(180deg, #22c55e, #15803d); }
.stat-item:nth-child(4)::before { background: linear-gradient(180deg, #f59e0b, #d97706); }

.stat-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon-decks {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.stat-icon-cards {
    background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
}

.stat-icon-mastered {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
}

.stat-icon-today {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 900;
    color: #111827;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.8125rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Edit Profile Section */
.edit-profile-section {
    background: white;
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.08);
    margin-bottom: 2.5rem;
    transition: all 0.3s ease;
}

.edit-profile-section:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
}

.section-title {
    font-size: 1.375rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title::before {
    content: '';
    width: 5px;
    height: 28px;
    background: linear-gradient(180deg, #4f46e5, #a855f7);
    border-radius: 2.5px;
}

.profile-form {
    max-width: 750px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
    font-size: 0.9375rem;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1.125rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 0.9375rem;
    font-family: inherit;
    background: #f9fafb;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-input:focus {
    outline: none;
    border-color: #4f46e5;
    background: white;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.form-input:hover {
    border-color: #d1d5db;
}

textarea.form-input {
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

.form-error {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: #ef4444;
    font-size: 0.8125rem;
    margin-top: 0.5rem;
    font-weight: 500;
    background: #fef2f2;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
}

.form-error::before {
    content: '⚠';
}

.form-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    margin: 2rem 0;
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-section-title::before {
    content: '🔒';
    font-size: 1.125rem;
}

.form-section-desc {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
    padding: 0.75rem 1rem;
    background: #fef3c7;
    border-radius: 0.5rem;
    border-left: 3px solid #f59e0b;
}

.form-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 2rem;
}

.form-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 2rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Recent Decks Section */
.recent-decks-section {
    background: white;
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.recent-decks-section:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
}

.decks-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.deck-item {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.deck-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #4f46e5, #a855f7);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.deck-item:hover {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-color: #dbeafe;
    transform: translateX(8px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.deck-item:hover::before {
    opacity: 1;
}

.deck-item-icon {
    font-size: 2rem;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-radius: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.deck-item-info {
    flex: 1;
}

.deck-item-title {
    font-weight: 700;
    color: #111827;
    margin: 0;
    font-size: 1.0625rem;
    letter-spacing: -0.025em;
}

.deck-item-meta {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.visibility-badge {
    color: #d97706;
    font-weight: 700;
    padding: 0.125rem 0.625rem;
    background: #fef3c7;
    border-radius: 9999px;
    font-size: 0.75rem;
    border: 1px solid #fcd34d;
}

.deck-item-arrow {
    width: 24px;
    height: 24px;
    color: #9ca3af;
    transition: all 0.3s ease;
}

.deck-item:hover .deck-item-arrow {
    color: #4f46e5;
    transform: translateX(4px);
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    padding: 0.875rem 1.5rem;
    color: #4f46e5;
    font-weight: 700;
    text-decoration: none;
    background: #eef2ff;
    border-radius: 0.75rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.view-all-link:hover {
    background: #4f46e5;
    color: white;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.view-all-link::after {
    content: '→';
    transition: transform 0.3s ease;
}

.view-all-link:hover::after {
    transform: translateX(4px);
}

/* Responsive */
@media (max-width: 640px) {
    .profile-header {
        padding: 2rem 1.25rem;
    }

    .profile-title {
        font-size: 1.625rem;
    }

    .profile-subtitle {
        font-size: 0.9375rem;
    }

    .profile-card,
    .stats-card,
    .edit-profile-section,
    .recent-decks-section {
        padding: 1.75rem;
        border-radius: 1.25rem;
    }

    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding-bottom: 1.5rem;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 0.875rem;
    }

    .stat-item {
        padding: 1.125rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 1.375rem;
    }

    .deck-item {
        padding: 1rem;
        gap: 0.875rem;
    }

    .deck-item-icon {
        width: 48px;
        height: 48px;
        font-size: 1.5rem;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .view-all-link {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@endsection
