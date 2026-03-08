@extends('layouts.public', [
    'title' => 'Flashcard Learning Hub',
    'description' => 'Tạo bộ thẻ của riêng bạn, ôn tập mỗi ngày và nâng cao hiệu suất học tập với Flashcard Learning Hub.'
])

@section('content')
    @include('components.public.public-hero')

    @include('components.public.public-features')

    @include('components.public.public-demo')

    @if($publicDecks->isNotEmpty())
    <section id="community-decks" class="public-decks-section">
        <div class="container">
            <div class="section-header">
                <span class="section-kicker">Thư viện cộng đồng</span>
                <h2 class="section-title">Một vài deck đang được xuất bản công khai.</h2>
                <p class="section-subtitle">Mỗi deck là một điểm bắt đầu tốt: có cấu trúc, có nội dung và sẵn sàng để sao chép vào thư viện học tập cá nhân.</p>
            </div>
            <div class="deck-grid">
                @foreach($publicDecks as $deck)
                <div class="deck-card">
                    <div class="deck-card-header">
                        <div class="flex-1 min-w-0">
                            <span class="deck-card-eyebrow">Deck public</span>
                            <h3 class="deck-card-title">{{ \Illuminate\Support\Str::limit($deck->title, 42) }}</h3>
                            <span class="deck-card-category">{{ $deck->category ? \Illuminate\Support\Str::limit($deck->category, 28) : 'Cong dong' }}</span>
                        </div>
                        <div class="deck-card-icon">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <p class="deck-card-description">{{ $deck->description ? \Illuminate\Support\Str::limit(trim($deck->description), 110) : 'Một deck công khai sẵn sàng để bạn dùng làm điểm bắt đầu cho phiên học tiếp theo.' }}</p>
                    <div class="deck-card-footer">
                        <span class="deck-card-stats">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <path d="M16 2v6M8 2v6"/>
                            </svg>
                            {{ $deck->flashcards_count ?? 0 }} thẻ
                        </span>
                        @if($deck->reviews_avg_rating)
                        <span class="deck-card-rating">
                            ★ {{ number_format($deck->reviews_avg_rating, 1) }}
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="view-all-cta">
                <a href="{{ route('register') }}">Mở tài khoản để khám phá toàn bộ thư viện →</a>
            </div>
        </div>
    </section>
    @endif

    @if($featuredReviews->isNotEmpty())
    <section class="public-reviews-section">
        <div class="container">
            <div class="section-header">
                <span class="section-kicker">Từ người dùng</span>
                <h2 class="section-title">Tín hiệu từ hoạt động thực tế trên nền tảng.</h2>
                <p class="section-subtitle">Review mới nhất giúp landing page bớt cảm giác giả lập và phản ánh hệ sinh thái đang có người dùng thật.</p>
            </div>

            <div class="public-reviews-grid">
                @foreach($featuredReviews as $review)
                    <article class="public-review-card">
                        <div class="public-review-rating">{{ str_repeat('★', (int) $review->rating) }}<span>{{ $review->rating }}/5</span></div>
                        <p class="public-review-copy">{{ $review->comment ?: 'Người dùng đã để lại đánh giá tích cực cho bộ thẻ này.' }}</p>
                        <div class="public-review-meta">
                            <strong>{{ $review->user?->name ?? 'Người dùng ẩn danh' }}</strong>
                            <span>{{ $review->deck?->title ?? 'Deck cộng đồng' }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('components.public.public-final-cta')
@endsection
