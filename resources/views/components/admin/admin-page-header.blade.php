{{-- Admin Page Header Component --}}
@props([
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'actions' => null
])

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        @if($icon)
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-sm">
                {!! $icon !!}
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm text-slate-400 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @if($actions)
        <div class="flex items-center gap-3">
            {!! $actions !!}
        </div>
    @endif
</div>
