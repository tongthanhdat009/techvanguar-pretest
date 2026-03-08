{{-- Public Footer Component --}}
<footer class="public-footer">
    <div class="container">
        <div class="content">
            {{-- Brand col --}}
            <div class="brand-col">
                <div class="brand">
                    {{-- Using app-logo with no link in footer --}}
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">Flashcard <span class="text-indigo-400">App</span></span>
                    </div>
                </div>
                <p class="description">
                    Nền tảng học tập thông minh dựa trên phương pháp Spaced Repetition,
                    giúp bạn ghi nhớ kiến thức hiệu quả và lâu dài.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link" title="GitHub">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836a9.59 9.59 0 012.504.337c1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.741 0 .267.18.579.688.481C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="link-heading">Nhanh</h4>
                <ul class="link-list">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="#demo">Học thử</a></li>
                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    <li><a href="{{ route('client.login') }}">Đăng nhập</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div>
                <h4 class="link-heading">Thông tin</h4>
                <ul class="link-list">
                    <li><span class="disabled">Về chúng tôi</span></li>
                    <li><span class="disabled">Chính sách bảo mật</span></li>
                    <li><span class="disabled">Điều khoản dịch vụ</span></li>
                    <li>
                        <a href="{{ route('admin.login') }}" class="admin-link">
                            Đăng nhập Admin ↗
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="bottom-bar">
            <p class="copyright">© {{ date('Y') }} Flashcard App. Xây dựng với ❤️ bằng Laravel & Tailwind CSS.</p>
            <p class="version">Phiên bản 1.0.0 &nbsp;·&nbsp; Laravel {{ app()->version() }}</p>
        </div>
    </div>
</footer>
