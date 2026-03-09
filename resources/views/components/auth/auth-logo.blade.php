{{-- Auth Logo Component --}}
@props([
    'type' => 'client',
    'title' => null,
    'subtitle' => null
])

<div class="auth-logo-block">
    @if($type === 'admin')
        <a href="{{ route('home') }}" class="auth-logo-link admin">
            <div class="auth-logo-mark admin">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="auth-logo-text admin">Admin <span>Portal</span></span>
        </a>
    @else
        <a href="{{ route('home') }}" class="auth-logo-link client">
            <span class="auth-logo-mark client">
                <img src="{{ asset('assets/icon-logo.svg') }}" alt="Flashcard Learning Hub">
            </span>
            <span class="auth-logo-text client">Flashcard <span>Hub</span></span>
        </a>
    @endif

    <span class="auth-logo-eyebrow {{ $type === 'admin' ? 'admin' : 'client' }}">
        {{ $type === 'admin' ? 'Restricted area' : 'Client workspace' }}
    </span>

    @if($title)
        <h1 class="auth-title {{ $type === 'admin' ? 'admin' : 'client' }}">
            {!! $title !!}
        </h1>
    @endif

    @if($subtitle)
        <p class="auth-subtitle {{ $type === 'admin' ? 'admin' : 'client' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
