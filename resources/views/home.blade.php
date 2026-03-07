<x-layouts.app :title="'Flashcard Learning Application'">
    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="glass-panel overflow-hidden p-8">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    <span class="pill bg-sky-100 text-sky-700">End-to-end learning flow</span>
                    <h1 class="mt-5 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">Study smarter with active flashcard decks and role-based dashboards.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-600">This app includes JWT authentication APIs, an admin dashboard for managing content, and a client portal with animated flip cards, progress tracking, and active deck filtering.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.overview') : route('client.portal') }}" class="rounded-full bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">Open your dashboard</a>
                        @else
                            <a href="#auth-panels" class="rounded-full bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">Login or register</a>
                        @endauth
                        <a href="#api-overview" class="rounded-full border border-slate-200 px-6 py-3 font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50">View API overview</a>
                    </div>
                </div>
                <div class="rounded-[2rem] bg-gradient-to-br from-sky-400 via-cyan-300 to-amber-200 p-6 text-slate-950 shadow-2xl shadow-sky-200/80">
                    <h2 class="text-lg font-bold">Seeded demo credentials</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="rounded-2xl bg-white/75 p-4">
                            <div class="font-semibold">Admin</div>
                            <div>admin@example.com</div>
                            <div>Password: password</div>
                        </div>
                        <div class="rounded-2xl bg-white/75 p-4">
                            <div class="font-semibold">Client</div>
                            <div>client@example.com</div>
                            <div>Password: password</div>
                        </div>
                    </div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Use the same credentials with the JWT auth endpoints under <code class="rounded bg-slate-950/10 px-2 py-1">/api/auth</code>.</p>
                </div>
            </div>
        </section>

        <section id="auth-panels" class="space-y-6">
            @guest
                <div class="glass-panel p-6">
                    <h2 class="text-xl font-bold text-slate-950">Sign in</h2>
                    <form action="{{ route('login') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-slate-950 px-4 py-3 font-semibold text-white transition hover:bg-slate-800">Login</button>
                    </form>
                </div>

                <div class="glass-panel p-6">
                    <h2 class="text-xl font-bold text-slate-950">Create a client account</h2>
                    <form action="{{ route('register') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                                <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Confirm password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-amber-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-amber-200">Register as client</button>
                    </form>
                </div>
            @else
                <div class="glass-panel p-6">
                    <h2 class="text-xl font-bold text-slate-950">Welcome back, {{ $currentUser->name }}!</h2>
                    <p class="mt-2 text-slate-600">You are currently signed in as <span class="font-semibold">{{ $currentUser->role }}</span>. Open your dashboard to continue learning or managing content.</p>
                </div>
            @endguest
        </section>
    </div>

    <section class="mt-8 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="glass-panel p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-950">Available active decks</h2>
                    <p class="mt-2 text-slate-600">Clients only see active study decks, while admins can manage both active and inactive content.</p>
                </div>
                <span class="pill bg-amber-100 text-amber-700">{{ $activeDecks->count() }} live</span>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($activeDecks as $deck)
                    <article class="rounded-3xl border border-sky-100 bg-gradient-to-r from-white to-sky-50 p-5 shadow-sm shadow-sky-100/70">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $deck->title }}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600">{{ $deck->description }}</p>
                            </div>
                            <span class="rounded-full bg-slate-950 px-3 py-1 text-sm font-semibold text-white">{{ $deck->flashcards_count }} cards</span>
                        </div>
                    </article>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-slate-500">No active decks available yet.</p>
                @endforelse
            </div>
        </div>

        <div id="api-overview" class="glass-panel p-8">
            <h2 class="text-2xl font-bold text-slate-950">JWT API overview</h2>
            <div class="mt-6 grid gap-4 text-sm text-slate-700 sm:grid-cols-2">
                <div class="rounded-3xl bg-slate-950 p-5 text-white">
                    <h3 class="font-bold">Auth</h3>
                    <ul class="mt-3 space-y-2 text-slate-200">
                        <li>POST /api/auth/register</li>
                        <li>POST /api/auth/login</li>
                        <li>POST /api/auth/logout</li>
                        <li>GET /api/auth/me</li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-sky-50 p-5">
                    <h3 class="font-bold text-slate-950">Client</h3>
                    <ul class="mt-3 space-y-2">
                        <li>GET /api/client/decks</li>
                        <li>GET /api/client/decks/{deck}</li>
                        <li>GET /api/client/progress</li>
                        <li>PUT /api/client/flashcards/{flashcard}/progress</li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-amber-50 p-5">
                    <h3 class="font-bold text-slate-950">Admin</h3>
                    <ul class="mt-3 space-y-2">
                        <li>CRUD /api/admin/users</li>
                        <li>CRUD /api/admin/decks</li>
                        <li>CRUD /api/admin/flashcards</li>
                        <li>GET /api/admin/statistics</li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-emerald-50 p-5">
                    <h3 class="font-bold text-slate-950">Deployment envs</h3>
                    <ul class="mt-3 space-y-2">
                        <li>.env.dev</li>
                        <li>.env.staging</li>
                        <li>.env.example</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
