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
    max-width: 1000px;
    margin: 0 auto;
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.profile-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

/* Profile Card */
.profile-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 9999px;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
}

.profile-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
}

.profile-email {
    color: #6b7280;
    font-size: 0.875rem;
}

.profile-bio {
    color: #4b5563;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    line-height: 1.5;
}

/* Level Display */
.level-display {
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    border-radius: 0.75rem;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.level-badge-large {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.level-badge-large .level-icon {
    font-size: 2.5rem;
}

.level-info {
    display: flex;
    flex-direction: column;
}

.level-label {
    font-size: 0.75rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.level-number {
    font-size: 2rem;
    font-weight: 700;
    color: #4f46e5;
}

.level-progress-bar {
    height: 8px;
    background: rgba(79, 70, 229, 0.2);
    border-radius: 9999px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.level-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 9999px;
    transition: width 0.5s ease;
}

.level-progress-text {
    text-align: center;
    font-size: 0.8125rem;
    color: #7c3aed;
    font-weight: 500;
}

/* Streak Display */
.streak-display {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
}

.streak-icon {
    font-size: 2rem;
}

.streak-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #d97706;
}

.streak-label {
    color: #92400e;
    font-size: 0.875rem;
}

/* Stats Card */
.stats-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stats-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.75rem;
}

.stat-icon {
    font-size: 1.5rem;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
}

.stat-icon-decks { background: #dbeafe; }
.stat-icon-cards { background: #fce7f3; }
.stat-icon-mastered { background: #dcfce7; }
.stat-icon-today { background: #fef3c7; }

.stat-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
}

.stat-label {
    font-size: 0.8125rem;
    color: #6b7280;
}

/* Edit Profile Section */
.edit-profile-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
}

.profile-form {
    max-width: 700px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-input {
    width: 100%;
    padding: 0.625rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-error {
    display: block;
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.form-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 1.5rem 0;
}

.form-section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
}

.form-section-desc {
    font-size: 0.8125rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.form-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 1.5rem;
}

.form-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.btn-primary {
    background: #4f46e5;
    color: white;
}

.btn-primary:hover {
    background: #4338ca;
}

/* Recent Decks Section */
.recent-decks-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.decks-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.deck-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.2s;
}

.deck-item:hover {
    background: #f3f4f6;
}

.deck-item-icon {
    font-size: 1.5rem;
}

.deck-item-info {
    flex: 1;
}

.deck-item-title {
    font-weight: 600;
    color: #111827;
    margin: 0;
    font-size: 0.9375rem;
}

.deck-item-meta {
    font-size: 0.8125rem;
    color: #6b7280;
    margin-top: 0.125rem;
}

.visibility-badge {
    color: #d97706;
    font-weight: 500;
}

.deck-item-arrow {
    width: 20px;
    height: 20px;
    color: #9ca3af;
}

.view-all-link {
    display: inline-block;
    margin-top: 1rem;
    color: #4f46e5;
    font-weight: 500;
    text-decoration: none;
}

.view-all-link:hover {
    text-decoration: underline;
}

@media (max-width: 640px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@endsection
