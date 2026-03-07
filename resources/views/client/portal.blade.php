<x-layouts.client :title="'Study Portal'">
    <section class="space-y-8">
        <div class="glass-panel">
            <div class="grid gap-8 xl:grid-cols-[0.8fr_1.2fr]">
                <div>
                    <p class="section-kicker">Client portal</p>
                    <h1 class="display-title mt-3 text-4xl">Build mastery one flashcard at a time.</h1>
                    <p class="reading-copy mt-4">This view keeps a classic reading layout while still letting you flip cards inline and mark progress quickly.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <x-shared.stat label="New" :value="$progressSummary['new']" tone="sky" />
                    <x-shared.stat label="Learning" :value="$progressSummary['learning']" tone="amber" />
                    <x-shared.stat label="Mastered" :value="$progressSummary['mastered']" tone="emerald" />
                </div>
            </div>
        </div>

        <div class="space-y-8">
            @forelse($decks as $deck)
                <section class="glass-panel">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="section-kicker">Deck</p>
                            <h2 class="mt-2 text-2xl font-bold text-stone-900">{{ $deck->title }}</h2>
                            <p class="mt-2 max-w-3xl text-sm leading-7 text-stone-600">{{ $deck->description ?: 'No description yet.' }}</p>
                        </div>
                        <span class="pill">{{ $deck->flashcards->count() }} flashcards</span>
                    </div>

                    <div class="mt-6 divide-y divide-stone-200 border-t border-stone-200">
                        @foreach($deck->flashcards as $flashcard)
                            @php($progress = $flashcard->studyProgress->first())
                            <div class="grid gap-6 py-5 xl:grid-cols-[1.1fr_0.9fr]">
                                <div class="flashcard-scene">
                                    <button type="button" class="flashcard w-full text-left" data-flashcard>
                                        <div class="flashcard-face">
                                            <div>
                                                <p class="section-kicker">Front</p>
                                                <p class="mt-5 text-2xl font-semibold leading-8 text-stone-900">{{ $flashcard->front_content }}</p>
                                            </div>
                                            <p class="text-sm text-stone-500">Click to flip</p>
                                        </div>
                                        <div class="flashcard-face flashcard-back bg-stone-900 text-white">
                                            <div>
                                                <p class="section-kicker text-stone-300">Back</p>
                                                <p class="mt-5 text-xl leading-8">{{ $flashcard->back_content }}</p>
                                            </div>
                                            <p class="text-sm text-stone-300">Click again to return</p>
                                        </div>
                                    </button>
                                </div>

                                <form action="{{ route('client.progress.update', $flashcard) }}" method="POST" class="space-y-4 border border-stone-300 bg-stone-50 p-4">
                                    @csrf
                                    <label class="field-label">Study progress</label>
                                    <div class="grid gap-2 sm:grid-cols-3">
                                        @foreach($statuses as $status)
                                            <label class="cursor-pointer border px-3 py-3 text-center text-sm font-semibold {{ optional($progress)->status === $status ? 'border-stone-900 bg-stone-900 text-white' : 'border-stone-300 bg-white text-stone-700 hover:border-stone-800' }}">
                                                <input type="radio" name="status" value="{{ $status }}" class="sr-only" {{ optional($progress)->status === $status ? 'checked' : '' }}>
                                                {{ ucfirst($status) }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="primary-button w-full">Save progress</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <x-shared.empty-state title="No active decks are available right now." />
            @endforelse
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-flashcard]').forEach((card) => {
            card.addEventListener('click', () => card.classList.toggle('is-flipped'));
        });
    </script>
</x-layouts.client>
