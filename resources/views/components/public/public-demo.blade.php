{{-- Public Demo Section Component --}}
<section id="demo" class="demo-section">
    <div class="container">
        <div class="section-header">
            <span class="section-kicker">Không cần đăng nhập</span>
            <h2 class="section-title">Demo nhỏ, cảm giác học thật.</h2>
            <p class="section-description">
                Nhấn vào bất kỳ thẻ nào để lật và xem đáp án. Cấu trúc tương tác giống với trải nghiệm học chính, chỉ được rút gọn để xem nhanh.
            </p>
            <div class="hint">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Nhấn vào thẻ để lật
            </div>
        </div>

        <div class="demo-shell">
            <div class="demo-lead-card">
                <span class="demo-lead-label">Study preview</span>
                <h3 class="demo-lead-title">Một vòng học rút gọn để người mới hiểu ngay cách nền tảng vận hành.</h3>
                <p class="demo-lead-copy">Thay vì chỉ xem landing page, bạn có thể chạm trực tiếp vào các khái niệm cốt lõi và cảm nhận logic lật thẻ, nhịp học và mức độ tập trung của giao diện.</p>

                <div class="demo-steps">
                    <div class="demo-step">
                        <span class="demo-step-index">01</span>
                        <div>
                            <strong>Chọn một thẻ</strong>
                            <p>Mỗi thẻ đại diện cho một khái niệm căn bản trong hệ thống học tập.</p>
                        </div>
                    </div>
                    <div class="demo-step">
                        <span class="demo-step-index">02</span>
                        <div>
                            <strong>Lật để kiểm tra trí nhớ</strong>
                            <p>Từ mặt trước sang mặt sau, giao diện giữ một nhịp chuyển rõ và không gây nhiễu.</p>
                        </div>
                    </div>
                    <div class="demo-step">
                        <span class="demo-step-index">03</span>
                        <div>
                            <strong>Khởi động học thật</strong>
                            <p>Khi sẵn sàng, đăng ký để tạo deck, dùng public deck hoặc bắt đầu study session của riêng bạn.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="demo-card-shell">
                <div class="demo-card-header">
                    <div class="deck-badge">
                        <span class="deck-badge-dot"></span>
                        <span class="deck-badge-name">Bộ thẻ Demo: Phương pháp học</span>
                        <span class="deck-badge-count">6 thẻ</span>
                    </div>
                    <div class="hint">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Nhấn vào thẻ để lật
                    </div>
                </div>

                <div class="card-grid" id="demo-cards">
                    @php
                    $demoCards = [
                        ['q' => 'Spaced Repetition', 'a' => 'Phương pháp lặp lại ngắt quãng: ôn tập đúng lúc bạn sắp quên để ghi nhớ lâu dài hơn.', 'front' => 'accent-teal', 'back' => 'accent-teal-back', 'emoji' => '🧠'],
                        ['q' => 'Flashcard', 'a' => 'Thẻ học hai mặt: mặt trước là câu hỏi, mặt sau là đáp án để kích hoạt nhớ lại chủ động.', 'front' => 'accent-sky', 'back' => 'accent-sky-back', 'emoji' => '📝'],
                        ['q' => 'Active Recall', 'a' => 'Chủ động nhớ lại thay vì chỉ đọc lại. Đây là lõi của học hiệu quả với flashcard.', 'front' => 'accent-green', 'back' => 'accent-green-back', 'emoji' => '💡'],
                        ['q' => 'Streak', 'a' => 'Chuỗi ngày học liên tiếp giúp việc ôn tập trở thành thói quen thay vì quyết tâm nhất thời.', 'front' => 'accent-amber', 'back' => 'accent-amber-back', 'emoji' => '🔥'],
                        ['q' => 'Experience Points', 'a' => 'XP phản ánh mức độ hoạt động và tạo một lớp phản hồi tức thời cho mỗi phiên học.', 'front' => 'accent-rose', 'back' => 'accent-rose-back', 'emoji' => '⭐'],
                        ['q' => 'Public Deck', 'a' => 'Deck công khai giúp người dùng bắt đầu nhanh, sao chép nội dung tốt và học theo cấu trúc có sẵn.', 'front' => 'accent-violet', 'back' => 'accent-violet-back', 'emoji' => '🌐'],
                    ];
                    @endphp

                    @foreach($demoCards as $card)
                    <div class="flip-card card" title="Nhấn để lật thẻ">
                        <div class="flip-card-inner w-full h-full">
                            <div class="flip-card-front demo-flip-front {{ $card['front'] }} select-none">
                                <div class="demo-card-top">
                                    <span class="demo-card-icon">{{ $card['emoji'] }}</span>
                                    <span class="demo-card-label">Câu hỏi</span>
                                    <p class="demo-card-title">{{ $card['q'] }}</p>
                                </div>
                                <div class="demo-card-bottom">
                                    <span class="demo-card-hint">Nhấn để lật</span>
                                </div>
                            </div>
                            <div class="flip-card-back demo-flip-back {{ $card['back'] }} select-none">
                                <div class="demo-card-top">
                                    <span class="demo-card-label">Đáp án</span>
                                    <p class="demo-card-answer">{{ $card['a'] }}</p>
                                </div>
                                <div class="demo-card-bottom">
                                    <span class="demo-card-hint">Nhấn để lật lại</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="demo-cta">
                    <button data-reset-cards class="reset-btn" type="button">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round"/>
                        </svg>
                        Lật lại tất cả thẻ
                    </button>
                </div>
            </div>
        </div>

        <div class="demo-cta">
            <p>Thấy workflow này hợp lý? Tạo tài khoản để bắt đầu với bộ thẻ của riêng bạn.</p>
            <a href="{{ route('register') }}" class="public-btn public-btn-primary public-btn-large">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Đăng ký miễn phí
            </a>
        </div>
    </div>
</section>
