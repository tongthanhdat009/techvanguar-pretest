@extends('layouts.auth', [
    'title' => 'Admin Đăng nhập',
    'type' => 'admin'
])

@section('content')
    <span class="sr-only">Login as admin</span>

    @include('components.auth.auth-logo', [
        'type' => 'admin',
        'title' => 'Đăng nhập quản trị',
        'subtitle' => 'Chỉ dành cho quản trị viên hệ thống.'
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
            ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'autocomplete' => 'email', 'placeholder' => 'admin@example.com', 'hint' => 'Đăng nhập bằng tài khoản có quyền quản trị hệ thống.', 'autofocus' => true, 'required' => true],
            ['name' => 'password', 'type' => 'password', 'label' => 'Mật khẩu', 'autocomplete' => 'current-password', 'placeholder' => '••••••••', 'hint' => 'Guard admin dùng session riêng với client portal.', 'required' => true]
        ],
        'submitText' => 'Vào Admin Portal',
        'submitLoadingText' => 'Đang xác thực...',
        'type' => 'admin'
    ])

    @include('components.auth.auth-links', [
        'type' => 'admin',
        'prompt' => 'Cần tài khoản học viên? <a href="' . route('register') . '">Tạo tài khoản client</a>'
    ])
@endsection
