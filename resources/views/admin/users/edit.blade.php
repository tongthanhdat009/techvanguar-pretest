@extends('layouts.admin', [
    'title' => 'Sửa: ' . $user->name,
    'sidebar' => true,
])

@section('content')

{{-- Back + heading --}}
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.users') }}" class="text-slate-400 hover:text-white transition-colors" title="Quay lại danh sách">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-white">Sửa thông tin người dùng</h1>
        <p class="text-slate-400 mt-0.5 text-sm">{{ $user->email }} &mdash; #{{ $user->id }}</p>
    </div>
</div>

<div class="admin-card w-full" style="max-width: 640px">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="name">
                Tên hiển thị <span class="text-red-400">*</span>
            </label>
            <input id="name" name="name" type="text"
                value="{{ old('name', $user->name) }}"
                class="admin-form-input {{ $errors->has('name') ? 'has-error' : '' }}"
                required autofocus>
            @error('name')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="email">
                Email <span class="text-red-400">*</span>
            </label>
            <input id="email" name="email" type="email"
                value="{{ old('email', $user->email) }}"
                class="admin-form-input {{ $errors->has('email') ? 'has-error' : '' }}"
                required>
            @error('email')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="password">Mật khẩu mới</label>
            <input id="password" name="password" type="password"
                class="admin-form-input {{ $errors->has('password') ? 'has-error' : '' }}"
                placeholder="Để trống nếu không muốn đổi mật khẩu">
            @error('password')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Role --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="role">
                Quyền <span class="text-red-400">*</span>
            </label>
            <select id="role" name="role"
                class="admin-form-input {{ $errors->has('role') ? 'has-error' : '' }}" required>
                <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="status">
                Trạng thái <span class="text-red-400">*</span>
            </label>
            <select id="status" name="status"
                class="admin-form-input {{ $errors->has('status') ? 'has-error' : '' }}" required>
                <option value="active"    {{ old('status', $user->status) === 'active'   ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive"  {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Vô hiệu hóa (ban)</option>
            </select>
            @error('status')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bio --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="bio">Giới thiệu bản thân</label>
            <textarea id="bio" name="bio" rows="3"
                class="admin-form-input {{ $errors->has('bio') ? 'has-error' : '' }}"
                placeholder="Mô tả ngắn về người dùng (không bắt buộc)">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Gamification (collapsed section) --}}
        <details class="mb-4">
            <summary class="admin-form-label cursor-pointer select-none mb-2">Thông tin gamification</summary>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="experience_points">Điểm kinh nghiệm (XP)</label>
                    <input id="experience_points" name="experience_points" type="number" min="0"
                        value="{{ old('experience_points', $user->experience_points) }}"
                        class="admin-form-input {{ $errors->has('experience_points') ? 'has-error' : '' }}">
                    @error('experience_points')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="daily_streak">Daily Streak (ngày)</label>
                    <input id="daily_streak" name="daily_streak" type="number" min="0"
                        value="{{ old('daily_streak', $user->daily_streak) }}"
                        class="admin-form-input {{ $errors->has('daily_streak') ? 'has-error' : '' }}">
                    @error('daily_streak')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </details>

        {{-- Buttons --}}
        <div class="flex items-center flex-wrap gap-3 pt-2">
            <button type="submit" class="btn-primary">Lưu thay đổi</button>
            <a href="{{ route('admin.users') }}" class="btn-secondary">Hủy</a>
        </div>

    </form>
</div>

@endsection
