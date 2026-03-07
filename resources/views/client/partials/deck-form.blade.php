@props([
    'action',
    'deck' => null,
    'submitLabel' => 'Save deck',
    'method' => null,
])

<form action="{{ $action }}" method="POST" class="space-y-4">
    @csrf
    @if($method)
        @method($method)
    @endif
    <div>
        <label class="field-label">Title</label>
        <input type="text" name="title" value="{{ old('title', $deck?->title) }}" class="field-input" required>
    </div>
    <div>
        <label class="field-label">Description</label>
        <textarea name="description" rows="3" class="field-input">{{ old('description', $deck?->description) }}</textarea>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label">Visibility</label>
            <select name="visibility" class="field-input">
                <option value="private" @selected(old('visibility', $deck?->visibility) === 'private')>Private</option>
                <option value="public" @selected(old('visibility', $deck?->visibility) === 'public')>Public</option>
            </select>
        </div>
        <div>
            <label class="field-label">Status</label>
            <select name="is_active" class="field-input">
                <option value="1" @selected((string) old('is_active', $deck?->is_active ?? true) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', $deck?->is_active ?? true) === '0')>Inactive</option>
            </select>
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label">Category</label>
            <input type="text" name="category" value="{{ old('category', $deck?->category) }}" class="field-input">
        </div>
        <div>
            <label class="field-label">Tags</label>
            <input type="text" name="tags" value="{{ old('tags', implode(', ', $deck?->tags ?? [])) }}" class="field-input" placeholder="grammar, beginner">
        </div>
    </div>
    <button type="submit" class="primary-button w-full">{{ $submitLabel }}</button>
</form>
