@props(['deck' => null])

<div class="flashcards-section">
    <h2 class="flashcards-section__title">Thẻ flashcards ({{ $deck->flashcards->count() }})</h2>

    @if($deck->flashcards->isEmpty())
        {{-- Empty State --}}
        @include('components.common.empty-state', [
            'title' => 'Chưa có thẻ nào',
            'description' => 'Bộ thẻ này chưa có flashcard nào.',
            'icon' => '<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
        ])
    @else
        {{-- Flashcards Table --}}
        <div class="flashcards-table">
            <table class="flashcards-table__table">
                <thead>
                    <tr>
                        <th>Mặt trước</th>
                        <th>Mặt sau</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deck->flashcards as $flashcard)
                        <tr class="flashcards-table__row">
                            <td class="flashcards-table__cell">
                                {{ \Illuminate\Support\Str::limit($flashcard->front_content, 100) }}
                            </td>
                            <td class="flashcards-table__cell">
                                {{ \Illuminate\Support\Str::limit($flashcard->back_content, 100) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
