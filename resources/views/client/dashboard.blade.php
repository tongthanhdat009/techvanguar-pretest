@extends('layouts.client-app', ['title' => 'Dashboard'])

@section('content')
    {{-- Dashboard Header --}}
    <div class="dashboard-header">
        <h1 class="dashboard-title">Welcome back, {{ auth()->user()->name }}!</h1>
        <a href="{{ route('client.decks.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:18px;height:18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Deck
        </a>
    </div>

    {{-- Progress summary --}}
    @include('components.client.progress-summary', ['summary' => $progressSummary])

    {{-- My decks --}}
    @include('components.client.study-section', [
        'title' => 'My library',
        'decks' => $ownedDecks,
        'emptyMessage' => 'You have no decks yet.',
        'emptyLink' => route('client.decks.create'),
        'emptyLinkText' => 'Create your first deck'
    ])

    {{-- Community library --}}
    @include('components.client.study-section', [
        'title' => 'Community library',
        'decks' => $communityDecks,
        'emptyMessage' => 'No public decks available yet.'
    ])
@endsection

@push('styles')
<style>
.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #4f46e5;
    color: white;
}

.btn-primary:hover {
    background: #4338ca;
}

@media (max-width: 640px) {
    .dashboard-header {
        flex-direction: column;
        align-items: stretch;
    }

    .dashboard-title {
        text-align: center;
    }

    .btn {
        justify-content: center;
    }
}
</style>
@endpush
