@extends('layouts.admin', [
    'title' => 'Quản lý bộ thẻ',
    'sidebar' => true,
])

@push('styles')
<style>
    /* Deck Form Sidebar Panel */
    #deck-form-sidebar {
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

    #deck-form-sidebar.is-open {
        transform: translateX(0);
    }

    #deck-form-sidebar .sidebar-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(96, 165, 250, 0.12);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(15, 23, 42, 0.5);
    }

    #deck-form-sidebar .sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #f1f5f9;
        font-family: 'Space Grotesk', sans-serif;
    }

    #deck-form-sidebar .sidebar-close {
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

    #deck-form-sidebar .sidebar-close:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #f87171;
    }

    #deck-form-sidebar .sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
    }

    #deck-form-sidebar .sidebar-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(96, 165, 250, 0.12);
        background: rgba(15, 23, 42, 0.5);
        display: flex;
        gap: 0.75rem;
    }

    #deck-form-sidebar .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 99;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    #deck-form-sidebar .sidebar-overlay.is-open {
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

    .sidebar-form-textarea {
        resize: vertical;
        min-height: 80px;
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

    .sidebar-checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .sidebar-checkbox-group input[type="checkbox"] {
        width: 1rem;
        height: 1rem;
        border-radius: 0.25rem;
        accent-color: #6366f1;
    }

    .sidebar-checkbox-group label {
        font-size: 0.875rem;
        color: #cbd5e1;
        cursor: pointer;
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

    .sidebar-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
        #deck-form-sidebar {
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
        min-width: 160px;
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

    .action-dropdown-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- Deck Form Sidebar Panel --}}
<div id="deck-form-sidebar">
    <div class="sidebar-overlay" data-sidebar-close></div>
    <div class="sidebar-header">
        <h2 class="sidebar-title" id="sidebar-title">Thêm bộ thẻ mới</h2>
        <button type="button" class="sidebar-close" data-sidebar-close aria-label="Đóng">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>

    <form id="deck-form" class="sidebar-content" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" id="form-method" value="POST">

        <!-- Basic Info Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Thông tin cơ bản</div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-title">
                    Tiêu đề <span class="text-red-400">*</span>
                </label>
                <input id="form-title" name="title" type="text"
                    class="sidebar-form-input"
                    placeholder="Nhập tiêu đề bộ thẻ"
                    required>
                <div class="sidebar-form-error" id="error-title" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-description">Mô tả</label>
                <textarea id="form-description" name="description" rows="3"
                    class="sidebar-form-input sidebar-form-textarea"
                    placeholder="Mô tả ngắn về bộ thẻ (không bắt buộc)"></textarea>
                <div class="sidebar-form-error" id="error-description" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-category">Danh mục</label>
                <select id="form-category" name="category" class="sidebar-form-input sidebar-form-select">
                    <option value="">— Chọn danh mục —</option>
                    <option value="Ngôn ngữ">🌐 Ngôn ngữ</option>
                    <option value="Tiếng Anh">🇬🇧 Tiếng Anh</option>
                    <option value="Tiếng Trung">🇨🇳 Tiếng Trung</option>
                    <option value="Tiếng Nhật">🇯🇵 Tiếng Nhật</option>
                    <option value="Tiếng Hàn">🇰🇷 Tiếng Hàn</option>
                    <option value="Toán học">📐 Toán học</option>
                    <option value="Khoa học">🔬 Khoa học</option>
                    <option value="Vật lý">⚛️ Vật lý</option>
                    <option value="Hóa học">🧪 Hóa học</option>
                    <option value="Sinh học">🧬 Sinh học</option>
                    <option value="Lịch sử">📜 Lịch sử</option>
                    <option value="Địa lý">🌍 Địa lý</option>
                    <option value="Văn học">📚 Văn học</option>
                    <option value="Triết học">💭 Triết học</option>
                    <option value="Kinh tế">💰 Kinh tế</option>
                    <option value="Tin học">💻 Tin học</option>
                    <option value="Lập trình">👨‍💻 Lập trình</option>
                    <option value="Âm nhạc">🎵 Âm nhạc</option>
                    <option value="Mỹ thuật">🎨 Mỹ thuật</option>
                    <option value="Thể thao">⚽ Thể thao</option>
                    <option value="Y học">🏥 Y học</option>
                    <option value="Luật">⚖️ Luật</option>
                    <option value="Khác">📁 Khác</option>
                </select>
                <div class="sidebar-form-error" id="error-category" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-tags">Thẻ tag</label>
                <input id="form-tags" name="tags" type="text"
                    class="sidebar-form-input"
                    placeholder="Cách nhau bởi dấu phẩy: tiếng anh, từ vựng, cơ bản">
                <div class="sidebar-form-error" id="error-tags" style="display: none;"></div>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Cài đặt</div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-user_id">Chủ sở hữu</label>
                <select id="form-user_id" name="user_id" class="sidebar-form-input sidebar-form-select">
                    <option value="">— Không có chủ sở hữu —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
                <div class="sidebar-form-error" id="error-user_id" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label" for="form-visibility">
                    Chế độ hiển thị <span class="text-red-400">*</span>
                </label>
                <select id="form-visibility" name="visibility"
                    class="sidebar-form-input sidebar-form-select" required>
                    <option value="public">Công khai</option>
                    <option value="private">Riêng tư</option>
                </select>
                <div class="sidebar-form-error" id="error-visibility" style="display: none;"></div>
            </div>

            <div class="sidebar-form-group">
                <label class="sidebar-form-label">Trạng thái kích hoạt</label>
                <div class="sidebar-checkbox-group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="form-is_active" value="1" checked>
                    <label for="form-is_active">Kích hoạt bộ thẻ</label>
                </div>
            </div>
        </div>
    </form>

    <div class="sidebar-footer">
        <button type="button" class="sidebar-btn-secondary" data-sidebar-close>Hủy</button>
        <button type="submit" form="deck-form" class="sidebar-btn-primary" id="form-submit-btn">
            Tạo bộ thẻ
        </button>
    </div>
</div>

{{-- Page heading --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Quản lý bộ thẻ</h1>
        <p class="text-slate-400 mt-1 text-sm">{{ $decks->total() }} bộ thẻ trong hệ thống</p>
    </div>
    <button type="button" class="btn-primary" data-sidebar-open>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Thêm bộ thẻ
    </button>
</div>

{{-- Search & Filter Bar --}}
<form method="GET" action="{{ route('admin.decks') }}" class="admin-filter-bar">
    <div class="admin-filter-search">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tìm kiếm theo tên, mô tả...">
    </div>

    <div class="admin-filter-group">
        <select name="visibility" class="admin-filter-select">
            <option value="">Tất cả chế độ</option>
            <option value="public" {{ ($filters['visibility'] ?? '') === 'public' ? 'selected' : '' }}>Công khai</option>
            <option value="private" {{ ($filters['visibility'] ?? '') === 'private' ? 'selected' : '' }}>Riêng tư</option>
        </select>

        <select name="is_active" class="admin-filter-select">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
            <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Đã tắt</option>
        </select>

        <select name="owner_id" class="admin-filter-select">
            <option value="">Tất cả chủ sở hữu</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ ($filters['owner_id'] ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->role === 'admin' ? 'Admin' : 'Client' }})
                </option>
            @endforeach
        </select>

        <select name="per_page" class="admin-filter-select" onchange="this.form.submit()">
            <option value="5" {{ ($filters['per_page'] ?? 5) == 5 ? 'selected' : '' }}>5 / trang</option>
            <option value="10" {{ ($filters['per_page'] ?? 5) == 10 ? 'selected' : '' }}>10 / trang</option>
            <option value="20" {{ ($filters['per_page'] ?? 5) == 20 ? 'selected' : '' }}>20 / trang</option>
            <option value="50" {{ ($filters['per_page'] ?? 5) == 50 ? 'selected' : '' }}>50 / trang</option>
        </select>

        @if(!empty($filters['search']) || !empty($filters['visibility']) || !empty($filters['is_active']) || !empty($filters['owner_id']))
        <a href="{{ route('admin.decks') }}" class="admin-filter-clear">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Xóa bộ lọc
        </a>
        @endif
    </div>
</form>

{{-- Active filter badges --}}
@if(!empty($filters['search']) || !empty($filters['visibility']) || !empty($filters['is_active']) || !empty($filters['owner_id']))
<div class="admin-filter-badges">
    @if(!empty($filters['search']))
    <span class="admin-filter-badge">
        Tìm: "{{ $filters['search'] }}"
    </span>
    @endif
    @if(!empty($filters['visibility']))
    <span class="admin-filter-badge">
        Chế độ: {{ $filters['visibility'] === 'public' ? 'Công khai' : 'Riêng tư' }}
    </span>
    @endif
    @if(!empty($filters['is_active']))
    <span class="admin-filter-badge">
        Trạng thái: {{ $filters['is_active'] === '1' ? 'Đang hoạt động' : 'Đã tắt' }}
    </span>
    @endif
    @if(!empty($filters['owner_id']))
        @php $owner = $users->firstWhere('id', $filters['owner_id']); @endphp
        @if($owner)
        <span class="admin-filter-badge">
            Chủ sở hữu: {{ $owner->name }}
        </span>
        @endif
    @endif
</div>
@endif

{{-- Decks Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <span class="text-white font-semibold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Danh sách bộ thẻ
        </span>
        <div class="flex gap-2">
            <span class="badge badge-active">{{ $decks->where('is_active', true)->count() }} Hoạt động</span>
            <span class="badge badge-inactive">{{ $decks->where('is_active', false)->count() }} Tắt</span>
        </div>
    </div>

    @if($decks->isEmpty())
        <div class="admin-empty">
            <svg class="w-12 h-12 mx-auto text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p>Chưa có bộ thẻ nào.</p>
        </div>
    @else
        {{-- Desktop table view (≥ sm) --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tên bộ thẻ</th>
                        <th>Chủ sở hữu</th>
                        <th>Chế độ</th>
                        <th>Trạng thái</th>
                        <th>Thẻ</th>
                        <th>Đánh giá TB</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($decks as $deck)
                    <tr data-deck-id="{{ $deck->id }}"
                        data-deck-title="{{ $deck->title }}"
                        data-deck-description="{{ $deck->description ?? '' }}"
                        data-deck-category="{{ $deck->category ?? '' }}"
                        data-deck-tags="{{ $deck->tags ? implode(', ', $deck->tags) : '' }}"
                        data-deck-user_id="{{ $deck->user_id ?? '' }}"
                        data-deck-visibility="{{ $deck->visibility }}"
                        data-deck-is_active="{{ $deck->is_active ? '1' : '0' }}">

                        {{-- Title + category --}}
                        <td>
                            <div>
                                <div class="font-medium text-white">{{ $deck->title }}</div>
                                @if($deck->category)
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $deck->category }}</div>
                                @endif
                            </div>
                        </td>

                        {{-- Owner --}}
                        <td class="text-slate-400">{{ $deck->owner?->name ?? '—' }}</td>

                        {{-- Visibility --}}
                        <td>
                            <span class="badge {{ $deck->visibility === 'public' ? 'badge-public' : 'badge-private' }}">
                                {{ $deck->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                            </span>
                        </td>

                        {{-- is_active toggle --}}
                        <td>
                            <form method="POST" action="{{ route('admin.decks.toggle', $deck) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="toggle-switch"
                                    data-admin-confirm
                                    data-confirm-message="{{ $deck->is_active ? 'Ẩn bộ thẻ &quot;'.$deck->title.'&quot; khỏi hệ thống?' : 'Kích hoạt lại bộ thẻ &quot;'.$deck->title.'&quot;?' }}"
                                    data-confirm-accept="{{ $deck->is_active ? 'Ẩn bộ thẻ' : 'Kích hoạt' }}"
                                    title="{{ $deck->is_active ? 'Click để tắt' : 'Click để bật' }}">
                                    <input type="checkbox" tabindex="-1" {{ $deck->is_active ? 'checked' : '' }} readonly>
                                    <span class="toggle-track"></span>
                                    <span class="text-xs {{ $deck->is_active ? 'text-green-400' : 'text-slate-500' }}">
                                        {{ $deck->is_active ? 'Bật' : 'Tắt' }}
                                    </span>
                                </button>
                            </form>
                        </td>

                        {{-- Flashcard count --}}
                        <td class="text-slate-300">{{ $deck->flashcards_count }}</td>

                        {{-- Avg rating --}}
                        <td>
                            @if($deck->reviews_avg_rating)
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-slate-300 text-sm">{{ number_format($deck->reviews_avg_rating, 1) }}</span>
                                </div>
                            @else
                                <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Created at --}}
                        <td class="text-slate-500 text-xs">{{ $deck->created_at->format('d/m/Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="flex items-center gap-1 flex-wrap">
                                <a href="{{ route('admin.decks.show', $deck) }}" class="btn-admin-action info">Xem</a>

                                <div class="action-dropdown" data-dropdown>
                                    <button type="button" class="action-dropdown-btn" data-dropdown-toggle>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div class="action-dropdown-menu">
                                        {{-- Edit --}}
                                        <button type="button" class="action-dropdown-item" data-edit-deck="{{ $deck->id }}">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Chỉnh sửa
                                        </button>

                                        @if(!$deck->is_active)
                                        <form method="POST" action="{{ route('admin.decks.destroy', $deck) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-dropdown-item" data-admin-confirm
                                                data-confirm-message="Xóa vĩnh viễn bộ thẻ &quot;{{ $deck->title }}&quot; và toàn bộ flashcard?"
                                                data-confirm-accept="Xóa bộ thẻ">
                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Xóa bộ thẻ
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>{{-- end desktop table --}}

        {{-- Mobile card view (< sm) --}}
        <div class="sm:hidden p-4 flex flex-col gap-3">
            @foreach($decks as $deck)
            <div class="mobile-data-card {{ !$deck->is_active ? 'opacity-60' : '' }}"
                data-deck-id="{{ $deck->id }}"
                data-deck-title="{{ $deck->title }}"
                data-deck-description="{{ $deck->description ?? '' }}"
                data-deck-category="{{ $deck->category ?? '' }}"
                data-deck-tags="{{ $deck->tags ? implode(', ', $deck->tags) : '' }}"
                data-deck-user_id="{{ $deck->user_id ?? '' }}"
                data-deck-visibility="{{ $deck->visibility }}"
                data-deck-is_active="{{ $deck->is_active ? '1' : '0' }}">
                {{-- Header: title + category + owner + badge --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium text-white text-sm">{{ $deck->title }}</p>
                        @if($deck->category)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $deck->category }}</p>
                        @endif
                        <p class="text-xs text-slate-500 mt-0.5">{{ $deck->owner?->name ?? '—' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        <span class="badge {{ $deck->visibility === 'public' ? 'badge-public' : 'badge-private' }}">
                            {{ $deck->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                        </span>
                        <span class="badge {{ $deck->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $deck->is_active ? 'Bật' : 'Tắt' }}
                        </span>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="mobile-data-card-meta">
                    <span>{{ $deck->flashcards_count }} thẻ</span>
                    @if($deck->reviews_avg_rating)
                        <span class="flex items-center gap-0.5">
                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($deck->reviews_avg_rating, 1) }}
                        </span>
                    @endif
                    <span>{{ $deck->created_at->format('d/m/Y') }}</span>
                </div>

                {{-- Actions --}}
                <div class="mobile-data-card-actions">
                    <a href="{{ route('admin.decks.show', $deck) }}" class="btn-admin-action info">Xem</a>
                    <button type="button" class="btn-admin-action warning" data-edit-deck="{{ $deck->id }}">Sửa</button>

                    <form method="POST" action="{{ route('admin.decks.toggle', $deck) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="btn-admin-action {{ $deck->is_active ? 'warning' : 'success' }}"
                            data-admin-confirm
                            data-confirm-message="{{ $deck->is_active ? 'Ẩn bộ thẻ &quot;'.$deck->title.'&quot; khỏi hệ thống?' : 'Kích hoạt lại bộ thẻ &quot;'.$deck->title.'&quot;?' }}"
                            data-confirm-accept="{{ $deck->is_active ? 'Ẩn bộ thẻ' : 'Kích hoạt' }}">
                            {{ $deck->is_active ? 'Tắt' : 'Bật' }}
                        </button>
                    </form>

                    @if(!$deck->is_active)
                    <form method="POST" action="{{ route('admin.decks.destroy', $deck) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="btn-admin-action danger"
                            data-admin-confirm
                            data-confirm-message="Xóa vĩnh viễn bộ thẻ &quot;{{ $deck->title }}&quot; và toàn bộ flashcard?"
                            data-confirm-accept="Xóa bộ thẻ">
                            Xóa
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($decks->hasPages())
        <nav class="admin-pagination" aria-label="Pagination">
            <span class="admin-pagination-info">
                Hiển thị {{ $decks->firstItem() }}-{{ $decks->lastItem() }} của {{ $decks->total() }} kết quả
            </span>

            {{-- Previous page link --}}
            @if ($decks->onFirstPage())
            <span class="disabled">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            @else
            <a href="{{ $decks->previousPageUrl() }}" aria-label="Previous page">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            @endif

            {{-- Pagination links --}}
            @foreach ($decks->getUrlRange(1, $decks->lastPage()) as $page => $url)
            @if ($page == $decks->currentPage())
            <span class="active">{{ $page }}</span>
            @elseif ($page == 1 || $page == $decks->lastPage() || ($page >= $decks->currentPage() - 2 && $page <= $decks->currentPage() + 2))
            <a href="{{ $url }}">{{ $page }}</a>
            @elseif ($page == $decks->currentPage() - 3 || $page == $decks->currentPage() + 3)
            <span>...</span>
            @endif
            @endforeach

            {{-- Next page link --}}
            @if ($decks->hasMorePages())
            <a href="{{ $decks->nextPageUrl() }}" aria-label="Next page">
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
    @endif
</div>

@push('scripts')
<script>
    (function() {
        const sidebar = document.getElementById('deck-form-sidebar');
        const form = document.getElementById('deck-form');
        const sidebarTitle = document.getElementById('sidebar-title');
        const formSubmitBtn = document.getElementById('form-submit-btn');
        const formMethod = document.getElementById('form-method');
        const isActiveCheckbox = document.getElementById('form-is_active');
        const isActiveHidden = form.querySelector('input[type="hidden"][name="is_active"]');

        let isEditMode = false;
        let currentDeckId = null;

        // Open sidebar for create
        document.querySelectorAll('[data-sidebar-open]').forEach(btn => {
            btn.addEventListener('click', () => openSidebar());
        });

        // Close sidebar
        document.querySelectorAll('[data-sidebar-close]').forEach(el => {
            el.addEventListener('click', closeSidebar);
        });

        // Edit deck buttons
        document.querySelectorAll('[data-edit-deck]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const deckId = e.target.closest('[data-edit-deck]').dataset.editDeck;
                const row = document.querySelector(`[data-deck-id="${deckId}"]`);
                if (row) {
                    openSidebar({
                        id: row.dataset.deckId,
                        title: row.dataset.deckTitle,
                        description: row.dataset.deckDescription,
                        category: row.dataset.deckCategory,
                        tags: row.dataset.deckTags,
                        user_id: row.dataset.deckUserId,
                        visibility: row.dataset.deckVisibility,
                        is_active: row.dataset.deckIsActive
                    });
                }
            });
        });

        function openSidebar(deck = null) {
            isEditMode = deck !== null;
            currentDeckId = deck?.id || null;

            // Update UI based on mode
            if (isEditMode) {
                sidebarTitle.textContent = 'Sửa: ' + deck.title;
                formSubmitBtn.textContent = 'Lưu thay đổi';
                formMethod.value = 'PUT';
                form.action = `/admin/decks/${deck.id}`;

                // Fill form
                document.getElementById('form-title').value = deck.title;
                document.getElementById('form-description').value = deck.description || '';
                document.getElementById('form-category').value = deck.category || '';
                document.getElementById('form-tags').value = deck.tags || '';
                document.getElementById('form-user_id').value = deck.user_id || '';
                document.getElementById('form-visibility').value = deck.visibility;

                // Set is_active checkbox
                const isActiveValue = deck.is_active === '1';
                isActiveCheckbox.checked = isActiveValue;
                if (!isActiveValue) {
                    isActiveHidden.value = '0';
                }
            } else {
                sidebarTitle.textContent = 'Thêm bộ thẻ mới';
                formSubmitBtn.textContent = 'Tạo bộ thẻ';
                formMethod.value = 'POST';
                form.action = '/admin/decks';

                // Reset form
                form.reset();
                isActiveHidden.value = '0';
                isActiveCheckbox.checked = true;
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

        // Handle is_active checkbox
        isActiveCheckbox.addEventListener('change', function() {
            isActiveHidden.value = this.checked ? '1' : '0';
        });

        // Form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();

            // Handle is_active value properly
            if (isActiveCheckbox.checked) {
                isActiveHidden.value = '1';
            } else {
                isActiveHidden.value = '0';
            }

            const formData = new FormData(form);
            const csrfToken = formData.get('_token') || '';
            const submitBtn = formSubmitBtn;
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang lưu...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
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
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });

        // Dropdown toggle functionality
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            const btn = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('.action-dropdown-menu');

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close all other dropdowns
                document.querySelectorAll('.action-dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('is-open');
                });
                menu.classList.toggle('is-open');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                menu.classList.remove('is-open');
            });
        });
    })();
</script>
@endpush

@endsection
