@props([
    'showNewsletter' => true,
    'compact' => false,
])

<footer class="mt-12 glass-panel p-8 {{ $compact ? 'lg:p-6' : 'lg:p-10' }}">
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        <!-- Product Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-950">About Flashcard Learning Hub</h3>
            <p class="text-sm leading-6 text-slate-600">
                An evidence-based flashcard learning platform powered by cognitive science research, featuring intelligent spaced repetition algorithms, community-vetted academic decks, and comprehensive learning analytics.
            </p>
            <div class="flex flex-wrap gap-2 text-xs text-slate-500">
                <span class="rounded-full bg-indigo-50 px-3 py-1">Spaced Repetition</span>
                <span class="rounded-full bg-violet-50 px-3 py-1">Active Recall</span>
                <span class="rounded-full bg-emerald-50 px-3 py-1">Academic Research</span>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-950">Contact Us</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    <a href="mailto:support@flashcardhub.com" class="hover:text-sky-600 transition">support@flashcardhub.com</a>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                    <a href="#" class="hover:text-sky-600 transition">@FlashcardHub</a>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    <a href="#" class="hover:text-sky-600 transition">GitHub</a>
                </li>
            </ul>
        </div>

        <!-- Support Links -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-950">Support</h3>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Help Center</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Documentation</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">FAQ</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Community Guidelines</a>
                </li>
            </ul>
        </div>

        <!-- Legal Links -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-950">Legal</h3>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Privacy Policy</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Terms of Service</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Cookie Policy</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 hover:text-sky-600 transition">Data Protection</a>
                </li>
            </ul>
        </div>
    </div>

    @if($showNewsletter)
    <!-- Newsletter Signup -->
    <div class="mt-8 border-t border-slate-200 pt-8">
        <div class="mx-auto max-w-2xl text-center">
            <h3 class="mb-2 text-lg font-bold text-slate-950">Stay Updated with Learning Science</h3>
            <p class="mb-4 text-sm text-slate-600">Get research-backed study tips, platform updates, and academic insights delivered to your inbox.</p>
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

    <!-- Copyright Notice -->
    <div class="mt-8 border-t border-slate-200 pt-6">
        <div class="flex flex-col items-center justify-between gap-4 text-sm text-slate-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} Flashcard Learning Hub. All rights reserved.</p>
            <p>Evidence-based learning for the academic community.</p>
        </div>
    </div>
</footer>
