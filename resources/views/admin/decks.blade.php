@extends('layouts.admin', [
    'title' => 'Quản lý bộ thẻ',
    'sidebar' => true,
])

@section('content')

{{-- Page heading --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Quản lý bộ thẻ</h1>
    <p class="text-slate-400 mt-1 text-sm">{{ $decks->count() }} bộ thẻ trong hệ thống</p>
</div>

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
        <div class="overflow-x-auto">
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

                        {{-- Delete --}}
                        <td>
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
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

