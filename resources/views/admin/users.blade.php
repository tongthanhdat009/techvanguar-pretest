@extends('layouts.admin', [
    'title' => 'Quản lý người dùng',
    'sidebar' => true,
])

@push('styles')
<style>
    /* User Form Sidebar Panel */
    #user-form-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 420px;
        max-width: 100vw;
        height: 100vh;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        border-left: 1px solid rgba(96, 165, 250, 0.15);
        box-shadow: -8px 0 32px rgba(0, 0, 0, 0.4);
        z-index: 100;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    #user-form-sidebar.is-open {
        transform: translateX(0);
    }

    #user-form-sidebar .sidebar-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(96, 165, 250, 0.12);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(15, 23, 42, 0.5);
    }

    #user-form-sidebar .sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #f1f5f9;
        font-family: 'Space Grotesk', sans-serif;
    }

    #user-form-sidebar .sidebar-close {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: rgba(239, 68, 68, 0.15);
        border-radius: 0.5rem;
        color: #fca5a5;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #user-form-sidebar .sidebar-close:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #f87171;
    }

    #user-form-sidebar .sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
    }

    #user-form-sidebar .sidebar-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(96, 165, 250, 0.12);
        background: rgba(15, 23, 42, 0.5);
        display: flex;
        gap: 0.75rem;
    }

    #user-form-sidebar .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 99;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    #user-form-sidebar .sidebar-overlay.is-open {
        opacity: 1;
        visibility: visible;
    }

    /* Form styles in sidebar */
    .sidebar-form-group {
        margin-bottom: 1rem;
    }

    .sidebar-form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 0.35rem;
        letter-spacing: 0.01em;
    }

    .sidebar-form-input {
        width: 100%;
        padding: 0.65rem 0.85rem;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid #475569;
        border-radius: 0.5rem;
        color: #f1f5f9;
        font-size: 0.875rem;
        transition: all 0.25s ease;
    }

    .sidebar-form-input:focus {
        outline: none;
        border-color: #60a5fa;
        background: rgba(30, 41, 59, 0.95);
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15);
    }

    .sidebar-form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        padding-right: 2.5rem;
    }

    .sidebar-form-error {
        margin-top: 0.35rem;
        font-size: 0.75rem;
        color: #fca5a5;
        background: rgba(185, 28, 28, 0.15);
        padding: 0.35rem 0.6rem;
        border-radius: 0.375rem;
        border-left: 3px solid #ef4444;
    }

    .sidebar-btn-primary {
        flex: 1;
        padding: 0.7rem 1.25rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 0.625rem;
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .sidebar-btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-1px);
    }

    .sidebar-btn-secondary {
        padding: 0.7rem 1.25rem;
        background: rgba(51, 65, 85, 0.5);
        border: 1px solid #475569;
        border-radius: 0.625rem;
        color: #cbd5e1;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .sidebar-btn-secondary:hover {
        background: rgba(71, 85, 105, 0.7);
        color: white;
    }

    .sidebar-section {
        margin-bottom: 1.25rem;
    }

    .sidebar-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #60a5fa;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.75rem;
    }

    @media (max-width: 480px) {
        #user-form-sidebar {
            width: 100vw;
        }
    }

    /* Action Dropdown Menu */
    .action-dropdown {
        position: relative;
        display: inline-block;
    }

    .action-dropdown-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: rgba(71, 85, 105, 0.5);
        border-radius: 0.5rem;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-dropdown-btn:hover {
        background: rgba(96, 165, 250, 0.2);
        color: #93c5fd;
    }

    .action-dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.35rem;
        min-width: 180px;
        background: rgba(30, 41, 59, 0.98);
        border: 1px solid rgba(96, 165, 250, 0.2);
        border-radius: 0.625rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        z-index: 50;
        display: none;
        overflow: hidden;
    }

    .action-dropdown-menu.is-open {
        display: block;
    }

    .action-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        width: 100%;
        padding: 0.65rem 0.85rem;
        border: none;
        background: transparent;
        color: #cbd5e1;
        font-size: 0.85rem;
        text-align: left;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .action-dropdown-item:hover {
        background: rgba(59, 130, 246, 0.1);
        color: #93c5fd;
    }

    .action-dropdown-item.danger:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .action-dropdown-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .action-dropdown-divider {
        height: 1px;
        background: rgba(71, 85, 105, 0.5);
        margin: 0.25rem 0;
    }

    /* Action buttons compact */
    .action-quick-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.65rem;
        border-radius: 0.375rem;
        border: none;
        background: transparent;
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-quick-btn:hover {
        background: rgba(71, 85, 105, 0.4);
        color: #e2e8f0;
    }

    .action-quick-btn.edit:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
    }

    .action-quick-btn.delete:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    /* Status toggle switch in table */
    .status-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .status-toggle.active {
        background: rgba(34, 197, 94, 0.15);
        color: #86efac;
        border-color: rgba(34, 197, 94, 0.3);
    }

    .status-toggle.inactive {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        border-color: rgba(239, 68, 68, 0.3);
    }

    .status-toggle:hover {
        transform: scale(1.05);
    }

    .role-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.65rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .role-toggle.admin {
        background: rgba(139, 92, 246, 0.15);
        color: #c4b5fd;
        border-color: rgba(139, 92, 246, 0.3);
    }

    .role-toggle.client {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        border-color: rgba(59, 130, 246, 0.3);
    }

    .role-toggle:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<!-- User Form Sidebar Panel -->
<div id="user-form-sidebar">
    <div class="sidebar-overlay" data-sidebar-close></div>
    <div class="sidebar-header">
        <h2 class="sidebar-title" id="sidebar-title">Thêm người dùng mới</h2>
        <button type="button" class="sidebar-close" data-sidebar-close aria-label="Đóng">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>

    <form id="user-form" class="sidebar-content" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" id="form-method" value="POST">

        <!-- Basic Info Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Thông tin cơ bản</div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-name">
                    Tên hiển thị <span class="text-red-400">*</span>
                </label>
                <input id="form-name" name="name" type="text"
                    class="sidebar-form-input"
                    placeholder="Nguyễn Văn A"
                    required>
                <div class="sidebar-form-error" id="error-name" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-email">
                    Email <span class="text-red-400">*</span>
                </label>
                <input id="form-email" name="email" type="email"
                    class="sidebar-form-input"
                    placeholder="user@example.com"
                    required>
                <div class="sidebar-form-error" id="error-email" style="display: none;"></div>
            </div>
        </div>

        <!-- Account Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Tài khoản</div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-password">
                    Mật khẩu <span id="password-required" class="text-red-400">*</span>
                </label>
                <input id="form-password" name="password" type="password"
                    class="sidebar-form-input"
                    placeholder="Tối thiểu 8 ký tự">
                <div class="sidebar-form-error" id="error-password" style="display: none;"></div>
                <p class="text-xs text-slate-500 mt-1" id="password-hint">Tối thiểu 8 ký tự</p>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-role">
                    Quyền <span class="text-red-400">*</span>
                </label>
                <select id="form-role" name="role"
                    class="sidebar-form-input sidebar-form-select" required>
                    <option value="client">Client</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="sidebar-form-error" id="error-role" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-status">
                    Trạng thái <span class="text-red-400">*</span>
                </label>
                <select id="form-status" name="status"
                    class="sidebar-form-input sidebar-form-select" required>
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Vô hiệu hóa (ban)</option>
                </select>
                <div class="sidebar-form-error" id="error-status" style="display: none;"></div>
            </div>
        </div>

        <!-- Bio Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Giới thiệu</div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-bio">Giới thiệu bản thân</label>
                <textarea id="form-bio" name="bio" rows="3"
                    class="sidebar-form-input"
                    placeholder="Mô tả ngắn về người dùng (không bắt buộc)"></textarea>
                <div class="sidebar-form-error" id="error-bio" style="display: none;"></div>
            </div>
        </div>

        <!-- Gamification Section (Edit only) -->
        <div class="sidebar-section" id="gamification-section" style="display: none;">
            <details>
                <summary class="sidebar-form-label cursor-pointer select-none">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Thông tin gamification
                </summary>
                <div class="grid grid-cols-2 gap-3 mt-3 pl-6">
                    <div class="sidebar-form-group">
                        <label class="sidebar-form-label" for="form-xp">Điểm XP</label>
                        <input id="form-xp" name="experience_points" type="number" min="0"
                            class="sidebar-form-input" value="0">
                    </div>
                    <div class="sidebar-form-group">
                        <label class="sidebar-form-label" for="form-streak">Daily Streak</label>
                        <input id="form-streak" name="daily_streak" type="number" min="0"
                            class="sidebar-form-input" value="0">
                    </div>
                </div>
            </details>
        </div>
    </form>

    <div class="sidebar-footer">
        <button type="button" class="sidebar-btn-secondary" data-sidebar-close>Hủy</button>
        <button type="submit" form="user-form" class="sidebar-btn-primary" id="form-submit-btn">
            Tạo tài khoản
        </button>
    </div>
</div>

{{-- Page heading --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Quản lý người dùng</h1>
        <p class="text-slate-400 mt-1 text-sm">{{ $users->total() }} tài khoản trong hệ thống</p>
    </div>
    <button type="button" class="btn-primary" data-sidebar-open>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Thêm người dùng
    </button>
</div>

{{-- Search & Filter Bar --}}
<form method="GET" action="{{ route('admin.users') }}" class="admin-filter-bar">
    <div class="admin-filter-search">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tìm kiếm theo tên, email...">
    </div>

    <div class="admin-filter-group">
        <select name="role" class="admin-filter-select">
            <option value="">Tất cả quyền</option>
            <option value="admin" {{ ($filters['role'] ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="client" {{ ($filters['role'] ?? '') === 'client' ? 'selected' : '' }}>Client</option>
        </select>

        <select name="status" class="admin-filter-select">
            <option value="">Tất cả trạng thái</option>
            <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Hoạt động</option>
            <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Bị ban</option>
        </select>

        <select name="per_page" class="admin-filter-select" onchange="this.form.submit()">
            <option value="5" {{ ($filters['per_page'] ?? 5) == 5 ? 'selected' : '' }}>5 / trang</option>
            <option value="10" {{ ($filters['per_page'] ?? 5) == 10 ? 'selected' : '' }}>10 / trang</option>
            <option value="20" {{ ($filters['per_page'] ?? 5) == 20 ? 'selected' : '' }}>20 / trang</option>
            <option value="50" {{ ($filters['per_page'] ?? 5) == 50 ? 'selected' : '' }}>50 / trang</option>
        </select>

        <button type="submit" class="admin-filter-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Lọc
        </button>

        @if(!empty($filters['search']) || !empty($filters['role']) || !empty($filters['status']))
        <a href="{{ route('admin.users') }}" class="admin-filter-clear">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Xóa bộ lọc
        </a>
        @endif
    </div>
</form>

{{-- Active filter badges --}}
@if(!empty($filters['search']) || !empty($filters['role']) || !empty($filters['status']))
<div class="admin-filter-badges">
    @if(!empty($filters['search']))
    <span class="admin-filter-badge">
        Tìm: "{{ $filters['search'] }}"
    </span>
    @endif
    @if(!empty($filters['role']))
    <span class="admin-filter-badge">
        Quyền: {{ $filters['role'] === 'admin' ? 'Admin' : 'Client' }}
    </span>
    @endif
    @if(!empty($filters['status']))
    <span class="admin-filter-badge">
        Trạng thái: {{ $filters['status'] === 'active' ? 'Hoạt động' : 'Bị ban' }}
    </span>
    @endif
</div>
@endif

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
        {{-- Desktop table view (≥ sm) --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Trạng thái</th>
                        <th>Kinh nghiệm</th>
                        <th>Chuỗi học</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr data-user-id="{{ $user->id }}"
                        data-user-name="{{ $user->name }}"
                        data-user-email="{{ $user->email }}"
                        data-user-role="{{ $user->role }}"
                        data-user-status="{{ $user->status }}"
                        data-user-bio="{{ $user->bio ?? '' }}"
                        data-user-xp="{{ $user->experience_points }}"
                        data-user-streak="{{ $user->daily_streak }}">
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

                        {{-- Status badge --}}
                        <td>
                            <span class="badge {{ $user->isActive() ? 'badge-active' : 'badge-inactive' }}">
                                {{ $user->isActive() ? 'Hoạt động' : 'Bị ban' }}
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
                            @unless(auth('admin')->user()?->is($user))
                                <div class="action-dropdown" data-dropdown>
                                    <button type="button" class="action-dropdown-btn" data-dropdown-toggle>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div class="action-dropdown-menu">
                                        {{-- Edit --}}
                                        <button type="button" class="action-dropdown-item" data-edit-user="{{ $user->id }}">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Chỉnh sửa
                                        </button>

                                        <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="action-dropdown-item" data-admin-confirm
                                                data-confirm-message="Đổi quyền của {{ $user->name }} thành {{ $user->isAdmin() ? 'Client' : 'Admin' }}?"
                                                data-confirm-accept="Xác nhận">
                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                {{ $user->isAdmin() ? 'Hạ thành Client' : 'Lên làm Admin' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="action-dropdown-item {{ $user->isActive() ? '' : 'success' }}" data-admin-confirm
                                                data-confirm-message="{{ $user->isActive() ? 'Ban tài khoản ' : 'Gỡ ban tài khoản ' }}{{ $user->name }}?"
                                                data-confirm-accept="{{ $user->isActive() ? 'Ban' : 'Gỡ ban' }}">
                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    @if($user->isActive())
                                                        <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke-linecap="round" stroke-linejoin="round"/>
                                                    @else
                                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                                    @endif
                                                </svg>
                                                {{ $user->isActive() ? 'Ban tài khoản' : 'Gỡ ban' }}
                                            </button>
                                        </form>

                                        <div class="action-dropdown-divider"></div>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-dropdown-item danger" data-admin-confirm
                                                data-confirm-message="Xóa tài khoản {{ $user->name }}? Toàn bộ dữ liệu sẽ bị xóa."
                                                data-confirm-accept="Xóa vĩnh viễn">
                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Xóa người dùng
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-500 italic px-2">Bạn</span>
                            @endunless
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>{{-- end desktop table --}}

        {{-- Mobile card view (< sm) --}}
        <div class="sm:hidden p-4 flex flex-col gap-3">
            @foreach($users as $user)
            <div class="mobile-data-card" data-user-id="{{ $user->id }}"
                data-user-name="{{ $user->name }}"
                data-user-email="{{ $user->email }}"
                data-user-role="{{ $user->role }}"
                data-user-status="{{ $user->status }}"
                data-user-bio="{{ $user->bio ?? '' }}"
                data-user-xp="{{ $user->experience_points }}"
                data-user-streak="{{ $user->daily_streak }}">
                {{-- Header: avatar + name + email + badges --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-white text-sm truncate">{{ $user->name }}</p>
                            <p class="text-slate-500 text-xs mt-0.5 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        <span class="badge {{ $user->isAdmin() ? 'badge-admin' : 'badge-client' }}">
                            {{ $user->isAdmin() ? 'Admin' : 'Client' }}
                        </span>
                        <span class="badge {{ $user->isActive() ? 'badge-active' : 'badge-inactive' }}">
                            {{ $user->isActive() ? 'Hoạt động' : 'Bị ban' }}
                        </span>
                    </div>
                </div>

                {{-- Meta info --}}
                <div class="mobile-data-card-meta">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ number_format($user->experience_points) }} XP
                    </span>
                    <span>🔥 {{ $user->daily_streak }} ngày</span>
                    <span>{{ $user->created_at->format('d/m/Y') }}</span>
                </div>

                {{-- Actions --}}
                @unless(auth('admin')->user()?->is($user))
                <div class="mobile-data-card-actions">
                    <button type="button" class="action-quick-btn edit" data-edit-user="{{ $user->id }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Sửa
                    </button>

                    <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="role-toggle {{ $user->role }}" data-admin-confirm
                            data-confirm-message="Đổi quyền của {{ $user->name }} thành {{ $user->isAdmin() ? 'Client' : 'Admin' }}?"
                            data-confirm-accept="Xác nhận">
                            {{ $user->isAdmin() ? 'Admin' : 'Client' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="status-toggle {{ $user->status }}" data-admin-confirm
                            data-confirm-message="{{ $user->isActive() ? 'Ban tài khoản ' : 'Gỡ ban tài khoản ' }}{{ $user->name }}?"
                            data-confirm-accept="{{ $user->isActive() ? 'Ban' : 'Gỡ ban' }}">
                            {{ $user->isActive() ? '✓ Hoạt động' : '🚫 Đã ban' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-quick-btn delete" data-admin-confirm
                            data-confirm-message="Xóa tài khoản {{ $user->name }}? Toàn bộ dữ liệu sẽ bị xóa."
                            data-confirm-accept="Xóa vĩnh viễn">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Xóa
                        </button>
                    </form>
                </div>
                @else
                <p class="mt-2 text-slate-600 text-xs italic">Tài khoản của bạn</p>
                @endunless
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Pagination --}}
@if ($users->hasPages())
<nav class="admin-pagination" aria-label="Pagination">
    <span class="admin-pagination-info">
        Hiển thị {{ $users->firstItem() }}-{{ $users->lastItem() }} của {{ $users->total() }} kết quả
    </span>

    {{-- Previous page link --}}
    @if ($users->onFirstPage())
    <span class="disabled">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </span>
    @else
    <a href="{{ $users->previousPageUrl() }}" aria-label="Previous page">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
    @endif

    {{-- Pagination links --}}
    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
    @if ($page == $users->currentPage())
    <span class="active">{{ $page }}</span>
    @elseif ($page == 1 || $page == $users->lastPage() || ($page >= $users->currentPage() - 2 && $page <= $users->currentPage() + 2))
    <a href="{{ $url }}">{{ $page }}</a>
    @elseif ($page == $users->currentPage() - 3 || $page == $users->currentPage() + 3)
    <span>...</span>
    @endif
    @endforeach

    {{-- Next page link --}}
    @if ($users->hasMorePages())
    <a href="{{ $users->nextPageUrl() }}" aria-label="Next page">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
    @else
    <span class="disabled">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </span>
    @endif
</nav>
@endif

@push('scripts')
<script>
    (function() {
        const sidebar = document.getElementById('user-form-sidebar');
        const form = document.getElementById('user-form');
        const sidebarTitle = document.getElementById('sidebar-title');
        const formSubmitBtn = document.getElementById('form-submit-btn');
        const formMethod = document.getElementById('form-method');
        const gamificationSection = document.getElementById('gamification-section');
        const passwordRequired = document.getElementById('password-required');
        const passwordHint = document.getElementById('password-hint');

        let isEditMode = false;
        let currentUserId = null;

        // Open sidebar for create
        document.querySelectorAll('[data-sidebar-open]').forEach(btn => {
            btn.addEventListener('click', () => openSidebar());
        });

        // Close sidebar
        document.querySelectorAll('[data-sidebar-close]').forEach(el => {
            el.addEventListener('click', closeSidebar);
        });

        // Edit user buttons
        document.querySelectorAll('[data-edit-user]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.target.closest('[data-edit-user]').dataset.editUser;
                const row = document.querySelector(`[data-user-id="${userId}"]`);
                if (row) {
                    openSidebar({
                        id: row.dataset.userId,
                        name: row.dataset.userName,
                        email: row.dataset.userEmail,
                        role: row.dataset.userRole,
                        status: row.dataset.userStatus,
                        bio: row.dataset.userBio,
                        xp: row.dataset.userXp,
                        streak: row.dataset.userStreak
                    });
                }
            });
        });

        function openSidebar(user = null) {
            isEditMode = user !== null;
            currentUserId = user?.id || null;

            // Update UI based on mode
            if (isEditMode) {
                sidebarTitle.textContent = 'Sửa: ' + user.name;
                formSubmitBtn.textContent = 'Lưu thay đổi';
                formMethod.value = 'PUT';
                form.action = `/admin/users/${user.id}`;
                gamificationSection.style.display = 'block';
                passwordRequired.style.display = 'none';
                passwordHint.textContent = 'Để trống nếu không muốn đổi mật khẩu';

                // Fill form
                document.getElementById('form-name').value = user.name;
                document.getElementById('form-email').value = user.email;
                document.getElementById('form-role').value = user.role;
                document.getElementById('form-status').value = user.status;
                document.getElementById('form-bio').value = user.bio || '';
                document.getElementById('form-xp').value = user.xp || 0;
                document.getElementById('form-streak').value = user.streak || 0;
                document.getElementById('form-password').value = '';
                document.getElementById('form-password').required = false;
            } else {
                sidebarTitle.textContent = 'Thêm người dùng mới';
                formSubmitBtn.textContent = 'Tạo tài khoản';
                formMethod.value = 'POST';
                form.action = '/admin/users';
                gamificationSection.style.display = 'none';
                passwordRequired.style.display = 'inline';
                passwordHint.textContent = 'Tối thiểu 8 ký tự';

                // Reset form
                form.reset();
                document.getElementById('form-password').required = true;
            }

            // Clear errors
            clearErrors();

            // Open sidebar
            sidebar.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            document.body.style.overflow = '';
            form.reset();
            clearErrors();
        }

        function clearErrors() {
            document.querySelectorAll('.sidebar-form-error').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });
            document.querySelectorAll('.sidebar-form-input').forEach(el => {
                el.style.borderColor = '';
            });
        }

        function showError(field, message) {
            const errorEl = document.getElementById('error-' + field);
            const inputEl = document.getElementById('form-' + field);
            if (errorEl && inputEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
                inputEl.style.borderColor = '#f87171';
            }
        }

        // Form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();

            const formData = new FormData(form);
            const submitBtn = formSubmitBtn;
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang lưu...';

            try {
                const response = await fetch(form.action, {
                    method: formMethod.value,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok && data.redirect) {
                    // Success - redirect
                    window.location.href = data.redirect;
                } else if (data.errors) {
                    // Validation errors
                    for (const [field, messages] of Object.entries(data.errors)) {
                        showError(field, Array.isArray(messages) ? messages[0] : messages);
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                } else if (data.message) {
                    // Other error
                    alert(data.message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });

        // Action Dropdown Menus
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            const toggle = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('.action-dropdown-menu');

            if (toggle && menu) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Close other dropdowns
                    document.querySelectorAll('.action-dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.remove('is-open');
                    });
                    menu.classList.toggle('is-open');
                });
            }
        });

        // Close dropdowns when clicking outside (but not on buttons inside)
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[data-dropdown]')) {
                document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                    menu.classList.remove('is-open');
                });
            }
        });

        // Close dropdowns when ESC key is pressed
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                    menu.classList.remove('is-open');
                });
                // Also close sidebar if open
                if (sidebar.classList.contains('is-open')) {
                    closeSidebar();
                }
            }
        });
    })();
</script>
@endpush
@endsection
