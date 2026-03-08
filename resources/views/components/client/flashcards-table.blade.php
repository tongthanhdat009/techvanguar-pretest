@props(['deck' => null, 'canManage' => false])

@php
    $canManage = $canManage ?? ($deck && auth()->id() === $deck->user_id);
@endphp

<div class="flashcards-section">
    <div class="flashcards-header">
        <div>
            <span class="dashboard-card-kicker">Nội dung deck</span>
            <h2 class="flashcards-section__title">Flashcard ({{ $deck->flashcards->count() }})</h2>
        </div>
        @if($canManage)
            <button type="button" class="dashboard-btn dashboard-btn-secondary flashcards-add-btn" data-deck-add-card>
                Thêm flashcard
            </button>
        @endif
    </div>

    @if($deck->flashcards->isEmpty())
        <div class="client-empty-panel flashcards-empty-state">
            <div class="client-empty-icon">🗂️</div>
            <h2>Deck này chưa có flashcard nào.</h2>
            <p>Hãy thêm vài thẻ đầu tiên để bộ nội dung có thể đi vào một phiên ôn hoàn chỉnh.</p>
            @if($canManage)
                <button type="button" class="dashboard-btn dashboard-btn-primary" data-deck-add-card>Thêm flashcard đầu tiên</button>
            @endif
        </div>
    @else
        <div class="flashcards-table">
            <table class="flashcards-table__table">
                <thead>
                    <tr>
                        <th>Mặt trước</th>
                        <th>Mặt sau</th>
                        @if($canManage)<th>Thao tác</th>@endif
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
                                            title="Chỉnh sửa flashcard">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('client.flashcards.destroy', $flashcard) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete"
                                                data-confirm="Bạn có chắc muốn xóa flashcard này?"
                                                title="Xóa flashcard">
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
