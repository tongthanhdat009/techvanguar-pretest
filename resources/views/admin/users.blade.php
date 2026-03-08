<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users – Admin</title>
</head>
<body>

{{-- Admin sidebar toggle --}}
<div admin-sidebar-open="false" id="admin-sidebar">
    <nav>Admin Panel</nav>
</div>

{{-- Toast stack for flash messages --}}
<div data-admin-toast-stack>
    @if(session('status'))
        <div class="toast">{{ session('status') }}</div>
    @endif
</div>

{{-- Confirm modal --}}
<div data-admin-confirm id="confirm-modal">
    <button data-confirm-message="Are you sure you want to delete this user?" data-confirm-accept="Delete user">
        Confirm
    </button>
</div>

<main>
    <h1>Manage Users</h1>

    <table>
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
                            data-confirm-accept="Delete user">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</main>

</body>
</html>
