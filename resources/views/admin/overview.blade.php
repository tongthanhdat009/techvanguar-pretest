<x-layouts.admin :title="'Admin Dashboard'" :breadcrumb="[['label' => 'Dashboard', 'url' => null]]">
    <section class="space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-slate-950">Platform Overview</h1>
            <p class="text-slate-600">Key metrics and quick actions for the flashcard learning platform.</p>
        </div>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-shared.stat label="Users" :value="$stats['users']" tone="sky">
                clients {{ $stats['clients'] }} · admins {{ $stats['admins'] }}
            </x-shared.stat>
            <x-shared.stat label="Decks" :value="$stats['decks']" tone="amber">
                public {{ $stats['public_decks'] }}
            </x-shared.stat>
            <x-shared.stat label="Flashcards" :value="$stats['flashcards']" tone="emerald" />
            <x-shared.stat label="Reviews" :value="$stats['reviews']" tone="slate">
                mastered {{ $stats['mastered'] }}
            </x-shared.stat>
        </section>

        <section class="grid gap-6 md:grid-cols-3">
            <a href="{{ route('admin.users') }}" class="glass-panel p-6 transition hover:border-sky-400">
                <p class="section-kicker">Users</p>
                <h2 class="mt-3 text-xl font-bold text-slate-900">Manage users</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Create, update, and review all learner and admin accounts.</p>
                <div class="mt-4 text-sm font-semibold uppercase tracking-[0.18em] text-sky-700">{{ $stats['users'] }} total</div>
            </a>
            <a href="{{ route('admin.decks') }}" class="glass-panel p-6 transition hover:border-amber-400">
                <p class="section-kicker">Decks</p>
                <h2 class="mt-3 text-xl font-bold text-slate-900">Manage decks</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Control public visibility, imports, and flashcard quality across the library.</p>
                <div class="mt-4 text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">{{ $stats['decks'] }} total</div>
            </a>
            <a href="{{ route('admin.reviews') }}" class="glass-panel p-6 transition hover:border-slate-500">
                <p class="section-kicker">Reviews</p>
                <h2 class="mt-3 text-xl font-bold text-slate-900">Moderate feedback</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Keep community signals useful by reviewing submitted ratings and comments.</p>
                <div class="mt-4 text-sm font-semibold uppercase tracking-[0.18em] text-slate-700">{{ $stats['reviews'] }} total</div>
            </a>
        </section>
    </section>
</x-layouts.admin>
