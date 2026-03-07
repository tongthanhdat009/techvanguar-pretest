<x-layouts.app :title="'Profile'">
    <section class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <div class="glass-panel p-8">
            <span class="pill bg-emerald-100 text-emerald-700">Learner profile</span>
            <h1 class="mt-4 text-3xl font-black text-slate-950">{{ $user->name }}</h1>
            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $user->bio ?: 'Add a short bio to personalize your account.' }}</p>
            <div class="mt-6 grid gap-4">
                <x-stat-card label="Level" :value="$user->level()" tone="sky">XP {{ $user->experience_points }}</x-stat-card>
                <x-stat-card label="Daily Streak" :value="$user->daily_streak" tone="amber">days</x-stat-card>
                <x-stat-card label="Decks" :value="$user->decks_count" tone="slate" />
                <x-stat-card label="Mastered" :value="$stats['mastered']" tone="emerald">cards</x-stat-card>
            </div>
        </div>

        <div class="glass-panel p-8">
            <h2 class="section-title">Edit profile</h2>
            <form action="{{ route('client.profile.update') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="field-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label">Bio</label>
                    <textarea name="bio" rows="5" class="field-input">{{ old('bio', $user->bio) }}</textarea>
                </div>
                <button type="submit" class="primary-button w-full">Save profile</button>
            </form>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <x-stat-card label="Reviews" :value="$stats['reviews']" tone="sky" />
                <x-stat-card label="Due Today" :value="$stats['due_today']" tone="amber" />
                <x-stat-card label="Last Study" :value="$user->last_studied_at?->format('M d') ?? 'N/A'" tone="slate" />
            </div>
        </div>
    </section>
</x-layouts.app>
