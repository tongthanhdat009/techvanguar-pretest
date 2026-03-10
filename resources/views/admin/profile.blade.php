@extends('layouts.admin', [
    'title' => 'Hồ sơ',
    'sidebar' => true,
    'header' => [
        'title' => 'Hồ sơ cá nhân',
        'subtitle' => 'Quản lý thông tin cá nhân của bạn',
    ],
])

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Avatar Section --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-white font-medium mb-4">Ảnh đại diện</h3>
            <div class="flex items-center gap-6">
                <div>
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-white font-medium">{{ $user->name }}</p>
                    <p class="text-sm text-slate-400">{{ $user->email }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ảnh đại diện hiện tại của tài khoản quản trị.</p>
                </div>
            </div>
        </div>

        {{-- Personal Info --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-white font-medium mb-4">Thông tin cá nhân</h3>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-400 mb-2">Họ và tên</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Nhập họ và tên"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Nhập email"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-slate-400 mb-2">Giới thiệu</label>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"
                        placeholder="Viết một chút về bản thân..."
                    >{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Role Info (Read-only) --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-white font-medium mb-4">Thông tin vai trò</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Vai trò</label>
                    <div class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-xl text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $user->isAdmin() ? 'bg-indigo-500' : 'bg-emerald-500' }}"></span>
                        {{ $user->isAdmin() ? 'Quản trị viên' : 'NgườI dùng' }}
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Trạng thái</label>
                    <div class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-xl text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $user->isActive() ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $user->isActive() ? 'Hoạt động' : 'Không hoạt động' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl transition-colors">
                Lưu thay đổi
            </button>
            <a href="{{ route('admin.overview') }}" class="px-6 py-2.5 text-slate-400 hover:text-white font-medium transition-colors">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection
