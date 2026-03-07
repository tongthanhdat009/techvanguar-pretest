<x-layouts.app :title="'Admin Dashboard'">
    <section class="space-y-6">
        <div class="glass-panel p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="pill bg-sky-100 text-sky-700">Admin dashboard</span>
                    <h1 class="mt-4 text-3xl font-black text-slate-950">Manage users, decks, flashcards, and learning statistics.</h1>
                    <p class="mt-3 max-w-3xl text-slate-600">This dashboard gives admins quick CRUD controls while the JWT admin APIs provide programmatic access to the same data.</p>
                </div>
                <div class="rounded-3xl bg-slate-950 px-5 py-4 text-sm text-white">
                    <div class="font-semibold">Statistics endpoint</div>
                    <div class="mt-1 text-slate-300">GET /api/admin/statistics</div>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <div class="dashboard-card"><div class="text-sm font-semibold text-slate-500">Users</div><div class="mt-2 text-3xl font-black">{{ $stats['users'] }}</div></div>
                <div class="dashboard-card"><div class="text-sm font-semibold text-slate-500">Decks</div><div class="mt-2 text-3xl font-black">{{ $stats['decks'] }}</div></div>
                <div class="dashboard-card"><div class="text-sm font-semibold text-slate-500">Active decks</div><div class="mt-2 text-3xl font-black">{{ $stats['active_decks'] }}</div></div>
                <div class="dashboard-card"><div class="text-sm font-semibold text-slate-500">Flashcards</div><div class="mt-2 text-3xl font-black">{{ $stats['flashcards'] }}</div></div>
                <div class="dashboard-card"><div class="text-sm font-semibold text-slate-500">Mastered entries</div><div class="mt-2 text-3xl font-black">{{ $stats['mastered'] }}</div></div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="glass-panel p-6">
                <h2 class="text-xl font-bold text-slate-950">Create user</h2>
                <form action="{{ route('admin.users.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Full name" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    <input type="email" name="email" placeholder="Email address" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    <input type="password" name="password" placeholder="Temporary password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    <select name="role" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="w-full rounded-2xl bg-slate-950 px-4 py-3 font-semibold text-white">Add user</button>
                </form>
            </section>

            <section class="glass-panel p-6">
                <h2 class="text-xl font-bold text-slate-950">Create deck</h2>
                <form action="{{ route('admin.decks.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="text" name="title" placeholder="Deck title" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    <textarea name="description" rows="4" placeholder="Deck description" class="w-full rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                    <select name="is_active" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <button type="submit" class="w-full rounded-2xl bg-sky-500 px-4 py-3 font-semibold text-white">Add deck</button>
                </form>
            </section>

            <section class="glass-panel p-6">
                <h2 class="text-xl font-bold text-slate-950">Create flashcard</h2>
                <form action="{{ route('admin.flashcards.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <select name="deck_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                        @foreach($decks as $deck)
                            <option value="{{ $deck->id }}">{{ $deck->title }}</option>
                        @endforeach
                    </select>
                    <textarea name="front_content" rows="3" placeholder="Front content" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required></textarea>
                    <textarea name="back_content" rows="3" placeholder="Back content" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required></textarea>
                    <button type="submit" class="w-full rounded-2xl bg-amber-300 px-4 py-3 font-semibold text-slate-950">Add flashcard</button>
                </form>
            </section>
        </div>

        <section class="grid gap-6 2xl:grid-cols-[0.7fr_1.3fr]">
            <div class="glass-panel p-6">
                <h2 class="text-xl font-bold text-slate-950">Manage users</h2>
                <div class="mt-5 space-y-4">
                    @foreach($users as $user)
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <input type="text" name="name" value="{{ $user->name }}" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                                    <input type="email" name="email" value="{{ $user->email }}" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <input type="password" name="password" placeholder="Leave blank to keep password" class="rounded-2xl border border-slate-200 px-4 py-3">
                                    <select name="role" class="rounded-2xl border border-slate-200 px-4 py-3">
                                        <option value="client" @selected($user->role === 'client')>Client</option>
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    </select>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" class="rounded-2xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Save</button>
                                </div>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-2xl border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600">Delete user</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                @foreach($decks as $deck)
                    <section class="glass-panel p-6">
                        <form action="{{ route('admin.decks.update', $deck) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-start">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <input type="text" name="title" value="{{ $deck->title }}" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                                    <select name="is_active" class="rounded-2xl border border-slate-200 px-4 py-3">
                                        <option value="1" @selected($deck->is_active)>Active</option>
                                        <option value="0" @selected(! $deck->is_active)>Inactive</option>
                                    </select>
                                </div>
                                <button type="submit" class="rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white">Save deck</button>
                            </div>
                            <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $deck->description }}</textarea>
                        </form>
                        <form action="{{ route('admin.decks.destroy', $deck) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-2xl border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600">Delete deck</button>
                        </form>

                        <div class="mt-6 grid gap-4">
                            @foreach($deck->flashcards as $flashcard)
                                <div class="rounded-3xl border border-sky-100 bg-sky-50/70 p-4">
                                    <form action="{{ route('admin.flashcards.update', $flashcard) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="deck_id" value="{{ $deck->id }}">
                                        <textarea name="front_content" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>{{ $flashcard->front_content }}</textarea>
                                        <textarea name="back_content" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>{{ $flashcard->back_content }}</textarea>
                                        <div class="flex flex-wrap gap-3">
                                            <button type="submit" class="rounded-2xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Save flashcard</button>
                                        </div>
                                    </form>
                                    <form action="{{ route('admin.flashcards.destroy', $flashcard) }}" method="POST" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-2xl border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600">Delete flashcard</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </section>
    </section>
</x-layouts.app>
