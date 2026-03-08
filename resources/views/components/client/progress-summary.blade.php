{{-- Progress Summary Cards Component --}}
@props(['summary' => []])

<div class="progress-summary" data-progress-summary>
    <div class="progress-card">
        <div class="progress-card-header">
            <span class="progress-card-kicker">Hàng chờ</span>
            <span class="progress-card-dot new"></span>
        </div>
        <div class="value new" data-count="{{ $summary['new'] ?? 0 }}">{{ $summary['new'] ?? 0 }}</div>
        <div class="label">Thẻ mới</div>
    </div>
    <div class="progress-card">
        <div class="progress-card-header">
            <span class="progress-card-kicker">Tiến độ</span>
            <span class="progress-card-dot learning"></span>
        </div>
        <div class="value learning" data-count="{{ $summary['learning'] ?? 0 }}">{{ $summary['learning'] ?? 0 }}</div>
        <div class="label">Đang học</div>
    </div>
    <div class="progress-card">
        <div class="progress-card-header">
            <span class="progress-card-kicker">Mastery</span>
            <span class="progress-card-dot mastered"></span>
        </div>
        <div class="value mastered" data-count="{{ $summary['mastered'] ?? 0 }}">{{ $summary['mastered'] ?? 0 }}</div>
        <div class="label">Đã mastery</div>
    </div>
    <div class="progress-card">
        <div class="progress-card-header">
            <span class="progress-card-kicker">Ưu tiên</span>
            <span class="progress-card-dot due-today"></span>
        </div>
        <div class="value due-today" data-count="{{ $summary['due_today'] ?? 0 }}">{{ $summary['due_today'] ?? 0 }}</div>
        <div class="label">Đến hạn hôm nay</div>
    </div>
</div>
