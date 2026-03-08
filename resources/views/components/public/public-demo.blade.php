{{-- Public Demo Section Component --}}
<section id="demo" class="demo-section">
    <div class="container">
        {{-- Section header --}}
        <div class="section-header">
            <span class="badge">Không cần đăng nhập</span>
            <h2 class="section-title">Trải nghiệm thử ngay!</h2>
            <p class="section-description">
                Nhấn vào bất kỳ thẻ nào để lật và xem đáp án. Cảm nhận trải nghiệm học thực sự!
            </p>
            <div class="hint">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Nhấn vào thẻ để lật
            </div>
        </div>

        {{-- Demo Deck Title --}}
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="deck-badge">
                <span class="deck-badge-dot"></span>
                <span class="deck-badge-name">Bộ thẻ Demo</span>
                <span class="deck-badge-count">6 thẻ</span>
            </div>
        </div>

        {{-- Flip Cards Grid --}}
        <div class="card-grid" id="demo-cards">
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

            @foreach($demoCards as $card)
            <div class="flip-card card" title="Nhấn để lật thẻ">
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
        <div class="text-center">
            <button data-reset-cards class="reset-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round"/>
                </svg>
                Lật lại tất cả thẻ
            </button>
        </div>

        {{-- CTA under demo --}}
        <div class="demo-cta">
            <p>Thích trải nghiệm này? Đăng ký để tạo bộ thẻ của riêng bạn!</p>
            <a href="{{ route('register') }}" class="btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Đăng ký miễn phí
            </a>
        </div>
    </div>
</section>
