@props(['deck' => null])

@if($deck)
    <div class="deck-hero">
        {{-- Title & Badge --}}
        <div class="deck-hero__header">
            <h1 class="deck-hero__title">{{ $deck->title }}</h1>
            @if($deck->visibility === 'public')
                <span class="deck-hero__badge deck-hero__badge--public">Công khai</span>
            @else
                <span class="deck-hero__badge deck-hero__badge--private">Riêng tư</span>
            @endif
        </div>

        {{-- Description --}}
        @if($deck->description)
            <p class="deck-hero__description">{{ $deck->description }}</p>
        @endif

        {{-- Meta Info --}}
        <div class="deck-hero__meta">
            {{-- Category --}}
            @if($deck->category)
                <span class="deck-hero__category">{{ $deck->category }}</span>
            @endif

            {{-- Author --}}
            <span class="deck-hero__author">
                bởi {{ $deck->owner?->name ?? 'Người dùng ẩn danh' }}
            </span>

            {{-- Stats --}}
            <span class="deck-hero__stats">
                {{ $deck->flashcards->count() }} thẻ
                @if($deck->reviews_avg_rating)
                    • ★ {{ number_format($deck->reviews_avg_rating, 1) }}
                @endif
            </span>
        </div>

        {{-- Tags as Badges --}}
        @if($deck->tags && is_array($deck->tags) && count($deck->tags) > 0)
            <div class="deck-hero__tags">
                @foreach($deck->tags as $tag)
                    <span class="deck-hero__tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </div>
@endif
