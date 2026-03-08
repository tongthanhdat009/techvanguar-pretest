@extends('layouts.client-app', ['title' => 'Hồ Sơ'])

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

<section class="client-page-shell profile-shell">
    <div class="client-page-hero profile-hero">
        <div>
            <span class="client-page-kicker">Hồ sơ học tập</span>
            <h1>Một nơi để xem tiến độ cá nhân và điều chỉnh tài khoản theo đúng cách bạn đang học.</h1>
            <p>Kiểm tra level, streak, khối lượng deck đang quản lý và cập nhật thông tin để không gian học tập luôn khớp workflow của bạn.</p>
        </div>
    </div>

    <div class="client-page-highlights">
        <div class="client-page-highlight">
            <strong>{{ $level }}</strong>
            <span>level hiện tại</span>
        </div>
        <div class="client-page-highlight">
            <strong>{{ $user->daily_streak ?? 0 }} ngày</strong>
            <span>streak đang duy trì</span>
        </div>
        <div class="client-page-highlight">
            <strong>{{ number_format($user->experience_points) }}</strong>
            <span>XP đã tích lũy</span>
        </div>
    </div>

    <div class="profile-layout">
        <article class="dashboard-card profile-summary-card">
            <div class="profile-summary-head">
                <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div>
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <p class="profile-email">{{ $user->email }}</p>
                    <p class="profile-bio">{{ $user->bio ?: 'Bạn chưa thêm mô tả cá nhân. Một vài dòng ngắn sẽ giúp hồ sơ có nhiều ngữ cảnh hơn.' }}</p>
                </div>
            </div>

            <div class="profile-level-panel">
                <div class="profile-level-badge">
                    <span>⭐</span>
                    <div>
                        <small>Level hiện tại</small>
                        <strong>{{ $level }}</strong>
                    </div>
                </div>
                <progress class="profile-progress-meter" max="100" value="{{ $levelProgress['percent'] }}"></progress>
                <p>{{ number_format($levelProgress['progress_within_level']) }} / {{ number_format($levelProgress['xp_per_level']) }} XP trong level này</p>
            </div>

            <div class="profile-mini-stats">
                <div>
                    <strong>{{ $totalDecks }}</strong>
                    <span>deck cá nhân</span>
                </div>
                <div>
                    <strong>{{ $totalCards }}</strong>
                    <span>flashcard đã tạo</span>
                </div>
                <div>
                    <strong>{{ $masteredCards }}</strong>
                    <span>thẻ đã mastery</span>
                </div>
                <div>
                    <strong>{{ $studiedToday }}</strong>
                    <span>lượt ôn hôm nay</span>
                </div>
            </div>
        </article>

        <article class="dashboard-card profile-form-card">
            <div class="dashboard-card-header">
                <div>
                    <span class="dashboard-card-kicker">Thiết lập tài khoản</span>
                    <h2>Cập nhật thông tin hồ sơ</h2>
                </div>
            </div>

            <form action="{{ route('client.profile.update') }}" method="POST" class="client-profile-form">
                @csrf
                @method('PUT')

                <div class="client-form-grid two-columns">
                    <div class="client-form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="client-form-input" required maxlength="255">
                        @error('name') <span class="client-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="client-form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="client-form-input" required>
                        @error('email') <span class="client-form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="client-form-group">
                    <label for="bio">Giới thiệu ngắn</label>
                    <textarea id="bio" name="bio" rows="4" class="client-form-input" placeholder="Mô tả ngắn về chủ đề bạn đang học hoặc cách bạn dùng nền tảng này...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <span class="client-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="client-form-divider"></div>

                <div class="dashboard-card-header compact-header">
                    <div>
                        <span class="dashboard-card-kicker">Bảo mật</span>
                        <h2>Đổi mật khẩu nếu cần</h2>
                    </div>
                </div>

                <div class="client-form-grid single-column">
                    <div class="client-form-group">
                        <label for="current_password">Mật khẩu hiện tại</label>
                        <input type="password" id="current_password" name="current_password" class="client-form-input" autocomplete="current-password">
                        @error('current_password') <span class="client-form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="client-form-grid two-columns">
                    <div class="client-form-group">
                        <label for="password">Mật khẩu mới</label>
                        <input type="password" id="password" name="password" class="client-form-input" autocomplete="new-password">
                        @error('password') <span class="client-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="client-form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="client-form-input" autocomplete="new-password">
                    </div>
                </div>

                <div class="client-form-actions">
                    <button type="submit" class="dashboard-btn dashboard-btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </article>
    </div>

    @if($recentDecks->count() > 0)
        <article class="dashboard-card profile-recent-card">
            <div class="dashboard-card-header">
                <div>
                    <span class="dashboard-card-kicker">Hoạt động gần đây</span>
                    <h2>Deck bạn vừa thao tác gần nhất</h2>
                </div>
                @if($totalDecks > 5)
                    <a href="{{ route('client.my-decks') }}">Xem toàn bộ deck</a>
                @endif
            </div>

            <div class="profile-recent-list">
                @foreach($recentDecks as $deck)
                    <a href="{{ route('client.decks.show', $deck) }}" class="profile-recent-item">
                        <div class="profile-recent-icon">📚</div>
                        <div>
                            <strong>{{ $deck->title }}</strong>
                            <span>{{ $deck->flashcards_count ?? $deck->flashcards()->count() }} thẻ · {{ $deck->visibility === 'public' ? 'Public' : 'Private' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </article>
    @endif
</section>
@endsection
