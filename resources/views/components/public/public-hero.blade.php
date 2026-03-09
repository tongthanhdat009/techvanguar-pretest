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
                <span>✓ Spaced repetition</span>
                <span>✓ Deck cộng đồng</span>
                <span>✓ Theo dõi tiến độ</span>
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
