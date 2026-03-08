{{-- Admin Table Component --}}
@props([
    'columns' => [], // ['title' => 'Name', 'key' => 'name']
    'rows' => [],
    'actions' => true
])

<table class="admin-table">
    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column['title'] }}</th>
            @endforeach
            @if($actions)
                <th>Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                @foreach($columns as $column)
                    <td>{{ $row[$column['key']] ?? '' }}</td>
                @endforeach
                @if($actions && isset($row['actions']))
                    <td>
                        {{ $row['actions'] }}
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
