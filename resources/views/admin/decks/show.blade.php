@extends('layouts.admin', [
    'title' => $deck->title,
    'sidebar' => true,
])

@section('content')

{{-- Back + heading --}}
<div class="mb-6 flex items-start justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.decks') }}" class="text-slate-400 hover:text-white transition-colors mt-1" title="Quay lại danh sách">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $deck->title }}</h1>
            <p class="text-slate-400 mt-0.5 text-sm">Chi tiết bộ thẻ</p>
        </div>
    </div>
</div>

{{-- Deck info card --}}
<div class="admin-card mb-6">
    <div class="admin-card-header">
        <span class="text-white font-semibold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Thông tin bộ thẻ
        </span>
        {{-- Status toggle + delete --}}
        <div class="flex items-center flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.decks.toggle', $deck) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="btn-admin-action {{ $deck->is_active ? 'success' : 'warning' }}"
                    data-admin-confirm
                    data-confirm-message="{{ $deck->is_active ? 'Ẩn bộ thẻ &quot;'.$deck->title.'&quot; khỏi hệ thống?' : 'Kích hoạt lại bộ thẻ &quot;'.$deck->title.'&quot;?' }}"
                    data-confirm-accept="{{ $deck->is_active ? 'Ẩn bộ thẻ' : 'Kích hoạt' }}">
                    {{ $deck->is_active ? '● Đang bật — nhấn để tắt' : '● Đang tắt — nhấn để bật' }}
                </button>
            </form>
            @if(!$deck->is_active)
            <form method="POST" action="{{ route('admin.decks.destroy', $deck) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn-admin-action danger"
                    data-admin-confirm
                    data-confirm-message="Xóa vĩnh viễn bộ thẻ &quot;{{ $deck->title }}&quot; và toàn bộ {{ $deck->flashcards->count() }} flashcard?"
                    data-confirm-accept="Xóa bộ thẻ">
                    Xóa bộ thẻ
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Info grid --}}
    <div class="deck-info-grid p-5">
        <div class="deck-info-item">
            <p class="info-label">Tiêu đề</p>
            <p class="info-value">{{ $deck->title }}</p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Chủ sở hữu</p>
            <p class="info-value">{{ $deck->owner?->name ?? '—' }}</p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Chế độ</p>
            <p class="info-value">
                <span class="badge {{ $deck->visibility === 'public' ? 'badge-public' : 'badge-private' }}">
                    {{ $deck->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                </span>
            </p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Trạng thái</p>
            <p class="info-value">
                <span class="badge {{ $deck->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $deck->is_active ? 'Đang hoạt động' : 'Đã vô hiệu hóa' }}
                </span>
            </p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Số flashcard</p>
            <p class="info-value">{{ $deck->flashcards->count() }}</p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Danh mục</p>
            <p class="info-value">{{ $deck->category ?? '—' }}</p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Thẻ tag</p>
            <p class="info-value">
                @if($deck->tags && count($deck->tags) > 0)
                    {{ implode(', ', $deck->tags) }}
                @else
                    —
                @endif
            </p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Ngày tạo</p>
            <p class="info-value">{{ $deck->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="deck-info-item">
            <p class="info-label">Cập nhật lần cuối</p>
            <p class="info-value">{{ $deck->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        @if($deck->description)
        <div class="deck-info-item" style="grid-column: 1 / -1">
            <p class="info-label">Mô tả</p>
            <p class="info-value">{{ $deck->description }}</p>
        </div>
        @endif
    </div>
</div>

{{-- Flashcard management --}}
@include('admin.decks._flashcards', ['deck' => $deck])

@endsection
