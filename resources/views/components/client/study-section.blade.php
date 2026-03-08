{{-- Study Section Component --}}
@props([
    'title' => '',
    'decks' => collect(),
    'emptyMessage' => 'No decks found.',
    'emptyLink' => null,
    'emptyLinkText' => null
])

<section class="study-section">
    <h2 class="section-title">{{ $title }}</h2>
    @include('components.client.deck-grid', [
        'decks' => $decks,
        'emptyMessage' => $emptyMessage,
        'emptyLink' => $emptyLink,
        'emptyLinkText' => $emptyLinkText
    ])
</section>
