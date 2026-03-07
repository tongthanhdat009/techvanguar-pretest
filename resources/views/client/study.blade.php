<x-layouts.app :title="$deck ? 'Study '.$deck->title : 'Study All'">
    <section class="space-y-6">
        <div class="glass-panel p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="pill bg-sky-100 text-sky-700">{{ $deck ? $deck->title : 'All accessible decks' }}</span>
                    <h1 class="mt-4 text-3xl font-black text-slate-950">Study mode: {{ str_replace('-', ' ', ucfirst($mode)) }}</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Use the result buttons after each card to update the spaced repetition schedule.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => 'flip']) : route('client.study.all', ['mode' => 'flip']) }}" class="secondary-button">Flip</a>
                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => 'multiple-choice']) : route('client.study.all', ['mode' => 'multiple-choice']) }}" class="secondary-button">Multiple choice</a>
                    <a href="{{ $deck ? route('client.decks.study', [$deck, 'mode' => 'typed']) : route('client.study.all', ['mode' => 'typed']) }}" class="secondary-button">Typed</a>
                </div>
            </div>
        </div>

        <div class="grid gap-6">
            @forelse($cards as $card)
                @php($flashcard = $card['flashcard'])
                @php($progress = $card['progress'])
                <article class="glass-panel p-6">
                    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                        <div class="space-y-4">
                            <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $flashcard->deck->title }}</div>
                            <div class="text-2xl font-black text-slate-950">{{ $flashcard->front_content }}</div>
                            @if($flashcard->hint)
                                <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm text-amber-800">Hint: {{ $flashcard->hint }}</div>
                            @endif

                            @if($mode === 'flip')
                                <details class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                    <summary class="cursor-pointer font-semibold text-slate-950">Reveal answer</summary>
                                    <div class="mt-4 text-sm leading-7 text-slate-700">{{ $flashcard->back_content }}</div>
                                </details>
                            @elseif($mode === 'multiple-choice')
                                <div class="grid gap-3">
                                    @foreach($card['choices'] as $choice)
                                        <label class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                                            <input type="radio" name="choice-{{ $flashcard->id }}" class="mr-3">
                                            {{ $choice }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="text-sm text-slate-500">Correct answer: {{ $flashcard->back_content }}</div>
                            @else
                                <textarea class="field-input" rows="3" placeholder="Type your answer before checking"></textarea>
                                <details class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                    <summary class="cursor-pointer font-semibold text-slate-950">Show expected answer</summary>
                                    <div class="mt-4 text-sm leading-7 text-slate-700">{{ $flashcard->back_content }}</div>
                                </details>
                            @endif

                            @if($flashcard->image_url)
                                <a href="{{ $flashcard->image_url }}" target="_blank" class="text-sm font-semibold text-sky-700">Open image asset</a>
                            @endif
                            @if($flashcard->audio_url)
                                <a href="{{ $flashcard->audio_url }}" target="_blank" class="ml-4 text-sm font-semibold text-sky-700">Open audio asset</a>
                            @endif
                        </div>

                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <div class="text-sm font-semibold text-slate-500">Current status</div>
                            <div class="mt-1 text-xl font-bold text-slate-950">{{ ucfirst($progress?->status ?? 'new') }}</div>
                            <div class="mt-2 text-sm text-slate-500">
                                Next review:
                                {{ $progress?->next_review_at?->format('M d, H:i') ?? 'Not scheduled yet' }}
                            </div>
                            <form action="{{ route('client.progress.update', $flashcard) }}" method="POST" class="mt-6 space-y-4">
                                @csrf
                                <div>
                                    <label class="field-label">Status</label>
                                    <select name="status" class="field-input">
                                        <option value="new" @selected(($progress?->status ?? 'new') === 'new')>New</option>
                                        <option value="learning" @selected(($progress?->status ?? 'new') === 'learning')>Learning</option>
                                        <option value="mastered" @selected(($progress?->status ?? 'new') === 'mastered')>Mastered</option>
                                    </select>
                                </div>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <button type="submit" name="result" value="again" class="secondary-button">Again</button>
                                    <button type="submit" name="result" value="hard" class="secondary-button">Hard</button>
                                    <button type="submit" name="result" value="good" class="primary-button">Good</button>
                                    <button type="submit" name="result" value="easy" class="primary-button">Easy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <x-empty-state title="No flashcards available" description="This study queue is empty right now." />
            @endforelse
        </div>
    </section>
</x-layouts.app>
