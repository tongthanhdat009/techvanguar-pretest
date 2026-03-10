{{-- Shared flashcard management partial.
     Expects: $deck (with flashcards loaded)
--}}
<div class="admin-card">
    <div class="admin-card-header">
        <span class="text-white font-semibold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Flashcards
        </span>
        <span class="badge badge-active">{{ $deck->flashcards->count() }} thẻ</span>
    </div>

    {{-- Add new flashcard form --}}
    <div class="p-5 border-b border-slate-700/50">
        <p class="text-sm font-medium text-slate-300 mb-3">Thêm flashcard mới</p>
        <form method="POST" action="{{ route('admin.decks.flashcards.store', $deck) }}">
            @csrf
            <input type="hidden" name="deck_id" value="{{ $deck->id }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="admin-form-group">
                    <label class="admin-form-label">Mặt trước <span class="text-red-400">*</span></label>
                    <textarea name="front_content" rows="3"
                        class="admin-form-input {{ $errors->has('front_content') ? 'has-error' : '' }}"
                        placeholder="Nội dung mặt trước...">{{ old('front_content') }}</textarea>
                    @error('front_content')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Mặt sau <span class="text-red-400">*</span></label>
                    <textarea name="back_content" rows="3"
                        class="admin-form-input {{ $errors->has('back_content') ? 'has-error' : '' }}"
                        placeholder="Nội dung mặt sau...">{{ old('back_content') }}</textarea>
                    @error('back_content')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="admin-form-group mt-3" style="max-width: 420px">
                <label class="admin-form-label">Gợi ý</label>
                <input type="text" name="hint" value="{{ old('hint') }}"
                    class="admin-form-input"
                    placeholder="Gợi ý (không bắt buộc)">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                <div class="admin-form-group">
                    <label class="admin-form-label">URL Hình ảnh</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}"
                        class="admin-form-input {{ $errors->has('image_url') ? 'has-error' : '' }}"
                        placeholder="https://...">
                    @error('image_url')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">URL Audio</label>
                    <div class="audio-preview-group" data-audio-preview-group>
                        <input type="url" name="audio_url" value="{{ old('audio_url') }}"
                            class="admin-form-input {{ $errors->has('audio_url') ? 'has-error' : '' }}"
                            placeholder="https://..." data-audio-preview-input>
                        <div class="audio-preview-shell" data-audio-preview-shell @if(!old('audio_url')) hidden @endif>
                            <span class="audio-preview-label">Nghe thử âm thanh</span>
                            <audio controls preload="none" data-audio-preview-player @if(old('audio_url')) src="{{ old('audio_url') }}" @endif></audio>
                        </div>
                    </div>
                    @error('audio_url')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn-primary mt-4">+ Thêm flashcard</button>
        </form>
    </div>

    {{-- Flashcard list --}}
    @if($deck->flashcards->isEmpty())
        <div class="admin-empty">
            <svg class="w-10 h-10 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm">Chưa có flashcard nào. Thêm thẻ đầu tiên ở trên.</p>
        </div>
    @else
        <div class="p-5 flex flex-col gap-3">
            @foreach($deck->flashcards as $fc)
            <div class="fc-card" data-fc-card id="fc-{{ $fc->id }}">

                {{-- Card header: index + actions --}}
                <div class="fc-card-header">
                    <span class="text-slate-500 text-xs font-mono">#{{ $loop->iteration }}</span>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-admin-action warning" data-fc-edit-btn>Sửa</button>
                        <form method="POST" action="{{ route('admin.flashcards.destroy', $fc) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn-admin-action danger"
                                data-admin-confirm
                                data-confirm-message="Xóa flashcard #{{ $loop->iteration }} ({{ Str::limit($fc->front_content, 40) }})?"
                                data-confirm-accept="Xóa flashcard">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>

                {{-- View mode --}}
                <div class="fc-card-body" data-fc-view>
                    <div>
                        <p class="fc-side-label">Mặt trước</p>
                        <div class="fc-side-content">{{ $fc->front_content }}</div>
                    </div>
                    <div>
                        <p class="fc-side-label">Mặt sau</p>
                        <div class="fc-side-content">{{ $fc->back_content }}</div>
                    </div>
                    @if($fc->hint)
                    <div style="grid-column: 1 / -1">
                        <p class="fc-side-label">Gợi ý</p>
                        <div class="fc-side-content" style="min-height: unset">{{ $fc->hint }}</div>
                    </div>
                    @endif
                    @if($fc->image_url || $fc->audio_url)
                    <div style="grid-column: 1 / -1" class="flex gap-3 flex-wrap">
                        @if($fc->image_url)
                            <a href="{{ $fc->image_url }}" target="_blank" rel="noopener noreferrer" class="btn-admin-action info text-xs">🖼 Xem ảnh</a>
                        @endif
                        @if($fc->audio_url)
                            <a href="{{ $fc->audio_url }}" target="_blank" rel="noopener noreferrer" class="btn-admin-action success text-xs">🔊 Nghe audio</a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Edit form (hidden by default) --}}
                <div class="fc-edit-form" data-fc-edit-form style="display:none">
                    <form method="POST" action="{{ route('admin.flashcards.update', $fc) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="deck_id" value="{{ $deck->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <div class="admin-form-group">
                                <label class="admin-form-label">Mặt trước <span class="text-red-400">*</span></label>
                                <textarea name="front_content" rows="3" class="admin-form-input" required>{{ $fc->front_content }}</textarea>
                            </div>
                            <div class="admin-form-group">
                                <label class="admin-form-label">Mặt sau <span class="text-red-400">*</span></label>
                                <textarea name="back_content" rows="3" class="admin-form-input" required>{{ $fc->back_content }}</textarea>
                            </div>
                        </div>
                        <div class="admin-form-group mb-3" style="max-width: 420px">
                            <label class="admin-form-label">Gợi ý</label>
                            <input type="text" name="hint" value="{{ $fc->hint }}" class="admin-form-input" placeholder="Gợi ý (không bắt buộc)">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                            <div class="admin-form-group">
                                <label class="admin-form-label">URL Hình ảnh</label>
                                <input type="url" name="image_url" value="{{ $fc->image_url }}" class="admin-form-input" placeholder="https://...">
                            </div>
                            <div class="admin-form-group">
                                <label class="admin-form-label">URL Audio</label>
                                <div class="audio-preview-group" data-audio-preview-group>
                                    <input type="url" name="audio_url" value="{{ $fc->audio_url }}" class="admin-form-input" placeholder="https://..." data-audio-preview-input>
                                    <div class="audio-preview-shell" data-audio-preview-shell @if(!$fc->audio_url) hidden @endif>
                                        <span class="audio-preview-label">Nghe thử âm thanh</span>
                                        <audio controls preload="none" data-audio-preview-player @if($fc->audio_url) src="{{ $fc->audio_url }}" @endif></audio>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="btn-primary">Lưu thay đổi</button>
                            <button type="button" class="btn-secondary" data-fc-cancel>Hủy</button>
                        </div>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-fc-edit-btn]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = btn.closest('[data-fc-card]');
            var editForm = card.querySelector('[data-fc-edit-form]');
            var viewBody = card.querySelector('[data-fc-view]');
            editForm.style.display = 'block';
            viewBody.style.display = 'none';
            btn.style.display = 'none';
        });
    });

    document.querySelectorAll('[data-fc-cancel]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = btn.closest('[data-fc-card]');
            var editForm = card.querySelector('[data-fc-edit-form]');
            var viewBody = card.querySelector('[data-fc-view]');
            var editBtn = card.querySelector('[data-fc-edit-btn]');
            editForm.style.display = 'none';
            viewBody.style.display = 'grid';
            editBtn.style.display = '';
        });
    });
}());
</script>
@endpush
