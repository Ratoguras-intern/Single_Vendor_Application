<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ site_name() }}</title>
        @include('partials.favicon')

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js'])
        @livewireStyles

        <script>
            (function() {
                const saved = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('theme', {
                    init() {
                        const saved = localStorage.getItem('theme');
                        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        this.current = saved || (prefersDark ? 'dark' : 'light');
                        this.apply();
                    },
                    current: 'light',
                    toggle() {
                        this.current = this.current === 'light' ? 'dark' : 'light';
                        localStorage.setItem('theme', this.current);
                        this.apply();
                    },
                    apply() {
                        document.documentElement.classList.toggle('dark', this.current === 'dark');
                    }
                });
            });
        </script>
    </head>
    <body class="font-sans text-secondary-900 dark:text-secondary-100 antialiased bg-secondary-50 dark:bg-secondary-950">
        <div class="min-h-screen">
            <nav class="bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-700 shadow-header" x-data="{ open: false }">
                <div class="section">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="/" class="shrink-0">
                                <x-brand-logo subtitle="Commerce Suite" />
                            </a>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-secondary-600 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-white/5 rounded-btn transition-colors">
                                        <div class="h-7 w-7 rounded-full bg-primary-500 flex items-center justify-center text-xs font-bold text-white">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div>{{ Auth::user()->name }}</div>
                                        <svg class="h-4 w-4 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-full text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                    </div>

                    <div class="pt-4 pb-1 border-t border-secondary-200 dark:border-secondary-700">
                        <div class="px-4">
                            <div class="font-medium text-base text-secondary-900 dark:text-white">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-secondary-500 dark:text-secondary-400">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            @isset($header)
                <header class="bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-700">
                    <div class="section py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
    @livewireScriptConfig
</html>
