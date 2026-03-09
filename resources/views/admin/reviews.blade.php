@extends('layouts.admin', [
    'title' => 'Quản lý đánh giá',
    'sidebar' => true,
])

@php
use Illuminate\Support\Str;
@endphp

@section('content')

{{-- Page heading --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Quản lý đánh giá</h1>
    <p class="text-slate-400 mt-1 text-sm">{{ $reviews->total() }} đánh giá trong hệ thống</p>
</div>

{{-- Search & Filter Bar --}}
<form method="GET" action="{{ route('admin.reviews') }}" class="admin-filter-bar">
    <div class="admin-filter-search">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tìm kiếm theo nội dung, bộ thẻ, ngườii dùng...">
    </div>

    <div class="admin-filter-group">
        <select name="rating" class="admin-filter-select">
            <option value="">Tất cả sao</option>
            <option value="5" {{ ($filters['rating'] ?? '') === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 sao)</option>
            <option value="4" {{ ($filters['rating'] ?? '') === '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 sao)</option>
            <option value="3" {{ ($filters['rating'] ?? '') === '3' ? 'selected' : '' }}>⭐⭐⭐ (3 sao)</option>
            <option value="2" {{ ($filters['rating'] ?? '') === '2' ? 'selected' : '' }}>⭐⭐ (2 sao)</option>
            <option value="1" {{ ($filters['rating'] ?? '') === '1' ? 'selected' : '' }}>⭐ (1 sao)</option>
        </select>

        <select name="deck_id" class="admin-filter-select">
            <option value="">Tất cả bộ thẻ</option>
            @foreach($decks as $deck)
                <option value="{{ $deck->id }}" {{ ($filters['deck_id'] ?? '') == $deck->id ? 'selected' : '' }}>
                    {{ Str::limit($deck->title, 40) }}
                </option>
            @endforeach
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

        @if(!empty($filters['search']) || !empty($filters['rating']) || !empty($filters['deck_id']))
        <a href="{{ route('admin.reviews') }}" class="admin-filter-clear">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Xóa bộ lọc
        </a>
        @endif
    </div>
</form>

{{-- Active filter badges --}}
@if(!empty($filters['search']) || !empty($filters['rating']) || !empty($filters['deck_id']))
<div class="admin-filter-badges">
    @if(!empty($filters['search']))
    <span class="admin-filter-badge">
        Tìm: "{{ $filters['search'] }}"
    </span>
    @endif
    @if(!empty($filters['rating']))
    <span class="admin-filter-badge">
        {{ $filters['rating'] }} sao
    </span>
    @endif
    @if(!empty($filters['deck_id']))
        @php $deck = $decks->firstWhere('id', $filters['deck_id']); @endphp
        @if($deck)
        <span class="admin-filter-badge">
            Bộ thẻ: {{ Str::limit($deck->title, 30) }}
        </span>
        @endif
    @endif
</div>
@endif

{{-- Reviews Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <span class="text-white font-semibold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Danh sách đánh giá
        </span>
        @if($reviews->lastPage() > 1)
        <span class="text-slate-400 text-xs">Trang {{ $reviews->currentPage() }} / {{ $reviews->lastPage() }}</span>
        @endif
    </div>

    @if($reviews->isEmpty())
        <div class="admin-empty">
            <svg class="w-12 h-12 mx-auto text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p>Chưa có đánh giá nào.</p>
        </div>
    @else
        {{-- Mobile card view (< sm) --}}
        <div class="sm:hidden p-4 flex flex-col gap-3">
            @foreach($reviews as $review)
            <div class="mobile-data-card">
                {{-- Header: deck name + user --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium text-white text-sm truncate">{{ $review->deck?->title ?? '—' }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ $review->user ? mb_strtoupper(mb_substr($review->user->name, 0, 1)) : '?' }}
                            </div>
                            <span class="text-slate-400 text-xs">{{ $review->user?->name ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-slate-500 text-xs">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                @if($review->comment)
                <p class="mt-2.5 text-slate-400 text-sm leading-relaxed">{{ $review->comment }}</p>
                @endif

                <div class="mobile-data-card-actions justify-end">
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="btn-admin-action danger"
                            data-admin-confirm
                            data-confirm-message="Xóa đánh giá này của {{ $review->user?->name ?? 'người dùng' }}?"
                            data-confirm-accept="Xóa đánh giá">
                            Xóa đánh giá
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Desktop table view (≥ sm) --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Bộ thẻ</th>
                        <th>Người dùng</th>
                        <th>Điểm</th>
                        <th>Nội dung</th>
                        <th>Thời gian</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>

                        {{-- Deck --}}
                        <td>
                            <span class="font-medium text-white">{{ $review->deck?->title ?? '—' }}</span>
                        </td>

                        {{-- User --}}
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ $review->user ? mb_strtoupper(mb_substr($review->user->name, 0, 1)) : '?' }}
                                </div>
                                <span class="text-slate-300">{{ $review->user?->name ?? '—' }}</span>
                            </div>
                        </td>

                        {{-- Rating stars --}}
                        <td>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </td>

                        {{-- Comment --}}
                        <td class="truncate-cell" title="{{ $review->comment }}">
                            {{ $review->comment ? \Illuminate\Support\Str::limit($review->comment, 80) : '—' }}
                        </td>

                        {{-- Created at --}}
                        <td class="text-slate-500 text-xs whitespace-nowrap">{{ $review->created_at->format('d/m/Y H:i') }}</td>

                        {{-- Delete --}}
                        <td>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn-admin-action danger"
                                    data-admin-confirm
                                    data-confirm-message="Xóa đánh giá này của {{ $review->user?->name ?? 'người dùng' }}?"
                                    data-confirm-accept="Xóa đánh giá">
                                    Xóa
                                </button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>{{-- end desktop table --}}

        {{-- Pagination --}}
        @if ($reviews->hasPages())
        <nav class="admin-pagination" aria-label="Pagination">
            <span class="admin-pagination-info">
                Hiển thị {{ $reviews->firstItem() }}-{{ $reviews->lastItem() }} của {{ $reviews->total() }} kết quả
            </span>

            {{-- Previous page link --}}
            @if ($reviews->onFirstPage())
            <span class="disabled">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            @else
            <a href="{{ $reviews->previousPageUrl() }}" aria-label="Previous page">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            @endif

            {{-- Pagination links --}}
            @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
            @if ($page == $reviews->currentPage())
            <span class="active">{{ $page }}</span>
            @elseif ($page == 1 || $page == $reviews->lastPage() || ($page >= $reviews->currentPage() - 2 && $page <= $reviews->currentPage() + 2))
            <a href="{{ $url }}">{{ $page }}</a>
            @elseif ($page == $reviews->currentPage() - 3 || $page == $reviews->currentPage() + 3)
            <span>...</span>
            @endif
            @endforeach

            {{-- Next page link --}}
            @if ($reviews->hasMorePages())
            <a href="{{ $reviews->nextPageUrl() }}" aria-label="Next page">
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

