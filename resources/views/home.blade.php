<x-layouts.app :title="'Flashcard Learning Application'">
    <div class="space-y-8">
        <!-- Hero Section -->
        <section class="glass-panel overflow-hidden p-8 lg:p-12">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div>
                    <span class="pill bg-sky-100 text-sky-700">Smart Learning Platform</span>
                    <h1 class="mt-5 text-4xl font-black tracking-tight text-slate-950 sm:text-6xl">
                        Learn Faster with <span class="text-sky-600">Spaced Repetition</span>
                    </h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                        Master any subject with science-backed flashcard learning. Create your own decks or discover community content. Track progress and retain knowledge longer.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.overview') : route('client.portal') }}" class="rounded-full bg-slate-950 px-8 py-4 text-lg font-semibold text-white transition hover:bg-slate-800 shadow-lg">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="rounded-full bg-slate-950 px-8 py-4 text-lg font-semibold text-white transition hover:bg-slate-800 shadow-lg">
                                Get Started Free
                            </a>
                            <a href="{{ route('client.login') }}" class="rounded-full border-2 border-slate-200 px-8 py-4 text-lg font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Hero Image/Graphic -->
                <div class="relative hidden lg:block">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/20 to-amber-400/20 rounded-3xl blur-3xl"></div>
                    <div class="relative glass-panel border-2 border-slate-100 rounded-3xl p-6">
                        <div class="space-y-4">
                            <!-- Sample Flashcard Preview -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-medium text-slate-500">Due Today</span>
                                    <span class="pill bg-emerald-100 text-emerald-700 text-xs">5 cards</span>
                                </div>
                                <div class="bg-gradient-to-r from-sky-50 to-amber-50 rounded-xl p-4 text-center">
                                    <p class="text-lg font-semibold text-slate-900">What is the capital of France?</p>
                                    <p class="mt-2 text-sm text-slate-500">Click to reveal answer</p>
                                </div>
                            </div>

                            <!-- Stats Preview -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-white rounded-xl p-4 text-center shadow">
                                    <p class="text-2xl font-bold text-slate-900">85%</p>
                                    <p class="text-xs text-slate-500">Retention</p>
                                </div>
                                <div class="bg-white rounded-xl p-4 text-center shadow">
                                    <p class="text-2xl font-bold text-slate-900">1.2k</p>
                                    <p class="text-xs text-slate-500">Cards</p>
                                </div>
                                <div class="bg-white rounded-xl p-4 text-center shadow">
                                    <p class="text-2xl font-bold text-slate-900">15</p>
                                    <p class="text-xs text-slate-500">Day Streak</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="grid gap-6 md:grid-cols-3">
            <div class="glass-panel p-6 hover:border-sky-300 transition">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 102 2h1a2 2 0 002-2v-1a3.374 3.374 0 00-.548-2.006l-2.828-9.9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Spaced Repetition</h3>
                <p class="mt-2 text-sm text-slate-600">Scientifically-proven algorithm schedules reviews at optimal intervals to maximize long-term retention.</p>
            </div>

            <div class="glass-panel p-6 hover:border-amber-300 transition">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 19.775 5.754 20.5 7.5 20.5S10.832 19.775 12 19.253v-13C13.832 5.477 15.246 5 16.5 5c1.754 0 3.168.477 4.168 1.253v13C15.246 20.5 13.832 19.775 12.5 19.253z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Study Modes</h3>
                <p class="mt-2 text-sm text-slate-600">Learn with flip cards, multiple choice, or type answers. Choose the style that works best for you.</p>
            </div>

            <div class="glass-panel p-6 hover:border-emerald-300 transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.649 0A7.016 7.016 0 017 12z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Community Decks</h3>
                <p class="mt-2 text-sm text-slate-600">Browse thousands of flashcards created by the community. Copy, customize, and start learning instantly.</p>
            </div>
        </section>

        <!-- How It Works -->
        <section class="glass-panel p-8">
            <h2 class="text-2xl font-bold text-slate-950 mb-6">How It Works</h2>
            <div class="grid gap-8 md:grid-cols-4">
                <div class="text-center">
                    <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-sky-600">1</span>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Create or Discover</h3>
                    <p class="text-sm text-slate-600">Build your own flashcard decks or explore community content</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-amber-600">2</span>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Study Daily</h3>
                    <p class="text-sm text-slate-600">Review cards scheduled for optimal learning retention</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-emerald-600">3</span>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Track Progress</h3>
                    <p class="text-sm text-slate-600">Monitor your improvement with detailed statistics</p>
                </div>
                <div class="text-center">
                    <div="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-purple-600">4</span>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Master Faster</h3>
                    <p class="text-sm text-slate-600">Achieve mastery with personalized review scheduling</p>
                </div>
            </div>
        </section>

        <!-- Available Decks Preview -->
        <section class="glass-panel p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-950">Popular Study Decks</h2>
                    <p class="mt-2 text-slate-600">Start learning with these community-created flashcard sets</p>
                </div>
                @guest
                    <a href="{{ route('register') }}" class="text-sky-600 hover:text-sky-700 font-medium">View all →</a>
                @endguest
            </div>

            @if($activeDecks->isNotEmpty())
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($activeDecks->take(6) as $deck)
                        <div class="border border-slate-200 rounded-xl p-5 hover:shadow-md hover:border-sky-200 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-900 truncate">{{ $deck->title }}</h3>
                                    <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ $deck->description }}</p>
                                    <div class="mt-3 flex items-center gap-2 text-xs text-slate-400">
                                        <span>{{ $deck->flashcards_count }} cards</span>
                                        @if($deck->category)
                                            <span>· {{ $deck->category }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($deck->reviews_avg_rating)
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 2.098 0 .3.732.256 1.371.737 1.895l2.212 2.212c.576.576 1.506.576 2.082 0 .326-.256.594-.587.738l-2.212 2.212c-.576.576-1.506.576-2.082 0-.326.256-.594.587-.738.738-.874.874-1.506 1.732-2.098 2.927m0 0A8.948 8.948 0 0118 8.948v.75m0-7.02a7.5 7.5 0 1115 0h.008m0 0h-.008" />
                                        </svg>
                                        <span class="text-slate-600 text-xs">{{ number_format($deck->reviews_avg_rating, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-shared.empty-state message="No decks available yet. Be the first to create one!" />
            @endif
        </section>

        <!-- CTA Section for Guests -->
        @guest
            <section id="auth-panels" class="glass-panel p-8">
                <div class="grid gap-6 md:grid-cols-2 text-center">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-slate-950 mb-4">Start Learning Today</h2>
                        <p class="text-slate-600 mb-6">Create your free account and start your learning journey with thousands of flashcards.</p>
                        <a href="{{ route('register') }}" class="inline-flex rounded-full bg-slate-950 px-8 py-4 text-lg font-semibold text-white transition hover:bg-slate-800">
                            Create Free Account
                        </a>
                    </div>
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-slate-950 mb-4">Already Have an Account?</h2>
                        <p class="text-slate-600 mb-6">Sign in to access your dashboard and continue learning.</p>
                        <a href="{{ route('client.login') }}" class="inline-flex rounded-full border-2 border-slate-200 px-8 py-4 text-lg font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50">
                            Sign In
                        </a>
                    </div>
                </div>
            </section>
        @endguest
    </div>

    <x-client.footer />
</x-layouts.app>
