@props([
    'minCards' => 1,
    'maxCards' => 50
])

<div class="dynamic-card-input" data-dynamic-card-input data-min-cards="{{ $minCards }}" data-max-cards="{{ $maxCards }}">
    <div class="card-inputs-list" data-card-inputs-list>
        <!-- Initial card input row -->
        <div class="card-input-row" data-card-input-row>
            <div class="card-input-number">1</div>
            <div class="card-input-fields">
                <div class="card-input-field">
                    <label class="card-input-label">Mặt trước</label>
                    <textarea name="cards[0][front]" rows="2"
                              class="card-input-textarea"
                              placeholder="Nhập câu hỏi, thuật ngữ hoặc khái niệm..."
                              required></textarea>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">Mặt sau</label>
                    <textarea name="cards[0][back]" rows="2"
                              class="card-input-textarea"
                              placeholder="Nhập đáp án, định nghĩa hoặc nội dung cần nhớ..."
                              required></textarea>
                </div>
                <div class="card-input-row-optional">
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">📷 URL hình ảnh</label>
                        <input type="url" name="cards[0][image_url]"
                               class="card-input-input"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">🎵 URL âm thanh</label>
                        <div class="audio-preview-group" data-audio-preview-group>
                            <input type="url" name="cards[0][audio_url]"
                                   class="card-input-input"
                                   placeholder="https://example.com/audio.mp3"
                                   data-audio-preview-input>
                            <div class="audio-preview-shell audio-preview-shell--compact" data-audio-preview-shell hidden>
                                <span class="audio-preview-label">Nghe thử</span>
                                <audio controls preload="none" data-audio-preview-player></audio>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">💡 Gợi ý</label>
                    <input type="text" name="cards[0][hint]"
                           class="card-input-input"
                           placeholder="Một gợi ý ngắn nếu cần...">
                </div>
                <div class="card-input-options">
                    <button type="button" class="card-input-remove" data-card-input-remove style="display: none;" aria-label="Remove card">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-input-actions">
        <button type="button" class="card-input-add" data-card-input-add>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Thêm một thẻ nữa</span>
        </button>
        <span class="card-count-info">
            <span data-card-count>1</span> / {{ $maxCards }} thẻ
        </span>
    </div>
</div>

@push('scripts')
<script>
// Dynamic Card Input Module
const DynamicCardInput = {
    init() {
        this.container = document.querySelector('[data-dynamic-card-input]');
        if (!this.container) return;

        this.list = this.container.querySelector('[data-card-inputs-list]');
        this.addBtn = this.container.querySelector('[data-card-input-add]');
        this.countDisplay = this.container.querySelector('[data-card-count]');
        this.minCards = parseInt(this.container.dataset.minCards) || 1;
        this.maxCards = parseInt(this.container.dataset.maxCards) || 50;
        this.cardCount = 1;

        this.bindEvents();
        this.updateUI();
    },

    bindEvents() {
        this.addBtn?.addEventListener('click', () => this.addCard());

        // Remove buttons (delegated)
        this.list?.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('[data-card-input-remove]');
            if (removeBtn) {
                this.removeCard(removeBtn);
            }
        });
    },

    addCard() {
        if (this.cardCount >= this.maxCards) {
            Toast.warning(`Tối đa ${this.maxCards} thẻ cho một lượt tạo deck`);
            return;
        }

        const row = this.createCardRow(this.cardCount);
        this.list.appendChild(row);
        this.cardCount++;
        this.updateUI();
        window.ClientAudioPreview?.refresh(row);

        // Focus the new input
        const newTextarea = row.querySelector('.card-input-textarea');
        newTextarea?.focus();
    },

    removeCard(btn) {
        if (this.cardCount <= this.minCards) {
            Toast.warning(`Bạn cần ít nhất ${this.minCards} thẻ`);
            return;
        }

        const row = btn.closest('[data-card-input-row]');
        row.style.animation = 'cardSlideOut 0.2s ease';
        setTimeout(() => {
            row.remove();
            this.cardCount--;
            this.renumberCards();
            this.updateUI();
        }, 200);
    },

    createCardRow(index) {
        const template = document.createElement('div');
        template.className = 'card-input-row';
        template.dataset.cardInputRow = '';
        template.innerHTML = `
            <div class="card-input-number">${index + 1}</div>
            <div class="card-input-fields">
                <div class="card-input-field">
                    <label class="card-input-label">Mặt trước</label>
                    <textarea name="cards[${index}][front]" rows="2"
                              class="card-input-textarea"
                              placeholder="Nhập câu hỏi, thuật ngữ hoặc khái niệm..."
                              required></textarea>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">Mặt sau</label>
                    <textarea name="cards[${index}][back]" rows="2"
                              class="card-input-textarea"
                              placeholder="Nhập đáp án, định nghĩa hoặc nội dung cần nhớ..."
                              required></textarea>
                </div>
                <div class="card-input-row-optional">
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">📷 URL hình ảnh</label>
                        <input type="url" name="cards[${index}][image_url]"
                               class="card-input-input"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">🎵 URL âm thanh</label>
                        <div class="audio-preview-group" data-audio-preview-group>
                            <input type="url" name="cards[${index}][audio_url]"
                                   class="card-input-input"
                                   placeholder="https://example.com/audio.mp3"
                                   data-audio-preview-input>
                            <div class="audio-preview-shell audio-preview-shell--compact" data-audio-preview-shell hidden>
                                <span class="audio-preview-label">Nghe thử</span>
                                <audio controls preload="none" data-audio-preview-player></audio>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">💡 Gợi ý</label>
                    <input type="text" name="cards[${index}][hint]"
                           class="card-input-input"
                           placeholder="Một gợi ý ngắn nếu cần...">
                </div>
                <div class="card-input-options">
                    <button type="button" class="card-input-remove" data-card-input-remove aria-label="Remove card">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        return template;
    },

    renumberCards() {
        const rows = this.list.querySelectorAll('[data-card-input-row]');
        rows.forEach((row, index) => {
            const numberEl = row.querySelector('.card-input-number');
            const frontInput = row.querySelector('textarea[name*="[front]"]');
            const backInput = row.querySelector('textarea[name*="[back]"]');
            const imageInput = row.querySelector('input[name*="[image_url]"]');
            const audioInput = row.querySelector('input[name*="[audio_url]"]');
            const hintInput = row.querySelector('input[name*="[hint]"]');

            numberEl.textContent = index + 1;
            if (frontInput) frontInput.name = `cards[${index}][front]`;
            if (backInput) backInput.name = `cards[${index}][back]`;
            if (imageInput) imageInput.name = `cards[${index}][image_url]`;
            if (audioInput) audioInput.name = `cards[${index}][audio_url]`;
            if (hintInput) hintInput.name = `cards[${index}][hint]`;
        });
    },

    updateUI() {
        if (this.countDisplay) {
            this.countDisplay.textContent = this.cardCount;
        }

        // Show/hide remove buttons based on card count
        const rows = this.list.querySelectorAll('[data-card-input-row]');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('[data-card-input-remove]');
            if (removeBtn) {
                removeBtn.style.display = this.cardCount > this.minCards ? 'flex' : 'none';
            }
        });

        // Disable add button if max reached
        this.addBtn.disabled = this.cardCount >= this.maxCards;
        if (this.cardCount >= this.maxCards) {
            this.addBtn.style.opacity = '0.5';
            this.addBtn.style.cursor = 'not-allowed';
        } else {
            this.addBtn.style.opacity = '';
            this.addBtn.style.cursor = '';
        }
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    DynamicCardInput.init();
});
</script>
@endpush
