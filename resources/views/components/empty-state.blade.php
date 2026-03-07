@props([
    'title' => null,
    'description' => null,
    'message' => null,
])

@if($message)
    <div {{ $attributes->class(['rounded-3xl border border-dashed border-slate-200 bg-white/70 px-6 py-10 text-center']) }}>
        <p class="text-sm leading-6 text-slate-500">{{ $message }}</p>
        @if (trim($slot))
            <div class="mt-4">{{ $slot }}</div>
        @endif
    </div>
@else
    <div {{ $attributes->class(['rounded-3xl border border-dashed border-slate-200 bg-white/70 px-6 py-10 text-center']) }}>
        @if($title)
            <h3 class="text-lg font-bold text-slate-950">{{ $title }}</h3>
        @endif
        @if($description)
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
        @endif
        @if (trim($slot))
            <div class="mt-4">{{ $slot }}</div>
        @endif
    </div>
@endif
