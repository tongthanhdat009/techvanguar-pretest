{{-- Study Section Component --}}
@props([
    'title' => '',
    'decks' => collect(),
    'emptyMessage' => 'No decks found.',
    'emptyLink' => null,
    'emptyLinkText' => null
])

<section class="study-section">
    <div class="study-section-header">
        <h2 class="section-title">{{ $title }}</h2>
        <span class="study-section-count">{{ $decks->count() }} decks</span>
    </div>
    @include('components.client.deck-grid', [
        'decks' => $decks,
        'emptyMessage' => $emptyMessage,
        'emptyLink' => $emptyLink,
        'emptyLinkText' => $emptyLinkText
    ])
</section>
