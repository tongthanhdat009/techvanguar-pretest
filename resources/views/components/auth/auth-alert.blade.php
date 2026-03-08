{{-- Auth Alert Component --}}
@props(['message' => null, 'type' => 'error'])

@if($message)
    <div class="auth-alert {{ $type }}">
        @if(is_array($message))
            <ul>
                @foreach($message as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ $message }}
        @endif
    </div>
@endif
