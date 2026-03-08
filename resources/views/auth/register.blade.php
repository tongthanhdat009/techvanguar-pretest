@extends('layouts.auth', [
    'title' => 'Đăng ký',
    'type' => 'client'
])

@section('content')
    @include('components.auth.auth-logo', [
        'type' => 'client',
        'title' => 'Tạo tài khoản mới',
        'subtitle' => 'Thiết lập không gian học tập của bạn trong chưa đến một phút.'
    ])

    {{-- Error Alert --}}
    @if($errors->any())
        @include('components.auth.auth-alert', [
            'message' => $errors->all(),
            'type' => 'error'
        ])
    @endif

    {{-- Register Form --}}
    @include('components.auth.auth-form', [
        'action' => route('register.store'),
        'method' => 'POST',
        'fields' => [
            ['name' => 'name', 'type' => 'text', 'label' => 'Họ và tên', 'autocomplete' => 'name', 'placeholder' => 'Nguyễn Văn A', 'value' => old('name'), 'hint' => 'Tên này sẽ hiển thị trong dashboard và phần review deck.', 'autofocus' => true, 'required' => true],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'autocomplete' => 'email', 'placeholder' => 'ban@email.com', 'value' => old('email'), 'hint' => 'Email dùng để đăng nhập lại và giữ lịch ôn tập của bạn.', 'required' => true],
            ['name' => 'password', 'type' => 'password', 'label' => 'Mật khẩu', 'autocomplete' => 'new-password', 'placeholder' => 'Tối thiểu 8 ký tự', 'hint' => 'Nên dùng mật khẩu đủ dài để bảo vệ dữ liệu học tập cá nhân.', 'required' => true],
            ['name' => 'password_confirmation', 'type' => 'password', 'label' => 'Xác nhận mật khẩu', 'autocomplete' => 'new-password', 'placeholder' => 'Nhập lại mật khẩu', 'hint' => 'Nhập lại để tránh lỗi trước khi tạo tài khoản.', 'required' => true]
        ],
        'submitText' => 'Tạo tài khoản miễn phí',
        'submitLoadingText' => 'Đang tạo tài khoản...',
        'type' => 'client'
    ])

    {{-- Auth Links --}}
    @include('components.auth.auth-links', [
        'type' => 'client',
        'prompt' => 'Đã có tài khoản? <a href="' . route('client.login') . '">Đăng nhập</a>'
    ])
@endsection
