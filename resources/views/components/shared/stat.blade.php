@props([
    'label',
    'value',
    'tone' => 'sky',
])

@php
    $tones = [
        'sky' => 'border-sky-300',
        'amber' => 'border-amber-300',
        'emerald' => 'border-emerald-300',
        'slate' => 'border-stone-300',
    ];
@endphp

<div {{ $attributes->class(['dashboard-card', $tones[$tone] ?? $tones['sky']]) }}>
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="text-xs font-bold uppercase tracking-[0.22em] text-stone-500">{{ $label }}</div>
        <div class="text-3xl font-bold text-stone-900">{{ $value }}</div>
    </div>
    @if (trim($slot))
        <div class="mt-2 text-sm text-stone-600">{{ $slot }}</div>
    @endif
</div>
