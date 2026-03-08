{{-- Admin Breadcrumb Component --}}
@if(isset($items) && count($items) > 0)
<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </li>
            @endif

            <li>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="text-slate-400 hover:text-slate-200 transition-colors">
                        {{ $item['title'] }}
                    </a>
                @else
                    <span class="text-slate-200">{{ $item['title'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
