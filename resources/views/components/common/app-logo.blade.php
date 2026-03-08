{{-- App Logo Component --}}
@props([
    'variant' => 'default', // default, admin, client, public
    'size' => 'md',          // sm, md, lg
    'showText' => true,
    'link' => null
])

@php
    $sizeClasses = match($size) {
        'sm' => 'w-8 h-8',
        'md' => 'w-9 h-9',
        'lg' => 'w-12 h-12'
    };

    $textSizes = match($size) {
        'sm' => 'text-base',
        'md' => 'text-lg',
        'lg' => 'text-xl'
    };

    $gradientClasses = match($variant) {
        'admin' => 'from-indigo-500 to-purple-600',
        'client' => 'from-indigo-500 to-purple-600',
        'public' => 'from-indigo-500 to-purple-600',
        'default' => 'from-indigo-500 to-purple-600'
    };

    $textColor = match($variant) {
        'admin' => 'text-white',
        'client' => 'text-gray-900',
        'public' => 'text-gray-900',
        'default' => 'text-gray-900'
    };

    $accentColor = match($variant) {
        'admin' => 'text-indigo-400',
        'client' => 'text-indigo-600',
        'public' => 'text-indigo-600',
        'default' => 'text-indigo-600'
    };

    $url = $link ?? (auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home'));
@endphp

<a href="{{ $url }}" class="inline-flex items-center gap-2.5 group">
    <div class="{{ $sizeClasses }} rounded-xl bg-gradient-to-br {{ $gradientClasses }} flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
        {{-- Flashcard Icon --}}
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
            <path d="M8 21h8M12 17v4"/>
        </svg>
    </div>
    @if($showText)
        <span class="{{ $textSizes }} font-bold {{ $textColor }}">
            Flashcard <span class="{{ $accentColor }}">App</span>
        </span>
    @endif
</a>
