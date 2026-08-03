<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NBK Vertex') }}</title>

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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-8">
                <a href="/">
                    <x-brand-logo subtitle="Commerce Suite" />
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-secondary-900 shadow-card rounded-card">
                {{ $slot }}
            </div>

            <p class="mt-6 text-sm text-secondary-500 dark:text-secondary-400">
                <a href="/" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">&larr; Back to shop</a>
            </p>
        </div>
    </body>
    @livewireScriptConfig
</html>
