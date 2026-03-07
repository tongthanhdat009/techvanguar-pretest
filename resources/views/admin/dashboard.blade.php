<x-layouts.app :title="'Admin Dashboard'">
    <section class="space-y-6">
        <div class="glass-panel p-8">
            <span class="pill bg-sky-100 text-sky-700">Admin dashboard</span>
            <h1 class="mt-4 text-3xl font-black text-slate-950">Users, decks, flashcards, community moderation, and reporting are separated into focused sections.</h1>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-shared.stat label="Users" :value="$stats['users']" tone="sky">clients {{ $stats['clients'] }} · admins {{ $stats['admins'] }}</x-shared.stat>
                <x-shared.stat label="Decks" :value="$stats['decks']" tone="amber">public {{ $stats['public_decks'] }}</x-shared.stat>
                <x-shared.stat label="Flashcards" :value="$stats['flashcards']" tone="emerald" />
                <x-shared.stat label="Reviews" :value="$stats['reviews']" tone="slate">mastered {{ $stats['mastered'] }}</x-shared.stat>
            </div>
        </div>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="glass-panel p-6">
                <h2 class="section-title">Create user</h2>
                <form action="{{ route('admin.users.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Full name" class="field-input" required>
                    <input type="email" name="email" placeholder="Email address" class="field-input" required>
                    <input type="password" name="password" placeholder="Temporary password" class="field-input" required>
                    <textarea name="bio" rows="3" class="field-input" placeholder="Bio"></textarea>
                    <select name="role" class="field-input">
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="primary-button w-full">Add user</button>
                </form>
            </div>

            <div class="glass-panel p-6">
                <h2 class="section-title">Create deck</h2>
                <form action="{{ route('admin.decks.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <select name="user_id" class="field-input">
                        <option value="">Global/community deck</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                    <input type="text" name="title" placeholder="Deck title" class="field-input" required>
                    <textarea name="description" rows="3" class="field-input" placeholder="Description"></textarea>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <select name="visibility" class="field-input">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                        <select name="is_active" class="field-input">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <input type="text" name="category" class="field-input" placeholder="Category">
                    <input type="text" name="tags" class="field-input" placeholder="tag1, tag2">
                    <button type="submit" class="primary-button w-full">Add deck</button>
                </form>
            </div>

            <div class="glass-panel p-6">
                <h2 class="section-title">Import CSV deck</h2>
                <form action="{{ route('admin.decks.import') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <input type="text" name="title" class="field-input" placeholder="Deck title" required>
                    <textarea name="description" rows="2" class="field-input" placeholder="Description"></textarea>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <select name="visibility" class="field-input">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                        <select name="is_active" class="field-input">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <input type="text" name="category" class="field-input" placeholder="Category">
                    <input type="text" name="tags" class="field-input" placeholder="tag1, tag2">
                    <select name="user_id" class="field-input">
                        <option value="">Global/community deck</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input type="file" name="csv_file" class="field-input" accept=".csv,text/csv" required>
                    <button type="submit" class="primary-button w-full">Import deck</button>
                </form>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
            <div class="glass-panel p-6">
                <h2 class="section-title">Manage users</h2>
                <div class="mt-6 space-y-4">
                    @foreach($users as $user)
                        <article class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $user->name }}" class="field-input" required>
                                <input type="email" name="email" value="{{ $user->email }}" class="field-input" required>
                                <textarea name="bio" rows="2" class="field-input">{{ $user->bio }}</textarea>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <input type="password" name="password" class="field-input" placeholder="Leave blank to keep password">
                                    <select name="role" class="field-input">
                                        <option value="client" @selected($user->role === 'client')>Client</option>
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    </select>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <input type="number" name="experience_points" value="{{ $user->experience_points }}" class="field-input" min="0" required>
                                    <input type="number" name="daily_streak" value="{{ $user->daily_streak }}" class="field-input" min="0" required>
                                </div>
                                <button type="submit" class="primary-button w-full">Save user</button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="secondary-button w-full border-rose-200 text-rose-600 hover:bg-rose-50">Delete user</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-panel p-6">
                    <h2 class="section-title">Moderation</h2>
                    <div class="mt-6 space-y-3">
                        @forelse($reviews as $review)
                            <article class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="font-bold text-slate-950">{{ $review->deck?->title }}</div>
                                        <div class="text-sm text-slate-500">{{ $review->user?->name }} · {{ $review->rating }}/5</div>
                                    </div>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="secondary-button border-rose-200 text-rose-600 hover:bg-rose-50">Remove</button>
                                    </form>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $review->comment }}</p>
                            </article>
                        @empty
                            <x-shared.empty-state title="No recent reviews" description="Community moderation entries will appear here." />
                        @endforelse
                    </div>
                </div>

                @foreach($decks as $deck)
                    <section class="glass-panel p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-950">{{ $deck->title }}</h2>
                                <p class="mt-2 text-sm text-slate-500">
                                    Owner: {{ $deck->owner?->name ?? 'Global' }} · {{ ucfirst($deck->visibility) }} · rating {{ number_format($deck->reviews_avg_rating ?? 0, 1) }}/5
                                </p>
                            </div>
                            <a href="{{ route('admin.decks.export', $deck) }}" class="secondary-button">Export CSV</a>
                        </div>
                        <form action="{{ route('admin.decks.update', $deck) }}" method="POST" class="mt-6 space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid gap-4 sm:grid-cols-2">
                                <select name="user_id" class="field-input">
                                    <option value="">Global/community deck</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected($deck->user_id === $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="title" value="{{ $deck->title }}" class="field-input" required>
                            </div>
                            <textarea name="description" rows="3" class="field-input">{{ $deck->description }}</textarea>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <select name="visibility" class="field-input">
                                    <option value="public" @selected($deck->visibility === 'public')>Public</option>
                                    <option value="private" @selected($deck->visibility === 'private')>Private</option>
                                </select>
                                <select name="is_active" class="field-input">
                                    <option value="1" @selected($deck->is_active)>Active</option>
                                    <option value="0" @selected(! $deck->is_active)>Inactive</option>
                                </select>
                                <input type="text" name="category" value="{{ $deck->category }}" class="field-input" placeholder="Category">
                            </div>
                            <input type="text" name="tags" value="{{ implode(', ', $deck->tags ?? []) }}" class="field-input" placeholder="tag1, tag2">
                            <button type="submit" class="primary-button w-full">Save deck</button>
                        </form>
                        <form action="{{ route('admin.decks.destroy', $deck) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="secondary-button w-full border-rose-200 text-rose-600 hover:bg-rose-50">Delete deck</button>
                        </form>

                        <div class="mt-6 rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <h3 class="text-lg font-bold text-slate-950">Add flashcard</h3>
                            <form action="{{ route('admin.flashcards.store') }}" method="POST" class="mt-4 space-y-4">
                                @csrf
                                <input type="hidden" name="deck_id" value="{{ $deck->id }}">
                                <textarea name="front_content" rows="2" class="field-input" placeholder="Front content" required></textarea>
                                <textarea name="back_content" rows="2" class="field-input" placeholder="Back content" required></textarea>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <input type="url" name="image_url" class="field-input" placeholder="Image URL">
                                    <input type="url" name="audio_url" class="field-input" placeholder="Audio URL">
                                </div>
                                <input type="text" name="hint" class="field-input" placeholder="Hint">
                                <button type="submit" class="primary-button w-full">Add flashcard</button>
                            </form>
                        </div>

                        <div class="mt-6 grid gap-4">
                            @foreach($deck->flashcards as $flashcard)
                                <article class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                    <form action="{{ route('admin.flashcards.update', $flashcard) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="deck_id" value="{{ $deck->id }}">
                                        <textarea name="front_content" rows="2" class="field-input" required>{{ $flashcard->front_content }}</textarea>
                                        <textarea name="back_content" rows="2" class="field-input" required>{{ $flashcard->back_content }}</textarea>
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <input type="url" name="image_url" value="{{ $flashcard->image_url }}" class="field-input" placeholder="Image URL">
                                            <input type="url" name="audio_url" value="{{ $flashcard->audio_url }}" class="field-input" placeholder="Audio URL">
                                        </div>
                                        <input type="text" name="hint" value="{{ $flashcard->hint }}" class="field-input" placeholder="Hint">
                                        <button type="submit" class="primary-button w-full">Save flashcard</button>
                                    </form>
                                    <form action="{{ route('admin.flashcards.destroy', $flashcard) }}" method="POST" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="secondary-button w-full border-rose-200 text-rose-600 hover:bg-rose-50">Delete flashcard</button>
                                    </form>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </section>
    </section>
</x-layouts.app>
