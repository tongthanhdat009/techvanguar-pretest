@extends('layouts.admin', [
    'title' => 'Tài khoản',
    'sidebar' => true,
    'header' => [
        'title' => 'Tài khoản và bảo mật',
        'subtitle' => 'Quản lý mật khẩu và cài đặt bảo mật',
    ],
])

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Change Password --}}
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-medium">Đổi mật khẩu</h3>
                <p class="text-sm text-slate-400">Cập nhật mật khẩu để bảo vệ tài khoản</p>
            </div>
        </div>

        <form action="{{ route('admin.account.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-400 mb-2">Mật khẩu hiện tại</label>
                <div class="relative">
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Nhập mật khẩu hiện tại"
                    >
                </div>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-400 mb-2">Mật khẩu mới</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập mật khẩu mới (ít nhất 8 ký tự)"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-2">Xác nhận mật khẩu mới</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập lại mật khẩu mới"
                >
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl transition-colors">
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>
    </div>

    {{-- Security Info --}}
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-medium">Thông tin bảo mật</h3>
                <p class="text-sm text-slate-400">Trạng thái bảo mật tài khoản</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-slate-300">Email đã xác thực</span>
                </div>
                <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-full">Đã xác thực</span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-slate-300">Mật khẩu</span>
                </div>
                <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-full">Đã đặt</span>
            </div>

            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-slate-300">Ngày tạo tài khoản</span>
                </div>
                <span class="text-slate-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-medium">Khu vực nguy hiểm</h3>
                <p class="text-sm text-slate-400">Các hành động không thể hoàn tác</p>
            </div>
        </div>

        <div class="flex items-center justify-between py-3 border-t border-red-500/20">
            <div>
                <p class="text-white font-medium">Xóa tài khoản</p>
                <p class="text-sm text-slate-400">Xóa vĩnh viễn tài khoản và tất cả dữ liệu</p>
            </div>
            <button type="button" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-medium rounded-lg transition-colors" disabled>
                Không khả dụng
            </button>
        </div>
    </div>
</div>
@endsection
