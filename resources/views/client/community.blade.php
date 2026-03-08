@extends('layouts.client-app', ['title' => 'Cộng Đồng'])

@section('content')
    <section class="client-page-shell">
        <div class="client-page-hero client-page-hero-community">
            <div>
                <span class="client-page-kicker">Khám phá nội dung</span>
                <h1>Deck công khai từ cộng đồng để bạn bắt đầu nhanh hơn.</h1>
                <p>Quan sát cách người khác tổ chức kiến thức, tìm các bộ thẻ có sẵn và mở rộng thư viện cá nhân mà không cần dựng mọi thứ từ đầu.</p>
            </div>
            <div class="client-page-actions">
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-secondary">Tạo deck để chia sẻ</a>
            </div>
        </div>

        <div class="client-page-highlights">
            <div class="client-page-highlight">
                <strong>{{ $communityDecks->total() }}</strong>
                <span>deck công khai đang hiển thị</span>
            </div>
            <div class="client-page-highlight">
                <strong>{{ number_format($communityDecks->getCollection()->sum('flashcards_count')) }}</strong>
                <span>flashcard trong trang hiện tại</span>
            </div>
            <div class="client-page-highlight">
                <strong>{{ $communityDecks->getCollection()->filter(fn($deck) => !empty($deck->reviews_avg_rating))->count() }}</strong>
                <span>deck đã có đánh giá</span>
            </div>
        </div>

        @if($communityDecks->isEmpty())
            <div class="client-empty-panel">
                <div class="client-empty-icon">🌍</div>
                <h2>Chưa có deck cộng đồng nào.</h2>
                <p>Bạn có thể là người đầu tiên đưa một bộ thẻ tốt ra không gian dùng chung để người khác học cùng.</p>
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-primary">Tạo deck public</a>
            </div>
        @else
            <div class="deck-grid client-list-grid">
                @foreach($communityDecks as $deck)
                    <a href="{{ route('client.decks.show', $deck) }}" class="deck-card client-list-card">
                        <div class="deck-card-topline">
                            <span class="deck-card-badge">Deck public</span>
                            @if($deck->reviews_avg_rating)
                                <span class="deck-card-rating">★ {{ number_format($deck->reviews_avg_rating, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="title">{{ $deck->title }}</h3>
                        <p class="deck-card-description">{{ $deck->description ? \Illuminate\Support\Str::limit($deck->description, 120) : 'Deck này đang mở cho cộng đồng nhưng chưa có mô tả chi tiết.' }}</p>
                        <div class="deck-card-meta">
                            <p class="count">{{ $deck->flashcards_count ?? 0 }} thẻ</p>
                            @if($deck->owner)
                                <span class="deck-card-category">{{ $deck->owner->name }}</span>
                            @endif
                        </div>
                        @if($deck->tags && count($deck->tags) > 0)
                            <div class="deck-tags client-list-tags">
                                @foreach(array_slice($deck->tags, 0, 3) as $tag)
                                    <span class="tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($communityDecks->hasPages())
                <div class="pagination client-pagination-shell">
                    {{ $communityDecks->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection
