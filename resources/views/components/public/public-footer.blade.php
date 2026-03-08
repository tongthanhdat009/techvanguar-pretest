{{-- Public Footer Component --}}
<footer class="public-footer">
    <div class="container">
        <div class="content">
            <div class="brand-col">
                <div class="brand">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-500 to-orange-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">Flashcard <span class="text-orange-300">Hub</span></span>
                    </div>
                </div>
                <p class="description">
                    Nền tảng flashcard cho học viên cần một workflow học tập bền vững và cho quản trị viên cần một bề mặt điều hành gọn, rõ, đáng tin cậy.
                </p>
                <div class="social-links">
                    <a href="{{ route('register') }}" class="social-link" title="Bắt đầu miễn phí">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3l7 4v5c0 4.25-2.7 8.16-7 9-4.3-.84-7-4.75-7-9V7l7-4zm0 4.3L8.75 9v2.94c0 2.75 1.46 5.3 3.25 6.1 1.79-.8 3.25-3.35 3.25-6.1V9L12 7.3z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="link-heading">Điều hướng</h4>
                <ul class="link-list">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="#features">Tính năng</a></li>
                    <li><a href="#demo">Học thử</a></li>
                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    <li><a href="{{ route('client.login') }}">Client login</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div>
                <h4 class="link-heading">Cổng truy cập</h4>
                <ul class="link-list">
                    <li><a href="{{ route('client.login') }}">Client portal</a></li>
                    <li>
                        <a href="{{ route('admin.login') }}" class="admin-link">
                            Admin login ↗
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bottom-bar">
            <p class="copyright">© {{ date('Y') }} Flashcard Learning Hub. Structured study experience on Laravel.</p>
            <p class="version">Version 1.0.0 · Laravel {{ app()->version() }}</p>
        </div>
    </div>
</footer>
