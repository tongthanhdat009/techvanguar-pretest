<x-layouts.app :title="'Client Portal'">
    <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <div class="space-y-6">
            <div class="glass-panel p-8">
                <span class="pill bg-amber-100 text-amber-700">Client dashboard</span>
                <h1 class="mt-4 text-3xl font-black text-slate-950">Learn by deck, review what is due today, and keep your own library organized.</h1>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <x-stat-card label="Due Today" :value="$progressSummary['due_today']" tone="amber" />
                    <x-stat-card label="Level" :value="auth()->user()->level()" tone="sky">XP {{ auth()->user()->experience_points }}</x-stat-card>
                    <x-stat-card label="Daily Streak" :value="auth()->user()->daily_streak" tone="emerald">days</x-stat-card>
                    <x-stat-card label="Mastered" :value="$progressSummary['mastered']" tone="slate">cards</x-stat-card>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('client.study.all', ['mode' => 'flip']) }}" class="primary-button">Study all</a>
                    <a href="{{ route('client.study.all', ['mode' => 'multiple-choice']) }}" class="secondary-button">Quiz mode</a>
                    <a href="{{ route('client.study.all', ['mode' => 'typed']) }}" class="secondary-button">Typed recall</a>
                </div>
            </div>

            <div class="glass-panel p-6">
                <h2 class="section-title">Create your own deck</h2>
                <p class="mt-2 text-sm text-slate-600">Private decks stay personal. Public decks appear in the community library.</p>
                <div class="mt-6">
                    @include('client.partials.deck-form', ['action' => route('client.decks.store'), 'deck' => null, 'submitLabel' => 'Create deck'])
                </div>
            </div>

            <div class="glass-panel p-6">
                <h2 class="section-title">Import from CSV</h2>
                <p class="mt-2 text-sm text-slate-600">CSV headers: <code>front_content,back_content,image_url,audio_url,hint</code>.</p>
                <form action="{{ route('client.decks.import') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <input type="text" name="title" class="field-input" placeholder="Deck title" required>
                    <textarea name="description" rows="2" class="field-input" placeholder="Description"></textarea>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <input type="text" name="category" class="field-input" placeholder="Category">
                        <input type="text" name="tags" class="field-input" placeholder="tag1, tag2">
                        <select name="visibility" class="field-input">
                            <option value="private">Private</option>
                            <option value="public">Public</option>
                        </select>
                    </div>
                    <input type="file" name="csv_file" class="field-input" accept=".csv,text/csv" required>
                    <button type="submit" class="primary-button w-full">Import deck</button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-panel p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="section-title">Review queue</h2>
                        <p class="mt-2 text-sm text-slate-600">Cards due now are surfaced first. The schedule below shows what is coming next.</p>
                    </div>
                    <span class="pill bg-sky-100 text-sky-700">{{ $dueCards->count() }} queued</span>
                </div>

                <div class="mt-6 space-y-3">
                    @forelse($dueCards as $flashcard)
                        <article class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-slate-500">{{ $flashcard->deck->title }}</div>
                                    <div class="mt-1 font-bold text-slate-950">{{ $flashcard->front_content }}</div>
                                </div>
                                <a href="{{ route('client.decks.study', [$flashcard->deck, 'mode' => 'flip']) }}" class="secondary-button">Study deck</a>
                            </div>
                        </article>
                    @empty
                        <x-empty-state title="No due cards" description="Your review queue is clear. Start a deck anyway to keep momentum." />
                    @endforelse
                </div>

                <div class="mt-6 grid gap-3">
                    @foreach($reviewTimeline as $progress)
                        <div class="rounded-2xl border border-slate-100 bg-white px-4 py-3 text-sm text-slate-600">
                            <span class="font-semibold text-slate-950">{{ $progress->flashcard?->deck?->title }}</span>
                            @if($progress->next_review_at)
                                review at {{ $progress->next_review_at->format('M d, H:i') }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-panel p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="section-title">My decks</h2>
                        <p class="mt-2 text-sm text-slate-600">Manage the decks and flashcards you own.</p>
                    </div>
                    <span class="pill bg-amber-100 text-amber-700">{{ $ownedDecks->count() }} decks</span>
                </div>
                <div class="mt-6 grid gap-4">
                    @forelse($ownedDecks as $deck)
                        <article class="soft-panel">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-950">{{ $deck->title }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $deck->description }}</p>
                                </div>
                                <div class="text-right text-sm text-slate-500">
                                    <div>{{ ucfirst($deck->visibility) }}</div>
                                    <div>{{ $deck->flashcards_count }} cards</div>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('client.decks.show', $deck) }}" class="secondary-button">Open deck</a>
                                <a href="{{ route('client.decks.study', [$deck, 'mode' => 'flip']) }}" class="primary-button">Study</a>
                                <a href="{{ route('client.decks.export', $deck) }}" class="secondary-button">Export CSV</a>
                            </div>
                        </article>
                    @empty
                        <x-empty-state title="No personal decks yet" description="Create your first deck or copy one from the community list." />
                    @endforelse
                </div>
            </div>

            <div class="glass-panel p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="section-title">Community library</h2>
                        <p class="mt-2 text-sm text-slate-600">Discover public decks, rate them, and copy them into your own workspace.</p>
                    </div>
                    <span class="pill bg-emerald-100 text-emerald-700">{{ $communityDecks->count() }} public</span>
                </div>
                <div class="mt-6 grid gap-4">
                    @forelse($communityDecks as $deck)
                        <article class="soft-panel">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-950">{{ $deck->title }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $deck->description }}</p>
                                </div>
                                <span class="pill bg-slate-100 text-slate-700">{{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5</span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('client.decks.show', $deck) }}" class="secondary-button">View details</a>
                                <form action="{{ route('client.decks.copy', $deck) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="primary-button">Copy deck</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <x-empty-state title="No public decks" description="Once decks are published publicly, they will appear here." />
                    @endforelse
                </div>
            </div>

            <div class="glass-panel p-6">
                <h2 class="section-title">Leaderboard</h2>
                <div class="mt-6 space-y-3">
                    @foreach($leaderboard as $index => $entry)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div class="font-semibold text-slate-950">{{ $index + 1 }}. {{ $entry->name }}</div>
                            <div class="text-sm text-slate-500">XP {{ $entry->experience_points }} · streak {{ $entry->daily_streak }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
