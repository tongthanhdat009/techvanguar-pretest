@php
    $adminUser = auth('admin')->user();
@endphp

<header class="admin-topnav">
    @if (isset($sidebar) && $sidebar !== false)
        <button data-admin-sidebar-toggle class="admin-topnav-toggle" aria-label="Toggle sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    @endif

    <div class="admin-topnav-title-group">
        <span class="admin-topnav-eyebrow">Operations</span>
        <span class="admin-topnav-title">{{ $title ?? 'Admin Portal' }}</span>
    </div>

    <div class="admin-topnav-right">
        <div class="user-dropdown" data-user-dropdown>
            <button class="user-dropdown-btn" data-dropdown-toggle aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr($adminUser->name ?? '', 0, 1)) }}
                </div>
                <span class="user-name">{{ $adminUser->name }}</span>
                <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div class="dropdown-backdrop" data-dropdown-backdrop aria-hidden="true"></div>

            <div class="dropdown-menu">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">{{ strtoupper(substr($adminUser->name ?? '', 0, 1)) }}</div>
                    <div class="dropdown-user-info">
                        <div class="dropdown-user-name">{{ $adminUser->name }}</div>
                        <div class="dropdown-user-email">{{ $adminUser->email }}</div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a href="{{ route('admin.profile') }}" class="dropdown-item">
                    <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Hồ sơ</span>
                </a>
                <a href="{{ route('admin.account') }}" class="dropdown-item">
                    <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Tài khoản</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="dropdown-item">
                    <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.341 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.751c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527.461a20.845 20.845 0 0 1-1.89 2.275c-.292.358-.748.54-1.212.46a7.606 7.606 0 0 1-1.77-.816c-.71-.41-1.38-.914-2.01-1.476-.77-.684-1.493-1.421-2.168-2.191-.459-.534-.786-1.178-1.058-1.832-.197-.478-.22-.988-.074-1.45.156-.49.76-.753 1.254-.91l.632-.182c.397-.116.82-.048 1.162.178.622.408 1.32.832 2.09 1.275" />
                    </svg>
                    <span>Cài đặt</span>
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('admin.logout') }}" method="POST" class="dropdown-logout-form">
                    @csrf
                    <button type="submit" class="dropdown-item dropdown-item-danger">
                        <svg class="dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
