{{-- Public Hero Section Component --}}
<section class="hero-section hero-gradient">
    <div class="blob blob-top"></div>
    <div class="blob blob-bottom"></div>

    <div class="hero-content">
        <div class="hero-copy">
            <span class="hero-badge">Học nhanh hơn. Nhớ lâu hơn. Vận hành gọn hơn.</span>
            <span class="sr-only">Flashcard Learning Hub – Evidence-Based Learning Platform</span>

            <h1 class="hero-headline">
                Biến việc ôn tập hằng ngày thành một nhịp học có cấu trúc.
            </h1>

            <p class="hero-subheadline">
                Tạo deck riêng, theo dõi tiến độ thật sự quan trọng và quay lại đúng lúc trí nhớ bắt đầu yếu đi. Một bề mặt học tập đủ nghiêm túc cho người học và đủ rõ ràng cho quản trị vận hành.
            </p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-cta public-btn public-btn-primary public-btn-large">
                    Bắt đầu miễn phí
                </a>
                <a href="#demo" class="public-btn public-btn-outline public-btn-large">
                    Xem trải nghiệm demo
                </a>
            </div>

            <div class="hero-proof-list">
                <span>Spaced repetition tích hợp</span>
                <span>Deck riêng và deck cộng đồng</span>
                <span>Dashboard tiến độ và streak</span>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-panel-card hero-panel-primary">
                <span class="hero-panel-label">Study cadence</span>
                <strong class="hero-panel-value">Review on time, not by mood.</strong>
                <p>Thiết kế ưu tiên đúng việc cần học tiếp theo: tạo thẻ, ôn lại đúng lúc và giữ tín hiệu tiến bộ luôn nhìn thấy được.</p>
            </div>

            <div class="hero-study-path" aria-label="Quy trình học tập">
                <div class="hero-step">
                    <span class="hero-step-index">01</span>
                    <div>
                        <strong>Tạo hoặc sao chép deck</strong>
                        <p>Bắt đầu từ bộ thẻ cá nhân hoặc dùng deck public làm nền để đi nhanh hơn.</p>
                    </div>
                </div>
                <div class="hero-step">
                    <span class="hero-step-index">02</span>
                    <div>
                        <strong>Ôn theo mức độ ghi nhớ</strong>
                        <p>Thẻ đến hạn, thẻ mới và thẻ đang học được gom vào đúng nhịp thay vì trộn lẫn ngẫu nhiên.</p>
                    </div>
                </div>
                <div class="hero-step">
                    <span class="hero-step-index">03</span>
                    <div>
                        <strong>Giữ streak và nâng độ bền trí nhớ</strong>
                        <p>XP, level và lịch review tạo vòng phản hồi ngắn nhưng đủ rõ để duy trì thói quen.</p>
                    </div>
                </div>
            </div>

            <div class="hero-panel-grid stats-bar">
                <div class="hero-mini-stat">
                    <strong data-count="{{ \App\Models\User::where('role', 'client')->count() }}">{{ number_format(\App\Models\User::where('role', 'client')->count()) }}+</strong>
                    <span>Học viên</span>
                </div>
                <div class="hero-mini-stat">
                    <strong data-count="{{ \App\Models\Deck::where('visibility', 'public')->count() }}">{{ number_format(\App\Models\Deck::where('visibility', 'public')->count()) }}+</strong>
                    <span>Deck công khai</span>
                </div>
                <div class="hero-mini-stat">
                    <strong data-count="{{ \App\Models\Flashcard::count() }}">{{ number_format(\App\Models\Flashcard::count()) }}+</strong>
                    <span>Flashcard</span>
                </div>
            </div>

            <div class="hero-status-strip">
                <div>
                    <span class="hero-status-label">Workflow</span>
                    <strong>Create → Study → Review → Master</strong>
                </div>
                <span class="hero-status-badge">Built for consistency</span>
            </div>
        </div>
    </div>
</section>
