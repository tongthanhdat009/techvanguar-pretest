<x-layouts.client :title="$deck->title">
    <section class="space-y-8">
        <div class="glass-panel">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="pill">{{ ucfirst($deck->visibility) }}</span>
                        @if($deck->category)
                            <span class="pill">{{ $deck->category }}</span>
                        @endif
                    </div>
                    <h1 class="display-title mt-4 text-4xl">{{ $deck->title }}</h1>
                    <p class="reading-copy mt-3 max-w-3xl">{{ $deck->description ?: 'No description yet.' }}</p>
                    @if(!empty($deck->tags))
                        <div class="mt-4 flex flex-wrap gap-2 text-xs text-stone-600">
                            @foreach($deck->tags ?? [] as $tag)
                                <span class="pill">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('client.decks.study', [$deck, 'mode' => 'flip']) }}" class="primary-button">Study deck</a>
                    <a href="{{ route('client.decks.study', [$deck, 'mode' => 'multiple-choice']) }}" class="secondary-button">Quiz mode</a>
                    <a href="{{ route('client.decks.export', $deck) }}" class="secondary-button">Export CSV</a>
                </div>
            </div>
        </div>

        @if($canManageDeck)
            <section class="grid gap-8 xl:grid-cols-[0.85fr_1.15fr]">
                <div class="glass-panel">
                    <p class="section-kicker">Deck settings</p>
                    <h2 class="mt-2 section-title">Edit deck</h2>
                    <div class="mt-6">
                        @include('client.partials.deck-form', ['action' => route('client.decks.update', $deck), 'deck' => $deck, 'submitLabel' => 'Save deck', 'method' => 'PUT'])
                    </div>
                    <form action="{{ route('client.decks.destroy', $deck) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="secondary-button w-full border-rose-400 text-rose-700 hover:bg-rose-50">Delete deck</button>
                    </form>
                </div>

                <div class="glass-panel">
                    <p class="section-kicker">Authoring</p>
                    <h2 class="mt-2 section-title">Add flashcard</h2>
                    <div class="mt-6">
                        @include('client.partials.flashcard-form', ['action' => route('client.flashcards.store', $deck), 'flashcard' => null, 'submitLabel' => 'Add flashcard'])
                    </div>
                </div>
            </section>
        @endif

        <section class="glass-panel">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="section-kicker">Deck contents</p>
                    <h2 class="mt-2 section-title">Flashcards</h2>
                </div>
                <span class="pill">{{ $deck->flashcards->count() }} cards</span>
            </div>
            <div class="mt-6 divide-y divide-stone-200 border-t border-stone-200">
                @forelse($deck->flashcards as $flashcard)
                    @php($progress = $flashcard->studyProgress->first())
                    <details class="py-5" @if($loop->first) open @endif>
                        <summary class="cursor-pointer list-none">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">Front</p>
                                    <h3 class="mt-2 text-lg font-semibold text-stone-900">{{ $flashcard->front_content }}</h3>
                                </div>
                                <div class="text-sm text-stone-600">
                                    Progress: <span class="font-semibold text-stone-900">{{ ucfirst($progress?->status ?? 'new') }}</span>
                                </div>
                            </div>
                        </summary>

                        <div class="mt-5 grid gap-6 xl:grid-cols-[1fr_0.95fr]">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">Back</p>
                                <div class="mt-2 text-sm leading-7 text-stone-700">{{ $flashcard->back_content }}</div>
                                @if($flashcard->hint)
                                    <div class="mt-4 text-sm text-stone-600">Hint: {{ $flashcard->hint }}</div>
                                @endif
                                <div class="mt-4 flex flex-wrap gap-4 text-sm font-semibold text-stone-700">
                                    @if($flashcard->image_url)
                                        <a href="{{ $flashcard->image_url }}" target="_blank">Open image</a>
                                    @endif
                                    @if($flashcard->audio_url)
                                        <a href="{{ $flashcard->audio_url }}" target="_blank">Open audio</a>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($canManageDeck)
                                    @include('client.partials.flashcard-form', ['action' => route('client.flashcards.update', $flashcard), 'flashcard' => $flashcard, 'submitLabel' => 'Save changes', 'method' => 'PUT'])
                                    <form action="{{ route('client.flashcards.destroy', $flashcard) }}" method="POST" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="secondary-button w-full border-rose-400 text-rose-700 hover:bg-rose-50">Delete flashcard</button>
                                    </form>
                                @else
                                    <form action="{{ route('client.progress.update', $flashcard) }}" method="POST" class="border border-stone-300 bg-stone-50 p-4">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $progress?->status ?? 'learning' }}">
                                        <div class="grid gap-2 sm:grid-cols-2">
                                            <button type="submit" name="result" value="again" class="secondary-button">Again</button>
                                            <button type="submit" name="result" value="good" class="primary-button">Good</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </details>
                @empty
                    <x-shared.empty-state title="No flashcards yet" description="Add cards to start studying this deck." />
                @endforelse
            </div>
        </section>

        @if($deck->visibility === 'public')
            <section class="grid gap-8 xl:grid-cols-[0.85fr_1.15fr]">
                <div class="glass-panel">
                    <p class="section-kicker">Community feedback</p>
                    <h2 class="mt-2 section-title">Rate this deck</h2>
                    <form action="{{ route('client.decks.reviews.store', $deck) }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="field-label">Rating</label>
                            <select name="rating" class="field-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected(old('rating', $reviewForm?->rating) == $i)>{{ $i }}/5</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Comment</label>
                            <textarea name="comment" rows="4" class="field-input">{{ old('comment', $reviewForm?->comment) }}</textarea>
                        </div>
                        <button type="submit" class="primary-button w-full">Save review</button>
                    </form>
                </div>

                <div class="glass-panel">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="section-kicker">Published feedback</p>
                            <h2 class="mt-2 section-title">Reviews</h2>
                        </div>
                        <span class="pill">{{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5</span>
                    </div>
                    <div class="mt-6 divide-y divide-stone-200 border-t border-stone-200">
                        @forelse($deck->reviews as $review)
                            <article class="py-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-stone-900">{{ $review->user->name }}</div>
                                    <span class="pill">{{ $review->rating }}/5</span>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-stone-600">{{ $review->comment }}</p>
                            </article>
                        @empty
                            <x-shared.empty-state title="No reviews yet" description="Be the first learner to leave feedback for this deck." />
                        @endforelse
                    </div>
                </div>
            </section>
        @endif
    </section>
</x-layouts.client>
