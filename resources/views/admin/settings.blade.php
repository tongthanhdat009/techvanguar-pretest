@extends('layouts.admin', [
    'title' => 'Cài đặt',
    'sidebar' => true,
    'header' => [
        'title' => 'Cài đặt hệ thống',
        'subtitle' => 'Tùy chỉnh giao diện và ngôn ngữ',
    ],
])

@section('content')
<div class="max-w-2xl space-y-6" x-data="{ theme: localStorage.getItem('theme') || 'dark', language: localStorage.getItem('language') || 'vi' }" x-init="$watch('theme', val => localStorage.setItem('theme', val)); $watch('language', val => localStorage.setItem('language', val))">

    {{-- Appearance --}}
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-medium">Giao diện</h3>
                <p class="text-sm text-slate-400">Chọn chế độ hiển thị phù hợp với bạn</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <button
                @click="theme = 'light'; setTheme('light')"
                class="relative flex flex-col items-center gap-3 p-5 rounded-xl border-2 transition-all"
                :class="theme === 'light' ? 'border-indigo-500 bg-indigo-500/10' : 'border-slate-700 hover:border-slate-600'"
            >
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="font-medium" :class="theme === 'light' ? 'text-white' : 'text-slate-400'">Sáng</p>
                    <p class="text-xs text-slate-500">Light mode</p>
                </div>
                <div x-show="theme === 'light'" class="absolute top-3 right-3">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </button>

            <button
                @click="theme = 'dark'; setTheme('dark')"
                class="relative flex flex-col items-center gap-3 p-5 rounded-xl border-2 transition-all"
                :class="theme === 'dark' ? 'border-indigo-500 bg-indigo-500/10' : 'border-slate-700 hover:border-slate-600'"
            >
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="font-medium" :class="theme === 'dark' ? 'text-white' : 'text-slate-400'">Tối</p>
                    <p class="text-xs text-slate-500">Dark mode</p>
                </div>
                <div x-show="theme === 'dark'" class="absolute top-3 right-3">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </button>
        </div>
    </div>

    {{-- Language --}}
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-medium">Ngôn ngữ</h3>
                <p class="text-sm text-slate-400">Chọn ngôn ngữ hiển thị</p>
            </div>
        </div>

        <div class="space-y-3">
            <button
                @click="language = 'vi'; setLanguage('vi')"
                class="w-full flex items-center justify-between p-4 rounded-xl border-2 transition-all"
                :class="language === 'vi' ? 'border-indigo-500 bg-indigo-500/10' : 'border-slate-700 hover:border-slate-600'"
            >
                <div class="flex items-center gap-4">
                    <span class="text-3xl">🇻🇳</span>
                    <div class="text-left">
                        <p class="font-medium" :class="language === 'vi' ? 'text-white' : 'text-slate-400'">Tiếng Việt</p>
                        <p class="text-sm text-slate-500">Vietnamese</p>
                    </div>
                </div>
                <svg x-show="language === 'vi'" class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <button
                @click="language = 'en'; setLanguage('en')"
                class="w-full flex items-center justify-between p-4 rounded-xl border-2 transition-all"
                :class="language === 'en' ? 'border-indigo-500 bg-indigo-500/10' : 'border-slate-700 hover:border-slate-600'"
            >
                <div class="flex items-center gap-4">
                    <span class="text-3xl">🇺🇸</span>
                    <div class="text-left">
                        <p class="font-medium" :class="language === 'en' ? 'text-white' : 'text-slate-400'">English</p>
                        <p class="text-sm text-slate-500">English (US)</p>
                    </div>
                </div>
                <svg x-show="language === 'en'" class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Preferences --}}
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-medium">Tùy chọn khác</h3>
                <p class="text-sm text-slate-400">Các cài đặt bổ sung</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-slate-700/50">
                <div>
                    <p class="text-slate-300 font-medium">Thông báo</p>
                    <p class="text-sm text-slate-500">Nhận thông báo về hoạt động hệ thống</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="text-slate-300 font-medium">Tự động lưu</p>
                    <p class="text-sm text-slate-500">Tự động lưu các thay đổi</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="bg-slate-800/30 border border-slate-700/50 rounded-2xl p-4">
        <p class="text-sm text-slate-500 text-center">
            Các cài đặt được lưu tự động vào local storage
        </p>
    </div>
</div>
@endsection
