@extends('layouts.auth', [
    'title' => 'Đăng nhập',
    'type' => 'client'
])

@section('content')
    @include('components.auth.auth-logo', [
        'type' => 'client',
        'title' => 'Chào mừng trở lại!',
        'subtitle' => 'Login as client – đăng nhập để tiếp tục học tập'
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
            ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'autocomplete' => 'email', 'placeholder' => 'ban@email.com', 'value' => old('email')],
            ['name' => 'password', 'type' => 'password', 'label' => 'Mật khẩu', 'autocomplete' => 'current-password', 'placeholder' => '••••••••', 'required' => true]
        ],
        'submitText' => 'Đăng nhập',
        'type' => 'client'
    ])

    {{-- Auth Links --}}
    @include('components.auth.auth-links', ['type' => 'client'])
@endsection
