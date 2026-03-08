@extends('layouts.client-app', ['title' => $deck->title])

@section('content')
    {{-- Hero Info Section --}}
    @include('components.client.deck-hero', ['deck' => $deck])

    {{-- Action Buttons --}}
    @include('components.client.deck-actions', [
        'deck' => $deck,
        'canManageDeck' => $canManageDeck
    ])

    {{-- Flashcards Preview --}}
    @include('components.client.flashcards-table', ['deck' => $deck])

    {{-- Reviews Section --}}
    @include('components.client.reviews-section', [
        'deck' => $deck,
        'reviewForm' => $reviewForm
    ])
@endsection

@stack('modals')
    {{-- Include modals for deck management --}}
    @include('components.client.modals', ['deck' => $deck, 'categories' => ['Language', 'Science', 'History', 'Math', 'Technology', 'Other']])
@endstack

@push('scripts')
    <script>
        // Modal Management
        const Modals = {
            init() {
                this.bindEditDeck();
                this.bindAddFlashcard();
                this.bindEditFlashcard();
                this.bindCloseButtons();
                this.bindBackdropClick();
            },

            openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            },

            closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            },

            bindEditDeck() {
                const editBtn = document.querySelector('[data-deck-edit]');
                if (editBtn) {
                    editBtn.addEventListener('click', () => {
                        this.openModal('editDeckModal');
                    });
                }
            },

            bindAddFlashcard() {
                const addBtn = document.querySelector('[data-deck-add-card]');
                if (addBtn) {
                    addBtn.addEventListener('click', () => {
                        this.openModal('addFlashcardModal');
                    });
                }
            },

            bindEditFlashcard() {
                const editButtons = document.querySelectorAll('[data-flashcard-edit]');
                editButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const cardId = btn.dataset.flashcardEdit;
                        const cardData = JSON.parse(btn.dataset.cardData || '{}');

                        // Populate form
                        document.getElementById('edit-flashcard-id').value = cardId;
                        document.getElementById('edit-card-front').value = cardData.front || '';
                        document.getElementById('edit-card-back').value = cardData.back || '';
                        document.getElementById('edit-card-image').value = cardData.image_url || '';
                        document.getElementById('edit-card-audio').value = cardData.audio_url || '';
                        document.getElementById('edit-card-hint').value = cardData.hint || '';

                        // Set form action
                        const form = document.getElementById('editFlashcardForm');
                        form.action = `/client/flashcards/${cardId}`;

                        this.openModal('editFlashcardModal');
                    });
                });
            },

            bindCloseButtons() {
                const closeButtons = document.querySelectorAll('[data-modal-close]');
                closeButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const modal = btn.closest('.modal');
                        if (modal) {
                            modal.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                    });
                });
            },

            bindBackdropClick() {
                const backdrops = document.querySelectorAll('[data-modal-backdrop]');
                backdrops.forEach(backdrop => {
                    backdrop.addEventListener('click', () => {
                        const modal = backdrop.closest('.modal');
                        if (modal) {
                            modal.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                    });
                });
            }
        };

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            Modals.init();

            if (typeof DeckDetail !== 'undefined') {
                DeckDetail.init();
            }
        });
    </script>
@endpush
