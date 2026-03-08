<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Decks – Admin</title>
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
    <h1>Manage Decks</h1>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Owner</th>
                <th>Visibility</th>
                <th>Flashcards</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($decks as $deck)
            <tr>
                <td>{{ $deck->title }}</td>
                <td>{{ $deck->owner?->name ?? '—' }}</td>
                <td>{{ $deck->visibility }}</td>
                <td>{{ $deck->flashcards_count }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.decks.destroy', $deck) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            data-admin-confirm
                            data-confirm-message="Delete this deck and all its flashcards?"
                            data-confirm-accept="Delete deck">
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
