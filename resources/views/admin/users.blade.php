@extends('layouts.admin', [
    'title' => 'Quản lý người dùng',
    'sidebar' => true,
])

@section('content')

{{-- Page heading --}}
<div class="mb-8 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Quản lý người dùng</h1>
        <p class="text-slate-400 mt-1 text-sm">{{ $users->count() }} tài khoản trong hệ thống</p>
    </div>
</div>

{{-- Users Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <span class="text-white font-semibold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Danh sách người dùng
        </span>
        <span class="badge badge-admin">{{ $users->where('role', 'admin')->count() }} Admin</span>
    </div>

    @if($users->isEmpty())
        <div class="admin-empty">
            <svg class="w-12 h-12 mx-auto text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p>Chưa có người dùng nào.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Kinh nghiệm</th>
                        <th>Chuỗi học</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        {{-- Name + avatar --}}
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-white">{{ $user->name }}</span>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="text-slate-400">{{ $user->email }}</td>

                        {{-- Role badge --}}
                        <td>
                            <span class="badge {{ $user->isAdmin() ? 'badge-admin' : 'badge-client' }}">
                                {{ $user->isAdmin() ? 'Admin' : 'Client' }}
                            </span>
                        </td>

                        {{-- XP --}}
                        <td>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-slate-300">{{ number_format($user->experience_points) }} XP</span>
                            </div>
                        </td>

                        {{-- Streak --}}
                        <td>
                            <div class="flex items-center gap-1.5">
                                <span class="text-orange-400">🔥</span>
                                <span class="text-slate-300">{{ $user->daily_streak }} ngày</span>
                            </div>
                        </td>

                        {{-- Created at --}}
                        <td class="text-slate-500 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="admin-table-actions">
                                {{-- Toggle Role --}}
                                @unless(auth('admin')->user()?->is($user))
                                    <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn-admin-action {{ $user->isAdmin() ? 'warning' : 'info' }}"
                                            data-admin-confirm
                                            data-confirm-message="Đổi quyền của {{ $user->name }} thành {{ $user->isAdmin() ? 'Client' : 'Admin' }}?"
                                            data-confirm-accept="Xác nhận">
                                            {{ $user->isAdmin() ? '↓ Hạ Client' : '↑ Lên Admin' }}
                                        </button>
                                    </form>

                                    {{-- Delete User --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn-admin-action danger"
                                            data-admin-confirm
                                            data-confirm-message="Xóa tài khoản {{ $user->name }}? Toàn bộ dữ liệu học tập sẽ bị xóa."
                                            data-confirm-accept="Xóa người dùng">
                                            Xóa
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-600 text-xs italic">Bạn</span>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

