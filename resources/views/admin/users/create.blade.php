@extends('layouts.admin', [
    'title' => 'Thêm người dùng mới',
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
        <h1 class="text-2xl font-bold text-white">Thêm người dùng mới</h1>
        <p class="text-slate-400 mt-0.5 text-sm">Tạo tài khoản mới trong hệ thống</p>
    </div>
</div>

<div class="admin-card w-full" style="max-width: 640px">
    <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
        @csrf

        {{-- Name --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="name">
                Tên hiển thị <span class="text-red-400">*</span>
            </label>
            <input id="name" name="name" type="text"
                value="{{ old('name') }}"
                class="admin-form-input {{ $errors->has('name') ? 'has-error' : '' }}"
                placeholder="Nguyễn Văn A"
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
                value="{{ old('email') }}"
                class="admin-form-input {{ $errors->has('email') ? 'has-error' : '' }}"
                placeholder="user@example.com"
                required>
            @error('email')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="password">
                Mật khẩu <span class="text-red-400">*</span>
            </label>
            <input id="password" name="password" type="password"
                class="admin-form-input {{ $errors->has('password') ? 'has-error' : '' }}"
                placeholder="Tối thiểu 8 ký tự"
                required>
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
                <option value="client" {{ old('role', 'client') === 'client' ? 'selected' : '' }}>Client</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
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
                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Vô hiệu hóa (ban)</option>
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
                placeholder="Mô tả ngắn về người dùng (không bắt buộc)">{{ old('bio') }}</textarea>
            @error('bio')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex items-center flex-wrap gap-3 pt-2">
            <button type="submit" class="btn-primary">Tạo tài khoản</button>
            <a href="{{ route('admin.users') }}" class="btn-secondary">Hủy</a>
        </div>

    </form>
</div>

@endsection
