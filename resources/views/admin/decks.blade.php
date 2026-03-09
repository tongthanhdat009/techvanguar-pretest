@extends('layouts.admin', [
    'title' => 'Quản lý bộ thẻ',
    'sidebar' => true,
])

@section('content')

{{-- Page heading --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Quản lý bộ thẻ</h1>
        <p class="text-slate-400 mt-1 text-sm">{{ $decks->total() }} bộ thẻ trong hệ thống</p>
    </div>
    <a href="{{ route('admin.decks.create') }}" class="btn-primary">+ Thêm bộ thẻ</a>
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
        {{-- Mobile card view (< sm) --}}
        <div class="sm:hidden p-4 flex flex-col gap-3">
            @foreach($decks as $deck)
            <div class="mobile-data-card {{ !$deck->is_active ? 'opacity-60' : '' }}">
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
                    <a href="{{ route('admin.decks.edit', $deck) }}" class="btn-admin-action warning">Sửa</a>

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
                    <tr class="{{ !$deck->is_active ? 'opacity-60' : '' }}">

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
                                <a href="{{ route('admin.decks.edit', $deck) }}" class="btn-admin-action warning">Sửa</a>
                                @if(!$deck->is_active)
                                <form method="POST" action="{{ route('admin.decks.destroy', $deck) }}">
                                    @csrf
                                    @method('DELETE')
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
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>{{-- end desktop table --}}

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

@endsection

