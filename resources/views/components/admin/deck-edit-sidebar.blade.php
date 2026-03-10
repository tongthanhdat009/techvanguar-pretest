{{-- Deck Edit Sidebar - Off-canvas panel for editing decks --}}
@props([
    'deck' => null,
])

<div id="deck-edit-sidebar" class="deck-edit-sidebar" data-deck-edit-sidebar>
    {{-- Overlay backdrop --}}
    <div class="deck-edit-sidebar-overlay" data-deck-edit-sidebar-overlay></div>

    {{-- Sidebar panel --}}
    <div class="deck-edit-sidebar-panel">
        {{-- Header --}}
        <div class="deck-edit-sidebar-header">
            <h2 class="deck-edit-sidebar-title">Sửa bộ thẻ</h2>
            <button type="button" class="deck-edit-sidebar-close" data-deck-edit-sidebar-close>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="deck-edit-sidebar-content">
            @if($deck)
            <form method="POST" action="{{ route('admin.decks.update', $deck) }}" id="deck-edit-form">
                @csrf
                @method('PUT')

                <div class="admin-form-group mb-4">
                    <label class="admin-form-label" for="edit-title">Tiêu đề <span class="text-red-400">*</span></label>
                    <input type="text" name="title" id="edit-title" value="{{ $deck->title }}"
                        class="admin-form-input" required
                        placeholder="Nhập tiêu đề bộ thẻ...">
                    @error('title')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-form-group mb-4">
                    <label class="admin-form-label" for="edit-description">Mô tả</label>
                    <textarea name="description" id="edit-description" rows="3"
                        class="admin-form-input"
                        placeholder="Mô tả về bộ thẻ (không bắt buộc)">{{ $deck->description }}</textarea>
                    @error('description')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="edit-category">Danh mục</label>
                        <input type="text" name="category" id="edit-category" value="{{ $deck->category }}"
                            class="admin-form-input"
                            placeholder="Ví dụ: Tiếng Anh, Toán...">
                        @error('category')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="edit-visibility">Chế độ</label>
                        <select name="visibility" id="edit-visibility" class="admin-form-input">
                            <option value="private" {{ $deck->visibility === 'private' ? 'selected' : '' }}>Riêng tư</option>
                            <option value="public" {{ $deck->visibility === 'public' ? 'selected' : '' }}>Công khai</option>
                        </select>
                        @error('visibility')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-group mb-4">
                    <label class="admin-form-label" for="edit-tags">Thẻ tag</label>
                    <input type="text" name="tags" id="edit-tags"
                        class="admin-form-input"
                        value="{{ $deck->tags ? implode(', ', $deck->tags) : '' }}"
                        placeholder="Ví dụ: từ vựng, ngữ pháp, cơ bản (cách nhau bằng dấu phẩy)">
                    <p class="text-xs text-slate-500 mt-1">Các thẻ tag cách nhau bằng dấu phẩy</p>
                    @error('tags')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-form-group mb-4">
                    <label class="admin-form-label" for="edit-owner_id">Chủ sở hữu</label>
                    <select name="owner_id" id="edit-owner_id" class="admin-form-input">
                        <option value="">— Không có chủ sở hữu —</option>
                        @foreach(\App\Models\User::where('role', 'client')->get() as $user)
                            <option value="{{ $user->id }}" {{ $deck->owner_id === $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('owner_id')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="deck-edit-sidebar-actions">
                    <button type="submit" class="btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn-secondary" data-deck-edit-sidebar-close>Hủy</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const sidebar = document.getElementById('deck-edit-sidebar');
    const openBtn = document.querySelector('[data-deck-edit-sidebar-open]');
    const closeBtns = document.querySelectorAll('[data-deck-edit-sidebar-close]');
    const overlay = document.querySelector('[data-deck-edit-sidebar-overlay]');

    if (!sidebar) return;

    function open() {
        sidebar.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        sidebar.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    // Open button
    if (openBtn) {
        openBtn.addEventListener('click', open);
    }

    // Close buttons
    closeBtns.forEach(btn => {
        btn.addEventListener('click', close);
    });

    // Overlay click
    if (overlay) {
        overlay.addEventListener('click', close);
    }

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
            close();
        }
    });

    // Expose to global scope for external calls
    window.DeckEditSidebar = { open, close };
})();
</script>
@endpush

@push('styles')
<style>
.deck-edit-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    pointer-events: none;
}

.deck-edit-sidebar-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.deck-edit-sidebar-panel {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 480px;
    max-width: 100%;
    background: #1e293b;
    box-shadow: -4px 0 24px rgba(0, 0, 0, 0.4);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
}

.deck-edit-sidebar.is-open {
    pointer-events: auto;
}

.deck-edit-sidebar.is-open .deck-edit-sidebar-overlay {
    opacity: 1;
}

.deck-edit-sidebar.is-open .deck-edit-sidebar-panel {
    transform: translateX(0);
}

.deck-edit-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgb(51 65 85);
}

.deck-edit-sidebar-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.deck-edit-sidebar-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    background: transparent;
    border: none;
    color: rgb(148 163 184);
    cursor: pointer;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.deck-edit-sidebar-close:hover {
    background: rgb(51 65 85);
    color: white;
}

.deck-edit-sidebar-close svg {
    width: 1.25rem;
    height: 1.25rem;
}

.deck-edit-sidebar-content {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
}

.deck-edit-sidebar-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid rgb(51 65 85);
    margin-top: 1rem;
}

@media (min-width: 640px) {
    .deck-edit-sidebar-panel {
        width: 560px;
    }
}
</style>
@endpush
