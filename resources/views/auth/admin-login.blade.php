@extends('layouts.auth', [
    'title' => 'Admin Đăng nhập',
    'type' => 'admin'
])

@section('content')
    <span class="sr-only">Login as admin</span>

    @include('components.auth.auth-logo', [
        'type' => 'admin',
        'title' => 'Đăng nhập admin',
        'subtitle' => 'Truy cập khu vực quản trị.'
    ])

    {{-- Error Alert --}}
    @if($errors->has('login'))
        @include('components.auth.auth-alert', [
            'message' => $errors->first('login'),
            'type' => 'error'
        ])
    @endif

    {{-- Login Form --}}
    @include('components.auth.auth-form', [
        'action' => route('admin.login.store'),
        'method' => 'POST',
        'fields' => [
            ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'autocomplete' => 'email', 'placeholder' => 'admin@example.com', 'autofocus' => true, 'required' => true],
            ['name' => 'password', 'type' => 'password', 'label' => 'Mật khẩu', 'autocomplete' => 'current-password', 'placeholder' => '••••••••', 'required' => true]
        ],
        'submitText' => 'Đăng nhập',
        'submitLoadingText' => 'Đang xác thực...',
        'type' => 'admin'
    ])

    @include('components.auth.auth-links', [
        'type' => 'admin',
        'prompt' => 'Cần tài khoản học viên? <a href="' . route('register') . '">Tạo tài khoản client</a>'
    ])
@endsection
