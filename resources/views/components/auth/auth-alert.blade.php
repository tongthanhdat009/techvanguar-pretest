{{-- Auth Alert Component --}}
@props(['message' => null, 'type' => 'error'])

@if($message)
    <div class="auth-alert {{ $type }} {{ request()->routeIs('admin.*') ? 'admin' : 'client' }}">
        <span class="auth-alert-icon" aria-hidden="true">{{ $type === 'success' ? 'OK' : '!' }}</span>
        <div class="auth-alert-content">
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
    </div>
@endif
