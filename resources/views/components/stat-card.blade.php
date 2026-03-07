@props([
    'label',
    'value',
    'tone' => 'sky',
])

@php
    $tones = [
        'sky' => 'border-sky-100 shadow-sky-100/70',
        'amber' => 'border-amber-100 shadow-amber-100/70',
        'emerald' => 'border-emerald-100 shadow-emerald-100/70',
        'slate' => 'border-slate-200 shadow-slate-200/60',
    ];
@endphp

<div {{ $attributes->class(['dashboard-card', $tones[$tone] ?? $tones['sky']]) }}>
    <div class="text-sm font-semibold text-slate-500">{{ $label }}</div>
    <div class="mt-2 text-3xl font-black text-slate-950">{{ $value }}</div>
    @if (trim($slot))
        <div class="mt-2 text-sm text-slate-500">{{ $slot }}</div>
    @endif
</div>
