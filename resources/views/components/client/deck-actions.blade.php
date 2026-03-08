@props([
    'deck' => null,
    'canManageDeck' => false
])

@if($deck)
    <div class="deck-actions">
        {{-- Study Now Button (Always visible) --}}
        <a href="{{ route('client.decks.study', $deck) }}"
           class="deck-actions__btn deck-actions__btn--primary">
            Học ngay
        </a>

        {{-- Owner-only Actions --}}
        @if($canManageDeck)
            <div class="deck-actions__owner">
                {{-- Edit Deck --}}
                <button type="button"
                        class="deck-actions__btn deck-actions__btn--secondary"
                        data-deck-edit
                        data-deck-id="{{ $deck->id }}">
                    Chỉnh sửa
                </button>

                {{-- Add Flashcard --}}
                <button type="button"
                        class="deck-actions__btn deck-actions__btn--secondary"
                        data-deck-add-card
                        data-deck-id="{{ $deck->id }}">
                    Thêm thẻ mới
                </button>

                {{-- Delete Deck (with confirmation) --}}
                <form method="POST"
                      action="{{ route('client.decks.destroy', $deck) }}"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="deck-actions__btn deck-actions__btn--danger"
                            data-deck-delete
                            data-confirm-message="Xóa bộ thẻ này và tất cả các thẻ bên trong?">
                        Xóa bộ thẻ
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif
