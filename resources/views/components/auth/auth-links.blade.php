{{-- Auth Links Component --}}
@props([
    'type' => 'client',
    'loginRoute' => 'client.login',
    'registerRoute' => 'register'
])

<p class="auth-links {{ $type }}">
    @if(isset($prompt))
        {{ $prompt }}
    @else
        @if($type === 'register')
            Đã có tài khoản?
            <a href="{{ route($loginRoute) }}">Đăng nhập</a>
        @else
            Chưa có tài khoản?
            <a href="{{ route($registerRoute) }}">Đăng ký miễn phí</a>
        @endif
    @endif
</p>
