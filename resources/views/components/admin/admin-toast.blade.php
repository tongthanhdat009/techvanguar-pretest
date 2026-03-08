{{-- Admin Toast Stack Component --}}
@props(['messages' => []])

<div data-admin-toast-stack class="admin-toast-stack">
    @foreach($messages as $message)
        <div class="toast {{ $message['type'] ?? 'success' }}">
            {{ $message['text'] ?? $message }}
        </div>
    @endforeach

    @if(session('status'))
        <div class="toast success">
            {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div class="toast error">
            {{ session('error') }}
        </div>
    @endif
</div>
