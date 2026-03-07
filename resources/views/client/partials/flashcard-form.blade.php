@props([
    'action',
    'flashcard' => null,
    'submitLabel' => 'Save flashcard',
    'method' => null,
])

<form action="{{ $action }}" method="POST" class="space-y-4">
    @csrf
    @if($method)
        @method($method)
    @endif
    <div>
        <label class="field-label">Front content</label>
        <textarea name="front_content" rows="3" class="field-input" required>{{ old('front_content', $flashcard?->front_content) }}</textarea>
    </div>
    <div>
        <label class="field-label">Back content</label>
        <textarea name="back_content" rows="3" class="field-input" required>{{ old('back_content', $flashcard?->back_content) }}</textarea>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label">Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url', $flashcard?->image_url) }}" class="field-input">
        </div>
        <div>
            <label class="field-label">Audio URL</label>
            <input type="url" name="audio_url" value="{{ old('audio_url', $flashcard?->audio_url) }}" class="field-input">
        </div>
    </div>
    <div>
        <label class="field-label">Hint</label>
        <input type="text" name="hint" value="{{ old('hint', $flashcard?->hint) }}" class="field-input">
    </div>
    <button type="submit" class="primary-button w-full">{{ $submitLabel }}</button>
</form>
