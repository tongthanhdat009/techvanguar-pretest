@props([
    'deck' => null,
    'reviewForm' => null
])

<div class="reviews-section">
    <h2 class="reviews-section__title">
        Đánh giá
        @if($deck->reviews_avg_rating)
            <span class="reviews-section__avg-rating">
                ★ {{ number_format($deck->reviews_avg_rating, 1) }}
            </span>
        @endif
    </h2>

    {{-- Review Form (for logged-in users) --}}
    @include('components.client.review-form', [
        'deck' => $deck,
        'existingReview' => $reviewForm
    ])

    {{-- Reviews List --}}
    @if($deck->reviews->isEmpty())
        @include('components.common.empty-state', [
            'title' => 'Chưa có đánh giá nào',
            'description' => 'Hãy là người đầu tiên đánh giá bộ thẻ này!'
        ])
    @else
        <div class="reviews-list">
            @foreach($deck->reviews as $review)
                <div class="reviews-list__item">
                    {{-- User & Rating --}}
                    <div class="reviews-list__header">
                        <span class="reviews-list__author">{{ $review->user?->name ?? 'Người dùng ẩn danh' }}</span>
                        @include('components.client.rating-stars', [
                            'rating' => $review->rating,
                            'readonly' => true,
                            'size' => 'sm'
                        ])
                    </div>

                    {{-- Comment --}}
                    @if($review->comment)
                        <p class="reviews-list__comment">{{ $review->comment }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
