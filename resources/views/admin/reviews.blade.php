@extends('layouts.admin', [
    'title' => 'Manage Reviews',
    'sidebar' => true,
    'header' => ['title' => 'Manage Reviews']
])

@section('content')
    <table class="admin-table">
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
                            data-confirm-accept="Remove review"
                            class="btn-admin-delete">
                            Remove
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
