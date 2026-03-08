@extends('layouts.client', ['title' => 'Dashboard'])

@section('content')
    {{-- Progress summary --}}
    @include('components.client.progress-summary', ['summary' => $progressSummary])

    {{-- My decks --}}
    @include('components.client.study-section', [
        'title' => 'My library',
        'decks' => $ownedDecks,
        'emptyMessage' => 'You have no decks yet.',
        'emptyLink' => route('register'),
        'emptyLinkText' => 'Create one!'
    ])

    {{-- Community library --}}
    @include('components.client.study-section', [
        'title' => 'Community library',
        'decks' => $communityDecks,
        'emptyMessage' => 'No public decks available yet.'
    ])
@endsection
