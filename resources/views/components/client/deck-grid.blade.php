{{-- Deck Grid Component --}}
@props([
    'decks' => collect(),
    'emptyMessage' => 'No decks found.',
    'emptyLink' => null,
    'emptyLinkText' => null
])

<div class="deck-grid">
    @if($decks->isEmpty())
        <div class="deck-empty-state col-span-full">
            <strong>{{ $emptyMessage }}</strong>
            @if($emptyLink)
                <a href="{{ $emptyLink }}">{{ $emptyLinkText ?? 'Create one!' }}</a>
            @endif
        </div>
    @else
        @foreach($decks as $deck)
            <a href="{{ route('client.decks.show', $deck) }}"
               class="deck-card">
                <div class="deck-card-topline">
                    <span class="deck-card-badge">{{ $deck->visibility === 'public' ? 'Public' : 'Private' }}</span>
                    @if($deck->reviews_avg_rating)
                        <span class="deck-card-rating">★ {{ number_format($deck->reviews_avg_rating, 1) }}</span>
                    @endif
                </div>
                <h3 class="title">{{ $deck->title }}</h3>
                @if($deck->description)
                    <p class="deck-card-description">{{ \Illuminate\Support\Str::limit($deck->description, 100) }}</p>
                @endif
                <div class="deck-card-meta">
                    <p class="count">{{ $deck->flashcards_count ?? 0 }} cards</p>
                    @if($deck->category)
                        <span class="deck-card-category">{{ $deck->category }}</span>
                    @endif
                </div>
            </a>
        @endforeach
    @endif
</div>
