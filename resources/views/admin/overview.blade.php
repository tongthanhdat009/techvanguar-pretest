@extends('layouts.admin', [
    'title' => 'Dashboard',
    'sidebar' => true,
])

@push('styles')
<style>
    /* Print styles */
    @media print {
        .no-print { display: none !important; }
        body.admin-shell { background: white; color: black; }
        .admin-card, .admin-stat-card, .chart-container {
            background: white !important;
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            color: black !important;
        }
        .admin-card-header, .stat-value, .stat-label {
            color: black !important;
        }
        .chart-container { page-break-inside: avoid; }
        canvas { max-width: 100% !important; }
    }

    /* Compact stats */
    .compact-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .compact-stat-card {
        padding: 0.85rem 1rem;
        border-radius: 0.75rem;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(96, 165, 250, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .compact-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(59, 130, 246, 0.2);
        flex-shrink: 0;
    }

    .compact-stat-content {
        flex: 1;
        min-width: 0;
    }

    .compact-stat-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: #f1f5f9;
        line-height: 1.2;
        font-family: 'Space Grotesk', sans-serif;
    }

    .compact-stat-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* Daily stats section */
    .daily-stats-section {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .daily-stat-card {
        padding: 0.75rem 0.85rem;
        border-radius: 0.625rem;
        background: rgba(34, 197, 94, 0.08);
        border: 1px solid rgba(34, 197, 94, 0.2);
        text-align: center;
    }

    .daily-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #86efac;
        line-height: 1.2;
        font-family: 'Space Grotesk', sans-serif;
    }

    .daily-stat-label {
        font-size: 0.68rem;
        color: #6ee7b7;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* Chart containers */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .chart-container {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(96, 165, 250, 0.12);
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .chart-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #f1f5f9;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .chart-subtitle {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .chart-canvas-wrapper {
        position: relative;
        height: 280px;
    }

    .chart-canvas-wrapper.tall {
        height: 320px;
    }

    /* Print button */
    .print-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(96, 165, 250, 0.3);
        border-radius: 0.5rem;
        color: #93c5fd;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .print-btn:hover {
        background: rgba(59, 130, 246, 0.3);
        border-color: rgba(96, 165, 250, 0.5);
    }

    @media (max-width: 1279px) {
        .compact-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .daily-stats-section { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .charts-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 767px) {
        .compact-stats-grid { grid-template-columns: 1fr; }
        .daily-stats-section { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>
@endpush

@section('content')
<!-- Print Header (visible on print) -->
<div style="display: none;" class="print-only">
    <h1 style="text-align: center; margin-bottom: 0.5rem;">Flashcard Learning Hub - Admin Dashboard</h1>
    <p style="text-align: center; color: #666;">Ngày in: {{ now()->format('d/m/Y H:i') }}</p>
    <hr style="margin: 1rem 0;">
</div>

<!-- Page Actions -->
<div class="flex justify-between items-center mb-4 no-print">
    <div>
        <span class="admin-overview-kicker">Dashboard · {{ now()->format('d/m/Y') }}</span>
    </div>
    <button onclick="window.print()" class="print-btn">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        In thống kê
    </button>
</div>

<!-- Compact Stats Grid -->
<section class="compact-stats-grid">
    <div class="compact-stat-card">
        <div class="compact-stat-icon">
            <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.0 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="compact-stat-content">
            <div class="compact-stat-value">{{ number_format($stats['users']) }}</div>
            <div class="compact-stat-label">Tổng users</div>
        </div>
    </div>

    <div class="compact-stat-card">
        <div class="compact-stat-icon">
            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="compact-stat-content">
            <div class="compact-stat-value">{{ number_format($stats['decks']) }}</div>
            <div class="compact-stat-label">Tổng decks</div>
        </div>
    </div>

    <div class="compact-stat-card">
        <div class="compact-stat-icon">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="compact-stat-content">
            <div class="compact-stat-value">{{ number_format($stats['flashcards']) }}</div>
            <div class="compact-stat-label">Tổng flashcards</div>
        </div>
    </div>

    <div class="compact-stat-card">
        <div class="compact-stat-icon">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="compact-stat-content">
            <div class="compact-stat-value">{{ number_format($stats['reviews']) }}</div>
            <div class="compact-stat-label">Đánh giá</div>
        </div>
    </div>
</section>

<!-- Daily Stats Section -->
<section class="daily-stats-section">
    <div class="daily-stat-card">
        <div class="daily-stat-value">{{ number_format($dailyStats['new_users']) }}</div>
        <div class="daily-stat-label">User mới (hôm nay)</div>
    </div>
    <div class="daily-stat-card">
        <div class="daily-stat-value">{{ number_format($dailyStats['new_decks']) }}</div>
        <div class="daily-stat-label">Deck mới (hôm nay)</div>
    </div>
    <div class="daily-stat-card">
        <div class="daily-stat-value">{{ number_format($dailyStats['new_flashcards']) }}</div>
        <div class="daily-stat-label">Flashcard mới (hôm nay)</div>
    </div>
    <div class="daily-stat-card">
        <div class="daily-stat-value">{{ number_format($dailyStats['new_reviews']) }}</div>
        <div class="daily-stat-label">Review mới (hôm nay)</div>
    </div>
    <div class="daily-stat-card">
        <div class="daily-stat-value">{{ number_format($dailyStats['study_sessions']) }}</div>
        <div class="daily-stat-label">Session học (hôm nay)</div>
    </div>
</section>

<!-- Charts Section -->
<section class="charts-grid">
    <!-- User Growth Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div>
                <div class="chart-title">Tăng trưởng Users</div>
                <div class="chart-subtitle">30 ngày gần nhất</div>
            </div>
        </div>
        <div class="chart-canvas-wrapper">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>

    <!-- Deck Growth Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div>
                <div class="chart-title">Tăng trưởng Decks</div>
                <div class="chart-subtitle">30 ngày gần nhất</div>
            </div>
        </div>
        <div class="chart-canvas-wrapper">
            <canvas id="deckGrowthChart"></canvas>
        </div>
    </div>

    <!-- Flashcard Growth Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div>
                <div class="chart-title">Tăng trưởng Flashcards</div>
                <div class="chart-subtitle">30 ngày gần nhất</div>
            </div>
        </div>
        <div class="chart-canvas-wrapper">
            <canvas id="flashcardGrowthChart"></canvas>
        </div>
    </div>

    <!-- Reviews Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div>
                <div class="chart-title">Đánh giá mới</div>
                <div class="chart-subtitle">30 ngày gần nhất</div>
            </div>
        </div>
        <div class="chart-canvas-wrapper">
            <canvas id="reviewsChart"></canvas>
        </div>
    </div>

    <!-- Weekly Users Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div>
                <div class="chart-title">Users mới theo tuần</div>
                <div class="chart-subtitle">12 tuần gần nhất</div>
            </div>
        </div>
        <div class="chart-canvas-wrapper tall">
            <canvas id="weeklyUsersChart"></canvas>
        </div>
    </div>

    <!-- Weekly Decks Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div>
                <div class="chart-title">Decks mới theo tuần</div>
                <div class="chart-subtitle">12 tuần gần nhất</div>
            </div>
        </div>
        <div class="chart-canvas-wrapper tall">
            <canvas id="weeklyDecksChart"></canvas>
        </div>
    </div>
</section>

<!-- Quick Actions (compact) -->
<section class="admin-insight-card no-print">
    <div class="admin-card-header">
        <div>
            <span class="admin-panel-kicker">Quick actions</span>
            <h2>Chức năng nhanh</h2>
        </div>
    </div>
    <div class="admin-action-grid">
        <a href="{{ route('admin.users') }}" class="admin-action-card">
            <strong>Quản lý Users</strong>
            <span>Xem và chỉnh sửa tài khoản.</span>
        </a>
        <a href="{{ route('admin.decks') }}" class="admin-action-card">
            <strong>Quản lý Decks</strong>
            <span>Xem và quản lý bộ thẻ.</span>
        </a>
        <a href="{{ route('admin.reviews') }}" class="admin-action-card">
            <strong>Quản lý Reviews</strong>
            <span>Xem đánh giá của cộng đồng.</span>
        </a>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const chartData = @js($chartData);

    // Chart defaults
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = 'rgba(148, 163, 184, 0.1)';
    Chart.defaults.font.family = "'Manrope', sans-serif";

    // Common options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                titleColor: '#f1f5f9',
                bodyColor: '#cbd5e1',
                borderColor: 'rgba(96, 165, 250, 0.3)',
                borderWidth: 1,
                cornerRadius: 8,
                padding: 12,
                displayColors: false,
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    maxRotation: 0,
                    autoSkip: true,
                    maxTicksLimit: 7
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(148, 163, 184, 0.08)'
                }
            }
        }
    };

    // User Growth Chart
    new Chart(document.getElementById('userGrowthChart'), {
        type: 'line',
        data: {
            labels: chartData.days,
            datasets: [{
                label: 'Users',
                data: chartData.userGrowth,
                borderColor: '#38bdf8',
                backgroundColor: 'rgba(56, 189, 248, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#38bdf8',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: commonOptions
    });

    // Deck Growth Chart
    new Chart(document.getElementById('deckGrowthChart'), {
        type: 'line',
        data: {
            labels: chartData.days,
            datasets: [{
                label: 'Decks',
                data: chartData.deckGrowth,
                borderColor: '#a78bfa',
                backgroundColor: 'rgba(167, 139, 250, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#a78bfa',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: commonOptions
    });

    // Flashcard Growth Chart
    new Chart(document.getElementById('flashcardGrowthChart'), {
        type: 'line',
        data: {
            labels: chartData.days,
            datasets: [{
                label: 'Flashcards',
                data: chartData.flashcardGrowth,
                borderColor: '#34d399',
                backgroundColor: 'rgba(52, 211, 153, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#34d399',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: commonOptions
    });

    // Reviews Chart (Bar)
    new Chart(document.getElementById('reviewsChart'), {
        type: 'bar',
        data: {
            labels: chartData.days,
            datasets: [{
                label: 'Reviews',
                data: chartData.reviewData,
                backgroundColor: 'rgba(251, 191, 36, 0.6)',
                borderColor: '#fbbf24',
                borderWidth: 1,
                borderRadius: 4,
                hoverBackgroundColor: 'rgba(251, 191, 36, 0.8)'
            }]
        },
        options: commonOptions
    });

    // Weekly Users Chart (Bar)
    new Chart(document.getElementById('weeklyUsersChart'), {
        type: 'bar',
        data: {
            labels: chartData.weeks,
            datasets: [{
                label: 'Users mới',
                data: chartData.weeklyUsers,
                backgroundColor: 'rgba(56, 189, 248, 0.6)',
                borderColor: '#38bdf8',
                borderWidth: 1,
                borderRadius: 4,
                hoverBackgroundColor: 'rgba(56, 189, 248, 0.8)'
            }]
        },
        options: commonOptions
    });

    // Weekly Decks Chart (Bar)
    new Chart(document.getElementById('weeklyDecksChart'), {
        type: 'bar',
        data: {
            labels: chartData.weeks,
            datasets: [{
                label: 'Decks mới',
                data: chartData.weeklyDecks,
                backgroundColor: 'rgba(167, 139, 250, 0.6)',
                borderColor: '#a78bfa',
                borderWidth: 1,
                borderRadius: 4,
                hoverBackgroundColor: 'rgba(167, 139, 250, 0.8)'
            }]
        },
        options: commonOptions
    });
</script>
@endpush
@endsection
