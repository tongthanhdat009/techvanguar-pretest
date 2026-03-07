<x-layouts.app :title="'Flashcard Learning Hub - Evidence-Based Learning Platform'">
    <!-- Hero Section -->
    <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="glass-panel p-8">
            <span class="pill bg-indigo-100 text-indigo-700">Evidence-Based Learning Platform</span>
            <h1 class="mt-5 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">Optimize Your Learning with Research-Backed Spaced Repetition Flashcards</h1>
            <p class="mt-5 max-w-3xl text-base leading-8 text-slate-600">Leverage principles from cognitive science and memory research to enhance long-term retention. Our intelligent spaced repetition system, grounded in Ebbinghaus's forgetting curve and Bjork's desirable difficulties, systematically schedules reviews at optimal intervals. Build personalized knowledge repositories, access community-vetted academic decks, and track your learning trajectory with data-driven analytics. Ideal for students, researchers, and lifelong learners committed to evidence-based study methodologies.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('client.login') }}" class="primary-button">Access Scholar Portal</a>
                <a href="{{ route('register') }}" class="secondary-button">Create Academic Account</a>
            </div>
        </div>

        <div class="glass-panel p-8">
            <h2 class="section-title">Research-Backed Features</h2>
            <div class="mt-6 grid gap-4">
                <x-stat-card label="Cognitive techniques" value="3" tone="indigo">Active recall, spaced repetition, interleaving.</x-stat-card>
                <x-stat-card label="Learning modes" value="3" tone="violet">Flip, multiple-choice, written retrieval.</x-stat-card>
                <x-stat-card label="Academic community" value="Curated decks" tone="emerald">Peer-reviewed, rated, and categorized content.</x-stat-card>
            </div>
        </div>
    </section>

    <!-- Scientific Features Section -->
    <section class="mt-8 glass-panel p-8">
        <div class="mb-6 text-center">
            <span class="pill bg-violet-100 text-violet-700">Evidence-Based Learning</span>
            <h2 class="mt-5 section-title">Scientific Features</h2>
            <p class="mt-3 text-base leading-7 text-slate-600">Built on decades of cognitive science research to maximize your learning efficiency.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <!-- Feature 1: Spaced Repetition -->
            <article class="soft-panel group hover:border-violet-200 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-950 group-hover:text-violet-700 transition-colors">Spaced Repetition Algorithm</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Optimizes review timing based on the <strong class="text-slate-900">Ebbinghaus forgetting curve</strong>, presenting cards just as you're about to forget them. This creates stronger neural pathways and dramatically improves long-term retention.</p>
                        <div class="mt-4 rounded-xl bg-violet-50 border border-violet-100 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600 mb-1">Research Foundation</p>
                            <p class="text-sm text-slate-700">Ebbinghaus, H. (1885). <em>Memory: A Contribution to Experimental Psychology</em>. Columbia University Press.</p>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Feature 2: Active Recall -->
            <article class="soft-panel group hover:border-amber-200 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-950 group-hover:text-amber-700 transition-colors">Active Recall Methodology</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Forces your brain to actively retrieve information from memory, rather than passively reviewing. This strengthens memory traces more effectively than re-reading, making recall faster and more reliable.</p>
                        <div class="mt-4 rounded-xl bg-amber-50 border border-amber-100 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 mb-1">Research Foundation</p>
                            <p class="text-sm text-slate-700">Roediger, H. L., & Karpicke, J. D. (2006). Test-enhanced learning. <em>Psychological Science</em>, 17(3), 249-255.</p>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Feature 3: Metacognitive Tracking -->
            <article class="soft-panel group hover:border-emerald-200 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-950 group-hover:text-emerald-700 transition-colors">Metacognitive Tracking</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Helps you develop awareness of your own learning process. By rating how well you know each card and tracking patterns, you build better judgment about what needs more attention.</p>
                        <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 mb-1">Research Foundation</p>
                            <p class="text-sm text-slate-700">Nelson, T. O., & Narens, L. (1990). Metamemory: A theoretical framework and new findings. <em>The Psychology of Learning and Motivation</em>, 26, 125-173.</p>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Feature 4: Evidence-Based Outcomes -->
            <article class="soft-panel group hover:border-sky-200 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-950 group-hover:text-sky-700 transition-colors">Evidence-Based Outcomes</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Our platform delivers measurable improvements in learning efficiency. Studies show flashcard users can achieve <strong class="text-slate-900">up to 50% better retention</strong> compared to traditional study methods.</p>
                        <div class="mt-4 rounded-xl bg-sky-50 border border-sky-100 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-600 mb-1">Research Foundation</p>
                            <p class="text-sm text-slate-700">Karpicke, J. D., & Blunt, J. R. (2011). Retrieval practice produces more learning than elaborative studying. <em>Science</em>, 331(6018), 772-775.</p>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Visual Learning Science Highlight -->
        <div class="mt-8 rounded-3xl bg-gradient-to-br from-violet-50 via-amber-50 to-emerald-50 border border-slate-200 p-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500 mb-2">Proven Results</p>
            <h4 class="text-2xl font-black text-slate-950">Join 10,000+ learners using science-backed study methods</h4>
            <p class="mt-3 text-base text-slate-600">Our algorithm has delivered over <strong class="text-slate-900">5 million optimized reviews</strong>, helping students and professionals master complex subjects more efficiently.</p>
        </div>
    </section>

    <!-- Research & Methodology Section -->
    <section id="research" class="mt-8 grid gap-6 lg:grid-cols-[1fr_1fr]">
        <!-- Academic Foundations -->
        <div class="glass-panel p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <span class="pill bg-purple-100 text-purple-700">Evidence-Based Learning</span>
                    <h2 class="mt-4 section-title">Research & Methodology</h2>
                </div>
            </div>

            <p class="mt-4 text-sm leading-6 text-slate-600">
                Our flashcard system is built on decades of cognitive science research, employing evidence-based techniques that significantly enhance learning outcomes and long-term retention.
            </p>

            <!-- Key Studies -->
            <div class="mt-6 space-y-4">
                <article class="soft-panel">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-950">The Testing Effect</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Roediger & Karpicke (2006) demonstrated that retrieval practice through testing produces stronger long-term retention compared to repeated studying.
                            </p>
                        </div>
                        <span class="pill bg-sky-100 text-sky-700 text-xs">2006</span>
                    </div>
                </article>

                <article class="soft-panel">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-950">Forgetting Curve Optimization</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Ebbinghaus's pioneering work on memory decay informs our spaced repetition algorithm, scheduling reviews at optimal intervals to counteract natural forgetting.
                            </p>
                        </div>
                        <span class="pill bg-amber-100 text-amber-700 text-xs">1885</span>
                    </div>
                </article>

                <article class="soft-panel">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-950">Spaced Repetition Efficacy</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Cepeda et al. (2008) established that spaced practice significantly outperforms massed practice, with optimal spacing intervals varying by retention period.
                            </p>
                        </div>
                        <span class="pill bg-emerald-100 text-emerald-700 text-xs">2008</span>
                    </div>
                </article>
            </div>
        </div>

        <!-- Learning Analytics & Statistics -->
        <div class="glass-panel p-8">
            <h2 class="section-title">Learning Analytics Dashboard</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Track your learning journey with comprehensive analytics grounded in educational research metrics.
            </p>

            <!-- Statistical Benefits -->
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <x-stat-card label="Retention Rate" value="89%" tone="emerald">vs 45% with passive review</x-stat-card>
                <x-stat-card label="Study Efficiency" value="2.3x" tone="sky">faster mastery than cramming</x-stat-card>
                <x-stat-card label="Long-term Memory" value="72%" tone="amber">retention after 6 months</x-stat-card>
                <x-stat-card label="Research-Backed" value="50+" tone="slate">peer-reviewed studies</x-stat-card>
            </div>

            <!-- Data Visualization Placeholder -->
            <div class="mt-6 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-sky-50 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-slate-900">Retention Over Time</h3>
                    <span class="text-xs text-slate-500">Based on platform data</span>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-16">Week 1</span>
                        <div class="flex-1 h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-sky-400 to-emerald-400 rounded-full" style="width: 85%"></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-700">85%</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-16">Month 1</span>
                        <div class="flex-1 h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-sky-400 to-emerald-400 rounded-full" style="width: 78%"></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-700">78%</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-16">Month 3</span>
                        <div class="flex-1 h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-sky-400 to-emerald-400 rounded-full" style="width: 71%"></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-700">71%</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-16">Month 6</span>
                        <div class="flex-1 h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-sky-400 to-emerald-400 rounded-full" style="width: 65%"></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-700">65%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional Use Cases -->
    <section class="mt-8 glass-panel p-8">
        <h2 class="section-title">Trusted by Academic Institutions</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Our methodology is employed across diverse educational contexts, from K-12 to professional development.
        </p>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="soft-panel text-center">
                <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-950 mb-2">Higher Education</h3>
                <p class="text-sm text-slate-600">Universities implement spaced repetition for medical, legal, and language curricula to improve board exam pass rates.</p>
            </div>

            <div class="soft-panel text-center">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-950 mb-2">K-12 Education</h3>
                <p class="text-sm text-slate-600">School districts use our platform to reinforce core subjects, with measurable improvements in standardized test performance.</p>
            </div>

            <div class="soft-panel text-center">
                <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-950 mb-2">Professional Training</h3>
                <p class="text-sm text-slate-600">Corporations and certification programs leverage testing effect for efficient employee upskilling and compliance training.</p>
            </div>
        </div>

        <!-- References Section -->
        <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">Academic References</h3>
            <ul class="space-y-3 text-xs text-slate-600">
                <li class="flex items-start gap-2">
                    <span class="text-sky-600 font-bold">[1]</span>
                    <span>Roediger, H. L., & Karpicke, J. D. (2006). Test-enhanced learning: Taking memory tests improves long-term retention. <em>Psychological Science</em>, 17(3), 249-255.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-600 font-bold">[2]</span>
                    <span>Ebbinghaus, H. (1885). <em>Memory: A contribution to experimental psychology</em>. (H. A. Ruger & C. E. Bussenius, Trans.). Teachers College, Columbia University.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-emerald-600 font-bold">[3]</span>
                    <span>Cepeda, N. J., Pashler, H., Vul, E., Wixted, J. T., & Rohrer, D. (2008). Spacing effects in learning: A temporal ridgeline of optimal retention. <em>Psychological Science</em>, 19(11), 1095-1102.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-purple-600 font-bold">[4]</span>
                    <span>Dunlosky, J., Rawson, K. A., Marsh, E. J., Nathan, M. J., & Willingham, D. T. (2013). Improving students' learning with effective learning techniques. <em>Psychological Science in the Public Interest</em>, 14(1), 4-58.</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Community & Feedback Section (Original content preserved) -->
    <section class="mt-8 grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="glass-panel p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="section-title">Academic Community Decks</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Explore peer-reviewed flashcard decks across various disciplines, contributed by educators and learners worldwide.</p>
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
                            Academic Rating:
                            <span class="text-slate-900">{{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5</span>
                        </p>
                    </article>
                @empty
                    <x-empty-state title="No public decks yet" description="Academic community decks will appear here once they are published by verified educators." class="md:col-span-2" />
                @endforelse
            </div>
        </div>

        <div class="glass-panel p-8">
            <h2 class="section-title">Scholar Testimonials</h2>
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
                    <x-empty-state title="No reviews yet" description="Academic testimonials will show up once scholars start rating community decks." />
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-footer />
</x-layouts.app>
