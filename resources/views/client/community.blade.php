@extends('layouts.client-app', ['title' => 'Community'])

@section('content')
    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Community Library</h1>
            <p class="page-description">Explore and discover flashcard decks from the community</p>
        </div>
    </div>

    {{-- Deck Grid --}}
    <div class="decks-container">
        @if($communityDecks->isEmpty())
            @include('components.common.empty-state', [
                'icon' => '🌍',
                'title' => 'No community decks yet',
                'description' => 'Be the first to share a deck with the community!',
                'actionLink' => route('client.decks.create'),
                'actionText' => 'Create a public deck'
            ])
        @else
            <div class="deck-grid">
                @foreach($communityDecks as $deck)
                    <a href="{{ route('client.decks.show', $deck) }}" class="deck-card">
                        <div class="deck-header">
                            <h3 class="deck-title">{{ $deck->title }}</h3>
                            @if($deck->is_public)
                                <span class="deck-badge public" title="Public deck">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h.75v.75H3.75V6Z" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                        <p class="deck-description">{{ $deck->description ?? 'No description' }}</p>
                        <div class="deck-meta">
                            <span class="deck-cards-count">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 10.5h.75v.75h-.75v-.75ZM6.75 14.25h.75v.75h-.75v-.75Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6Z" />
                                </svg>
                                {{ $deck->flashcards_count ?? 0 }} cards
                            </span>
                            @if($deck->reviews_avg_rating)
                                <span class="deck-rating">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006Z" clip-rule="evenodd" />
                                    </svg>
                                    {{ number_format($deck->reviews_avg_rating, 1) }}
                                </span>
                            @endif
                        </div>
                        @if($deck->owner)
                            <div class="deck-author">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                {{ $deck->owner->name }}
                            </div>
                        @endif
                        @if($deck->tags && count($deck->tags) > 0)
                            <div class="deck-tags">
                                @foreach(array_slice($deck->tags, 0, 3) as $tag)
                                    <span class="tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($communityDecks->hasPages())
                <div class="pagination">
                    {{ $communityDecks->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
