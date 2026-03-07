@props([
    'showNewsletter' => true,
    'compact' => false,
    'variant' => 'default',
])

@if ($variant === 'dashboard')
<footer class="mt-12 border-t border-stone-300/80 bg-stone-200/55">
    <div class="classic-frame py-8 lg:py-10">
        <div class="glass-panel">
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr_0.8fr]">
                <div>
                    <p class="section-kicker">Flashcard Learning Hub</p>
                    <h2 class="display-title mt-3 text-2xl">The same dashboard language continues all the way to the page footer.</h2>
                    <p class="reading-copy mt-4 max-w-2xl">Keep learners oriented with one consistent system for queue review, deck management, study modes, and profile momentum.</p>
                </div>

                <article class="dashboard-metric dashboard-metric-amber">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="metric-label">Study modes</p>
                            <h3 class="mt-2 text-xl font-semibold text-stone-900">Three active recall paths</h3>
                        </div>
                        <span class="metric-pill">Ready</span>
                    </div>
                    <ul class="mt-4 space-y-2 text-sm leading-6 text-stone-700">
                        <li>Flip review for fast repetition.</li>
                        <li>Multiple choice for guided checks.</li>
                        <li>Typed recall for higher-friction retrieval.</li>
                    </ul>
                </article>

                <article class="dashboard-metric dashboard-metric-sky">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="metric-label">Quick access</p>
                            <h3 class="mt-2 text-xl font-semibold text-stone-900">Move without losing context</h3>
                        </div>
                        <span class="metric-pill">Portal</span>
                    </div>
                    <div class="mt-4 flex flex-col gap-2 text-sm font-semibold uppercase tracking-[0.12em]">
                        @if (auth('client')->check())
                            <a href="{{ route('client.dashboard') }}" class="secondary-button w-full">Dashboard</a>
                            <a href="{{ route('client.study.all', ['mode' => 'flip']) }}" class="secondary-button w-full">Study</a>
                            <a href="{{ route('client.profile') }}" class="secondary-button w-full">Profile</a>
                        @else
                            <a href="{{ route('home') }}" class="secondary-button w-full">Home</a>
                            <a href="{{ route('client.login') }}" class="secondary-button w-full">Client login</a>
                            <a href="{{ route('register') }}" class="primary-button w-full">Create account</a>
                        @endif
                    </div>
                </article>
            </div>

            <div class="mt-8 flex flex-col gap-3 border-t border-stone-200 pt-6 text-sm text-stone-600 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} Flashcard Learning Hub. All rights reserved.</p>
                <p>Evidence-based learning for the academic community.</p>
            </div>
        </div>
    </div>
</footer>
@else
<footer class="mt-10 border-t-4 border-stone-900 bg-stone-200/70">
    <div class="classic-frame py-8 {{ $compact ? 'lg:py-6' : 'lg:py-10' }}">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-3">
                <p class="section-kicker">Flashcard Learning Hub</p>
                <p class="text-sm leading-6 text-stone-700">
                    Built for learners who want a calmer, more readable workspace for spaced repetition and long-term retention.
                </p>
            </div>

            <div class="space-y-3">
                <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-stone-700">Study methods</h3>
                <ul class="space-y-2 text-sm text-stone-600">
                    <li>Flip review</li>
                    <li>Multiple choice drills</li>
                    <li>Typed recall practice</li>
                </ul>
            </div>

            <div class="space-y-3">
                <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-stone-700">Support</h3>
                <ul class="space-y-2 text-sm text-stone-600">
                    <li><a href="mailto:support@flashcardhub.com" class="hover:text-stone-900">support@flashcardhub.com</a></li>
                    <li><a href="#" class="hover:text-stone-900">Documentation</a></li>
                    <li><a href="#" class="hover:text-stone-900">Community guide</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-stone-700">Account</h3>
                <ul class="space-y-2 text-sm text-stone-600">
                    @if (auth('client')->check())
                        <li><a href="{{ route('client.dashboard') }}" class="hover:text-stone-900">Dashboard</a></li>
                        <li><a href="{{ route('client.study.all', ['mode' => 'flip']) }}" class="hover:text-stone-900">Study</a></li>
                        <li><a href="{{ route('client.profile') }}" class="hover:text-stone-900">Profile</a></li>
                    @else
                        <li><a href="{{ route('home') }}" class="hover:text-stone-900">Home</a></li>
                        <li><a href="{{ route('client.login') }}" class="hover:text-stone-900">Client login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-stone-900">Create account</a></li>
                    @endif
                </ul>
            </div>
        </div>

    @if($showNewsletter)
    <div class="mt-8 border-t border-stone-400 pt-8">
        <div class="mx-auto max-w-2xl text-center">
            <h3 class="mb-2 text-lg font-bold text-stone-900">Stay Updated with Learning Science</h3>
            <p class="mb-4 text-sm text-stone-600">Get research-backed study tips and platform updates delivered to your inbox.</p>
            <form class="flex flex-col gap-3 sm:flex-row" onsubmit="event.preventDefault(); alert('Newsletter subscription coming soon!');">
                <input
                    type="email"
                    placeholder="Enter your academic email"
                    class="field-input flex-1"
                    required
                >
                <button type="submit" class="primary-button whitespace-nowrap">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
    @endif

        <div class="mt-8 border-t border-stone-400 pt-6">
            <div class="flex flex-col items-center justify-between gap-4 text-sm text-stone-600 sm:flex-row">
            <p>&copy; {{ date('Y') }} Flashcard Learning Hub. All rights reserved.</p>
            <p>Evidence-based learning for the academic community.</p>
            </div>
        </div>
    </div>
</footer>
@endif
