@extends('layouts.client-app', ['title' => 'Create New Deck'])

@section('content')
<div class="create-deck-page">
    <div class="page-header">
        <h1 class="page-title">Create New Deck</h1>
        <p class="page-subtitle">Create a new flashcard deck and add your first cards</p>
    </div>

    <form action="{{ route('client.decks.store') }}" method="POST" class="create-deck-form">
        @csrf
        <input type="hidden" name="is_active" value="1">

        {{-- Deck Information --}}
        <div class="form-section">
            <h2 class="form-section-title">Deck Information</h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="form-input" placeholder="e.g., Spanish Vocabulary" required>
                    @error('title') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-input">
                        <option value="">Select category</option>
                        <option value="Language" {{ old('category') === 'Language' ? 'selected' : '' }}>Language</option>
                        <option value="Science" {{ old('category') === 'Science' ? 'selected' : '' }}>Science</option>
                        <option value="History" {{ old('category') === 'History' ? 'selected' : '' }}>History</option>
                        <option value="Math" {{ old('category') === 'Math' ? 'selected' : '' }}>Math</option>
                        <option value="Technology" {{ old('category') === 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="3" class="form-input"
                          placeholder="What is this deck about?">{{ old('description') }}</textarea>
                @error('description') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="visibility" class="form-label">Visibility</label>
                    <select id="visibility" name="visibility" class="form-input">
                        <option value="private" {{ old('visibility', 'private') === 'private' ? 'selected' : '' }}>
                            🔒 Private - Only you can see this deck
                        </option>
                        <option value="public" {{ old('visibility') === 'public' ? 'selected' : '' }}>
                            🌍 Public - Share with the community
                        </option>
                    </select>
                    @error('visibility') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                           class="form-input" placeholder="e.g., spanish, beginner, vocabulary">
                    <span class="form-hint">Separate multiple tags with commas</span>
                    @error('tags') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Flashcards --}}
        <div class="form-section">
            <h2 class="form-section-title">Add Flashcards</h2>
            <p class="form-section-desc">Add at least one flashcard to get started</p>

            <x-client.dynamic-card-input :minCards="1" :maxCards="50" />
        </div>

        {{-- Form Actions --}}
        <div class="form-actions">
            <a href="{{ route('client.dashboard') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Deck
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.create-deck-page {
    max-width: 800px;
    margin: 0 auto;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.create-deck-form {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.form-section-desc {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
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

select.form-input {
    background: white;
    cursor: pointer;
}

.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
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

.btn-secondary {
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #f9fafb;
}

.btn-primary {
    background: #4f46e5;
    color: white;
}

.btn-primary:hover {
    background: #4338ca;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

@media (max-width: 640px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush
