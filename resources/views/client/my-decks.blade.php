@extends('layouts.client-app', ['title' => 'Deck Của Tôi'])

@section('content')
    <section class="client-page-shell">
        <div class="client-page-hero">
            <div>
                <span class="client-page-kicker">Thư viện cá nhân</span>
                <h1>Toàn bộ deck bạn đang tự xây và duy trì.</h1>
                <p>Từ đây bạn có thể mở nhanh từng deck, xem quy mô nội dung và giữ cấu trúc học tập cá nhân luôn rõ ràng.</p>
            </div>
            <div class="client-page-actions">
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-primary">Tạo deck mới</a>
            </div>
        </div>

        <div class="client-page-highlights">
            <div class="client-page-highlight">
                <strong>{{ $ownedDecks->total() }}</strong>
                <span>deck bạn đang quản lý</span>
            </div>
            <div class="client-page-highlight">
                <strong>{{ number_format($ownedDecks->getCollection()->sum('flashcards_count')) }}</strong>
                <span>flashcard trên trang hiện tại</span>
            </div>
            <div class="client-page-highlight">
                <strong>{{ $ownedDecks->currentPage() }}</strong>
                <span>trang đang được xem</span>
            </div>
        </div>

        @if($ownedDecks->isEmpty())
            <div class="client-empty-panel">
                <div class="client-empty-icon">📚</div>
                <h2>Bạn chưa có deck nào.</h2>
                <p>Hãy bắt đầu bằng một bộ thẻ nhỏ, rõ chủ đề và đủ tập trung để bạn quay lại ôn mỗi ngày.</p>
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-primary">Tạo deck đầu tiên</a>
            </div>
        @else
            <div class="deck-grid client-list-grid">
                @foreach($ownedDecks as $deck)
                    <a href="{{ route('client.decks.show', $deck) }}" class="deck-card client-list-card">
                        <div class="deck-card-topline">
                            <span class="deck-card-badge">{{ $deck->visibility === 'public' ? 'Public' : 'Private' }}</span>
                            @if($deck->reviews_avg_rating)
                                <span class="deck-card-rating">★ {{ number_format($deck->reviews_avg_rating, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="title">{{ $deck->title }}</h3>
                        <p class="deck-card-description">{{ $deck->description ? \Illuminate\Support\Str::limit($deck->description, 120) : 'Deck này chưa có mô tả. Bạn có thể thêm vài dòng để nhận diện mục tiêu học nhanh hơn.' }}</p>
                        <div class="deck-card-meta">
                            <p class="count">{{ $deck->flashcards_count ?? 0 }} thẻ</p>
                            <span class="deck-card-category">Cập nhật cá nhân</span>
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

            @if($ownedDecks->hasPages())
                <div class="pagination client-pagination-shell">
                    {{ $ownedDecks->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection
