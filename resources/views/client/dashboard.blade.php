<x-layouts.client :title="'Learning Dashboard'">
    @php($currentUser = auth('client')->user())
    <section class="space-y-8">
        <section class="glass-panel">
            <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr] lg:grid-cols-2">
                <div>
                    <p class="section-kicker">Learning dashboard</p>
                    <h1 class="display-title mt-3 text-3xl sm:text-4xl">Keep the queue moving, turn XP into visible progress, and make every study day feel earned.</h1>
                    <p class="reading-copy mt-4 max-w-3xl">This dashboard now emphasizes daily execution. You can see what is due today, how much XP is left to the next level, where your current streak stands across recent days, and how strong your mastered-card base is becoming.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('client.study.all', ['mode' => 'flip']) }}" class="primary-button">Study all</a>
                        <a href="{{ route('client.study.all', ['mode' => 'multiple-choice']) }}" class="secondary-button">Quiz mode</a>
                        <a href="{{ route('client.study.all', ['mode' => 'typed']) }}" class="secondary-button">Typed recall</a>
                        <a href="{{ route('client.profile') }}" class="secondary-button">Manage deck tools</a>
                    </div>
                </div>
                <div class="grid gap-4">
                    <article class="dashboard-metric dashboard-metric-amber {{ $dashboardSummary['due_today'] >= 12 || $dashboardSummary['completed_today'] >= 6 ? 'metric-burst' : '' }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="metric-label">Due today</p>
                                <p class="mt-2 text-sm font-medium text-stone-700">{{ $dashboardSummary['today_label'] }}</p>
                            </div>
                            <span class="metric-pill">Completed {{ $dashboardSummary['completed_today'] }}</span>
                        </div>
                        <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
                            <div>
                                <div class="metric-value">{{ $dashboardSummary['due_today'] }}</div>
                                <p class="metric-caption">cards still waiting in the queue</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <div class="metric-mini-value">{{ $dashboardSummary['completed_today'] }}</div>
                                <p class="metric-caption">tasks finished today</p>
                            </div>
                        </div>
                    </article>
                    <article class="dashboard-metric dashboard-metric-sky {{ $dashboardSummary['level']['current_xp'] >= 500 ? 'metric-burst' : '' }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="metric-label">Level progress</p>
                                <h2 class="mt-2 text-2xl font-semibold text-stone-900">Level {{ $dashboardSummary['level']['level'] }}</h2>
                            </div>
                            <span class="metric-pill">XP {{ number_format($dashboardSummary['level']['current_xp']) }} / {{ number_format($dashboardSummary['level']['next_level_at']) }}</span>
                        </div>
                        <div class="mt-5 metric-progress" role="progressbar" aria-valuenow="{{ $dashboardSummary['level']['percent'] }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="metric-progress-bar metric-progress-bar-sky" data-metric-progress="{{ $dashboardSummary['level']['percent'] }}"></div>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            <span>{{ number_format($dashboardSummary['level']['progress_within_level']) }} / {{ number_format($dashboardSummary['level']['xp_per_level']) }} XP in this level</span>
                            <span>{{ number_format($dashboardSummary['level']['remaining_xp']) }} XP to next level</span>
                        </div>
                    </article>
                </div>
                <article class="dashboard-metric dashboard-metric-emerald {{ $currentUser->daily_streak >= 5 ? 'metric-burst' : '' }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="metric-label">Daily streak</p>    
                            <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $currentUser->daily_streak }} day streak</h2>
                        </div>
                        <span class="metric-pill">Recent 7 days</span>  
                    </div>
                    <div class="mt-5 grid grid-cols-7 gap-1 sm:gap-2">
                        @foreach ($dashboardSummary['streak_timeline'] as $day)
                            <div class="streak-day {{ $day['is_active'] ? 'is-active' : '' }} {{ $day['is_today'] ? 'is-today' : '' }}" title="{{ $day['full'] }}">
                                <span class="streak-day-label">{{ $day['label'] }}</span>
                                <span class="streak-day-number">{{ $day['day'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>
                <article class="dashboard-metric dashboard-metric-slate dashboard-mastery {{ $dashboardSummary['mastery']['count'] >= 20 ? 'metric-burst' : '' }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="metric-label">Mastered vault</p>
                            <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $dashboardSummary['mastery']['tier'] }} tier</h2>
                        </div>
                        <span class="mastery-badge">{{ $dashboardSummary['mastery']['rate'] }}% mastery</span>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-[auto_1fr] sm:items-center">
                        <div class="mastery-orb">
                            <span class="mastery-orb-value">{{ $dashboardSummary['mastery']['count'] }}</span>
                            <span class="mastery-orb-label">cards</span>
                        </div>
                        <div>
                            <p class="text-sm leading-7 text-stone-700">{{ $dashboardSummary['mastery']['message'] }}</p>
                            <div class="mt-4 metric-progress" role="progressbar" aria-valuenow="{{ $dashboardSummary['mastery']['rate'] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="metric-progress-bar metric-progress-bar-emerald" data-metric-progress="{{ $dashboardSummary['mastery']['rate'] }}"></div>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                                <span>Mastery rate {{ $dashboardSummary['mastery']['rate'] }}%</span>
                                <span>{{ $dashboardSummary['mastery']['next_milestone'] }}</span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-2 xl:grid-cols-[1.05fr_0.95fr]">
            <div class="glass-panel">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="section-kicker">Review queue</p>
                        <h2 class="mt-2 section-title">Cards that need attention now</h2>
                    </div>
                    <span class="pill">{{ $dueCards->count() }} queued</span>
                </div>

                <div class="mt-6 data-stack">
                    @forelse($dueCards as $flashcard)
                        <article class="data-row">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ $flashcard->deck->title }}</p>
                                <h3 class="mt-2 text-lg font-semibold text-stone-900">{{ $flashcard->front_content }}</h3>
                            </div>
                            <div class="flex items-center">
                                <a href="{{ route('client.decks.study', [$flashcard->deck, 'mode' => 'flip']) }}" class="secondary-button">Study deck</a>
                            </div>
                        </article>
                    @empty
                        <x-empty-state title="No due cards" description="Your review queue is clear. Start a study session anyway to keep momentum." class="mt-6" />
                    @endforelse
                </div>

                @if($reviewTimeline->isNotEmpty())
                    <div class="mt-8 border-t border-stone-200 pt-6">
                        <p class="section-kicker">Upcoming schedule</p>
                        <div class="mt-4 space-y-3 text-sm text-stone-600">
                            @foreach($reviewTimeline as $progress)
                                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 pb-3 last:border-b-0 last:pb-0">
                                    <span class="font-semibold text-stone-900">{{ $progress->flashcard?->deck?->title }}</span>
                                    @if($progress->next_review_at)
                                        <span>{{ $progress->next_review_at->format('M d, H:i') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="glass-panel">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="section-kicker">My library</p>
                        <h2 class="mt-2 section-title">Decks you own</h2>
                    </div>
                    <span class="pill">{{ $ownedDecks->count() }} decks</span>
                </div>

                <div class="mt-6 data-stack">
                    @forelse($ownedDecks as $deck)
                        <article class="data-row">
                            <div>
                                <h3 class="text-lg font-semibold text-stone-900">{{ $deck->title }}</h3>
                                <p class="mt-2 text-sm leading-6 text-stone-600">{{ $deck->description ?: 'No description yet.' }}</p>
                                <p class="mt-2 text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ ucfirst($deck->visibility) }} · {{ $deck->flashcards_count }} cards</p>
                            </div>
                            <div class="flex flex-wrap items-center justify-start lg:justify-end gap-2 mt-2 lg:mt-0">
                                <a href="{{ route('client.decks.show', $deck) }}" class="secondary-button">Open</a>
                                <a href="{{ route('client.decks.study', [$deck, 'mode' => 'flip']) }}" class="primary-button">Study</a>
                                <a href="{{ route('client.decks.export', $deck) }}" class="secondary-button">Export</a>
                            </div>
                        </article>
                    @empty
                        <x-empty-state title="No personal decks yet" description="Use the Profile page to create your first deck or import one from CSV." class="mt-6" />
                    @endforelse
                </div>
            </div>
        </section>

        <section class="glass-panel">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="section-kicker">Community library</p>
                    <h2 class="mt-2 section-title">Public decks worth copying</h2>
                </div>
                <span class="pill">{{ $communityDecks->count() }} public</span>
            </div>

            <div class="mt-6 data-stack">
                @forelse($communityDecks as $deck)
                    <article class="data-row">
                        <div>
                            <h3 class="text-lg font-semibold text-stone-900">{{ $deck->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-stone-600">{{ $deck->description ?: 'No description provided.' }}</p>
                            <p class="mt-2 text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ $deck->flashcards_count }} cards · {{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5 rating</p>
                        </div>
                        <div class="flex flex-wrap items-center justify-start lg:justify-end gap-2 mt-2 lg:mt-0">
                            <a href="{{ route('client.decks.show', $deck) }}" class="secondary-button">View details</a>
                            <form action="{{ route('client.decks.copy', $deck) }}" method="POST">
                                @csrf
                                <button type="submit" class="primary-button">Copy deck</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <x-empty-state title="No public decks" description="Once decks are published publicly, they will appear here." class="mt-6" />
                @endforelse
            </div>
        </section>

        <section class="glass-panel">
            <div>
                <p class="section-kicker">Leaderboard</p>
                <h2 class="mt-2 section-title">Top learners this round</h2>
            </div>
            <div class="mt-6 data-stack">
                @foreach($leaderboard as $index => $entry)
                    <div class="data-row">
                        <div>
                            <span class="text-lg font-semibold text-stone-900">{{ $index + 1 }}. {{ $entry->name }}</span>
                        </div>
                        <div class="text-sm text-stone-600">XP {{ $entry->experience_points }} · streak {{ $entry->daily_streak }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    </section>
</x-layouts.client>
