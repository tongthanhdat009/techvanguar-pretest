<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Flashcard Learning Hub' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $clientUser = auth('client')->user();
        $navigationItems = [
            [
                'label' => 'Dashboard',
                'href' => route('client.dashboard'),
                'active' => request()->routeIs('client.dashboard'),
            ],
            [
                'label' => 'Library',
                'href' => route('client.portal'),
                'active' => request()->routeIs('client.portal', 'client.decks.*'),
            ],
            [
                'label' => 'Study',
                'href' => route('client.study.all', ['mode' => 'flip']),
                'active' => request()->routeIs('client.study.all', 'client.decks.study'),
            ],
            [
                'label' => 'Profile',
                'href' => route('client.profile'),
                'active' => request()->routeIs('client.profile', 'client.profile.update'),
            ],
            // [
            //     'label' => 'Support',
            //     'href' => route('client.support'),
            //     'active' => request()->routeIs('client.support'),
            // ],
            // [
            //     'label' => 'Documentation',
            //     'href' => route('client.documentation'),
            //     'active' => request()->routeIs('client.documentation'),
            // ],

        ];
    @endphp
    <body class="app-shell font-sans antialiased">
        <header class="border-b border-stone-200 bg-white/80 backdrop-blur-md sticky top-0 z-40 shadow-sm">
            <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                <div x-data="{ mobileMenuOpen: false }">
                    <div class="flex justify-between h-16 sm:h-20 items-center gap-4">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center" aria-label="Flashcard Learning Hub dashboard">
                                <picture>
                                    <source srcset="{{ asset('assets/client-logo-dark.svg') }}" media="(prefers-color-scheme: dark)">
                                    <img src="{{ asset('assets/client-logo-light.svg') }}" alt="Flashcard Learning Hub" class="h-10 sm:h-12 w-auto">
                                </picture>
                            </a>
                        </div>
                        <!-- Desktop Nav -->
                        <div class="hidden lg:flex lg:items-center lg:space-x-8 lg:flex-1 lg:ml-8">
                            <nav class="flex space-x-8 h-full" aria-label="Client navigation">
                                @foreach ($navigationItems as $item)
                                    <a
                                        href="{{ $item['href'] }}"
                                        class="px-1 py-5 sm:py-7 text-sm font-medium transition-colors border-b-2 {{ $item['active'] ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-stone-600 hover:text-indigo-600 hover:border-indigo-300' }}"
                                        @if ($item['active']) aria-current="page" @endif
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                        <!-- Desktop User/Actions -->
                        <div class="hidden lg:flex lg:items-center lg:gap-6">
                            @if ($clientUser)
                                <div class="flex items-center gap-3 text-right">
                                    <div>
                                        <p class="text-sm font-semibold text-stone-900 leading-none">{{ $clientUser->name }}</p>
                                        <p class="text-xs uppercase tracking-wider text-indigo-600 font-semibold mt-1">Lvl {{ $clientUser->level() }} <span class="text-stone-400 font-normal">·</span> XP {{ number_format($clientUser->experience_points) }}</p>
                                    </div>
                                    <span class="inline-flex items-center justify-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 ring-1 ring-inset ring-indigo-300 aspect-square h-8 w-8 uppercase">
                                        {{ substr(ucfirst($clientUser->role), 0, 1) }}
                                    </span>
                                </div>
                                <div class="w-px h-8 bg-stone-200"></div>
                                <form action="{{ route('client.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-stone-500 hover:text-rose-600 transition-colors">Log out</button>
                                </form>
                            @else
                                <a href="{{ route('client.login') }}" class="text-sm font-medium text-stone-600 hover:text-indigo-600">Client login</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">Create account</a>
                            @endif
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center lg:hidden">
                            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-stone-500 hover:text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                                <span class="sr-only">Open main menu</span>
                                <!-- Icon when menu is closed -->
                                <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                <!-- Icon when menu is open -->
                                <svg x-show="mobileMenuOpen" class="hidden h-6 w-6" :class="{'hidden': !mobileMenuOpen, 'block': mobileMenuOpen}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Menu Panel -->
                    <div x-show="mobileMenuOpen" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        @click.away="mobileMenuOpen = false"
                        class="absolute top-16 sm:top-20 inset-x-0 bg-white shadow-lg lg:hidden z-50 border-t border-stone-200 origin-top" 
                        style="display: none; left: 0; right: 0;">
                        <div class="pt-2 pb-3 space-y-1 px-4 sm:px-6">
                            @foreach ($navigationItems as $item)
                                <a href="{{ $item['href'] }}"
                                class="block pl-3 pr-4 py-3 border-l-4 text-base font-medium transition-colors {{ $item['active'] ? 'bg-indigo-50 border-indigo-600 text-indigo-700' : 'border-transparent text-stone-600 hover:bg-stone-50 hover:border-stone-300 hover:text-stone-800' }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                        <div class="pt-4 pb-5 border-t border-stone-200 px-4 sm:px-6">
                            @if ($clientUser)
                                <div class="flex items-center px-3 mb-5 gap-4">
                                    <span class="inline-flex items-center justify-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-800 ring-1 ring-inset ring-indigo-300 aspect-square h-10 w-10 uppercase">
                                        {{ substr(ucfirst($clientUser->role), 0, 1) }}
                                    </span>
                                    <div>
                                        <div class="text-base font-medium text-stone-900">{{ $clientUser->name }}</div>
                                        <div class="text-sm font-medium text-indigo-600 mt-1">Level {{ $clientUser->level() }} <span class="text-stone-400">·</span> XP {{ number_format($clientUser->experience_points) }}</div>
                                    </div>
                                </div>
                                <div class="mt-3 space-y-1">
                                    <form action="{{ route('client.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-md transition-colors">Log out</button>
                                    </form>
                                </div>
                            @else
                                <div class="space-y-3 px-3 flex flex-col pt-1">
                                    <a href="{{ route('client.login') }}" class="block text-center px-4 py-2 border border-stone-300 shadow-sm text-base font-medium rounded-md text-stone-700 bg-white hover:bg-stone-50 transition-colors">Log in</a>
                                    <a href="{{ route('register') }}" class="block text-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">Create account</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="classic-frame py-8 space-y-6">
            @if (session('status'))
                <div class="border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>

        <x-footer :showNewsletter="false" :compact="true" />
    </body>
</html>
