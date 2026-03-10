import confetti from 'canvas-confetti';
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
            <button class="toast-close" aria-label="Đóng thông báo">&times;</button>
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

const FlashToast = {
    init() {
        const flashToasts = document.querySelectorAll('[data-flash-toast]');
        if (!flashToasts.length) return;

        flashToasts.forEach((node) => {
            const message = node.dataset.flashToastMessage;
            const type = node.dataset.flashToastType || 'info';

            if (!message) {
                return;
            }

            Toast.show(message, type);
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Client App Layout (Sidebar + Topbar)
// ───────────────────────────────────────────────────────────────────────────────

const ClientApp = {
    init() {
        this.desktopMediaQuery = window.matchMedia('(min-width: 1025px)');
        this.desktopCollapsed = false;
        this.initSidebar();
        this.initTopbar();
        this.initMobileMenu();
        this.syncSidebarForViewport();

        this.desktopMediaQuery.addEventListener('change', () => {
            this.syncSidebarForViewport();
        });
    },

    initSidebar() {
        const sidebar = document.querySelector('.client-sidebar');
        const mainWrapper = document.querySelector('.client-main-wrapper');
        const toggleBtn = document.querySelector('[data-sidebar-toggle]');

        this.sidebar = sidebar;
        this.mainWrapper = mainWrapper;

        toggleBtn?.addEventListener('click', () => {
            if (!this.isDesktopViewport()) {
                return;
            }

            this.desktopCollapsed = !this.desktopCollapsed;
            this.applyDesktopSidebarState();
        });
    },

    initTopbar() {
        // User dropdown
        const dropdown = document.querySelector('[data-user-dropdown]');
        const toggleBtn = dropdown?.querySelector('[data-dropdown-toggle]');
        const dropdownMenu = dropdown?.querySelector('.dropdown-menu');
        const dropdownBackdrop = dropdown?.querySelector('[data-dropdown-backdrop]');
        const mobileDropdownMediaQuery = window.matchMedia('(max-width: 640px)');

        const placeDropdownInViewport = () => {
            if (!mobileDropdownMediaQuery.matches || !dropdownMenu || !dropdownBackdrop) {
                return;
            }

            if (dropdownBackdrop.parentElement !== document.body) {
                document.body.appendChild(dropdownBackdrop);
            }

            if (dropdownMenu.parentElement !== document.body) {
                document.body.appendChild(dropdownMenu);
            }
        };

        const restoreDropdownPlacement = () => {
            if (!dropdown || !dropdownMenu || !dropdownBackdrop) {
                return;
            }

            if (dropdownBackdrop.parentElement !== dropdown) {
                dropdown.insertBefore(dropdownBackdrop, dropdownMenu.parentElement === dropdown ? dropdownMenu : null);
            }

            if (dropdownMenu.parentElement !== dropdown) {
                dropdown.appendChild(dropdownMenu);
            }
        };

        const syncDropdownPlacement = () => {
            if (mobileDropdownMediaQuery.matches) {
                placeDropdownInViewport();
                return;
            }

            restoreDropdownPlacement();
        };

        const syncMobileDropdownState = (isOpen) => {
            document.body.classList.toggle('mobile-dropdown-open', isOpen && mobileDropdownMediaQuery.matches);
            dropdownMenu?.classList.toggle('is-open', isOpen);
            dropdownBackdrop?.classList.toggle('is-open', isOpen);
        };

        const closeDropdown = () => {
            dropdown?.classList.remove('active');
            toggleBtn?.setAttribute('aria-expanded', 'false');
            syncMobileDropdownState(false);
        };

        const openDropdown = () => {
            dropdown?.classList.add('active');
            toggleBtn?.setAttribute('aria-expanded', 'true');
            syncMobileDropdownState(true);
        };

        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const expanded = toggleBtn.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                closeDropdown();
                return;
            }

            syncDropdownPlacement();
            openDropdown();
        });

        dropdownBackdrop?.addEventListener('click', closeDropdown);
        dropdownMenu?.addEventListener('click', (e) => e.stopPropagation());

        // Close dropdown on outside click
        document.addEventListener('click', closeDropdown);

        syncDropdownPlacement();

        mobileDropdownMediaQuery.addEventListener('change', () => {
            if (!mobileDropdownMediaQuery.matches) {
                document.body.classList.remove('mobile-dropdown-open');
            }

            syncDropdownPlacement();
            syncMobileDropdownState(dropdown?.classList.contains('active') ?? false);
        });
    },

    initMobileMenu() {
        const layout = document.querySelector('[data-client-app]');
        const toggleBtn = document.querySelector('[data-mobile-menu-toggle]');
        const sidebar = document.querySelector('.client-sidebar');
        const overlay = document.querySelector('[data-sidebar-overlay]');

        this.layout = layout;
        this.overlay = overlay;

        toggleBtn?.addEventListener('click', () => {
            if (this.isDesktopViewport()) {
                return;
            }

            const shouldOpen = !sidebar.classList.contains('active');
            if (shouldOpen) {
                this.openMobileSidebar();
                return;
            }

            this.closeMobileSidebar();
        });

        overlay?.addEventListener('click', () => this.closeMobileSidebar());
    },

    isDesktopViewport() {
        return this.desktopMediaQuery.matches;
    },

    applyDesktopSidebarState() {
        if (!this.sidebar || !this.mainWrapper || !this.isDesktopViewport()) {
            return;
        }

        this.sidebar.classList.toggle('collapsed', this.desktopCollapsed);
        this.mainWrapper.classList.toggle('collapsed', this.desktopCollapsed);
    },

    openMobileSidebar() {
        if (!this.sidebar || !this.layout) {
            return;
        }

        this.sidebar.classList.remove('collapsed');
        this.mainWrapper?.classList.remove('collapsed');
        this.sidebar.classList.add('active');
        this.layout.classList.add('sidebar-open');
        document.body.style.overflow = 'hidden';
    },

    closeMobileSidebar() {
        if (!this.sidebar || !this.layout) {
            return;
        }

        this.sidebar.classList.remove('active');
        this.layout.classList.remove('sidebar-open');
        document.body.style.overflow = '';

        if (this.isDesktopViewport()) {
            this.applyDesktopSidebarState();
        }
    },

    syncSidebarForViewport() {
        if (!this.sidebar || !this.layout) {
            return;
        }

        if (this.isDesktopViewport()) {
            this.closeMobileSidebar();
            this.applyDesktopSidebarState();
            return;
        }

        this.sidebar.classList.remove('active', 'collapsed');
        this.mainWrapper?.classList.remove('collapsed');
        this.layout.classList.remove('sidebar-open');
        document.body.style.overflow = '';
    },

    syncDueCount(count) {
        const reminder = document.querySelector('[data-study-reminder]');
        const countNode = document.querySelector('[data-due-count]');
        const labelNode = document.querySelector('[data-due-label]');

        if (!reminder || !countNode || !labelNode || typeof count !== 'number') {
            return;
        }

        countNode.textContent = count;

        if (count > 0) {
            reminder.classList.remove('study-reminder--idle');
            labelNode.textContent = 'thẻ đến hạn hôm nay';
        } else {
            reminder.classList.add('study-reminder--idle');
            labelNode.textContent = 'Hàng chờ hôm nay đã sạch';
        }
    }
};

const ConfirmDialog = {
    modal: null,
    title: null,
    message: null,
    submitButton: null,
    cancelButton: null,
    pendingAction: null,

    init() {
        this.modal = document.getElementById('confirmActionModal');
        if (!this.modal) return;

        this.title = document.getElementById('confirmActionTitle');
        this.message = document.getElementById('confirmActionMessage');
        this.submitButton = this.modal.querySelector('[data-confirm-submit]');
        this.cancelButton = this.modal.querySelector('[data-confirm-cancel]');

        this.submitButton?.addEventListener('click', () => {
            const action = this.pendingAction;
            this.close();
            action?.();
        });

        this.cancelButton?.addEventListener('click', () => this.close());

        this.modal.querySelectorAll('[data-modal-backdrop], [data-modal-close]').forEach((node) => {
            node.addEventListener('click', () => this.close());
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && this.modal?.classList.contains('active')) {
                this.close();
            }
        });
    },

    open({ title, message, confirmLabel = 'Xác nhận' }, onConfirm) {
        if (!this.modal) {
            if (confirm(message || title || 'Bạn có chắc muốn tiếp tục?')) {
                onConfirm?.();
            }
            return;
        }

        this.pendingAction = onConfirm;
        if (this.title) this.title.textContent = title || 'Xác nhận thao tác';
        if (this.message) this.message.textContent = message || 'Hành động này có thể ảnh hưởng đến dữ liệu hiện tại.';
        if (this.submitButton) this.submitButton.textContent = confirmLabel;

        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    },

    close() {
        if (!this.modal) return;

        this.modal.classList.remove('active');
        document.body.style.overflow = '';
        this.pendingAction = null;
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
        if (form && confirm('Bạn có chắc muốn đăng xuất?')) {
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

        this.cards = JSON.parse(this.studyRoom.dataset.cards || '[]');

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

        console.log('StudyRoom.initControlButtons:', { form, buttons: buttons.length, resultInput });

        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (this.isSubmitting) return;

                const result = btn.dataset.result;
                console.log('Control button clicked:', result);
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
                Toast.warning('Vui lòng nhập đáp án trước khi kiểm tra');
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
                this.showNextCard(result);
            }, 300);
        } catch (error) {
            console.error('Error submitting result:', error);
            Toast.error('Không thể lưu tiến độ học');
        } finally {
            this.setSubmittingState(false);
        }
    },

    showRatingFeedback(result) {
        const feedbackMap = {
            'again': { message: 'Ôn lại', color: '#ef4444' },
            'hard': { message: 'Khó', color: '#f59e0b' },
            'good': { message: 'Ổn', color: '#3b82f6' },
            'easy': { message: 'Dễ', color: '#10b981' }
        };

        const feedback = feedbackMap[result] || { message: 'Rated!', color: '#6b7280' };

        const existingFeedback = this.studyRoom.querySelector('[data-study-feedback]');
        existingFeedback?.remove();

        const feedbackChip = document.createElement('div');
        feedbackChip.className = 'study-feedback-chip';
        feedbackChip.dataset.studyFeedback = 'true';
        feedbackChip.dataset.result = result;
        feedbackChip.style.setProperty('--feedback-color', feedback.color);
        feedbackChip.textContent = feedback.message;

        this.studyRoom.appendChild(feedbackChip);

        requestAnimationFrame(() => {
            feedbackChip.classList.add('is-visible');
        });

        setTimeout(() => {
            feedbackChip.classList.remove('is-visible');
            setTimeout(() => feedbackChip.remove(), 220);
        }, 520);
    },

    showNextCard(result = null) {
        // Reset flip state
        this.isFlipped = false;

        // Get current card before moving
        const currentCard = this.cards[this.currentIndex];

        // If user chose "Again" or "Hard", add current card to the end for review
        if (result === 'again' || result === 'hard') {
            if (currentCard) {
                this.cards.push(currentCard);
                this.totalCards = this.cards.length;
            }
        }

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
        const progressMeter = this.studyRoom.querySelector('.study-progress-meter');
        const currentNum = this.studyRoom.querySelector('.current-num');
        const totalNum = this.studyRoom.querySelector('.total-num');

        // Update total cards count in case cards were added for review
        if (totalNum) {
            totalNum.textContent = this.cards.length;
        }

        if (progressFill) {
            progressFill.style.width = `${((this.currentIndex + 1) / this.cards.length) * 100}%`;
        }
        if (progressMeter) {
            progressMeter.value = ((this.currentIndex + 1) / this.cards.length) * 100;
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
        const hintText = this.studyRoom.querySelector('.flip-card-front .card-hint-text');

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
        const totalReviewed = this.cards.length;
        const message = this.deckId
            ? `Bạn đã lưu tiến độ cho toàn bộ ${totalReviewed} thẻ trong deck này.`
            : `Bạn đã hoàn thành ${totalReviewed} thẻ trong phiên ôn tập này.`;
        const backLabel = this.deckId ? 'Quay lại chi tiết deck' : 'Quay lại dashboard';
        
        // Haptic Feedback (if supported) & Sound
        if (navigator.vibrate) {
            navigator.vibrate([100, 50, 100, 50, 200]);
        }
        this.playCompletionSound();
        
        // Fire Confetti!
        this.fireConfetti();

        studyRoom.innerHTML = `
            <div class="study-complete">
                <div class="complete-icon">🎉</div>
                <h2>Hoàn thành phiên ôn tập</h2>
                <p>${message}</p>
                <div class="complete-actions">
                    <a href="${this.restartUrl}" class="btn btn-primary">Ôn lại lần nữa</a>
                    <a href="${this.backUrl}" class="btn btn-secondary">${backLabel}</a>
                </div>
            </div>
        `;
    },

    fireConfetti() {
        var duration = 3 * 1000;
        var animationEnd = Date.now() + duration;
        var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            var particleCount = 50 * (timeLeft / duration);
            
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
        }, 250);
    },
    
    playCompletionSound() {
        try {
            // Using a simple Web Audio API oscillator for a synthesized "Tada/Success" sound
            // to avoid needing external audio assets immediately, while still providing the haptic sound.
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.type = 'sine';
            
            // Success Arpeggio
            oscillator.frequency.setValueAtTime(523.25, audioCtx.currentTime); // C5
            oscillator.frequency.setValueAtTime(659.25, audioCtx.currentTime + 0.1); // E5
            oscillator.frequency.setValueAtTime(783.99, audioCtx.currentTime + 0.2); // G5
            oscillator.frequency.setValueAtTime(1046.50, audioCtx.currentTime + 0.3); // C6
            
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.8);
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.8);
        } catch (e) {
            console.log("Audio not supported or blocked");
        }
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
                Toast.error('Không thể lưu tiến độ học');
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
        const feedback = isCorrect ? 'Chính xác' : `Chưa đúng. Đáp án là: ${correctAnswer}`;
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
                Toast.error('Không thể lưu tiến độ học');
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
            throw new Error(data.message || 'Không thể lưu tiến độ học');
        }

        if (data.back_url) {
            this.backUrl = data.back_url;
        }

        if (data.restart_url) {
            this.restartUrl = data.restart_url;
        }

        if (typeof data.due_count === 'number') {
            ClientApp.syncDueCount(data.due_count);
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
        const deleteBtns = document.querySelectorAll('button[type="submit"][data-confirm-message], button[type="submit"][data-confirm]');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const form = btn.closest('form');
                if (!form) return;

                e.preventDefault();

                ConfirmDialog.open({
                    title: btn.dataset.confirmTitle || 'Xác nhận thao tác',
                    message: btn.dataset.confirmMessage || btn.dataset.confirm || 'Bạn có chắc muốn tiếp tục?',
                    confirmLabel: btn.dataset.confirmLabel || 'Xác nhận'
                }, () => form.submit());
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
    FlashToast.init();
    ClientApp.init();
    ConfirmDialog.init();
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
