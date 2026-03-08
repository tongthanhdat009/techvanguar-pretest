@props([
    'rating' => 0,
    'readonly' => false,
    'size' => 'md'
])

@php
    $sizeClasses = match($size) {
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        default => 'text-base'
    };
@endphp

<div class="rating-stars {{ $sizeClasses }}" {{ $readonly ? 'data-readonly' : '' }}>
    @for($i = 1; $i <= 5; $i++)
        <span class="rating-stars__star {{ $i <= $rating ? 'rating-stars__star--filled' : '' }}">
            ★
        </span>
    @endfor
</div>
