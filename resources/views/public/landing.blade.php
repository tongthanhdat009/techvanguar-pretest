<x-layouts.app :title="'Flashcard Learning Hub'">
    <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="glass-panel p-8">
            <span class="pill bg-sky-100 text-sky-700">Public learning portal</span>
            <h1 class="mt-5 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">Study with private decks, public community content, and spaced repetition that keeps daily review clear.</h1>
            <p class="mt-5 max-w-3xl text-base leading-8 text-slate-600">Clients can build their own library, learn by deck or across all due cards, track streaks and XP, and clone public decks into their own workspace. Admins get a dedicated control surface for users, content, moderation, and reporting.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('client.login') }}" class="primary-button">Client login</a>
                <a href="{{ route('register') }}" class="secondary-button">Create account</a>
                <a href="{{ route('admin.login') }}" class="secondary-button">Admin login</a>
            </div>
        </div>

        <div class="glass-panel p-8">
            <h2 class="section-title">What changed</h2>
            <div class="mt-6 grid gap-4">
                <x-stat-card label="Separate portals" value="2" tone="amber">Distinct admin and client login flows.</x-stat-card>
                <x-stat-card label="Study modes" value="3" tone="sky">Flip, multiple-choice, and typed recall.</x-stat-card>
                <x-stat-card label="Community" value="Public decks" tone="emerald">Ratings, comments, and copy-to-library flow.</x-stat-card>
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="glass-panel p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="section-title">Community decks</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Only product-facing content is shown here. No demo credentials or endpoint inventory is exposed on the public page.</p>
                </div>
                <span class="pill bg-emerald-100 text-emerald-700">{{ $publicDecks->count() }} featured</span>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @forelse($publicDecks as $deck)
                    <article class="soft-panel">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-slate-950">{{ $deck->title }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $deck->description }}</p>
                            </div>
                            <span class="pill bg-slate-100 text-slate-700">{{ $deck->flashcards_count }} cards</span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-500">
                            @if($deck->category)
                                <span class="rounded-full bg-sky-50 px-3 py-1">{{ $deck->category }}</span>
                            @endif
                            @foreach($deck->tags ?? [] as $tag)
                                <span class="rounded-full bg-slate-100 px-3 py-1">#{{ $tag }}</span>
                            @endforeach
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-500">
                            Rating:
                            <span class="text-slate-900">{{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5</span>
                        </p>
                    </article>
                @empty
                    <x-empty-state title="No public decks yet" description="Public community decks will appear here once they are published." class="md:col-span-2" />
                @endforelse
            </div>
        </div>

        <div class="glass-panel p-8">
            <h2 class="section-title">Learner feedback</h2>
            <div class="mt-6 space-y-4">
                @forelse($featuredReviews as $review)
                    <article class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-bold text-slate-950">{{ $review->deck?->title }}</div>
                            <span class="pill bg-amber-100 text-amber-700">{{ $review->rating }}/5</span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $review->comment }}</p>
                        <p class="mt-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $review->user?->name }}</p>
                    </article>
                @empty
                    <x-empty-state title="No reviews yet" description="Community feedback will show up once learners start rating public decks." />
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
