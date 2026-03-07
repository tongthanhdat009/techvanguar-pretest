<x-layouts.client :title="$deck->title">
    <section class="space-y-6">
        <div class="glass-panel p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="pill bg-sky-100 text-sky-700">{{ ucfirst($deck->visibility) }}</span>
                        @if($deck->category)
                            <span class="pill bg-amber-100 text-amber-700">{{ $deck->category }}</span>
                        @endif
                    </div>
                    <h1 class="mt-4 text-3xl font-black text-slate-950">{{ $deck->title }}</h1>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">{{ $deck->description }}</p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-500">
                        @foreach($deck->tags ?? [] as $tag)
                            <span class="rounded-full bg-slate-100 px-3 py-1">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('client.decks.study', [$deck, 'mode' => 'flip']) }}" class="primary-button">Study deck</a>
                    <a href="{{ route('client.decks.study', [$deck, 'mode' => 'multiple-choice']) }}" class="secondary-button">Quiz mode</a>
                    <a href="{{ route('client.decks.export', $deck) }}" class="secondary-button">Export CSV</a>
                </div>
            </div>
        </div>

        @if($canManageDeck)
            <section class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
                <div class="glass-panel p-6">
                    <h2 class="section-title">Edit deck</h2>
                    <div class="mt-6">
                        @include('client.partials.deck-form', ['action' => route('client.decks.update', $deck), 'deck' => $deck, 'submitLabel' => 'Save deck', 'method' => 'PUT'])
                    </div>
                    <form action="{{ route('client.decks.destroy', $deck) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="secondary-button w-full border-rose-200 text-rose-600 hover:bg-rose-50">Delete deck</button>
                    </form>
                </div>

                <div class="glass-panel p-6">
                    <h2 class="section-title">Add flashcard</h2>
                    <div class="mt-6">
                        @include('client.partials.flashcard-form', ['action' => route('client.flashcards.store', $deck), 'flashcard' => null, 'submitLabel' => 'Add flashcard'])
                    </div>
                </div>
            </section>
        @endif

        <section class="glass-panel p-6">
            <div class="flex items-center justify-between gap-3">
                <h2 class="section-title">Flashcards</h2>
                <span class="pill bg-slate-100 text-slate-700">{{ $deck->flashcards->count() }} cards</span>
            </div>
            <div class="mt-6 grid gap-4">
                @forelse($deck->flashcards as $flashcard)
                    @php($progress = $flashcard->studyProgress->first())
                    <article class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                        <div class="grid gap-4 xl:grid-cols-[1fr_0.9fr]">
                            <div>
                                <div class="font-semibold text-slate-500">Front</div>
                                <div class="mt-2 text-lg font-bold text-slate-950">{{ $flashcard->front_content }}</div>
                                <div class="mt-4 font-semibold text-slate-500">Back</div>
                                <div class="mt-2 text-sm leading-7 text-slate-700">{{ $flashcard->back_content }}</div>
                                @if($flashcard->hint)
                                    <div class="mt-4 text-sm text-slate-500">Hint: {{ $flashcard->hint }}</div>
                                @endif
                                @if($flashcard->image_url)
                                    <a href="{{ $flashcard->image_url }}" target="_blank" class="mt-3 inline-block text-sm font-semibold text-sky-700">Open image</a>
                                @endif
                                @if($flashcard->audio_url)
                                    <a href="{{ $flashcard->audio_url }}" target="_blank" class="mt-3 ml-4 inline-block text-sm font-semibold text-sky-700">Open audio</a>
                                @endif
                                <div class="mt-4 text-sm text-slate-500">
                                    Progress: <span class="font-semibold text-slate-900">{{ ucfirst($progress?->status ?? 'new') }}</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @if($canManageDeck)
                                    <div class="rounded-3xl border border-white bg-white p-4">
                                        @include('client.partials.flashcard-form', ['action' => route('client.flashcards.update', $flashcard), 'flashcard' => $flashcard, 'submitLabel' => 'Save changes', 'method' => 'PUT'])
                                        <form action="{{ route('client.flashcards.destroy', $flashcard) }}" method="POST" class="mt-3">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="secondary-button w-full border-rose-200 text-rose-600 hover:bg-rose-50">Delete flashcard</button>
                                        </form>
                                    </div>
                                @else
                                    <form action="{{ route('client.progress.update', $flashcard) }}" method="POST" class="rounded-3xl border border-white bg-white p-4">
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
                    </article>
                @empty
                    <x-empty-state title="No flashcards yet" description="Add cards to start studying this deck." />
                @endforelse
            </div>
        </section>

        @if($deck->visibility === 'public')
            <section class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
                <div class="glass-panel p-6">
                    <h2 class="section-title">Rate this deck</h2>
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

                <div class="glass-panel p-6">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="section-title">Reviews</h2>
                        <span class="pill bg-amber-100 text-amber-700">{{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse($deck->reviews as $review)
                            <article class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-slate-950">{{ $review->user->name }}</div>
                                    <span class="pill bg-slate-100 text-slate-700">{{ $review->rating }}/5</span>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $review->comment }}</p>
                            </article>
                        @empty
                            <x-empty-state title="No reviews yet" description="Be the first learner to leave feedback for this deck." />
                        @endforelse
                    </div>
                </div>
            </section>
        @endif
    </section>
</x-layouts.client>
