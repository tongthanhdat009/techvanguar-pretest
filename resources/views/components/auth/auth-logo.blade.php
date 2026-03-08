{{-- Auth Logo Component --}}
@props([
    'type' => 'client',
    'title' => null,
    'subtitle' => null
])

<div class="text-center mb-8">
    @if($type === 'admin')
        {{-- Admin uses shield icon for security context --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center border border-indigo-700 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-white">Admin <span class="text-indigo-400">Portal</span></span>
        </a>
    @else
        {{-- Client uses standard app logo --}}
        <x-common.app-logo variant="client" size="lg" />
    @endif

    {{-- Auth Title --}}
    @if($title)
        <h1 class="mt-5 text-2xl font-extrabold {{ $type === 'admin' ? 'text-white' : 'text-gray-900' }}">
            {{ $title }}
        </h1>
    @endif

    {{-- Auth Subtitle --}}
    @if($subtitle)
        <p class="mt-1 text-sm {{ $type === 'admin' ? 'text-slate-400' : 'text-gray-500' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
