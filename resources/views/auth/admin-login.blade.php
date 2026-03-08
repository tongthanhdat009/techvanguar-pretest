@extends('layouts.auth', [
    'title' => 'Admin Đăng nhập',
    'type' => 'admin'
])

@section('content')
    @include('components.auth.auth-logo', [
        'type' => 'admin',
        'title' => 'Đăng nhập quản trị',
        'subtitle' => 'Login as admin – chỉ dành cho quản trị viên'
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
            ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'autocomplete' => 'email', 'placeholder' => ''],
            ['name' => 'password', 'type' => 'password', 'label' => 'Mật khẩu', 'autocomplete' => 'current-password', 'placeholder' => '••••••••', 'required' => true]
        ],
        'submitText' => 'Đăng nhập Admin',
        'type' => 'admin'
    ])
@endsection
