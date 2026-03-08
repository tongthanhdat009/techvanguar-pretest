@props([
    'deck' => null,
    'existingReview' => null
])

<div class="review-form">
    <h3 class="review-form__title">
        {{ $existingReview ? 'Cập nhật đánh giá của bạn' : 'Viết đánh giá' }}
    </h3>

    <form method="POST"
          action="{{ route('client.decks.review', $deck) }}"
          class="review-form__form">
        @csrf

        {{-- Rating Selector --}}
        <div class="review-form__field">
            <label class="review-form__label">Đánh giá</label>
            <div class="review-form__rating" data-rating-selector>
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            class="review-form__star {{ $i <= ($existingReview->rating ?? 0) ? 'review-form__star--active' : '' }}"
                            data-rating-value="{{ $i }}"
                            type="button">
                        ★
                    </button>
                @endfor
                <input type="hidden"
                       name="rating"
                       value="{{ $existingReview->rating ?? '' }}"
                       data-rating-input
                       required>
            </div>
        </div>

        {{-- Comment --}}
        <div class="review-form__field">
            <label class="review-form__label" for="comment">Bình luận (tùy chọn)</label>
            <textarea
                name="comment"
                id="comment"
                rows="3"
                maxlength="1200"
                class="review-form__textarea"
                placeholder="Chia sẻ suy nghĩ của bạn về bộ thẻ này...">{{ $existingReview->comment ?? '' }}</textarea>
            <span class="review-form__char-count">
                <span data-char-count>{{ $existingReview ? strlen($existingReview->comment) : 0 }}</span>/1200
            </span>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="review-form__submit">
            {{ $existingReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
        </button>
    </form>
</div>
