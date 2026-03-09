{{-- Announcement Marquee Banner --}}
<div class="hero-marquee">
    <div class="marquee-content">
        <span class="marquee-item">🎉 Đăng ký ngay để nhận 50 XP khởi động!</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">📚 Hàng trăm deck công khai miễn phí</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">🔥 Duy trì streak để nhận thưởng hàng ngày</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">🧠 Học thông minh với Spaced Repetition</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">✨ Tạo flashcard không giới hạn</span>
    </div>
    <div class="marquee-content" aria-hidden="true">
        <span class="marquee-item">🎉 Đăng ký ngay để nhận 50 XP khởi động!</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">📚 Hàng trăm deck công khai miễn phí</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">🔥 Duy trì streak để nhận thưởng hàng ngày</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">🧠 Học thông minh với Spaced Repetition</span>
        <span class="marquee-separator">•</span>
        <span class="marquee-item">✨ Tạo flashcard không giới hạn</span>
    </div>
</div>
{{-- Public Hero Section Component --}}
<section class="hero-section hero-gradient">
    <div class="blob blob-top"></div>
    <div class="blob blob-bottom"></div>

    <div class="hero-content">
        <div class="hero-copy">
            <span class="hero-badge">Học thông minh. Ghi nhớ sâu.</span>
            <span class="sr-only">Flashcard Learning Hub – Evidence-Based Learning Platform</span>

            <h1 class="hero-headline">
                <span class="typing-text" data-typing='["Ôn tập đúng lúc.", "Ghi nhớ sâu hơn.", "Tiến độ rõ ràng."]'></span>
            </h1>

            <p class="hero-subheadline">
                Tạo flashcard, ôn tập theo nhịp và theo dõi tiến độ mỗi ngày.
            </p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-cta public-btn public-btn-primary public-btn-large">
                    Bắt đầu miễn phí
                </a>
                <a href="#demo" class="public-btn public-btn-outline public-btn-large">
                    Xem demo
                </a>
            </div>

            <div class="hero-proof-list">
                <span><span class="stat-number">{{ number_format($userCount ?? 0) }}</span> người dùng</span>
                <span><span class="stat-number">{{ number_format($deckCount ?? 0) }}</span> deck công khai</span>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-floating-icon">
                <img src="{{ asset('assets/icon-logo.svg') }}" alt="Flashcard Learning Hub" class="floating-logo">
                <div class="floating-particles">
                    <span class="particle particle-1"></span>
                    <span class="particle particle-2"></span>
                    <span class="particle particle-3"></span>
                </div>
            </div>
        </div>
    </div>
</section>
