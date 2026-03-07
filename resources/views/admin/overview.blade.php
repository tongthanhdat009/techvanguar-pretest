<x-layouts.admin :title="'Admin Dashboard'" :breadcrumb="[['label' => 'Dashboard', 'url' => null]]">
    <section class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-slate-950">Platform Overview</h1>
            <p class="mt-2 text-slate-600">Key metrics and statistics for your flashcard learning platform.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-600">Users</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['users'] }}</p>
                <p class="mt-1 text-xs text-slate-500">clients {{ $stats['clients'] }} · admins {{ $stats['admins'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-600">Decks</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['decks'] }}</p>
                <p class="mt-1 text-xs text-slate-500">public {{ $stats['public_decks'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-600">Flashcards</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['flashcards'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-600">Reviews</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['reviews'] }}</p>
                <p class="mt-1 text-xs text-slate-500">mastered {{ $stats['mastered'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <a href="{{ route('admin.users') }}" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md hover:border-sky-300 transition cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-sky-100 rounded-lg">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Manage Users</h2>
                        <p class="text-sm text-slate-500">{{ $stats['users'] }} total</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.decks') }}" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md hover:border-amber-300 transition cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Manage Decks</h2>
                        <p class="text-sm text-slate-500">{{ $stats['decks'] }} total</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.reviews') }}" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md hover:border-slate-400 transition cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Moderate Reviews</h2>
                        <p class="text-sm text-slate-500">{{ $stats['reviews'] }} total</p>
                    </div>
                </div>
            </a>
        </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-stat-card label="Users" :value="$stats['users']" tone="sky">
                    clients {{ $stats['clients'] }} · admins {{ $stats['admins'] }}
                </x-stat-card>
                <x-stat-card label="Decks" :value="$stats['decks']" tone="amber">
                    public {{ $stats['public_decks'] }}
                </x-stat-card>
                <x-stat-card label="Flashcards" :value="$stats['flashcards']" tone="emerald" />
                <x-stat-card label="Reviews" :value="$stats['reviews']" tone="slate">
                    mastered {{ $stats['mastered'] }}
                </x-stat-card>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <a href="{{ route('admin.users') }}" class="glass-panel p-6 hover:ring-2 hover:ring-sky-500 transition">
                <h2 class="text-xl font-bold text-slate-900">Manage Users</h2>
                <p class="mt-2 text-slate-600">Create, edit, and manage user accounts.</p>
                <div class="mt-4 text-sky-600 font-medium">View all users →</div>
            </a>
            <a href="{{ route('admin.decks') }}" class="glass-panel p-6 hover:ring-2 hover:ring-amber-500 transition">
                <h2 class="text-xl font-bold text-slate-900">Manage Decks</h2>
                <p class="mt-2 text-slate-600">Create, import, and manage flashcard decks.</p>
                <div class="mt-4 text-amber-600 font-medium">View all decks →</div>
            </a>
            <a href="{{ route('admin.reviews') }}" class="glass-panel p-6 hover:ring-2 hover:ring-slate-500 transition">
                <h2 class="text-xl font-bold text-slate-900">Moderate Reviews</h2>
                <p class="mt-2 text-slate-600">Review and moderate community feedback.</p>
                <div class="mt-4 text-slate-600 font-medium">View reviews →</div>
            </a>
        </div>
    </section>
</x-layouts.app>
