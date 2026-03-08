<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Đăng nhập – Flashcard Learning Hub</title>
    @vite(['resources/css/app.css'])
    <style>
        .auth-gradient { background: linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#4338ca 100%); }
    </style>
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center py-12 px-4 font-sans antialiased">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl auth-gradient flex items-center justify-center border border-indigo-700 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white">Admin <span class="text-indigo-400">Portal</span></span>
            </div>
            <h1 class="mt-5 text-2xl font-extrabold text-white">Đăng nhập quản trị</h1>
            <p class="mt-1 text-sm text-slate-400">Login as admin – chỉ dành cho quản trị viên</p>
        </div>

        <div class="bg-slate-800 rounded-2xl border border-slate-700 p-8">

            @if($errors->has('login'))
            <div class="mb-5 p-4 bg-red-900/40 border border-red-700 rounded-xl text-sm text-red-300">
                {{ $errors->first('login') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 text-white rounded-xl text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 text-white rounded-xl text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" />
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 text-sm font-bold text-white auth-gradient rounded-xl shadow-md hover:opacity-90 transition-opacity">
                    Đăng nhập Admin
                </button>
            </form>
        </div>

        <p class="mt-4 text-center text-xs text-slate-600">
            <a href="{{ route('home') }}" class="hover:text-slate-400 transition-colors">← Về trang chủ</a>
        </p>
    </div>

</body>
</html>
