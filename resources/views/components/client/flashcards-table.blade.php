@props(['deck' => null, 'canManage' => false])

@php
    $canManage = $canManage ?? ($deck && auth()->id() === $deck->user_id);
@endphp

<div class="flashcards-section">
    <div class="flashcards-header">
        <h2 class="flashcards-section__title">Flashcards ({{ $deck->flashcards->count() }})</h2>
        @if($canManage)
            <button type="button" class="btn btn-primary" data-deck-add-card>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Card
            </button>
        @endif
    </div>

    @if($deck->flashcards->isEmpty())
        {{-- Empty State --}}
        @include('components.common.empty-state', [
            'title' => 'No cards yet',
            'description' => 'This deck has no flashcards yet.',
            'icon' => '<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
        ])
    @else
        {{-- Flashcards Table --}}
        <div class="flashcards-table">
            <table class="flashcards-table__table">
                <thead>
                    <tr>
                        <th>Front</th>
                        <th>Back</th>
                        @if($canManage)<th>Actions</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($deck->flashcards as $flashcard)
                        <tr class="flashcards-table__row">
                            <td class="flashcards-table__cell">
                                {{ \Illuminate\Support\Str::limit($flashcard->front_content, 100) }}
                                @if($flashcard->image_url)
                                    <span class="card-has-media">🖼️</span>
                                @endif
                            </td>
                            <td class="flashcards-table__cell">
                                {{ \Illuminate\Support\Str::limit($flashcard->back_content, 100) }}
                            </td>
                            @if($canManage)
                            <td class="flashcards-table__cell">
                                <div class="card-actions">
                                    <button type="button" class="action-btn action-btn-edit"
                                            data-flashcard-edit="{{ $flashcard->id }}"
                                            data-card-data="{{ json_encode([
                                                'front' => $flashcard->front_content,
                                                'back' => $flashcard->back_content,
                                                'image_url' => $flashcard->image_url,
                                                'audio_url' => $flashcard->audio_url,
                                                'hint' => $flashcard->hint
                                            ]) }}"
                                            title="Edit card">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('client.flashcards.destroy', $flashcard) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete"
                                                data-confirm="Are you sure you want to delete this card?"
                                                title="Delete card">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('styles')
<style>
.flashcards-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.flashcards-header .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #4f46e5;
    color: white;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.flashcards-header .btn:hover {
    background: #4338ca;
}

.card-has-media {
    margin-left: 0.5rem;
    font-size: 0.75rem;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn svg {
    width: 16px;
    height: 16px;
}

.action-btn-edit {
    background: #f3f4f6;
    color: #4b5563;
}

.action-btn-edit:hover {
    background: #e5e7eb;
    color: #111827;
}

.action-btn-delete {
    background: #fef2f2;
    color: #dc2626;
}

.action-btn-delete:hover {
    background: #fee2e2;
}

.delete-form {
    margin: 0;
}
</style>
@endpush
