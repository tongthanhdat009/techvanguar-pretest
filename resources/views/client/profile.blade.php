<x-layouts.client :title="'Learner Profile'">
    @php($currentUser = auth('client')->user())
    <section class="space-y-8">
        <section class="glass-panel">
            <div class="grid gap-8 lg:grid-cols-2 xl:grid-cols-[1fr_0.95fr]">
                <div>
                    <p class="section-kicker">Learner profile</p>
                    <h1 class="display-title mt-3 text-3xl sm:text-4xl">{{ $user->name }}</h1>
                    <p class="reading-copy mt-4 max-w-3xl">{{ $user->bio ?: 'Add a short bio to personalize your account and make your study workspace feel more yours.' }}</p>
                </div>
                <div class="grid gap-4">
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

            <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <x-shared.stat label="Decks" :value="$user->decks_count" tone="slate" />
                <x-shared.stat label="Reviews" :value="$stats['reviews']" tone="sky" />
                <x-shared.stat label="Due today" :value="$stats['due_today']" tone="amber">done {{ $stats['completed_today'] }}</x-shared.stat>
                <x-shared.stat label="Last study" :value="$user->last_studied_at?->format('M d') ?? 'N/A'" tone="slate" />
            </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-2 xl:grid-cols-[0.95fr_1.05fr]">
            <div class="glass-panel">
                <p class="section-kicker">Profile settings</p>
                <h2 class="mt-2 section-title">Edit account details</h2>
                <form action="{{ route('client.profile.update') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="field-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="field-input" required>
                    </div>
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input" required>
                    </div>
                    <div>
                        <label class="field-label">Bio</label>
                        <textarea name="bio" rows="5" class="field-input">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <button type="submit" class="primary-button w-full">Save profile</button>
                </form>
            </div>

            <div class="glass-panel">
                <p class="section-kicker">Deck tools</p>
                <h2 class="mt-2 section-title">Create a new deck</h2>
                <p class="reading-copy mt-3">All deck creation utilities now live here so your dashboard stays focused on studying and review queues.</p>
                <div class="mt-6">
                    @include('client.partials.deck-form', ['action' => route('client.decks.store'), 'deck' => null, 'submitLabel' => 'Create deck'])
                </div>
            </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-2 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="glass-panel">
                <p class="section-kicker">CSV import</p>
                <h2 class="mt-2 section-title">Import a deck from spreadsheet</h2>
                <p class="reading-copy mt-3">Use headers front_content, back_content, image_url, audio_url, hint.</p>
                <form action="{{ route('client.decks.import') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <input type="text" name="title" class="field-input" placeholder="Deck title" required>
                    <textarea name="description" rows="2" class="field-input" placeholder="Description"></textarea>
                    <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3">
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

            <div class="glass-panel">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="section-kicker">Recent decks</p>
                        <h2 class="mt-2 section-title">Your latest working library</h2>
                    </div>
                    <span class="pill">{{ $recentDecks->count() }} shown</span>
                </div>

                <div class="mt-6 data-stack">
                    @forelse($recentDecks as $deck)
                        <article class="data-row">
                            <div>
                                <h3 class="text-lg font-semibold text-stone-900">{{ $deck->title }}</h3>
                                <p class="mt-2 text-sm text-stone-600">{{ $deck->flashcards_count }} cards</p>
                            </div>
                            <div class="flex flex-wrap items-center justify-start lg:justify-end gap-2 mt-2 lg:mt-0">
                                <a href="{{ route('client.decks.show', $deck) }}" class="secondary-button">Open</a>
                                <a href="{{ route('client.decks.study', [$deck, 'mode' => 'flip']) }}" class="primary-button">Study</a>
                            </div>
                        </article>
                    @empty
                        <x-shared.empty-state title="No decks yet" description="Create your first deck here or import one from CSV." class="mt-6" />
                    @endforelse
                </div>
            </div>
        </section>
    </section>
</x-layouts.client>
