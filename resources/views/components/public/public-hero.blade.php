{{-- Public Hero Section Component --}}
<section class="hero-section hero-gradient">
    {{-- Decorative blobs --}}
    <div class="blob blob-top"></div>
    <div class="blob blob-bottom"></div>

    <div class="content">
        <span class="badge">✨ Học thông minh hơn mỗi ngày</span>

        {{-- Hidden brand text for SEO --}}
        <span class="sr-only">Flashcard Learning Hub – Evidence-Based Learning Platform</span>

        {{-- Headline --}}
        <h1 class="headline">
            Ghi nhớ mọi thứ <br class="hidden sm:block" />
            dễ dàng hơn với <span class="text-yellow-300">Flashcard</span>
        </h1>

        {{-- Sub-headline --}}
        <p class="sub-headline">
            Tạo bộ thẻ của riêng bạn, ôn tập mỗi ngày và nâng cao hiệu suất học tập.
            Hệ thống lặp lại theo khoảng cách giúp bạn nhớ lâu hơn, học ít hơn.
        </p>

        {{-- CTA Buttons --}}
        <div class="cta-group">
            <a href="{{ route('register') }}"
               class="btn-cta inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-gray-900 bg-yellow-400 hover:bg-yellow-300 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Bắt đầu miễn phí ngay
            </a>
            <a href="#demo"
               class="inline-flex items-center gap-2 px-6 py-4 text-base font-medium text-white border border-white/40 rounded-2xl hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M10 8l6 4-6 4V8z"/>
                </svg>
                Xem thử ngay
            </a>
        </div>

        {{-- Stats bar --}}
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value" data-count="{{ \App\Models\User::where('role', 'client')->count() }}">
                    {{ number_format(\App\Models\User::where('role', 'client')->count()) }}+
                </div>
                <div class="stat-label">Học viên</div>
            </div>
            <div class="stat-item stat-divider">
                <div class="stat-value" data-count="{{ \App\Models\Deck::where('visibility','public')->count() }}">
                    {{ number_format(\App\Models\Deck::where('visibility','public')->count()) }}+
                </div>
                <div class="stat-label">Bộ thẻ công khai</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" data-count="{{ \App\Models\Flashcard::count() }}">
                    {{ number_format(\App\Models\Flashcard::count()) }}+
                </div>
                <div class="stat-label">Thẻ học</div>
            </div>
        </div>
    </div>
</section>
