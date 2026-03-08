<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập – Flashcard App</title>
    @vite(['resources/css/app.css'])
    <style>
        .auth-gradient { background: linear-gradient(135deg,#4f46e5 0%,#7c3aed 50%,#a855f7 100%); }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 font-sans antialiased">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl auth-gradient flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">Flashcard <span class="text-indigo-600">App</span></span>
            </a>
            <h1 class="mt-6 text-2xl font-extrabold text-gray-900">Chào mừng trở lại!</h1>
            <p class="mt-1 text-sm text-gray-500">Login as client – đăng nhập để tiếp tục học tập</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            @if($errors->has('login'))
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                {{ $errors->first('login') }}
            </div>
            @endif

            @if(session('status'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('client.login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           placeholder="ban@email.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('email') border-red-400 @enderror" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-sm font-medium text-gray-700">Mật khẩu</label>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" />
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 text-sm font-bold text-white auth-gradient rounded-xl shadow-md hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Đăng nhập
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Chưa có tài khoản?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Đăng ký miễn phí</a>
            </p>
        </div>

        <p class="mt-4 text-center text-xs text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-gray-600 transition-colors">← Về trang chủ</a>
        </p>
    </div>

</body>
</html>
