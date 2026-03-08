@props([
    'deck' => null,
    'categories' => []
])

{{-- Edit Deck Modal --}}
<div class="modal" id="editDeckModal" data-modal="edit-deck" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Deck</h3>
                <button type="button" class="modal-close" data-modal-close aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($deck)
            <form action="{{ route('client.decks.update', $deck) }}" method="POST" class="modal-body" data-edit-deck-form>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="edit-title" class="form-label">Title *</label>
                    <input type="text" id="edit-title" name="title" value="{{ old('title', $deck->title) }}"
                           class="form-input" required maxlength="255">
                    @error('title') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="edit-description" class="form-label">Description</label>
                    <textarea id="edit-description" name="description" rows="3"
                              class="form-input">{{ old('description', $deck->description) }}</textarea>
                    @error('description') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-category" class="form-label">Category</label>
                        <select id="edit-category" name="category" class="form-input">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $deck->category) === $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="edit-visibility" class="form-label">Visibility</label>
                        <select id="edit-visibility" name="visibility" class="form-input">
                            <option value="private" {{ old('visibility', $deck->visibility) === 'private' ? 'selected' : '' }}>
                                🔒 Private
                            </option>
                            <option value="public" {{ old('visibility', $deck->visibility) === 'public' ? 'selected' : '' }}>
                                🌍 Public
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit-tags" class="form-label">Tags</label>
                    <input type="text" id="edit-tags" name="tags" value="{{ old('tags', implode(', ', $deck->tags ?? [])) }}"
                           class="form-input" placeholder="e.g., javascript, programming, beginner">
                    <span class="form-hint">Separate multiple tags with commas</span>
                </div>
            </form>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                @if($deck)
                <button type="submit" form="{{ $deck->id ?? '' }}" class="btn btn-primary" data-edit-deck-submit>
                    Save Changes
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Add Flashcard Modal --}}
<div class="modal" id="addFlashcardModal" data-modal="add-flashcard" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Flashcard</h3>
                <button type="button" class="modal-close" data-modal-close aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($deck)
            <form action="{{ route('client.decks.flashcards.store', $deck) }}" method="POST" class="modal-body" data-add-flashcard-form>
                @csrf

                <div class="form-group">
                    <label for="card-front" class="form-label">Front *</label>
                    <textarea id="card-front" name="front_content" rows="3"
                              class="form-input" required placeholder="Enter the question or term...">{{ old('front_content') }}</textarea>
                    @error('front_content') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="card-back" class="form-label">Back *</label>
                    <textarea id="card-back" name="back_content" rows="3"
                              class="form-input" required placeholder="Enter the answer or definition...">{{ old('back_content') }}</textarea>
                    @error('back_content') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="card-image" class="form-label">Image URL (optional)</label>
                        <input type="url" id="card-image" name="image_url" value="{{ old('image_url') }}"
                               class="form-input" placeholder="https://...">
                        @error('image_url') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="card-audio" class="form-label">Audio URL (optional)</label>
                        <input type="url" id="card-audio" name="audio_url" value="{{ old('audio_url') }}"
                               class="form-input" placeholder="https://...">
                        @error('audio_url') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="card-hint" class="form-label">Hint (optional)</label>
                    <input type="text" id="card-hint" name="hint" value="{{ old('hint') }}"
                           class="form-input" placeholder="A helpful hint...">
                    @error('hint') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </form>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                @if($deck)
                <button type="submit" form="addFlashcardForm" class="btn btn-primary" data-add-flashcard-submit>
                    Add Card
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Edit Flashcard Modal --}}
<div class="modal" id="editFlashcardModal" data-modal="edit-flashcard" tabindex="-1" aria-hidden="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Flashcard</h3>
                <button type="button" class="modal-close" data-modal-close aria-label="Close">
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
                    <label for="edit-card-front" class="form-label">Front *</label>
                    <textarea id="edit-card-front" name="front_content" rows="3"
                              class="form-input" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit-card-back" class="form-label">Back *</label>
                    <textarea id="edit-card-back" name="back_content" rows="3"
                              class="form-input" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-card-image" class="form-label">Image URL (optional)</label>
                        <input type="url" id="edit-card-image" name="image_url" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="edit-card-audio" class="form-label">Audio URL (optional)</label>
                        <input type="url" id="edit-card-audio" name="audio_url" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit-card-hint" class="form-label">Hint (optional)</label>
                    <input type="text" id="edit-card-hint" name="hint" class="form-input">
                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" form="editFlashcardForm" class="btn btn-primary" data-edit-flashcard-submit>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Modal styles */
.modal {
    position: fixed;
    inset: 0;
    z-index: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.25s ease, visibility 0.25s ease;
}

.modal.active {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
}

.modal-dialog {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 1rem;
}

.modal-dialog.modal-lg {
    max-width: 700px;
}

.modal-content {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    transform: translateY(-20px) scale(0.95);
    opacity: 0;
    transition: transform 0.25s ease, opacity 0.25s ease;
}

.modal.active .modal-content {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.modal-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #f3f4f6;
    border: none;
    border-radius: 0.5rem;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #e5e7eb;
    color: #111827;
}

.modal-close svg {
    width: 20px;
    height: 20px;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-secondary {
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.btn-primary {
    background: #4f46e5;
    color: white;
}

.btn-primary:hover {
    background: #4338ca;
}

/* Form styles */
.form-group {
    margin-bottom: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-input {
    width: 100%;
    padding: 0.625rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-family: inherit;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-error {
    display: block;
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.form-hint {
    display: block;
    color: #9ca3af;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

@media (max-width: 640px) {
    .modal-dialog {
        margin: 1rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
