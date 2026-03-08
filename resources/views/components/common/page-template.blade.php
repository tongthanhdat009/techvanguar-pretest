{--
    PAGE TEMPLATE HELPER

    Usage for new admin pages:
    @extends('layouts.admin', ['title' => 'Page Title', 'sidebar' => true, 'header' => ['title' => 'Page Title']])

    @section('content')
        <x-common.page-header
            title="Page Title"
            subtitle="Optional description"
        />

        {{-- Page content here --}}
    @endsection


    Usage for new client pages:
    @extends('layouts.client', ['title' => 'Page Title'])

    @section('content')
        <x-common.page-header
            title="Page Title"
            subtitle="Optional description"
        />

        {{-- Page content here --}}
    @endsection


    Usage for new auth pages:
    @extends('layouts.auth', ['title' => 'Page Title', 'type' => 'client'])

    @section('content')
        <x-auth.auth-logo
            type="client"
            title="Page Title"
            subtitle="Optional description"
        />

        {{-- Form content here --}}
    @endsection


    Usage for new public pages:
    @extends('layouts.public', ['title' => 'Page Title'])

    @section('content')
        {{-- Page content here --}}
    @endsection
--}}

{{-- This file serves as documentation only. Do not include directly. --}}
