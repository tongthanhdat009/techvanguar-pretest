@extends('layouts.public', [
    'title' => 'Flashcard Learning Hub',
    'description' => 'Tạo bộ thẻ của riêng bạn, ôn tập mỗi ngày và nâng cao hiệu suất học tập với Flashcard Learning Hub.'
])

@section('content')
    {{-- Hero Section --}}
    @include('components.public.public-hero')

    {{-- Features Section --}}
    @include('components.public.public-features')

    {{-- Demo Section --}}
    @include('components.public.public-demo')

    {{-- Public Decks Section --}}
    @if($publicDecks->isNotEmpty())
    <section class="public-decks-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Bộ thẻ nổi bật từ cộng đồng</h2>
                <p class="section-subtitle">Khám phá các bộ thẻ công khai được tạo bởi cộng đồng học viên.</p>
            </div>
            <div class="deck-grid">
                @foreach($publicDecks as $deck)
                <div class="deck-card">
                    <div class="deck-card-header">
                        <div class="flex-1 min-w-0">
                            <h3 class="deck-card-title">{{ $deck->title }}</h3>
                            @if($deck->category)
                            <span class="deck-card-category">{{ $deck->category }}</span>
                            @endif
                        </div>
                        <div class="deck-card-icon">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    @if($deck->description)
                    <p class="deck-card-description">{{ $deck->description }}</p>
                    @endif
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
                <a href="{{ route('register') }}">Xem tất cả bộ thẻ sau khi đăng ký →</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Final CTA --}}
    @include('components.public.public-final-cta')
@endsection
