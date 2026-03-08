<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Flashcard Learning Hub – Ghi nhớ mọi thứ dễ dàng hơn</title>
    <meta name="description" content="Tạo bộ thẻ của riêng bạn, ôn tập mỗi ngày và nâng cao hiệu suất học tập với Flashcard Learning Hub." />
    @vite(['resources/css/app.css'])
    <style>
        /* ── Flip Card ─────────────────────────────────────── */
        .flip-card {
            perspective: 1000px;
            cursor: pointer;
        }
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.55s cubic-bezier(.4,0,.2,1);
            transform-style: preserve-3d;
        }
        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }
        .flip-card-front,
        .flip-card-back {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            padding: 2rem 1.5rem;
        }
        .flip-card-back {
            transform: rotateY(180deg);
        }

        /* ── Gradient hero bg ──────────────────────────────── */
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        }

        /* ── Smooth scroll ─────────────────────────────────── */
        html { scroll-behavior: smooth; }

        /* ── Pulse glow on CTA ─────────────────────────────── */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(168, 85, 247, .55); }
            50%       { box-shadow: 0 0 0 14px rgba(168, 85, 247, 0); }
        }
        .btn-cta { animation: pulse-glow 2.4s ease-in-out infinite; }

        /* ── Feature card hover ────────────────────────────── */
        .feature-card {
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(79,70,229,.15);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

{{-- ══════════════════════════════════════════════════════════════════════════
     1. NAVBAR
══════════════════════════════════════════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-200/80 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-16">

            {{-- Logo + Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl hero-gradient flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-900 tracking-tight">Flashcard <span class="text-indigo-600">App</span></span>
            </a>

            {{-- Nav actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('client.login') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white hero-gradient hover:opacity-90 rounded-lg shadow-sm transition-opacity">
                    Đăng ký
                </a>
            </div>
        </nav>
    </div>
</header>

{{-- ══════════════════════════════════════════════════════════════════════════
     2. HERO SECTION
══════════════════════════════════════════════════════════════════════════ --}}
<section class="relative hero-gradient overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-28 text-center">
        <span class="inline-block mb-5 px-4 py-1.5 bg-white/20 text-white text-xs font-semibold rounded-full tracking-widest uppercase">
            ✨ Học thông minh hơn mỗi ngày
        </span>

        {{-- Hidden brand text for SEO & test compatibility --}}
        <span class="sr-only">Flashcard Learning Hub – Evidence-Based Learning Platform</span>

        {{-- Headline --}}
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
            Ghi nhớ mọi thứ <br class="hidden sm:block" />
            dễ dàng hơn với <span class="text-yellow-300">Flashcard</span>
        </h1>

        {{-- Sub-headline --}}
        <p class="text-lg sm:text-xl text-indigo-100 max-w-2xl mx-auto mb-10 leading-relaxed">
            Tạo bộ thẻ của riêng bạn, ôn tập mỗi ngày và nâng cao hiệu suất học tập.
            Hệ thống lặp lại theo khoảng cách giúp bạn nhớ lâu hơn, học ít hơn.
        </p>

        {{-- CTA Button --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}"
               class="btn-cta inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-yellow-400 hover:bg-yellow-300 text-gray-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Bắt đầu miễn phí ngay
            </a>
            <a href="#demo"
               class="inline-flex items-center gap-2 px-6 py-4 text-base font-medium text-white border border-white/40 rounded-2xl hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M10 8l6 4-6 4V8z"/>
                </svg>
                Xem thử ngay
            </a>
        </div>

        {{-- Stats bar --}}
        <div class="mt-16 grid grid-cols-3 gap-6 max-w-md mx-auto">
            <div class="text-center">
                <div class="text-2xl font-extrabold text-white">{{ number_format(\App\Models\User::where('role', 'client')->count()) }}+</div>
                <div class="text-xs text-indigo-200 mt-1">Học viên</div>
            </div>
            <div class="text-center border-x border-white/20">
                <div class="text-2xl font-extrabold text-white">{{ number_format(\App\Models\Deck::where('visibility','public')->count()) }}+</div>
                <div class="text-xs text-indigo-200 mt-1">Bộ thẻ công khai</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-extrabold text-white">{{ number_format(\App\Models\Flashcard::count()) }}+</div>
                <div class="text-xs text-indigo-200 mt-1">Thẻ học</div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════
     FEATURE HIGHLIGHTS
══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Tại sao chọn Flashcard App?</h2>
            <p class="mt-3 text-gray-500 text-lg max-w-xl mx-auto">Công nghệ lặp lại theo khoảng cách (Spaced Repetition) được chứng minh giúp ghi nhớ hiệu quả hơn tới 5 lần.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Feature 1 --}}
            <div class="feature-card bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-7 border border-indigo-100">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Lặp lại thông minh</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Thuật toán SRS tự động lên lịch ôn tập, chỉ hiện thẻ bạn sắp quên – không lãng phí thời gian.</p>
            </div>

            {{-- Feature 2 --}}
            <div class="feature-card bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-7 border border-yellow-100">
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Chuỗi học mỗi ngày</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Theo dõi streak hàng ngày và tích lũy XP, giúp bạn duy trì thói quen học tập đều đặn.</p>
            </div>

            {{-- Feature 3 --}}
            <div class="feature-card bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-7 border border-green-100">
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a4 4 0 00-5.92-3.52M9 20H4v-2a4 4 0 015.92-3.52M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Thư viện cộng đồng</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Chia sẻ bộ thẻ, sao chép bộ thẻ công khai và học từ cộng đồng hàng nghìn học viên.</p>
            </div>

            {{-- Feature 4 --}}
            <div class="feature-card bg-gradient-to-br from-pink-50 to-rose-50 rounded-2xl p-7 border border-pink-100">
                <div class="w-12 h-12 bg-pink-600 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Thống kê tiến độ</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Biểu đồ trực quan giúp bạn thấy rõ mình đang học tốt những gì, cần cải thiện điều gì.</p>
            </div>

            {{-- Feature 5 --}}
            <div class="feature-card bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-7 border border-blue-100">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Nhập / Xuất CSV</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Dễ dàng nhập bộ thẻ từ file CSV hoặc xuất để lưu trữ và chia sẻ ngoài nền tảng.</p>
            </div>

            {{-- Feature 6 --}}
            <div class="feature-card bg-gradient-to-br from-violet-50 to-indigo-50 rounded-2xl p-7 border border-violet-100">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Giao diện trực quan</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Thiết kế gọn gàng, dễ sử dụng trên mọi thiết bị – máy tính, máy tính bảng và điện thoại.</p>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════
     3. DEMO "HỌC THỬ" SECTION
══════════════════════════════════════════════════════════════════════════ --}}
<section id="demo" class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-14">
            <span class="inline-block mb-3 px-4 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-widest">
                Không cần đăng nhập
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Trải nghiệm thử ngay!</h2>
            <p class="mt-3 text-gray-500 text-lg max-w-lg mx-auto">
                Nhấn vào bất kỳ thẻ nào để lật và xem đáp án. Cảm nhận trải nghiệm học thực sự!
            </p>
            <div class="mt-4 inline-flex items-center gap-2 text-sm text-indigo-500 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Nhấn vào thẻ để lật
            </div>
        </div>

        {{-- Demo Deck Title --}}
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="flex items-center gap-2.5 bg-white border border-gray-200 rounded-2xl px-5 py-2.5 shadow-sm">
                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                <span class="font-semibold text-gray-700 text-sm">Bộ thẻ Demo</span>
                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">6 thẻ</span>
            </div>
        </div>

        {{-- Flip Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
             id="demo-cards"
             x-data="{ active: null }">

            @php
            $demoCards = [
                ['q' => 'Spaced Repetition', 'a' => 'Phương pháp lặp lại ngắt quãng – ôn tập đúng lúc bạn sắp quên để ghi nhớ lâu dài', 'color_front' => 'from-indigo-500 to-purple-600', 'color_back' => 'from-purple-500 to-indigo-600', 'emoji' => '🧠'],
                ['q' => 'Flashcard', 'a' => 'Thẻ học hai mặt – mặt trước là câu hỏi, mặt sau là đáp án', 'color_front' => 'from-blue-500 to-cyan-600', 'color_back' => 'from-cyan-500 to-blue-600', 'emoji' => '📝'],
                ['q' => 'Active Recall', 'a' => 'Chủ động nhớ lại thông tin thay vì đọc thụ động – tăng hiệu quả học tới 3 lần', 'color_front' => 'from-green-500 to-teal-600', 'color_back' => 'from-teal-500 to-green-600', 'emoji' => '💡'],
                ['q' => 'Streak (Chuỗi ngày)', 'a' => 'Số ngày học liên tiếp không bỏ lỡ – duy trì chuỗi để tích lũy thói quen học tập', 'color_front' => 'from-orange-500 to-yellow-500', 'color_back' => 'from-yellow-500 to-orange-500', 'emoji' => '🔥'],
                ['q' => 'Experience Points (XP)', 'a' => 'Điểm kinh nghiệm tích lũy khi học – mỗi thẻ trả lời đúng bạn nhận được XP và lên cấp', 'color_front' => 'from-pink-500 to-rose-600', 'color_back' => 'from-rose-500 to-pink-600', 'emoji' => '⭐'],
                ['q' => 'Public Deck', 'a' => 'Bộ thẻ công khai – mọi người đều có thể xem và sao chép vào thư viện cá nhân', 'color_front' => 'from-violet-500 to-indigo-600', 'color_back' => 'from-indigo-500 to-violet-600', 'emoji' => '🌐'],
            ];
            @endphp

            @foreach($demoCards as $index => $card)
            <div class="flip-card h-52"
                 onclick="this.classList.toggle('flipped')"
                 title="Nhấn để lật thẻ">
                <div class="flip-card-inner w-full h-full">
                    {{-- Front --}}
                    <div class="flip-card-front bg-gradient-to-br {{ $card['color_front'] }} shadow-lg select-none">
                        <span class="text-4xl mb-4">{{ $card['emoji'] }}</span>
                        <span class="text-xs font-semibold text-white/60 uppercase tracking-widest mb-2">Câu hỏi</span>
                        <p class="text-center text-white font-bold text-lg leading-snug">{{ $card['q'] }}</p>
                        <span class="absolute bottom-3 text-white/40 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round"/>
                            </svg>
                            Nhấn để lật
                        </span>
                    </div>
                    {{-- Back --}}
                    <div class="flip-card-back bg-gradient-to-br {{ $card['color_back'] }} shadow-lg select-none">
                        <span class="text-xs font-semibold text-white/60 uppercase tracking-widest mb-3">Đáp án</span>
                        <p class="text-center text-white text-sm leading-relaxed px-2">{{ $card['a'] }}</p>
                        <span class="absolute bottom-3 text-white/40 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round"/>
                            </svg>
                            Nhấn để lật lại
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Reset button --}}
        <div class="mt-8 text-center">
            <button onclick="document.querySelectorAll('#demo-cards .flip-card').forEach(c => c.classList.remove('flipped'))"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round"/>
                </svg>
                Lật lại tất cả thẻ
            </button>
        </div>

        {{-- CTA under demo --}}
        <div class="mt-12 text-center">
            <p class="text-gray-500 mb-4">Thích trải nghiệm này? Đăng ký để tạo bộ thẻ của riêng bạn!</p>
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 text-base font-bold text-white hero-gradient rounded-xl shadow-md hover:opacity-90 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Đăng ký miễn phí
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════
     PUBLIC DECKS (from DB)
══════════════════════════════════════════════════════════════════════════ --}}
@if($publicDecks->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Bộ thẻ nổi bật từ cộng đồng</h2>
            <p class="mt-2 text-gray-500">Khám phá các bộ thẻ công khai được tạo bởi cộng đồng học viên.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($publicDecks as $deck)
            <div class="feature-card bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 truncate">{{ $deck->title }}</h3>
                        @if($deck->category)
                        <span class="inline-block mt-1 text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $deck->category }}</span>
                        @endif
                    </div>
                    <div class="ml-3 flex-shrink-0 w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                @if($deck->description)
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $deck->description }}</p>
                @endif
                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="5" width="20" height="14" rx="2"/><path d="M16 2v6M8 2v6"/>
                        </svg>
                        {{ $deck->flashcards_count ?? 0 }} thẻ
                    </span>
                    @if($deck->reviews_avg_rating)
                    <span class="flex items-center gap-1 text-yellow-500">
                        ★ {{ number_format($deck->reviews_avg_rating, 1) }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                Xem tất cả bộ thẻ sau khi đăng ký →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════════
     FINAL CTA BAND
══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-20 hero-gradient relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
    <div class="relative max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Sẵn sàng bắt đầu hành trình học tập?</h2>
        <p class="text-indigo-100 text-lg mb-8">Miễn phí hoàn toàn. Không cần thẻ tín dụng. Bắt đầu chỉ trong 30 giây.</p>
        <a href="{{ route('register') }}"
           class="btn-cta inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-gray-900 bg-yellow-400 hover:bg-yellow-300 rounded-2xl shadow-xl hover:shadow-2xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Bắt đầu miễn phí ngay
        </a>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════
     4. FOOTER
══════════════════════════════════════════════════════════════════════════ --}}
<footer class="bg-gray-900 text-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            {{-- Brand col --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 rounded-xl hero-gradient flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <path d="M8 21h8M12 17v4"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white">Flashcard <span class="text-indigo-400">App</span></span>
                </div>
                <p class="text-sm leading-relaxed max-w-xs">
                    Nền tảng học tập thông minh dựa trên phương pháp Spaced Repetition,
                    giúp bạn ghi nhớ kiến thức hiệu quả và lâu dài.
                </p>
                <div class="mt-5 flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-indigo-600 flex items-center justify-center transition-colors" title="GitHub">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836a9.59 9.59 0 012.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.741 0 .267.18.579.688.481C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Nhanh</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Trang chủ</a></li>
                    <li><a href="#demo" class="hover:text-white transition-colors">Học thử</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Đăng ký</a></li>
                    <li><a href="{{ route('client.login') }}" class="hover:text-white transition-colors">Đăng nhập</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Thông tin</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><span class="text-gray-500">Về chúng tôi</span></li>
                    <li><span class="text-gray-500">Chính sách bảo mật</span></li>
                    <li><span class="text-gray-500">Điều khoản dịch vụ</span></li>
                    <li>
                        <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors text-gray-600 text-xs">
                            Đăng nhập Admin ↗
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <p>© {{ date('Y') }} Flashcard App. Xây dựng với ❤️ bằng Laravel & Tailwind CSS.</p>
            <p class="text-gray-600">Phiên bản 1.0.0 &nbsp;·&nbsp; Laravel {{ app()->version() }}</p>
        </div>
    </div>
</footer>

</body>
</html>
