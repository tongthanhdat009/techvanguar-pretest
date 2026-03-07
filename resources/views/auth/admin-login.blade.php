<x-layouts.app :title="'Admin Login'">
    <section class="mx-auto max-w-xl glass-panel p-8">
        <span class="pill bg-sky-100 text-sky-700">Admin portal</span>
        <h1 class="mt-4 text-3xl font-black text-slate-950">Sign in to manage users, content, moderation, and statistics.</h1>
        <form action="{{ route('admin.login.attempt') }}" method="POST" class="mt-8 space-y-4">
            @csrf
            <div>
                <label class="field-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Password</label>
                <input type="password" name="password" class="field-input" required>
            </div>
            <button type="submit" class="primary-button w-full">Login as admin</button>
        </form>
    </section>
</x-layouts.app>
