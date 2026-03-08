<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard – Flashcard Learning Hub</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    <a href="{{ route('home') }}" class="text-lg font-bold text-indigo-700">Flashcard Learning Hub</a>
    <div class="flex items-center gap-4 text-sm">
        <span class="text-gray-600">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-red-500 hover:text-red-700">Logout</button>
        </form>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Progress summary --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
            <div class="text-2xl font-bold text-indigo-600">{{ $progressSummary['new'] }}</div>
            <div class="text-xs text-gray-500 mt-1">New</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
            <div class="text-2xl font-bold text-yellow-500">{{ $progressSummary['learning'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Learning</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
            <div class="text-2xl font-bold text-green-600">{{ $progressSummary['mastered'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Mastered</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
            <div class="text-2xl font-bold text-orange-500">{{ $progressSummary['due_today'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Due today</div>
        </div>
    </div>

    {{-- My decks --}}
    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 mb-4">My library</h2>
        @if($ownedDecks->isEmpty())
        <p class="text-gray-400 text-sm">You have no decks yet. <a href="{{ route('register') }}" class="text-indigo-600">Create one!</a></p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($ownedDecks as $deck)
            <a href="{{ route('client.decks.show', $deck) }}"
               class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $deck->title }}</h3>
                <p class="text-xs text-gray-400">{{ $deck->flashcards_count ?? 0 }} cards</p>
            </a>
            @endforeach
        </div>
        @endif
    </section>

    {{-- Community library --}}
    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Community library</h2>
        @if($communityDecks->isEmpty())
        <p class="text-gray-400 text-sm">No public decks available yet.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($communityDecks as $deck)
            <a href="{{ route('client.decks.show', $deck) }}"
               class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $deck->title }}</h3>
                <p class="text-xs text-gray-400">{{ $deck->flashcards_count ?? 0 }} cards</p>
            </a>
            @endforeach
        </div>
        @endif
    </section>

</main>

</body>
</html>
