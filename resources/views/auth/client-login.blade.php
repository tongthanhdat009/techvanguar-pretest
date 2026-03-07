<x-layouts.app :title="'Client Login'">
    <section class="mx-auto max-w-xl glass-panel p-8">
        <span class="pill">Client portal</span>
        <h1 class="display-title mt-4 text-3xl">Sign in to study, review due cards, and manage your decks.</h1>
        <form action="{{ route('client.login.attempt') }}" method="POST" class="mt-8 space-y-4">
            @csrf
            <div>
                <label class="field-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Password</label>
                <input type="password" name="password" class="field-input" required>
            </div>
            <button type="submit" class="primary-button w-full">Login as client</button>
        </form>
    </section>

    <x-footer :show-newsletter="false" />
</x-layouts.app>
