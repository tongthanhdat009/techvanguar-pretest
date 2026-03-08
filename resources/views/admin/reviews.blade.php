@extends('layouts.admin', [
    'title' => 'Quản lý đánh giá',
    'sidebar' => true,
])

@section('content')

{{-- Page heading --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Quản lý đánh giá</h1>
    <p class="text-slate-400 mt-1 text-sm">{{ $reviews->total() }} đánh giá trong hệ thống</p>
</div>

{{-- Reviews Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <span class="text-white font-semibold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Danh sách đánh giá
        </span>
        <span class="text-slate-400 text-xs">Trang {{ $reviews->currentPage() }} / {{ $reviews->lastPage() }}</span>
    </div>

    @if($reviews->isEmpty())
        <div class="admin-empty">
            <svg class="w-12 h-12 mx-auto text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p>Chưa có đánh giá nào.</p>
        </div>
    @else
        <div class="overflow-x-auto">
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
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between flex-wrap gap-3">
                <span class="text-slate-500 text-sm">
                    Hiển thị {{ $reviews->firstItem() }}–{{ $reviews->lastItem() }} / {{ $reviews->total() }}
                </span>
                <div class="flex items-center gap-1">
                    @if($reviews->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg text-slate-600 text-sm cursor-not-allowed">&larr;</span>
                    @else
                        <a href="{{ $reviews->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-colors">&larr;</a>
                    @endif

                    @foreach($reviews->getUrlRange(max(1, $reviews->currentPage()-2), min($reviews->lastPage(), $reviews->currentPage()+2)) as $page => $url)
                        @if($page == $reviews->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($reviews->hasMorePages())
                        <a href="{{ $reviews->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-colors">&rarr;</a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg text-slate-600 text-sm cursor-not-allowed">&rarr;</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>

@endsection

