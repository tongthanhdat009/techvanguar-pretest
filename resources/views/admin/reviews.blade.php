<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews – Admin</title>
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

<main>
    <h1>Manage Reviews</h1>

    <table>
        <thead>
            <tr>
                <th>Deck</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->deck?->title ?? '—' }}</td>
                <td>{{ $review->user?->name ?? '—' }}</td>
                <td>{{ $review->rating }}</td>
                <td>{{ $review->comment }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            data-admin-confirm
                            data-confirm-message="Remove this review?"
                            data-confirm-accept="Remove review">
                            Remove
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
