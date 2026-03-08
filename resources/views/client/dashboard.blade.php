@extends('layouts.client-app', ['title' => 'Dashboard'])

@section('content')
    @php
        $user = auth()->user();
        $statusLabels = [
            \App\Models\StudyProgress::STATUS_NEW => 'Mới',
            \App\Models\StudyProgress::STATUS_LEARNING => 'Đang học',
            \App\Models\StudyProgress::STATUS_MASTERED => 'Đã mastery',
        ];
    @endphp

    <section class="dashboard-hero">
        <div class="dashboard-hero-copy">
            <span class="dashboard-kicker">{{ $dashboardSummary['today_label'] }}</span>
            <h1>Xin chào {{ $user->name }}, hôm nay là lúc giữ nhịp học thật gọn và đúng trọng tâm.</h1>
            <p>
                {{ $dashboardSummary['mastery']['message'] }}
            </p>
            <div class="dashboard-hero-actions">
                <a href="{{ route('client.study.all') }}" class="dashboard-btn dashboard-btn-primary">Bắt đầu ôn tập</a>
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-secondary">Tạo deck mới</a>
            </div>

            <div class="dashboard-hero-highlights">
                <div class="dashboard-hero-highlight">
                    <strong>{{ $user->daily_streak ?? 0 }} ngày</strong>
                    <span>streak hiện tại</span>
                </div>
                <div class="dashboard-hero-highlight">
                    <strong>{{ $ownedDecks->count() }}</strong>
                    <span>deck cá nhân đang quản lý</span>
                </div>
                <div class="dashboard-hero-highlight">
                    <strong>{{ $communityDecks->count() }}</strong>
                    <span>deck cộng đồng đang sẵn sàng sao chép</span>
                </div>
            </div>
        </div>
        <div class="dashboard-hero-panel">
            <div class="dashboard-panel-heading">
                <span class="dashboard-card-kicker">Phiên hôm nay</span>
                <h2>Ảnh chụp nhanh</h2>
            </div>
            <div class="hero-panel-row">
                <span>Thẻ cần ưu tiên</span>
                <strong>{{ $dashboardSummary['due_today'] }}</strong>
            </div>
            <div class="hero-panel-row">
                <span>Đã xử lý hôm nay</span>
                <strong>{{ $dashboardSummary['completed_today'] }}</strong>
            </div>
            <div class="hero-panel-row">
                <span>Mốc mastery</span>
                <strong>{{ $dashboardSummary['mastery']['tier'] }}</strong>
            </div>
            <div class="dashboard-level-progress">
                <progress class="dashboard-native-progress" max="100" value="{{ $dashboardSummary['level']['percent'] }}"></progress>
                <p>Level {{ $dashboardSummary['level']['level'] }} · còn {{ number_format($dashboardSummary['level']['remaining_xp']) }} XP để lên mốc tiếp theo</p>
            </div>
            <div class="dashboard-panel-note">{{ $dashboardSummary['mastery']['next_milestone'] }}</div>
        </div>
    </section>

    @include('components.client.progress-summary', ['summary' => $progressSummary])

    <section class="dashboard-grid">
        <article class="dashboard-card dashboard-card-wide">
            <div class="dashboard-card-header">
                <div>
                    <span class="dashboard-card-kicker">Nhịp ôn tập</span>
                    <h2>Tiến độ 7 ngày gần nhất</h2>
                </div>
                <span class="dashboard-card-pill">{{ $dashboardSummary['mastery']['tier'] }}</span>
            </div>

            <div class="mastery-layout">
                <div class="mastery-spotlight">
                    <strong>{{ $dashboardSummary['mastery']['rate'] }}%</strong>
                    <span>thẻ theo dõi đã đạt mastery</span>
                </div>

                <div>
                    <div class="streak-strip">
                        @foreach($dashboardSummary['streak_timeline'] as $day)
                            <div class="streak-day {{ $day['is_active'] ? 'active' : '' }} {{ $day['is_today'] ? 'today' : '' }}">
                                <small>{{ $day['label'] }}</small>
                                <strong>{{ $day['day'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                    <p class="dashboard-muted-copy">{{ $dashboardSummary['mastery']['message'] }}</p>
                </div>
            </div>

            <div class="dashboard-collection-strip">
                <div class="dashboard-collection-chip">
                    <strong>{{ $ownedDecks->count() }}</strong>
                    <span>deck cá nhân</span>
                </div>
                <div class="dashboard-collection-chip">
                    <strong>{{ $communityDecks->count() }}</strong>
                    <span>deck cộng đồng nổi bật</span>
                </div>
                <div class="dashboard-collection-chip">
                    <strong>{{ number_format($user->experience_points) }}</strong>
                    <span>XP đang tích lũy</span>
                </div>
            </div>
        </article>

        <article class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <span class="dashboard-card-kicker">Hàng chờ</span>
                    <h2>Thẻ cần xử lý tiếp</h2>
                </div>
                <a href="{{ route('client.study.all') }}">Mở phòng học</a>
            </div>

            <div class="dashboard-list">
                @forelse($dueCards as $card)
                    <a href="{{ route('client.decks.study', ['deck' => $card->deck, 'mode' => 'flip']) }}" class="dashboard-list-item">
                        <div>
                            <strong>{{ \Illuminate\Support\Str::limit($card->front_content, 58) }}</strong>
                            <span>{{ $card->deck?->title ?? 'Deck không xác định' }}</span>
                        </div>
                        <span class="dashboard-list-meta">
                            {{ $statusLabels[$card->studyProgress->first()?->status ?? \App\Models\StudyProgress::STATUS_NEW] ?? ucfirst((string) ($card->studyProgress->first()?->status ?? 'Mới')) }}
                        </span>
                    </a>
                @empty
                    <div class="dashboard-empty">Không có thẻ đến hạn. Bạn có thể tạo deck mới hoặc chuyển qua thư viện cộng đồng để lấy thêm nội dung.</div>
                @endforelse
            </div>
        </article>

        <article class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <span class="dashboard-card-kicker">Lịch ôn</span>
                    <h2>Những lượt sắp quay lại</h2>
                </div>
            </div>

            <div class="dashboard-list compact">
                @forelse($reviewTimeline as $progress)
                    <div class="dashboard-list-item">
                        <div>
                            <strong>{{ $progress->flashcard?->deck?->title ?? 'Deck không xác định' }}</strong>
                            <span>{{ \Illuminate\Support\Str::limit($progress->flashcard?->front_content ?? 'Flashcard', 52) }}</span>
                        </div>
                        <span class="dashboard-list-meta">{{ optional($progress->next_review_at)->format('d/m H:i') }}</span>
                    </div>
                @empty
                    <div class="dashboard-empty">Chưa có lịch ôn tập tiếp theo.</div>
                @endforelse
            </div>
        </article>

    </section>
    <article class="dashboard-card">
        <div class="dashboard-card-header ">
            <div>
                <span class="dashboard-card-kicker">Bảng xếp hạng</span>
                <h2>Nhóm dẫn đầu theo XP</h2>
            </div>
        </div>

        <div class="leaderboard-list">
            @foreach($leaderboard as $index => $learner)
                <div class="leaderboard-item {{ $learner->is(auth()->user()) ? 'is-current-user' : '' }}">
                    <span class="leaderboard-rank">{{ $index + 1 }}</span>
                    <div>
                        <strong>{{ $learner->name }}</strong>
                        <span>Level {{ $learner->level() }} · {{ number_format($learner->experience_points) }} XP</span>
                    </div>
                </div>
            @endforeach
        </div>
    </article>

    @include('components.client.study-section', [
        'title' => 'Thư viện của bạn',
        'decks' => $ownedDecks,
        'emptyMessage' => 'Bạn chưa có deck nào.',
        'emptyLink' => route('client.decks.create'),
        'emptyLinkText' => 'Tạo deck đầu tiên'
    ])

    @include('components.client.study-section', [
        'title' => 'Gợi ý từ cộng đồng',
        'decks' => $communityDecks,
        'emptyMessage' => 'Chưa có deck công khai nào.'
    ])
@endsection
