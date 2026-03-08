{{-- Progress Summary Cards Component --}}
@props(['summary' => []])

<div class="progress-summary" data-progress-summary>
    <div class="progress-card">
        <div class="value new" data-count="{{ $summary['new'] ?? 0 }}">{{ $summary['new'] ?? 0 }}</div>
        <div class="label">New</div>
    </div>
    <div class="progress-card">
        <div class="value learning" data-count="{{ $summary['learning'] ?? 0 }}">{{ $summary['learning'] ?? 0 }}</div>
        <div class="label">Learning</div>
    </div>
    <div class="progress-card">
        <div class="value mastered" data-count="{{ $summary['mastered'] ?? 0 }}">{{ $summary['mastered'] ?? 0 }}</div>
        <div class="label">Mastered</div>
    </div>
    <div class="progress-card">
        <div class="value due-today" data-count="{{ $summary['due_today'] ?? 0 }}">{{ $summary['due_today'] ?? 0 }}</div>
        <div class="label">Due today</div>
    </div>
</div>
