<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flashcard Learning Hub - Adaptive Flashcard Learning Platform</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon-logo.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-stone-50 to-stone-100 font-sans antialiased">
    @php
        $clientLoggedIn = auth()->guard('client')->check();
        $adminLoggedIn = auth()->guard('admin')->check();
        $primaryHref = $adminLoggedIn
            ? route('admin.overview')
            : ($clientLoggedIn ? route('client.dashboard') : route('register'));
        $primaryLabel = $adminLoggedIn
            ? 'Open admin overview'
            : ($clientLoggedIn ? 'Enter learning dashboard' : 'Start learning free');
        $secondaryHref = $clientLoggedIn ? route('client.study.all', ['mode' => 'flip']) : route('client.login');
        $secondaryLabel = $clientLoggedIn ? 'Study now' : 'Scholar login';
        $featuredDeck = $publicDecks->first();
        $flashcardVolume = $publicDecks->sum('flashcards_count');
        $averageRating = number_format($publicDecks->avg('reviews_avg_rating') ?? 0, 1);
    @endphp

    <!-- Landing Header -->
    <header class="sticky top-0 z-50 border-b border-stone-200/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative" data-disclosure>
            <div class="flex h-16 items-center justify-between gap-4 sm:h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/icon-logo.svg') }}" alt="Flashcard Learning Hub" class="h-8 w-8 sm:h-10 sm:w-10">
                    <span class="text-xl font-bold tracking-tight text-stone-900 sm:text-2xl">Flashcard Learning Hub</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden sm:flex items-center gap-6">
                    <a href="#features" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors">How it works</a>
                    <a href="#community" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors">Community</a>
                </nav>

                <!-- Desktop Actions -->
                <div class="hidden items-center gap-3 sm:flex">
                    @if($adminLoggedIn || $clientLoggedIn)
                        @if($adminLoggedIn)
                            <a href="{{ route('admin.overview') }}" class="px-4 py-2 text-sm font-semibold text-stone-700 hover:text-stone-900 transition-colors">Admin Panel</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-stone-700 hover:text-stone-900 transition-colors">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('client.login') }}" class="px-4 py-2 text-sm font-semibold text-stone-700 hover:text-stone-900 transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 transition-all hover:bg-indigo-500 hover:shadow-xl hover:shadow-indigo-600/30">Get Started</a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <button type="button" data-disclosure-toggle class="flex sm:hidden items-center justify-center p-2 rounded-lg text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors" aria-label="Toggle menu" aria-expanded="false">
                    <svg data-disclosure-icon-open class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg data-disclosure-icon-close class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Panel -->
            <div data-disclosure-panel class="hidden sm:hidden border-t border-stone-200 py-4 space-y-4">
                <nav class="flex flex-col gap-3">
                    <a href="#features" class="px-3 py-2 text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors">Features</a>
                    <a href="#how-it-works" class="px-3 py-2 text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors">How it works</a>
                    <a href="#community" class="px-3 py-2 text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors">Community</a>
                </nav>
                <div class="border-t border-stone-200 pt-4 flex flex-col gap-3">
                    @if($adminLoggedIn || $clientLoggedIn)
                        @if($adminLoggedIn)
                            <a href="{{ route('admin.overview') }}" class="w-full px-4 py-2.5 text-center text-sm font-semibold text-stone-700 bg-stone-100 rounded-full hover:bg-stone-200 transition-colors">Admin Panel</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="w-full px-4 py-2.5 text-center text-sm font-semibold text-stone-700 bg-stone-100 rounded-full hover:bg-stone-200 transition-colors">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('client.login') }}" class="w-full px-4 py-2.5 text-center text-sm font-semibold text-stone-700 hover:text-stone-900 transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="w-full px-4 py-2.5 text-center text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-500 transition-colors">Get Started</a>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <section class="landing-hero glass-panel overflow-hidden">
            <div class="grid gap-8 xl:grid-cols-[1.08fr_0.92fr] xl:items-start">
                <div class="relative z-10">
                    <p class="section-kicker">Flashcard Learning Hub</p>
                    <h1 class="display-title mt-4 max-w-4xl text-4xl sm:text-5xl xl:text-6xl">A sharper flashcard workspace for spaced repetition, active recall, and visible learning momentum.</h1>
                    <p class="reading-copy mt-6 max-w-3xl text-base sm:text-lg">This project turns the flashcard routine into a guided system: build decks, pull from the community library, study in multiple modes, and keep progress measurable with XP, streaks, review queues, and mastery signals that feel consistent from landing page to dashboard.</p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ $primaryHref }}" class="primary-button">{{ $primaryLabel }}</a>
                        <a href="{{ $secondaryHref }}" class="secondary-button">{{ $secondaryLabel }}</a>
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-3">
                        <div class="landing-signal-card">
                            <span class="landing-signal-label">Public decks</span>
                            <strong class="landing-signal-value">{{ $publicDecks->count() }}</strong>
                            <p class="landing-signal-copy">Ready-to-copy starting points for new learners.</p>
                        </div>
                        <div class="landing-signal-card">
                            <span class="landing-signal-label">Cards indexed</span>
                            <strong class="landing-signal-value">{{ number_format($flashcardVolume) }}</strong>
                            <p class="landing-signal-copy">Visible study volume pulled from featured community sets.</p>
                        </div>
                        <div class="landing-signal-card">
                            <span class="landing-signal-label">Average rating</span>
                            <strong class="landing-signal-value">{{ $averageRating }}/5</strong>
                            <p class="landing-signal-copy">Social proof from the decks learners review and reuse.</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-1">
                    <article class="dashboard-metric dashboard-metric-amber metric-burst">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="metric-label">What learners see</p>
                                <h2 class="mt-2 text-2xl font-semibold text-stone-900">A calm queue instead of study chaos</h2>
                            </div>
                            <span class="metric-pill">Daily execution</span>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div>
                                <div class="metric-mini-value">3</div>
                                <p class="metric-caption">study modes</p>
                            </div>
                            <div>
                                <div class="metric-mini-value">XP</div>
                                <p class="metric-caption">progress feedback</p>
                            </div>
                            <div>
                                <div class="metric-mini-value">7d</div>
                                <p class="metric-caption">streak timeline</p>
                            </div>
                        </div>
                    </article>

                    <article class="dashboard-metric dashboard-metric-sky landing-showcase-card">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="metric-label">Dashboard language</p>
                                <h2 class="mt-2 text-2xl font-semibold text-stone-900">The landing page now speaks the same visual system</h2>
                            </div>
                            <span class="metric-pill">Stone / amber / sky</span>
                        </div>
                        <div class="mt-5 space-y-3">
                            <div class="landing-inline-row">
                                <span class="landing-inline-dot landing-inline-dot-sky"></span>
                                <span class="text-sm font-semibold text-stone-800">Serif display headings and editorial spacing</span>
                            </div>
                            <div class="landing-inline-row">
                                <span class="landing-inline-dot landing-inline-dot-amber"></span>
                                <span class="text-sm font-semibold text-stone-800">Metric cards reused from the client dashboard</span>
                            </div>
                            <div class="landing-inline-row">
                                <span class="landing-inline-dot landing-inline-dot-emerald"></span>
                                <span class="text-sm font-semibold text-stone-800">Soft glass panels with subtle motion and depth</span>
                            </div>
                        </div>
                    </article>

                    <article class="dashboard-metric dashboard-metric-slate dashboard-mastery lg:col-span-2 xl:col-span-1">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="metric-label">Featured deck</p>
                                <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $featuredDeck?->title ?? 'Your next deck can live here' }}</h2>
                            </div>
                            <span class="mastery-badge">{{ $featuredDeck ? $featuredDeck->flashcards_count . ' cards' : 'Community ready' }}</span>
                        </div>
                        <p class="mt-5 text-sm leading-7 text-stone-700">{{ $featuredDeck?->description ?: 'Publish public decks and the landing page can immediately showcase them as part of the product story.' }}</p>
                        <div class="mt-5 metric-progress" role="progressbar" aria-valuenow="{{ $featuredDeck ? min(100, max(20, (int) round((($featuredDeck->reviews_avg_rating ?? 0) / 5) * 100))) : 72 }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="metric-progress-bar metric-progress-bar-emerald" data-metric-progress="{{ $featuredDeck ? min(100, max(20, (int) round((($featuredDeck->reviews_avg_rating ?? 0) / 5) * 100))) : 72 }}"></div>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            <span>{{ $featuredDeck?->category ?: 'Adaptive learning flow' }}</span>
                            <span>{{ $featuredDeck ? number_format($featuredDeck->reviews_avg_rating ?? 0, 1) . '/5 rated' : 'Built for demo and production storytelling' }}</span>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="mt-8 grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
            <div class="glass-panel">
                <p class="section-kicker">Why this product exists</p>
                <h2 class="mt-3 section-title">Flashcard Learning Hub is not just a card list. It is a study operating system.</h2>
                <p class="reading-copy mt-4">The core value of the project is not only storing prompts and answers. It is creating a repeatable loop where learners know what to review, how to review it, and whether they are actually getting stronger over time.</p>

                <div class="mt-6 space-y-4">
                    <article class="landing-step">
                        <span class="landing-step-number">01</span>
                        <div>
                            <h3 class="text-lg font-semibold text-stone-900">Build or import a deck</h3>
                            <p class="reading-copy mt-2">Create personal flashcards, import from CSV, or copy a public deck and make it your own.</p>
                        </div>
                    </article>
                    <article class="landing-step">
                        <span class="landing-step-number">02</span>
                        <div>
                            <h3 class="text-lg font-semibold text-stone-900">Study in the mode that fits recall</h3>
                            <p class="reading-copy mt-2">Switch between flip cards, multiple choice, and typed recall to train recognition and retrieval together.</p>
                        </div>
                    </article>
                    <article class="landing-step">
                        <span class="landing-step-number">03</span>
                        <div>
                            <h3 class="text-lg font-semibold text-stone-900">See progress instead of guessing</h3>
                            <p class="reading-copy mt-2">XP, streaks, due queues, and mastery metrics turn a passive study log into a visible learning rhythm.</p>
                        </div>
                    </article>
                </div>
            </div>

            <div class="glass-panel overflow-hidden">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="section-kicker">Inside the experience</p>
                        <h2 class="mt-2 section-title">The public page now previews the dashboard mindset</h2>
                    </div>
                    <span class="pill">Product story</span>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-2">
                    <article class="landing-mode-card">
                        <p class="metric-label">Mode one</p>
                        <h3 class="mt-3 text-xl font-semibold text-stone-900">Flip review</h3>
                        <p class="mt-3 text-sm leading-7 text-stone-700">Fast repetition when the goal is coverage, orientation, and keeping the review queue moving.</p>
                    </article>
                    <article class="landing-mode-card">
                        <p class="metric-label">Mode two</p>
                        <h3 class="mt-3 text-xl font-semibold text-stone-900">Multiple choice</h3>
                        <p class="mt-3 text-sm leading-7 text-stone-700">A guided checkpoint for learners who need momentum, confidence, and quick error detection.</p>
                    </article>
                    <article class="landing-mode-card">
                        <p class="metric-label">Mode three</p>
                        <h3 class="mt-3 text-xl font-semibold text-stone-900">Typed recall</h3>
                        <p class="mt-3 text-sm leading-7 text-stone-700">The highest-friction mode for stronger retrieval practice when precision matters.</p>
                    </article>
                    <article class="landing-mode-card landing-mode-card-contrast">
                        <p class="metric-label text-stone-300">Built for retention</p>
                        <h3 class="mt-3 text-xl font-semibold text-stone-50">One visual language from marketing to product</h3>
                        <p class="mt-3 text-sm leading-7 text-stone-300">Visitors see the same cards, texture, spacing, and tone they get after sign in, so the transition into the dashboard feels intentional instead of disconnected.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="features" class="mt-8 glass-panel">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="section-kicker">Value pillars</p>
                    <h2 class="mt-2 section-title">Why Flashcard Learning Hub is worth promoting</h2>
                </div>
                <span class="pill">Learner-focused</span>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                <article class="soft-panel landing-feature-panel">
                    <p class="metric-label">Spaced repetition</p>
                    <h3 class="mt-3 text-xl font-semibold text-stone-900">Reviews arrive when memory needs reinforcement</h3>
                    <p class="mt-3 text-sm leading-7 text-stone-700">Instead of cramming everything at once, the platform keeps attention on what is due now and what should wait.</p>
                </article>
                <article class="soft-panel landing-feature-panel">
                    <p class="metric-label">Active recall</p>
                    <h3 class="mt-3 text-xl font-semibold text-stone-900">Every study mode pushes retrieval, not passive reading</h3>
                    <p class="mt-3 text-sm leading-7 text-stone-700">The product is shaped around answering, typing, choosing, and evaluating, which makes sessions feel active and measurable.</p>
                </article>
                <article class="soft-panel landing-feature-panel">
                    <p class="metric-label">Community library</p>
                    <h3 class="mt-3 text-xl font-semibold text-stone-900">Public decks reduce time-to-first-value</h3>
                    <p class="mt-3 text-sm leading-7 text-stone-700">New users do not need to start from zero. They can copy proven material, then customize it as their own study system evolves.</p>
                </article>
            </div>
        </section>

        <section id="community" class="mt-8 grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <div class="glass-panel">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="section-kicker">Community decks</p>
                        <h2 class="mt-2 section-title">Featured study sets that already make the product feel alive</h2>
                    </div>
                    <span class="pill">{{ $publicDecks->count() }} live</span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @forelse($publicDecks as $deck)
                        <article class="landing-deck-card">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-stone-900">{{ $deck->title }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-stone-700">{{ $deck->description ?: 'No description yet.' }}</p>
                                </div>
                                <span class="metric-pill">{{ $deck->flashcards_count }} cards</span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.14em] text-stone-500">
                                @if($deck->category)
                                    <span class="rounded-full border border-stone-300 bg-stone-100 px-3 py-1">{{ $deck->category }}</span>
                                @endif
                                @foreach(collect($deck->tags ?? [])->take(3) as $tag)
                                    <span class="rounded-full border border-stone-200 bg-white px-3 py-1">#{{ $tag }}</span>
                                @endforeach
                            </div>
                            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 text-sm text-stone-600">
                                <span>Rated {{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5</span>
                                <span>{{ ucfirst($deck->visibility) }}</span>
                            </div>
                        </article>
                    @empty
                        <x-shared.empty-state title="No public decks yet" description="Publish a deck and the landing page will immediately have real material to promote." class="md:col-span-2" />
                    @endforelse
                </div>
            </div>

            <div class="glass-panel">
                <p class="section-kicker">Social proof</p>
                <h2 class="mt-2 section-title">Learner feedback sits next to the product promise</h2>
                <div class="mt-6 space-y-4">
                    @forelse($featuredReviews as $review)
                        <article class="landing-quote-card">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-semibold text-stone-900">{{ $review->deck?->title ?: 'Community deck' }}</span>
                                <span class="mastery-badge">{{ $review->rating }}/5</span>
                            </div>
                            <p class="mt-4 text-sm leading-7 text-stone-700">&ldquo;{{ $review->comment ?: 'Useful structure, good recall flow, and easy to keep using daily.' }}&rdquo;</p>
                            <p class="mt-4 text-xs font-bold uppercase tracking-[0.24em] text-stone-500">{{ $review->user?->name ?: 'Learner' }}</p>
                        </article>
                    @empty
                        <x-shared.empty-state title="No reviews yet" description="When learners review public decks, this area becomes direct product validation." />
                    @endforelse
                </div>

                <div class="mt-6 rounded-[2rem] border border-stone-300 bg-stone-900 px-6 py-6 text-stone-100 shadow-2xl shadow-stone-900/20">
                    <p class="section-kicker text-stone-400">Call to action</p>
                    <h3 class="mt-3 font-serif text-2xl font-semibold tracking-tight">Turn the homepage into the first study touchpoint.</h3>
                    <p class="mt-3 text-sm leading-7 text-stone-300">The page now introduces the product with the same visual confidence users meet inside the dashboard. That makes onboarding clearer and the project feel more complete.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ $primaryHref }}" class="inline-flex items-center justify-center border border-amber-300 bg-amber-300 px-4 py-2 text-sm font-semibold uppercase tracking-[0.12em] text-stone-950 transition hover:bg-amber-200">{{ $primaryLabel }}</a>
                        <a href="{{ route('client.login') }}" class="inline-flex items-center justify-center border border-stone-500 bg-transparent px-4 py-2 text-sm font-semibold uppercase tracking-[0.12em] text-stone-100 transition hover:border-stone-300 hover:bg-stone-800">Explore portal</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Landing Footer -->
    <footer class="mt-12 border-t border-stone-300/80 bg-gradient-to-b from-stone-100 to-stone-200">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Brand Column -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('assets/icon-logo.svg') }}" alt="Flashcard Learning Hub" class="h-8 w-8">
                        <span class="text-lg font-bold text-stone-900">Flashcard Learning Hub</span>
                    </div>
                    <p class="text-sm leading-6 text-stone-600">
                        Built for learners who want a calmer, more readable workspace for spaced repetition and long-term retention.
                    </p>
                </div>

                <!-- Features Column -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-stone-900">Study Methods</h3>
                    <ul class="space-y-2 text-sm text-stone-600">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            Flip review
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            Multiple choice drills
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            Typed recall practice
                        </li>
                    </ul>
                </div>

                <!-- Support Column -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-stone-900">Support</h3>
                    <ul class="space-y-2 text-sm text-stone-600">
                        <li>
                            <a href="mailto:support@flashcardhub.com" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                support@flashcardhub.com
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Documentation
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Community guide
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Account Column -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-stone-900">Account</h3>
                    <ul class="space-y-2 text-sm text-stone-600">
                        @if (auth('client')->check())
                            <li>
                                <a href="{{ route('client.dashboard') }}" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.study.all', ['mode' => 'flip']) }}" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Study
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.profile') }}" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.login') }}" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Client login
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="flex items-center gap-2 hover:text-stone-900 transition-colors">
                                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Create account
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="mt-10 border-t border-stone-300 pt-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h3 class="text-lg font-bold text-stone-900">Stay Updated with Learning Science</h3>
                    <p class="mt-2 text-sm text-stone-600">Get research-backed study tips and platform updates delivered to your inbox.</p>
                    <form class="mt-4 flex flex-col gap-3 sm:flex-row" onsubmit="event.preventDefault(); alert('Newsletter subscription coming soon!');">
                        <input
                            type="email"
                            placeholder="Enter your academic email"
                            class="flex-1 rounded-full border border-stone-300 bg-white px-4 py-2.5 text-sm placeholder:text-stone-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            required
                        >
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 transition-all hover:bg-indigo-500 hover:shadow-xl hover:shadow-indigo-600/30">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-8 border-t border-stone-300 pt-6">
                <div class="flex flex-col items-center justify-between gap-4 text-sm text-stone-600 sm:flex-row">
                    <p>&copy; {{ date('Y') }} Flashcard Learning Hub. All rights reserved.</p>
                    <p>Evidence-based learning for the academic community.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js for mobile menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
