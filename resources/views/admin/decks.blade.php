@extends('layouts.admin', [
    'title' => 'Manage Decks',
    'sidebar' => true,
    'header' => ['title' => 'Manage Decks']
])

@section('content')
    <table class="admin-table">
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
                            data-confirm-accept="Delete deck"
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
