@extends('layouts.admin', [
    'title' => 'Manage Users',
    'sidebar' => true,
    'header' => ['title' => 'Manage Users']
])

@section('content')
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            data-admin-confirm
                            data-confirm-message="Are you sure you want to delete this user?"
                            data-confirm-accept="Delete user"
                            class="btn-admin-delete">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
