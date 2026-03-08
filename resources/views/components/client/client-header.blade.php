{{-- Client Header Component --}}
<header class="client-header">
    {{-- App Logo --}}
    <x-common.app-logo variant="client" size="md" :link="route('home')" />

    <div class="flex items-center gap-4 text-sm">
        <span class="text-gray-600">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                Logout
            </button>
        </form>
    </div>
</header>
