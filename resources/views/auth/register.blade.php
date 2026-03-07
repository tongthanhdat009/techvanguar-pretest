<x-layouts.app :title="'Create Account'">
    <section class="mx-auto max-w-2xl glass-panel p-8">
        <span class="pill bg-emerald-100 text-emerald-700">Client registration</span>
        <h1 class="mt-4 text-3xl font-black text-slate-950">Create a learner account.</h1>
        <form action="{{ route('register.store') }}" method="POST" class="mt-8 grid gap-4 sm:grid-cols-2">
            @csrf
            <div class="sm:col-span-2">
                <label class="field-label">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="field-input" required>
            </div>
            <div class="sm:col-span-2">
                <label class="field-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Password</label>
                <input type="password" name="password" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Confirm password</label>
                <input type="password" name="password_confirmation" class="field-input" required>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="primary-button w-full">Create client account</button>
            </div>
        </form>
    </section>
</x-layouts.app>
