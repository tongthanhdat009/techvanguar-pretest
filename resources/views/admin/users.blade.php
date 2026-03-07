<x-layouts.admin :title="'Manage Users'" :breadcrumb="[['label' => 'Dashboard', 'url' => route('admin.overview')], ['label' => 'Users', 'url' => null]]">
    <section class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-slate-950">Manage Users</h1>
            <p class="mt-2 text-slate-600">Create and manage user accounts.</p>
        </div>

        <div class="glass-panel p-6">
            <h2 class="section-title">Create new user</h2>
            <form action="{{ route('admin.users.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <input type="text" name="name" placeholder="Full name" class="field-input" required>
                    <input type="email" name="email" placeholder="Email address" class="field-input" required>
                    <input type="password" name="password" placeholder="Temporary password" class="field-input" required>
                    <select name="role" class="field-input">
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <textarea name="bio" rows="3" class="field-input" placeholder="Bio (optional)"></textarea>
                <button type="submit" class="primary-button">Add user</button>
            </form>
        </div>

        <div class="glass-panel p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">All users ({{ $users->count() }})</h2>
            @if($users->isEmpty())
                <x-shared.empty-state message="No users found." />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b border-slate-200">
                            <tr class="text-sm text-slate-600">
                                <th class="pb-3 font-semibold">Name</th>
                                <th class="pb-3 font-semibold">Email</th>
                                <th class="pb-3 font-semibold">Role</th>
                                <th class="pb-3 font-semibold">XP / Streak</th>
                                <th class="pb-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                                <tr class="text-sm">
                                    <td class="py-3">
                                        <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-slate-500">{{ $user->bio ? Str::limit($user->bio, 50) : 'No bio' }}</div>
                                    </td>
                                    <td class="py-3 text-slate-600">{{ $user->email }}</td>
                                    <td class="py-3">
                                        <span class="pill @if($user->role === 'admin') bg-sky-100 text-sky-700 @else bg-slate-100 text-slate-700 @endif">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-slate-600">
                                        {{ $user->experience_points }} XP · {{ $user->daily_streak }} day streak
                                    </td>
                                    <td class="py-3 text-right">
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-700 text-sm font-medium"
                                                    data-confirm-title="Delete user"
                                                    data-confirm-message="Are you sure you want to delete this user?"
                                                    data-confirm-accept="Delete user">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
