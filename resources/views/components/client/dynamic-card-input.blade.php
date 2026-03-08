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
                    <label class="card-input-label">Front</label>
                    <textarea name="cards[0][front]" rows="2"
                              class="card-input-textarea"
                              placeholder="Enter the question or term..."
                              required></textarea>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">Back</label>
                    <textarea name="cards[0][back]" rows="2"
                              class="card-input-textarea"
                              placeholder="Enter the answer or definition..."
                              required></textarea>
                </div>
                <div class="card-input-row-optional">
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">📷 Image URL (optional)</label>
                        <input type="url" name="cards[0][image_url]"
                               class="card-input-input"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">🎵 Audio URL (optional)</label>
                        <input type="url" name="cards[0][audio_url]"
                               class="card-input-input"
                               placeholder="https://example.com/audio.mp3">
                    </div>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">💡 Hint (optional)</label>
                    <input type="text" name="cards[0][hint]"
                           class="card-input-input"
                           placeholder="A hint for the answer...">
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
            <span>Add Another Card</span>
        </button>
        <span class="card-count-info">
            <span data-card-count>1</span> / {{ $maxCards }} cards
        </span>
    </div>
</div>

@push('styles')
<style>
/* Dynamic Card Input Styles */
.dynamic-card-input {
    width: 100%;
}

.card-inputs-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1rem;
}

.card-input-row {
    display: flex;
    gap: 1rem;
    animation: cardSlideIn 0.2s ease;
}

@keyframes cardSlideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-input-number {
    display: flex;
    align-items: flex-start;
    padding-top: 1.75rem;
    font-weight: 700;
    font-size: 1rem;
    color: #9ca3af;
    min-width: 32px;
    text-align: center;
}

.card-input-fields {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.card-input-field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.card-input-row-optional {
    display: flex;
    gap: 0.75rem;
}

.card-input-field-half {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.card-input-label {
    font-weight: 500;
    font-size: 0.8125rem;
    color: #6b7280;
}

.card-input-textarea,
.card-input-input {
    width: 100%;
    padding: 0.625rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-family: inherit;
    transition: all 0.2s;
}

.card-input-textarea {
    resize: vertical;
    min-height: 60px;
}

.card-input-input {
    height: 42px;
}

.card-input-textarea:focus,
.card-input-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.card-input-options {
    display: flex;
    justify-content: flex-end;
    padding-top: 0.25rem;
}

.card-input-remove {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.375rem;
    color: #dc2626;
    cursor: pointer;
    transition: all 0.2s;
}

.card-input-remove:hover {
    background: #fee2e2;
    border-color: #fca5a5;
}

.card-input-remove svg {
    width: 16px;
    height: 16px;
}

.card-input-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: #f9fafb;
    border: 1px dashed #d1d5db;
    border-radius: 0.5rem;
}

.card-input-add {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    color: #374151;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
}

.card-input-add:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.card-input-add svg {
    width: 18px;
    height: 18px;
}

.card-count-info {
    font-size: 0.8125rem;
    color: #6b7280;
}

.card-count-info [data-card-count] {
    font-weight: 600;
    color: #4f46e5;
}

@media (max-width: 640px) {
    .card-input-row {
        flex-direction: column;
        gap: 0.5rem;
    }

    .card-input-number {
        padding-top: 0;
        padding-bottom: 0.5rem;
        text-align: left;
    }

    .card-input-fields {
        width: 100%;
    }

    .card-input-row-optional {
        flex-direction: column;
        gap: 0.75rem;
    }

    .card-input-field-half {
        width: 100%;
    }
}
</style>
@endpush

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
            Toast.warning(`Maximum ${this.maxCards} cards allowed`);
            return;
        }

        const row = this.createCardRow(this.cardCount);
        this.list.appendChild(row);
        this.cardCount++;
        this.updateUI();

        // Focus the new input
        const newTextarea = row.querySelector('.card-input-textarea');
        newTextarea?.focus();
    },

    removeCard(btn) {
        if (this.cardCount <= this.minCards) {
            Toast.warning(`Minimum ${this.minCards} card${this.minCards > 1 ? 's' : ''} required`);
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
                    <label class="card-input-label">Front</label>
                    <textarea name="cards[${index}][front]" rows="2"
                              class="card-input-textarea"
                              placeholder="Enter the question or term..."
                              required></textarea>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">Back</label>
                    <textarea name="cards[${index}][back]" rows="2"
                              class="card-input-textarea"
                              placeholder="Enter the answer or definition..."
                              required></textarea>
                </div>
                <div class="card-input-row-optional">
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">📷 Image URL (optional)</label>
                        <input type="url" name="cards[${index}][image_url]"
                               class="card-input-input"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="card-input-field card-input-field-half">
                        <label class="card-input-label">🎵 Audio URL (optional)</label>
                        <input type="url" name="cards[${index}][audio_url]"
                               class="card-input-input"
                               placeholder="https://example.com/audio.mp3">
                    </div>
                </div>
                <div class="card-input-field">
                    <label class="card-input-label">💡 Hint (optional)</label>
                    <input type="text" name="cards[${index}][hint]"
                           class="card-input-input"
                           placeholder="A hint for the answer...">
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
