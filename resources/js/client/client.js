/**
 * Client JavaScript Module
 * Handles client portal functionality
 */

// ───────────────────────────────────────────────────────────────────────────────
// Client Navigation
// ───────────────────────────────────────────────────────────────────────────────

const ClientNav = {
    init() {
        this.header = document.querySelector('.client-header');
        if (!this.header) return;
    },

    logout(e) {
        e.preventDefault();
        const form = e.target.closest('form');
        if (form && confirm('Are you sure you want to logout?')) {
            form.submit();
        }
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Deck Cards
// ───────────────────────────────────────────────────────────────────────────────

const DeckCards = {
    init() {
        this.cards = document.querySelectorAll('.deck-card');
        this.bindEvents();
    },

    bindEvents() {
        // Add hover effects via JS if needed
        this.cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('hover');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('hover');
            });
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Study Session (Future implementation)
// ───────────────────────────────────────────────────────────────────────────────

const StudySession = {
    init() {
        this.sessionContainer = document.querySelector('[data-study-session]');
        if (!this.sessionContainer) return;

        this.currentCard = null;
        this.cards = [];
        this.currentIndex = 0;
        this.bindEvents();
    },

    bindEvents() {
        const nextBtn = this.sessionContainer?.querySelector('[data-study-next]');
        const prevBtn = this.sessionContainer?.querySelector('[data-study-prev]');
        const flipBtn = this.sessionContainer?.querySelector('[data-study-flip]');

        nextBtn?.addEventListener('click', () => this.nextCard());
        prevBtn?.addEventListener('click', () => this.prevCard());
        flipBtn?.addEventListener('click', () => this.flipCard());
    },

    nextCard() {
        if (this.currentIndex < this.cards.length - 1) {
            this.currentIndex++;
            this.showCard(this.currentIndex);
        }
    },

    prevCard() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.showCard(this.currentIndex);
        }
    },

    flipCard() {
        const cardEl = this.sessionContainer?.querySelector('.study-card');
        cardEl?.classList.toggle('flipped');
    },

    showCard(index) {
        // Implementation for showing specific card
        this.currentIndex = index;
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Progress Summary
// ───────────────────────────────────────────────────────────────────────────────

const ProgressSummary = {
    init() {
        this.summary = document.querySelector('[data-progress-summary]');
        if (!this.summary) return;

        this.animateNumbers();
    },

    animateNumbers() {
        const numbers = this.summary.querySelectorAll('[data-count]');
        numbers.forEach(el => {
            const target = parseInt(el.dataset.count);
            const duration = 1000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.textContent = target;
                    clearInterval(timer);
                } else {
                    el.textContent = Math.floor(current);
                }
            }, 16);
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Deck Detail
// ───────────────────────────────────────────────────────────────────────────────

const DeckDetail = {
    init() {
        this.initRatingSelector();
        this.initCharCounter();
        this.initDeleteConfirmation();
        this.initEditModal();
        this.initAddCardModal();
    },

    initRatingSelector() {
        const ratingContainers = document.querySelectorAll('[data-rating-selector]');
        ratingContainers.forEach(container => {
            const stars = container.querySelectorAll('[data-rating-value]');
            const input = container.querySelector('[data-rating-input]');

            if (!stars.length || !input) return;

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = parseInt(star.dataset.ratingValue);
                    input.value = value;
                    this.updateStars(stars, value);
                });

                star.addEventListener('mouseenter', () => {
                    const value = parseInt(star.dataset.ratingValue);
                    this.updateStars(stars, value);
                });

                star.addEventListener('mouseleave', () => {
                    const currentValue = parseInt(input.value) || 0;
                    this.updateStars(stars, currentValue);
                });
            });
        });
    },

    updateStars(stars, value) {
        stars.forEach(star => {
            const starValue = parseInt(star.dataset.ratingValue);
            if (starValue <= value) {
                star.classList.add('review-form__star--active');
            } else {
                star.classList.remove('review-form__star--active');
            }
        });
    },

    initCharCounter() {
        const textareas = document.querySelectorAll('.review-form__textarea');
        textareas.forEach(textarea => {
            const counter = textarea.parentElement.querySelector('[data-char-count]');
            if (!counter) return;

            // Initialize count
            counter.textContent = textarea.value.length;

            textarea.addEventListener('input', () => {
                counter.textContent = textarea.value.length;
            });
        });
    },

    initDeleteConfirmation() {
        const deleteBtns = document.querySelectorAll('[data-deck-delete]');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const message = btn.dataset.confirmMessage || 'Delete this deck?';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    },

    initEditModal() {
        const editBtns = document.querySelectorAll('[data-deck-edit]');
        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // TODO: Implement edit modal
                alert('Edit modal to be implemented');
            });
        });
    },

    initAddCardModal() {
        const addCardBtns = document.querySelectorAll('[data-deck-add-card]');
        addCardBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // TODO: Implement add card modal
                alert('Add card modal to be implemented');
            });
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    ClientNav.init();
    DeckCards.init();
    StudySession.init();
    ProgressSummary.init();
    DeckDetail.init();

    // Logout confirmation
    const logoutBtns = document.querySelectorAll('[data-logout]');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', ClientNav.logout);
    });
});
