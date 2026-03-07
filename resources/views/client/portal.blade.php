<x-layouts.client :title="'Study Portal'">
    <section class="grid gap-6 lg:grid-cols-[0.3fr_0.7fr]">
        <div class="glass-panel p-6">
            <span class="pill bg-amber-100 text-amber-700">Client portal</span>
            <h1 class="mt-4 text-3xl font-black text-slate-950">Build mastery one flashcard at a time.</h1>
            <p class="mt-3 text-slate-600">Flip cards to reveal answers, then update your learning state as new, learning, or mastered.</p>

            <div class="mt-6 space-y-4">
                <div class="dashboard-card">
                    <div class="text-sm font-semibold text-slate-500">New</div>
                    <div class="mt-2 text-3xl font-black text-slate-950">{{ $progressSummary['new'] }}</div>
                </div>
                <div class="dashboard-card">
                    <div class="text-sm font-semibold text-slate-500">Learning</div>
                    <div class="mt-2 text-3xl font-black text-slate-950">{{ $progressSummary['learning'] }}</div>
                </div>
                <div class="dashboard-card">
                    <div class="text-sm font-semibold text-slate-500">Mastered</div>
                    <div class="mt-2 text-3xl font-black text-slate-950">{{ $progressSummary['mastered'] }}</div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @forelse($decks as $deck)
                <section class="glass-panel p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-950">{{ $deck->title }}</h2>
                            <p class="mt-2 max-w-3xl text-sm leading-7 text-slate-600">{{ $deck->description }}</p>
                        </div>
                        <span class="pill bg-sky-100 text-sky-700">{{ $deck->flashcards->count() }} flashcards</span>
                    </div>

                    <div class="mt-6 grid gap-5 xl:grid-cols-2">
                        @foreach($deck->flashcards as $flashcard)
                            @php($progress = $flashcard->studyProgress->first())
                            <div class="rounded-[2rem] border border-sky-100 bg-gradient-to-br from-white to-sky-50 p-5 shadow-lg shadow-sky-100/70">
                                <div class="flashcard-scene">
                                    <button type="button" class="flashcard group relative h-64 w-full rounded-[1.75rem] text-left" data-flashcard>
                                        <div class="flashcard-face absolute inset-0 rounded-[1.75rem] bg-slate-950 p-6 text-white shadow-xl shadow-slate-950/20">
                                            <div class="pill bg-white/10 text-white">Front</div>
                                            <p class="mt-5 text-xl font-semibold leading-8">{{ $flashcard->front_content }}</p>
                                            <p class="absolute bottom-6 text-sm text-slate-300">Tap to flip</p>
                                        </div>
                                        <div class="flashcard-face flashcard-back absolute inset-0 rounded-[1.75rem] bg-gradient-to-br from-amber-200 via-amber-100 to-white p-6 text-slate-950 shadow-xl shadow-amber-100/60">
                                            <div class="pill bg-slate-950/10 text-slate-700">Back</div>
                                            <p class="mt-5 text-lg font-semibold leading-8">{{ $flashcard->back_content }}</p>
                                            <p class="absolute bottom-6 text-sm text-slate-500">Tap again to review the prompt</p>
                                        </div>
                                    </button>
                                </div>

                                <form action="{{ route('client.progress.update', $flashcard) }}" method="POST" class="mt-5 space-y-3">
                                    @csrf
                                    <label class="block text-sm font-semibold text-slate-700">Study progress</label>
                                    <div class="grid gap-2 sm:grid-cols-3">
                                        @foreach($statuses as $status)
                                            <label class="cursor-pointer rounded-2xl border px-3 py-3 text-center text-sm font-semibold transition {{ optional($progress)->status === $status ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-sky-300 hover:bg-sky-50' }}">
                                                <input type="radio" name="status" value="{{ $status }}" class="sr-only" {{ optional($progress)->status === $status ? 'checked' : '' }}>
                                                {{ ucfirst($status) }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="w-full rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Save progress</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="glass-panel p-8 text-center text-slate-500">No active decks are available right now.</div>
            @endforelse
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-flashcard]').forEach((card) => {
            card.addEventListener('click', () => card.classList.toggle('is-flipped'));
        });
    </script>
</x-layouts.client>
