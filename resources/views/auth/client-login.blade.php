@extends('layouts.auth', [
    'title' => 'Đăng nhập',
    'type' => 'client'
])

@section('content')
    <span class="sr-only">Login as client</span>

    @include('components.auth.auth-logo', [
        'type' => 'client',
        'title' => 'Chào mừng trở lại',
        'subtitle' => 'Đăng nhập để tiếp tục học.'
    ])

    {{-- Error Alert --}}
    @if($errors->has('login'))
        @include('components.auth.auth-alert', [
            'message' => $errors->first('login'),
            'type' => 'error'
        ])
    @endif

    {{-- Success Alert --}}
    @if(session('status'))
        @include('components.auth.auth-alert', [
            'message' => session('status'),
            'type' => 'success'
        ])
    @endif

    {{-- Login Form --}}
    @include('components.auth.auth-form', [
        'action' => route('client.login.store'),
        'method' => 'POST',
        'fields' => [
            ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'autocomplete' => 'email', 'placeholder' => 'ban@email.com', 'value' => old('email'), 'autofocus' => true, 'required' => true],
            ['name' => 'password', 'type' => 'password', 'label' => 'Mật khẩu', 'autocomplete' => 'current-password', 'placeholder' => '••••••••', 'required' => true]
        ],
        'submitText' => 'Tiếp tục học',
        'submitLoadingText' => 'Đang đăng nhập...',
        'type' => 'client'
    ])

    {{-- Auth Links --}}
    @include('components.auth.auth-links', ['type' => 'client'])

    {{-- Forgot Password Link --}}
    <p class="auth-links client">
        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
    </p>
@endsection
