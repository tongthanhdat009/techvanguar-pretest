@props([
    'deck' => null,
    'categories' => []
])

@php
    $categoryLabels = [
        'Language' => 'Ngôn ngữ',
        'Science' => 'Khoa học',
        'History' => 'Lịch sử',
        'Math' => 'Toán học',
        'Technology' => 'Công nghệ',
        'Other' => 'Khác',
    ];
@endphp

{{-- Edit Deck Modal --}}
<div class="modal" id="editDeckModal" data-modal="edit-deck" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-heading">
                    <span class="modal-kicker">Quản lý deck</span>
                    <h3 class="modal-title">Chỉnh sửa deck</h3>
                    <p class="modal-description">Điều chỉnh mô tả, danh mục, mức chia sẻ và các tag để deck hiển thị rõ ràng hơn với người học.</p>
                </div>
                <button type="button" class="modal-close" data-modal-close aria-label="Đóng modal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($deck)
            <form action="{{ route('client.decks.update', $deck) }}" method="POST" class="modal-body modal-form" id="editDeckForm" data-edit-deck-form>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="edit-title" class="form-label">Tên deck</label>
                    <input type="text" id="edit-title" name="title" value="{{ old('title', $deck->title) }}"
                           class="form-input" required maxlength="255">
                    @error('title') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="edit-description" class="form-label">Mô tả</label>
                    <textarea id="edit-description" name="description" rows="3"
                              class="form-input">{{ old('description', $deck->description) }}</textarea>
                    @error('description') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-category" class="form-label">Danh mục</label>
                        <select id="edit-category" name="category" class="form-input">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $deck->category) === $category ? 'selected' : '' }}>
                                    {{ $categoryLabels[$category] ?? $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="edit-visibility" class="form-label">Mức chia sẻ</label>
                        <select id="edit-visibility" name="visibility" class="form-input">
                            <option value="private" {{ old('visibility', $deck->visibility) === 'private' ? 'selected' : '' }}>
                                Riêng tư
                            </option>
                            <option value="public" {{ old('visibility', $deck->visibility) === 'public' ? 'selected' : '' }}>
                                Công khai
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit-tags" class="form-label">Tag</label>
                    <input type="text" id="edit-tags" name="tags" value="{{ old('tags', implode(', ', $deck->tags ?? [])) }}"
                           class="form-input" placeholder="Ví dụ: javascript, beginner, interview">
                    <span class="form-hint">Phân tách nhiều tag bằng dấu phẩy.</span>
                </div>
            </form>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Hủy</button>
                @if($deck)
                <button type="submit" form="editDeckForm" class="btn btn-primary" data-edit-deck-submit>
                    Lưu thay đổi
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Add Flashcard Modal --}}
<div class="modal" id="addFlashcardModal" data-modal="add-flashcard" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-heading">
                    <span class="modal-kicker">Mở rộng nội dung</span>
                    <h3 class="modal-title">Thêm flashcard</h3>
                    <p class="modal-description">Mỗi flashcard nên chỉ chứa một ý chính để quá trình tự nhớ lại vẫn nhanh, chính xác và dễ đo tiến độ.</p>
                </div>
                <button type="button" class="modal-close" data-modal-close aria-label="Đóng modal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($deck)
            <form action="{{ route('client.decks.flashcards.store', $deck) }}" method="POST" class="modal-body modal-form" id="addFlashcardForm" data-add-flashcard-form>
                @csrf

                <div class="form-group">
                    <label for="card-front" class="form-label">Mặt trước</label>
                    <textarea id="card-front" name="front_content" rows="3"
                              class="form-input" required placeholder="Nhập câu hỏi, thuật ngữ hoặc khái niệm...">{{ old('front_content') }}</textarea>
                    @error('front_content') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="card-back" class="form-label">Mặt sau</label>
                    <textarea id="card-back" name="back_content" rows="3"
                              class="form-input" required placeholder="Nhập đáp án, định nghĩa hoặc nội dung cần nhớ...">{{ old('back_content') }}</textarea>
                    @error('back_content') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="card-image" class="form-label">URL hình ảnh</label>
                        <input type="url" id="card-image" name="image_url" value="{{ old('image_url') }}"
                               class="form-input" placeholder="https://...">
                        @error('image_url') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="card-audio" class="form-label">URL âm thanh</label>
                        <div class="audio-preview-group" data-audio-preview-group>
                            <input type="url" id="card-audio" name="audio_url" value="{{ old('audio_url') }}"
                                   class="form-input" placeholder="https://..." data-audio-preview-input>
                            <div class="audio-preview-shell" data-audio-preview-shell @if(!old('audio_url')) hidden @endif>
                                <span class="audio-preview-label">Nghe thử âm thanh</span>
                                <audio controls preload="none" data-audio-preview-player @if(old('audio_url')) src="{{ old('audio_url') }}" @endif></audio>
                            </div>
                        </div>
                        @error('audio_url') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="card-hint" class="form-label">Gợi ý</label>
                    <input type="text" id="card-hint" name="hint" value="{{ old('hint') }}"
                           class="form-input" placeholder="Một gợi ý ngắn nếu cần...">
                    @error('hint') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </form>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Hủy</button>
                @if($deck)
                <button type="submit" form="addFlashcardForm" class="btn btn-primary" data-add-flashcard-submit>
                    Thêm flashcard
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Edit Flashcard Modal --}}
<div class="modal" id="editFlashcardModal" data-modal="edit-flashcard" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-heading">
                    <span class="modal-kicker">Tinh chỉnh nội dung</span>
                    <h3 class="modal-title">Chỉnh sửa flashcard</h3>
                    <p class="modal-description">Cập nhật lại mặt trước, mặt sau hoặc gợi ý để thẻ rõ nghĩa hơn và ít gây nhầm lẫn hơn khi ôn tập.</p>
                </div>
                <button type="button" class="modal-close" data-modal-close aria-label="Đóng modal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="#" method="POST" class="modal-body" id="editFlashcardForm" data-edit-flashcard-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="_card_id" id="edit-flashcard-id" value="">

                <div class="form-group">
                    <label for="edit-card-front" class="form-label">Mặt trước</label>
                    <textarea id="edit-card-front" name="front_content" rows="3"
                              class="form-input" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit-card-back" class="form-label">Mặt sau</label>
                    <textarea id="edit-card-back" name="back_content" rows="3"
                              class="form-input" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-card-image" class="form-label">URL hình ảnh</label>
                        <input type="url" id="edit-card-image" name="image_url" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="edit-card-audio" class="form-label">URL âm thanh</label>
                        <div class="audio-preview-group" data-audio-preview-group>
                            <input type="url" id="edit-card-audio" name="audio_url" class="form-input" data-audio-preview-input>
                            <div class="audio-preview-shell" data-audio-preview-shell hidden>
                                <span class="audio-preview-label">Nghe thử âm thanh</span>
                                <audio controls preload="none" data-audio-preview-player></audio>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit-card-hint" class="form-label">Gợi ý</label>
                    <input type="text" id="edit-card-hint" name="hint" class="form-input">
                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Hủy</button>
                <button type="submit" form="editFlashcardForm" class="btn btn-primary" data-edit-flashcard-submit>
                    Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-confirm" id="confirmActionModal" data-modal="confirm-action" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog">
        <div class="modal-content modal-confirm__content">
            <div class="modal-header modal-confirm__header">
                <div class="modal-confirm__badge">!</div>
                <div class="modal-heading">
                    <span class="modal-kicker">Xác nhận thao tác</span>
                    <h3 class="modal-title" id="confirmActionTitle">Bạn có chắc muốn tiếp tục?</h3>
                    <p class="modal-description" id="confirmActionMessage">Hành động này có thể ảnh hưởng đến dữ liệu hiện tại.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-confirm-cancel>Giữ lại</button>
                <button type="button" class="btn btn-danger" data-confirm-submit>Xác nhận xóa</button>
            </div>
        </div>
    </div>
</div>
