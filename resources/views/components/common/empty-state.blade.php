{{-- Empty State Component --}}
@props([
    'title' => 'No items found',
    'description' => null,
    'icon' => null,
    'action' => null, // ['url' => '', 'text' => '']
])

<div class="text-center py-12">
    @if($icon)
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
            {!! $icon !!}
        </div>
    @endif

    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>

    @if($description)
        <p class="text-gray-500 mb-6">{{ $description }}</p>
    @endif

    @if($action)
        <a href="{{ $action['url'] }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
            {{ $action['text'] }}
        </a>
    @endif
</div>
