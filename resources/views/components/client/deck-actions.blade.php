@props([
    'deck' => null,
    'canManageDeck' => false
])

@if($deck)
    <div class="deck-actions">
        @if($canManageDeck)
            <a href="{{ route('client.decks.study', $deck) }}"
               class="deck-actions__btn deck-actions__btn--primary">
                Ôn deck này
            </a>
        @else
            <form method="POST"
                  action="{{ route('client.decks.copy', $deck) }}"
                  class="inline">
                @csrf
                <input type="hidden" name="redirect_to" value="study">
                <button type="submit"
                        class="deck-actions__btn deck-actions__btn--primary">
                    Ôn deck này
                </button>
            </form>
        @endif

        <a href="{{ route('client.my-decks') }}" class="deck-actions__btn deck-actions__btn--ghost">
            Quay lại thư viện
        </a>

        @if($canManageDeck)
            <div class="deck-actions__owner">
                <button type="button"
                        class="deck-actions__btn deck-actions__btn--secondary"
                        data-deck-edit
                        data-deck-id="{{ $deck->id }}">
                    Chỉnh sửa deck
                </button>

                <button type="button"
                        class="deck-actions__btn deck-actions__btn--secondary"
                        data-deck-add-card
                        data-deck-id="{{ $deck->id }}">
                    Thêm flashcard
                </button>

                <form method="POST"
                      action="{{ route('client.decks.destroy', $deck) }}"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="deck-actions__btn deck-actions__btn--danger"
                            data-deck-delete
                            data-confirm-title="Xóa deck này?"
                            data-confirm-message="Hành động này sẽ xóa toàn bộ flashcard, tiến độ học và đánh giá liên quan trong deck này."
                            data-confirm-label="Xóa deck">
                        Xóa deck
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif
