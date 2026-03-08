@extends('layouts.client', ['title' => $deck->title])

@section('content')
    {{-- Hero Info Section --}}
    @include('components.client.deck-hero', ['deck' => $deck])

    {{-- Action Buttons --}}
    @include('components.client.deck-actions', [
        'deck' => $deck,
        'canManageDeck' => $canManageDeck
    ])

    {{-- Flashcards Preview --}}
    @include('components.client.flashcards-table', ['deck' => $deck])

    {{-- Reviews Section --}}
    @include('components.client.reviews-section', [
        'deck' => $deck,
        'reviewForm' => $reviewForm
    ])
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof DeckDetail !== 'undefined') {
                DeckDetail.init();
            }
        });
    </script>
@endpush
