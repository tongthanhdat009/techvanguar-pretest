<x-layouts.client :title="$deck ? 'Study '.$deck->title : 'Study All'">
    <section class="space-y-8">
        <div class="glass-panel">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="section-kicker">Study session</p>
                    <h1 class="display-title mt-3 text-4xl">{{ $deck ? $deck->title : 'All accessible decks' }}</h1>
                    <p class="reading-copy mt-3">Work through one flashcard at a time. Reveal or check the answer first, then rate how difficult recall felt so the scheduler can move you to the next item.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => 'flip']) : route('client.study.all', ['mode' => 'flip']) }}" class="secondary-button">Flip</a>
                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => 'multiple-choice']) : route('client.study.all', ['mode' => 'multiple-choice']) }}" class="secondary-button">Multiple choice</a>
                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => 'typed']) : route('client.study.all', ['mode' => 'typed']) }}" class="secondary-button">Typed</a>
                </div>
            </div>
        </div>

        @if($currentCard)
            @php($flashcard = $currentCard['flashcard'])
            @php($progress = $currentCard['progress'])
            <div class="study-shell" data-study-session>
                <article class="glass-panel">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 pb-4">
                        <div>
                            <p class="section-kicker">{{ str_replace('-', ' ', $mode) }}</p>
                            <p class="mt-2 text-sm text-stone-600">Card {{ $currentIndex + 1 }} of {{ $totalCards }} · {{ $flashcard->deck->title }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($currentIndex > 0)
                                <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => $mode, 'card' => $currentIndex - 1]) : route('client.study.all', ['mode' => $mode, 'card' => $currentIndex - 1]) }}" class="secondary-button">Previous</a>
                            @endif
                            @if($currentIndex + 1 < $totalCards)
                                <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => $mode, 'card' => $currentIndex + 1]) : route('client.study.all', ['mode' => $mode, 'card' => $currentIndex + 1]) }}" class="secondary-button">Skip</a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 space-y-5">
                        @if($flashcard->hint)
                            <div class="border-l-4 border-amber-400 bg-amber-50 px-4 py-3 text-sm text-amber-900">Hint: {{ $flashcard->hint }}</div>
                        @endif

                        @if($mode === 'flip')
                            <div class="flashcard-scene">
                                <div class="flashcard" data-flip-card>
                                    <div class="flashcard-face">
                                        <div>
                                            <p class="section-kicker">Front</p>
                                            <h2 class="display-title mt-6 text-3xl">{{ $flashcard->front_content }}</h2>
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            <button type="button" class="primary-button" data-toggle-answer>Flip card</button>
                                            <span class="text-sm text-stone-500">Use the flip action before grading the card.</span>
                                        </div>
                                    </div>
                                    <div class="flashcard-face flashcard-back bg-stone-900 text-white">
                                        <div>
                                            <p class="section-kicker text-stone-300">Back</p>
                                            <div class="mt-6 text-xl leading-8">{{ $flashcard->back_content }}</div>
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            <button type="button" class="secondary-button border-stone-500 bg-transparent text-white hover:bg-white/10 hover:text-white" data-toggle-answer>Flip back</button>
                                            <span class="text-sm text-stone-300">Your grading controls unlock after the first reveal.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($mode === 'multiple-choice')
                            <div class="study-card p-6">
                                <p class="section-kicker">Prompt</p>
                                <h2 class="display-title mt-5 text-3xl">{{ $flashcard->front_content }}</h2>
                                <div class="mt-6 grid gap-3" data-choice-group>
                                    @foreach($currentCard['choices'] as $choice)
                                        <label class="flex cursor-pointer items-start gap-3 border border-stone-300 bg-white px-4 py-3 text-sm text-stone-700 hover:border-stone-800">
                                            <input type="radio" name="study_choice" value="{{ $choice }}" class="mt-1" data-choice-input>
                                            <span>{{ $choice }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <button type="button" class="primary-button" data-check-choice data-answer="{{ e($flashcard->back_content) }}">Check answer</button>
                                    <span class="text-sm text-stone-500">Choose one option, then reveal whether it matched.</span>
                                </div>
                                <div class="study-response" data-study-response>
                                    <p data-study-feedback></p>
                                </div>
                            </div>
                        @else
                            <div class="study-card p-6">
                                <p class="section-kicker">Prompt</p>
                                <h2 class="display-title mt-5 text-3xl">{{ $flashcard->front_content }}</h2>
                                <textarea class="field-input mt-6" rows="5" placeholder="Type your answer before checking" data-typed-input></textarea>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <button type="button" class="primary-button" data-check-typed data-answer="{{ e($flashcard->back_content) }}">Compare answer</button>
                                    <span class="text-sm text-stone-500">This does a simple text comparison so you can self-evaluate before grading.</span>
                                </div>
                                <div class="study-response" data-study-response>
                                    <p data-study-feedback></p>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-4 text-sm font-semibold text-stone-700">
                            @if($flashcard->image_url)
                                <a href="{{ $flashcard->image_url }}" target="_blank">Open image asset</a>
                            @endif
                            @if($flashcard->audio_url)
                                <a href="{{ $flashcard->audio_url }}" target="_blank">Open audio asset</a>
                            @endif
                        </div>
                    </div>
                </article>

                <aside class="glass-panel">
                    <p class="section-kicker">Scheduler</p>
                    <h2 class="mt-2 section-title">Rate this recall</h2>
                    <div class="mt-6 space-y-2 text-sm text-stone-600">
                        <div>Current status: <span class="font-semibold text-stone-900">{{ ucfirst($progress?->status ?? 'new') }}</span></div>
                        <div>Next review: <span class="font-semibold text-stone-900">{{ $progress?->next_review_at?->format('M d, H:i') ?? 'Not scheduled yet' }}</span></div>
                    </div>

                    <form action="{{ route('client.progress.update', $flashcard) }}" method="POST" class="mt-6 space-y-4" data-review-form>
                        @csrf
                        <input type="hidden" name="study_mode" value="{{ $mode }}">
                        <input type="hidden" name="card_index" value="{{ $currentIndex }}">
                        @if($deck)
                            <input type="hidden" name="study_deck_id" value="{{ $deck->id }}">
                        @endif
                        <div>
                            <label class="field-label">Status</label>
                            <select name="status" class="field-input">
                                <option value="new" @selected(($progress?->status ?? 'new') === 'new')>New</option>
                                <option value="learning" @selected(($progress?->status ?? 'new') === 'learning')>Learning</option>
                                <option value="mastered" @selected(($progress?->status ?? 'new') === 'mastered')>Mastered</option>
                            </select>
                        </div>

                        <div class="border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-600" data-review-gate>
                            Reveal or check the answer to unlock grading controls.
                        </div>

                        <div class="grid gap-2 sm:grid-cols-2" data-review-actions hidden>
                            <button type="submit" name="result" value="again" class="secondary-button">Again</button>
                            <button type="submit" name="result" value="hard" class="secondary-button">Hard</button>
                            <button type="submit" name="result" value="good" class="primary-button">Good</button>
                            <button type="submit" name="result" value="easy" class="primary-button">Easy</button>
                        </div>
                    </form>

                    @if($cards->isNotEmpty())
                        <div class="mt-8 border-t border-stone-200 pt-6">
                            <p class="section-kicker">Queue map</p>
                            <div class="mt-4 grid gap-2">
                                @foreach($cards as $queueIndex => $queueCard)
                                    @php($queueFlashcard = $queueCard['flashcard'])
                                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => $mode, 'card' => $queueIndex]) : route('client.study.all', ['mode' => $mode, 'card' => $queueIndex]) }}" class="border px-3 py-3 text-sm {{ $queueIndex === $currentIndex ? 'border-stone-900 bg-stone-900 text-white' : 'border-stone-300 bg-white text-stone-700 hover:border-stone-800' }}">
                                        <span class="block text-xs font-bold uppercase tracking-[0.18em] {{ $queueIndex === $currentIndex ? 'text-stone-300' : 'text-stone-500' }}">{{ $queueIndex + 1 }}</span>
                                        <span class="mt-1 block truncate">{{ $queueFlashcard->front_content }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        @else
            <x-shared.empty-state title="No flashcards available" description="This study queue is empty right now." />
        @endif
    </section>

    <script>
        (() => {
            const session = document.querySelector('[data-study-session]');

            if (!session) {
                return;
            }

            const reviewGate = session.querySelector('[data-review-gate]');
            const reviewActions = session.querySelector('[data-review-actions]');
            const unlockReview = () => {
                if (reviewActions && reviewActions.hasAttribute('hidden')) {
                    reviewActions.removeAttribute('hidden');
                }

                if (reviewGate) {
                    reviewGate.textContent = 'Answer revealed. Grade the card to continue to the next item.';
                }
            };

            const flipCard = session.querySelector('[data-flip-card]');
            session.querySelectorAll('[data-toggle-answer]').forEach((button) => {
                button.addEventListener('click', () => {
                    flipCard?.classList.toggle('is-flipped');
                    unlockReview();
                });
            });

            const response = session.querySelector('[data-study-response]');
            const feedback = session.querySelector('[data-study-feedback]');

            session.querySelector('[data-check-choice]')?.addEventListener('click', (event) => {
                const expected = event.currentTarget.dataset.answer?.trim().toLowerCase();
                const selected = session.querySelector('[data-choice-input]:checked')?.value?.trim().toLowerCase();

                if (!response || !feedback) {
                    return;
                }

                response.classList.add('is-visible');

                if (!selected) {
                    feedback.textContent = 'Select an option first. The expected answer is: ' + event.currentTarget.dataset.answer;
                    return;
                }

                feedback.textContent = selected === expected
                    ? 'Correct. The expected answer is: ' + event.currentTarget.dataset.answer
                    : 'Not quite. The expected answer is: ' + event.currentTarget.dataset.answer;

                unlockReview();
            });

            session.querySelector('[data-check-typed]')?.addEventListener('click', (event) => {
                const expectedRaw = event.currentTarget.dataset.answer || '';
                const expected = expectedRaw.trim().toLowerCase();
                const typed = session.querySelector('[data-typed-input]')?.value?.trim().toLowerCase() || '';

                if (!response || !feedback) {
                    return;
                }

                response.classList.add('is-visible');

                if (!typed) {
                    feedback.textContent = 'Type your answer first. The expected answer is: ' + expectedRaw;
                    return;
                }

                feedback.textContent = typed === expected
                    ? 'Exact text match. The expected answer is: ' + expectedRaw
                    : 'Use your own judgement here. The expected answer is: ' + expectedRaw;

                unlockReview();
            });
        })();
    </script>
</x-layouts.client>
