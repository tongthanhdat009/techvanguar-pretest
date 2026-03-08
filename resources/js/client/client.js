/**
 * Client JavaScript Module
 * Handles client portal functionality
 */

// ───────────────────────────────────────────────────────────────────────────────
// Toast Notifications
// ───────────────────────────────────────────────────────────────────────────────

const Toast = {
    container: null,

    init() {
        this.container = document.querySelector('[data-toasts-container]');
        if (!this.container) return;
    },

    show(message, type = 'info') {
        if (!this.container) this.init();
        if (!this.container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-icon">${this.getIcon(type)}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close" aria-label="Close">&times;</button>
        `;

        this.container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => toast.classList.add('show'));

        // Close button
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.hide(toast));

        // Auto dismiss
        setTimeout(() => this.hide(toast), 3000);
    },

    hide(toast) {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    },

    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        return icons[type] || icons.info;
    },

    success(message) { this.show(message, 'success'); },
    error(message) { this.show(message, 'error'); },
    warning(message) { this.show(message, 'warning'); },
    info(message) { this.show(message, 'info'); }
};

// ───────────────────────────────────────────────────────────────────────────────
// Client App Layout (Sidebar + Topbar)
// ───────────────────────────────────────────────────────────────────────────────

const ClientApp = {
    init() {
        this.initSidebar();
        this.initTopbar();
        this.initMobileMenu();
    },

    initSidebar() {
        const sidebar = document.querySelector('.client-sidebar');
        const toggleBtn = document.querySelector('[data-sidebar-toggle]');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    },

    initTopbar() {
        // User dropdown
        const dropdown = document.querySelector('[data-user-dropdown]');
        const toggleBtn = dropdown?.querySelector('[data-dropdown-toggle]');

        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const expanded = toggleBtn.getAttribute('aria-expanded') === 'true';
            toggleBtn.setAttribute('aria-expanded', !expanded);
            dropdown.classList.toggle('active', !expanded);
        });

        // Close dropdown on outside click
        document.addEventListener('click', () => {
            dropdown?.classList.remove('active');
            toggleBtn?.setAttribute('aria-expanded', 'false');
        });

        dropdown?.addEventListener('click', (e) => e.stopPropagation());
    },

    initMobileMenu() {
        const layout = document.querySelector('[data-client-app]');
        const toggleBtn = document.querySelector('[data-mobile-menu-toggle]');
        const sidebar = document.querySelector('.client-sidebar');
        const overlay = document.querySelector('.client-app-layout::before');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            layout?.classList.toggle('sidebar-open');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        });

        // Close on overlay click
        layout?.addEventListener('click', (e) => {
            if (e.target === layout || e.target.classList.contains('sidebar-overlay')) {
                sidebar.classList.remove('active');
                layout.classList.remove('sidebar-open');
                document.body.style.overflow = '';
            }
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Client Navigation (Legacy)
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
// Study Room
// ───────────────────────────────────────────────────────────────────────────────

const StudyRoom = {
    init() {
        this.studyRoom = document.querySelector('[data-study-room]');
        if (!this.studyRoom) return;

        this.deckId = this.studyRoom.dataset.deckId;
        this.mode = this.studyRoom.dataset.mode || 'flip';
        this.currentIndex = parseInt(this.studyRoom.dataset.currentIndex) || 0;
        this.totalCards = parseInt(this.studyRoom.dataset.totalCards, 10) || 0;
        this.progressUrl = this.studyRoom.dataset.progressUrl || window.studyRoomData?.progressUrl || '/client/study/progress';
        this.backUrl = this.studyRoom.dataset.backUrl || window.studyRoomData?.backUrl || '/client/dashboard';
        this.restartUrl = this.studyRoom.dataset.restartUrl || window.studyRoomData?.restartUrl || window.location.href;
        this.csrfToken = this.studyRoom.dataset.csrfToken || window.studyRoomData?.csrfToken || '';
        this.isFlipped = false;
        this.isSubmitting = false;

        // Get cards data from embedded data or parse JSON
        if (window.studyRoomData) {
            this.cards = window.studyRoomData.cards || [];
        } else {
            this.cards = JSON.parse(this.studyRoom.dataset.cards || '[]');
        }

        this.initFlipCard();
        this.initControlButtons();
        this.initKeyboardShortcuts();
        this.initModeSelector();
        this.initMultipleChoice();
        this.initTypedInput();
    },

    initFlipCard() {
        if (this.mode !== 'flip') return;

        const flipCard = this.studyRoom.querySelector('[data-flip-card]');
        if (!flipCard) return;

        // Click to flip
        flipCard.addEventListener('click', () => {
            if (!this.isFlipped) {
                this.flip();
            }
        });
    },

    initControlButtons() {
        if (this.mode !== 'flip') return;

        const form = this.studyRoom.querySelector('[data-control-form]');
        const buttons = this.studyRoom.querySelectorAll('.control-btn[data-result]');
        const resultInput = this.studyRoom.querySelector('[data-result-input]');

        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.isSubmitting) return;

                const result = btn.dataset.result;
                if (resultInput) {
                    resultInput.value = result;
                }

                this.submitResult(form, result);
            });
        });
    },

    initKeyboardShortcuts() {
        if (this.mode !== 'flip') return;

        document.addEventListener('keydown', (e) => {
            // Only handle if we're in study room
            if (!this.studyRoom) return;

            // Space to flip
            if (e.code === 'Space' && !this.isFlipped) {
                e.preventDefault();
                this.flip();
                return;
            }

            // Number keys to rate (after flip)
            if (this.isFlipped) {
                const keyMap = { '1': 'again', '2': 'hard', '3': 'good', '4': 'easy' };
                if (keyMap[e.key]) {
                    e.preventDefault();
                    const form = this.studyRoom.querySelector('[data-control-form]');
                    const resultInput = this.studyRoom.querySelector('[data-result-input]');
                    if (resultInput) resultInput.value = keyMap[e.key];
                    this.submitResult(form, keyMap[e.key]);
                }
            }
        });
    },

    initModeSelector() {
        const modeBtns = this.studyRoom.querySelectorAll('.mode-btn[data-mode]');
        modeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const newMode = btn.dataset.mode;
                // Reload with new mode
                const url = new URL(window.location);
                url.searchParams.set('mode', newMode);
                window.location.href = url.toString();
            });
        });
    },

    initMultipleChoice() {
        if (this.mode !== 'multiple-choice') return;

        const choices = this.studyRoom.querySelectorAll('.mcq-choice[data-choice]');
        choices.forEach(choice => {
            choice.addEventListener('click', () => {
                if (choice.classList.contains('correct') || choice.classList.contains('incorrect')) return;

                const choiceIndex = parseInt(choice.dataset.choice);
                this.submitMultipleChoice(choiceIndex);
            });
        });
    },

    initTypedInput() {
        if (this.mode !== 'typed') return;

        const submitBtn = this.studyRoom.querySelector('[data-typed-submit]');
        const input = this.studyRoom.querySelector('[data-typed-input]');

        submitBtn?.addEventListener('click', () => {
            const answer = input?.value.trim();
            if (!answer) {
                Toast.warning('Please enter your answer');
                return;
            }
            this.submitTypedAnswer(answer);
        });

        // Submit on Ctrl+Enter
        input?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                submitBtn?.click();
            }
        });
    },

    flip() {
        if (this.isFlipped) return;
        this.isFlipped = true;

        const flipCard = this.studyRoom.querySelector('[data-flip-card]');
        const controls = this.studyRoom.querySelector('[data-study-controls]');

        flipCard?.classList.add('flipped');
        controls?.style.removeProperty('display');
    },

    async submitResult(form, result) {
        if (this.isSubmitting) return;
        this.setSubmittingState(true);

        try {
            await this.persistProgress(result, form?.action);
            this.showRatingFeedback(result);
            setTimeout(() => {
                this.showNextCard();
            }, 300);
        } catch (error) {
            console.error('Error submitting result:', error);
            Toast.error('Failed to save progress');
        } finally {
            this.setSubmittingState(false);
        }
    },

    showRatingFeedback(result) {
        const feedbackMap = {
            'again': { message: 'Again!', color: '#ef4444' },
            'hard': { message: 'Hard', color: '#f59e0b' },
            'good': { message: 'Good!', color: '#3b82f6' },
            'easy': { message: 'Easy!', color: '#10b981' }
        };

        const feedback = feedbackMap[result] || { message: 'Rated!', color: '#6b7280' };

        // Show temporary feedback overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: ${feedback.color};
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            z-index: 9999;
            animation: fadeIn 0.1s ease;
        `;
        overlay.textContent = feedback.message;
        document.body.appendChild(overlay);

        setTimeout(() => {
            overlay.remove();
        }, 300);
    },

    showNextCard() {
        // Reset flip state
        this.isFlipped = false;

        // Move to next card
        this.currentIndex++;

        // Check if we've completed all cards
        if (this.currentIndex >= this.cards.length) {
            this.showCompletionMessage();
            return;
        }

        // Update the display
        this.updateCardDisplay();
    },

    updateCardDisplay() {
        const currentCard = this.cards[this.currentIndex];

        // Update progress bar
        const progressFill = this.studyRoom.querySelector('.progress-fill');
        const currentNum = this.studyRoom.querySelector('.current-num');
        if (progressFill) {
            progressFill.style.width = `${((this.currentIndex + 1) / this.totalCards) * 100}%`;
        }
        if (currentNum) {
            currentNum.textContent = this.currentIndex + 1;
        }

        // Update card content based on mode
        if (this.mode === 'flip') {
            this.updateFlipCardContent(currentCard);
        } else if (this.mode === 'multiple-choice') {
            this.updateMultipleChoiceContent(currentCard);
        } else if (this.mode === 'typed') {
            this.updateTypedContent(currentCard);
        }

        // Reset flip state
        const flipCard = this.studyRoom.querySelector('[data-flip-card]');
        const flipInner = this.studyRoom.querySelector('[data-flip-card-inner]');
        const controls = this.studyRoom.querySelector('[data-study-controls]');
        const cardIndexInput = this.studyRoom.querySelector('[data-card-index-input]');
        const resultInput = this.studyRoom.querySelector('[data-result-input]');

        if (flipCard) {
            flipCard.classList.remove('flipped');
        }
        if (flipInner) {
            flipInner.classList.remove('flipped');
        }
        if (controls) {
            controls.style.display = 'none';
        }
        if (cardIndexInput) {
            cardIndexInput.value = this.currentIndex;
        }
        if (resultInput) {
            resultInput.value = 'again';
        }
    },

    updateFlipCardContent(card) {
        const frontContent = this.studyRoom.querySelector('.flip-card-front .card-content');
        const backContent = this.studyRoom.querySelector('.flip-card-back .card-content');
        const frontImage = this.studyRoom.querySelector('.flip-card-front .card-image');
        const backImage = this.studyRoom.querySelector('.flip-card-back .card-image');
        const frontAudio = this.studyRoom.querySelector('.flip-card-front .card-audio');
        const backAudio = this.studyRoom.querySelector('.flip-card-back .card-audio');
        const hintText = this.studyRoom.querySelector('.card-hint-text');

        // Update text content
        if (frontContent) frontContent.textContent = card.front;
        if (backContent) backContent.textContent = card.back;

        // Update image
        if (frontImage) {
            if (card.image_url) {
                frontImage.innerHTML = `<img src="${card.image_url}" alt="Card image">`;
                frontImage.style.display = 'block';
            } else {
                frontImage.innerHTML = '';
                frontImage.style.display = 'none';
            }
        }
        if (backImage) {
            if (card.image_url) {
                backImage.innerHTML = `<img src="${card.image_url}" alt="Card image">`;
                backImage.style.display = 'block';
            } else {
                backImage.innerHTML = '';
                backImage.style.display = 'none';
            }
        }

        // Update audio
        if (frontAudio) {
            if (card.audio_url) {
                frontAudio.innerHTML = `<audio controls src="${card.audio_url}"></audio>`;
                frontAudio.style.display = 'block';
            } else {
                frontAudio.innerHTML = '';
                frontAudio.style.display = 'none';
            }
        }
        if (backAudio) {
            if (card.audio_url) {
                backAudio.innerHTML = `<audio controls src="${card.audio_url}"></audio>`;
                backAudio.style.display = 'block';
            } else {
                backAudio.innerHTML = '';
                backAudio.style.display = 'none';
            }
        }

        // Update hint
        if (hintText && card.hint) {
            hintText.innerHTML = `<span class="hint-label">💡 Hint:</span> ${card.hint}`;
            hintText.style.display = 'block';
        } else if (hintText) {
            hintText.style.display = 'none';
        }

        // Update flashcard_id in form
        const flashcardIdInput = this.studyRoom.querySelector('input[name="flashcard_id"]');
        if (flashcardIdInput) {
            flashcardIdInput.value = card.id;
        }
    },

    updateMultipleChoiceContent(card) {
        const questionContent = this.studyRoom.querySelector('.mcq-front .card-content');
        const questionImage = this.studyRoom.querySelector('.mcq-front .card-image');
        const questionAudio = this.studyRoom.querySelector('.mcq-front .card-audio');
        const choicesContainer = this.studyRoom.querySelector('[data-mcq-choices]');

        // Update question
        if (questionContent) questionContent.textContent = card.front;

        // Update image
        if (questionImage) {
            if (card.image_url) {
                questionImage.innerHTML = `<img src="${card.image_url}" alt="Card image">`;
                questionImage.style.display = 'block';
            } else {
                questionImage.innerHTML = '';
                questionImage.style.display = 'none';
            }
        }

        // Update audio
        if (questionAudio) {
            if (card.audio_url) {
                questionAudio.innerHTML = `<audio controls src="${card.audio_url}"></audio>`;
                questionAudio.style.display = 'block';
            } else {
                questionAudio.innerHTML = '';
                questionAudio.style.display = 'none';
            }
        }

        // Update choices
        if (choicesContainer) {
            const choices = card.choices || [];
            const letters = ['A', 'B', 'C', 'D'];
            choicesContainer.innerHTML = choices.map((choice, index) => `
                <button class="mcq-choice" data-choice="${index}">
                    <span class="choice-letter">${letters[index]}</span>
                    <span class="choice-text">${choice}</span>
                </button>
            `).join('');

            // Re-attach event listeners
            this.initMultipleChoice();
        }
    },

    updateTypedContent(card) {
        const questionContent = this.studyRoom.querySelector('.typed-front .card-content');
        const questionImage = this.studyRoom.querySelector('.typed-front .card-image');
        const questionAudio = this.studyRoom.querySelector('.typed-front .card-audio');
        const typedInput = this.studyRoom.querySelector('[data-typed-input]');

        // Update question
        if (questionContent) questionContent.textContent = card.front;

        // Update image
        if (questionImage) {
            if (card.image_url) {
                questionImage.innerHTML = `<img src="${card.image_url}" alt="Card image">`;
                questionImage.style.display = 'block';
            } else {
                questionImage.innerHTML = '';
                questionImage.style.display = 'none';
            }
        }

        // Update audio
        if (questionAudio) {
            if (card.audio_url) {
                questionAudio.innerHTML = `<audio controls src="${card.audio_url}"></audio>`;
                questionAudio.style.display = 'block';
            } else {
                questionAudio.innerHTML = '';
                questionAudio.style.display = 'none';
            }
        }

        // Clear input
        if (typedInput) typedInput.value = '';
    },

    showCompletionMessage() {
        const studyRoom = this.studyRoom;
        const message = this.deckId
            ? `You've saved progress for all ${this.totalCards} cards in this deck.`
            : `You've reviewed all ${this.totalCards} cards in this session.`;
        const backLabel = this.deckId ? 'Back to Deck Details' : 'Back to Dashboard';
        studyRoom.innerHTML = `
            <div class="study-complete">
                <div class="complete-icon">🎉</div>
                <h2>Study Session Complete!</h2>
                <p>${message}</p>
                <div class="complete-actions">
                    <a href="${this.restartUrl}" class="btn btn-primary">Study Again</a>
                    <a href="${this.backUrl}" class="btn btn-secondary">${backLabel}</a>
                </div>
            </div>
        `;
    },

    async submitMultipleChoice(choiceIndex) {
        if (this.isSubmitting) return;
        this.setSubmittingState(true);

        const currentCard = this.cards[this.currentIndex];
        const correctAnswer = currentCard.back;
        const choices = this.studyRoom.querySelectorAll('.mcq-choice');

        // Find the correct choice index
        const choicesData = currentCard.choices || [];
        const correctIndex = choicesData.findIndex(c => c === correctAnswer);

        const isCorrect = choiceIndex === correctIndex;

        // Show result
        if (isCorrect) {
            choices[choiceIndex]?.classList.add('correct');
        } else {
            choices[choiceIndex]?.classList.add('incorrect');
            choices[correctIndex]?.classList.add('correct');
        }

        // Submit result and move to next card
        setTimeout(async () => {
            try {
                await this.persistProgress(isCorrect ? 'good' : 'again');
                this.showRatingFeedback(isCorrect ? 'good' : 'again');
                setTimeout(() => {
                    this.showNextCard();
                }, 300);
            } catch (error) {
                console.error('Error submitting result:', error);
                Toast.error('Failed to save progress');
            } finally {
                this.setSubmittingState(false);
            }
        }, 800);
    },

    async submitTypedAnswer(answer) {
        if (this.isSubmitting) return;
        this.setSubmittingState(true);

        const currentCard = this.cards[this.currentIndex];
        const correctAnswer = currentCard.back.trim().toLowerCase();
        const isCorrect = answer.toLowerCase() === correctAnswer;

        // Show feedback
        const feedback = isCorrect ? 'Correct!' : `Incorrect. The answer was: ${correctAnswer}`;
        Toast[isCorrect ? 'success' : 'error'](feedback);

        // Submit result and move to next card
        setTimeout(async () => {
            try {
                await this.persistProgress(isCorrect ? 'good' : 'again');
                this.showRatingFeedback(isCorrect ? 'good' : 'again');
                setTimeout(() => {
                    this.showNextCard();
                }, 300);
            } catch (error) {
                console.error('Error submitting result:', error);
                Toast.error('Failed to save progress');
            } finally {
                this.setSubmittingState(false);
            }
        }, 1200);
    },

    getCurrentCard() {
        return this.cards[this.currentIndex] || null;
    },

    buildProgressFormData(result, status = 'learning') {
        const currentCard = this.getCurrentCard();
        const formData = new FormData();

        if (!currentCard) {
            return formData;
        }

        if (this.csrfToken) {
            formData.append('_token', this.csrfToken);
        }

        formData.append('flashcard_id', currentCard.id);
        formData.append('status', status);
        formData.append('result', result);
        formData.append('study_mode', this.mode);
        formData.append('card_index', this.currentIndex);

        if (this.deckId) {
            formData.append('deck_id', this.deckId);
        }

        return formData;
    },

    async persistProgress(result, url = null) {
        const response = await fetch(url || this.progressUrl, {
            method: 'POST',
            body: this.buildProgressFormData(result),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Failed to save progress');
        }

        if (data.back_url) {
            this.backUrl = data.back_url;
        }

        if (data.restart_url) {
            this.restartUrl = data.restart_url;
        }

        return data;
    },

    setSubmittingState(isSubmitting) {
        this.isSubmitting = isSubmitting;

        this.studyRoom.querySelectorAll('.control-btn, .mcq-choice, [data-typed-submit]').forEach((button) => {
            button.disabled = isSubmitting;
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Study Session (Legacy - for backward compatibility)
// ───────────────────────────────────────────────────────────────────────────────

const StudySession = {
    init() {
        // Delegate to StudyRoom
        StudyRoom.init();
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
        // Modal implementations are handled in deck.blade.php
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

    // Modal implementations are handled in deck.blade.php to avoid conflicts
    // The Modals object in deck.blade.php handles editDeck, addFlashcard, and editFlashcard modals
};

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    Toast.init();
    ClientApp.init();
    ClientNav.init();
    DeckCards.init();
    StudyRoom.init();
    StudySession.init();
    ProgressSummary.init();
    DeckDetail.init();
    DeckSearch.init();

    // Logout confirmation
    const logoutBtns = document.querySelectorAll('[data-logout]');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', ClientNav.logout);
    });
});

// ───────────────────────────────────────────────────────────────────────────────
// Deck Search
// ───────────────────────────────────────────────────────────────────────────────

const DeckSearch = {
    init() {
        const searchInput = document.querySelector('#deckSearch');
        if (!searchInput) return;

        let debounceTimer;

        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            debounceTimer = setTimeout(() => {
                this.search(query);
            }, 300);
        });
    },

    async search(query) {
        if (!query) {
            // Show all decks
            document.querySelectorAll('.deck-card').forEach(card => {
                card.style.display = '';
            });
            return;
        }

        try {
            const response = await fetch(`/client/api/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.filterDecks(query);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    },

    filterDecks(query) {
        const lowerQuery = query.toLowerCase();
        document.querySelectorAll('.deck-card').forEach(card => {
            const title = card.querySelector('.title')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.description')?.textContent.toLowerCase() || '';
            const matches = title.includes(lowerQuery) || description.includes(lowerQuery);
            card.style.display = matches ? '' : 'none';
        });
    }
};
