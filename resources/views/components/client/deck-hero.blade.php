@props(['deck' => null])

@php
    $categoryLabels = [
        'Language' => 'Ngôn ngữ',
        'Science' => 'Khoa học',
        'History' => 'Lịch sử',
        'Math' => 'Toán học',
        'Technology' => 'Công nghệ',
        'Other' => 'Khác',
    ];
@endphp

@if($deck)
    <article class="deck-hero deck-hero-panel">
        <div class="deck-hero__header">
            <div>
                <span class="client-page-kicker">Chi tiết deck</span>
                <h1 class="deck-hero__title">{{ $deck->title }}</h1>
            </div>
            @if($deck->visibility === 'public')
                <span class="deck-hero__badge deck-hero__badge--public">Công khai</span>
            @else
                <span class="deck-hero__badge deck-hero__badge--private">Riêng tư</span>
            @endif
        </div>

        <p class="deck-hero__description">{{ $deck->description ?: 'Deck này chưa có mô tả. Bạn có thể bổ sung vài dòng để người học hiểu nhanh mục tiêu và phạm vi nội dung.' }}</p>

        <div class="deck-hero__meta-grid">
            <div class="deck-hero__meta-card">
                <strong>{{ $deck->flashcards->count() }}</strong>
                <span>flashcard hiện có</span>
            </div>
            <div class="deck-hero__meta-card">
                <strong>{{ $deck->reviews_avg_rating ? number_format($deck->reviews_avg_rating, 1) : 'Chưa có' }}</strong>
                <span>điểm đánh giá trung bình</span>
            </div>
            <div class="deck-hero__meta-card">
                <strong>{{ $deck->owner?->name ?? 'Người dùng ẩn danh' }}</strong>
                <span>người sở hữu deck</span>
            </div>
        </div>

        <div class="deck-hero__meta">
            @if($deck->category)
                <span class="deck-hero__category">{{ $categoryLabels[$deck->category] ?? $deck->category }}</span>
            @endif
            <span class="deck-hero__author">bởi {{ $deck->owner?->name ?? 'Người dùng ẩn danh' }}</span>
            <span class="deck-hero__stats">{{ $deck->flashcards->count() }} thẻ@if($deck->reviews_avg_rating) • ★ {{ number_format($deck->reviews_avg_rating, 1) }}@endif</span>
        </div>

        @if($deck->tags && is_array($deck->tags) && count($deck->tags) > 0)
            <div class="deck-hero__tags">
                @foreach($deck->tags as $tag)
                    <span class="deck-hero__tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </article>
@endif
