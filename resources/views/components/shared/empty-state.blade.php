@props([
    'title' => null,
    'description' => null,
    'message' => null,
])

@if($message)
    <div {{ $attributes->class(['border-y border-dashed border-stone-400 bg-transparent px-6 py-10 text-center']) }}>
        <p class="text-sm leading-6 text-stone-600">{{ $message }}</p>
        @if (trim($slot))
            <div class="mt-4">{{ $slot }}</div>
        @endif
    </div>
@else
    <div {{ $attributes->class(['border-y border-dashed border-stone-400 bg-transparent px-6 py-10 text-center']) }}>
        @if($title)
            <h3 class="text-lg font-bold text-stone-900">{{ $title }}</h3>
        @endif
        @if($description)
            <p class="mt-2 text-sm leading-6 text-stone-600">{{ $description }}</p>
        @endif
        @if (trim($slot))
            <div class="mt-4">{{ $slot }}</div>
        @endif
    </div>
@endif
