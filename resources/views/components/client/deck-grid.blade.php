{{-- Deck Grid Component --}}
@props([
    'decks' => collect(),
    'emptyMessage' => 'No decks found.',
    'emptyLink' => null,
    'emptyLinkText' => null
])

<div class="deck-grid">
    @if($decks->isEmpty())
        <p class="text-gray-400 text-sm col-span-full">
            {{ $emptyMessage }}
            @if($emptyLink)
                <a href="{{ $emptyLink }}" class="text-indigo-600 hover:text-indigo-500 ml-1">
                    {{ $emptyLinkText ?? 'Create one!' }}
                </a>
            @endif
        </p>
    @else
        @foreach($decks as $deck)
            <a href="{{ route('client.decks.show', $deck) }}"
               class="deck-card">
                <h3 class="title">{{ $deck->title }}</h3>
                <p class="count">{{ $deck->flashcards_count ?? 0 }} cards</p>
            </a>
        @endforeach
    @endif
</div>
