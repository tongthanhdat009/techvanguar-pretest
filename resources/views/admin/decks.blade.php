<x-layouts.admin :title="'Manage Decks'" :breadcrumb="[['label' => 'Dashboard', 'url' => route('admin.overview')], ['label' => 'Decks', 'url' => null]]">
    <section class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-slate-950">Manage Decks</h1>
            <p class="mt-2 text-slate-600">Create, import, and manage flashcard decks.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h2 class="section-title">Create deck</h2>
                <form action="{{ route('admin.decks.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <select name="user_id" class="field-input">
                        <option value="">Global/community deck</option>
                        @auth
                            @foreach(auth()->user()->role === 'admin' ? \App\Models\User::latest()->get() : [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                            @endforeach
                        @endauth
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

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h2 class="section-title">Import CSV deck</h2>
                <form action="{{ route('admin.decks.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
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
                        @foreach(\App\Models\User::latest()->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input type="file" name="csv_file" accept=".csv" class="field-input" required>
                    <p class="text-xs text-slate-500">CSV headers: front_content, back_content, image_url, audio_url, hint</p>
                    <button type="submit" class="primary-button w-full">Import CSV</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">All decks ({{ $decks->count() }})</h2>
            @if($decks->isEmpty())
                <x-empty-state message="No decks found." />
            @else
                <div class="mt-4 space-y-4">
                    @foreach($decks as $deck)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-slate-300 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-slate-900">{{ $deck->title }}</h3>
                                        <span class="pill @if($deck->visibility === 'public') bg-emerald-100 text-emerald-700 @else bg-slate-100 text-slate-700 @endif">
                                            {{ $deck->visibility }}
                                        </span>
                                        @if(!$deck->is_active)
                                            <span class="pill bg-amber-100 text-amber-700">Inactive</span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-slate-600">{{ $deck->description ?? 'No description' }}</p>
                                    <div class="mt-2 flex items-center gap-4 text-sm text-slate-500">
                                        <span>{{ $deck->flashcards_count }} flashcards</span>
                                        <span>By {{ $deck->owner?->name ?? 'System' }}</span>
                                        @if($deck->reviews_avg_rating)
                                            <span>★ {{ number_format($deck->reviews_avg_rating, 1) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.decks.export', $deck) }}"
                                       class="text-sky-600 hover:text-sky-700 text-sm font-medium">
                                        Export
                                    </a>
                                    <form action="{{ route('admin.decks.destroy', $deck) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-700 text-sm font-medium"
                                                data-confirm-title="Delete deck"
                                                data-confirm-message="Delete this deck and all its flashcards?"
                                                data-confirm-accept="Delete deck">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
