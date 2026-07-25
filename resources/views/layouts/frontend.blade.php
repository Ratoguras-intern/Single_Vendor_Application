<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'NBK Vertex'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Embedded translations for client-side i18n (no API required) -->
    <script>
        window.i18nTranslations = {
            'en': {!! file_get_contents(lang_path('en.json')) !!},
            'ja': {!! file_get_contents(lang_path('ja.json')) !!},
            'ne': {!! file_get_contents(lang_path('ne.json')) !!}
        };
        window.defaultLocale = '{{ config("app.locale", "en") }}';
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.current = savedTheme || systemTheme;
                    this.apply();
                },
                current: 'light',
                toggle() {
                    this.current = this.current === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.current);
                    this.apply();
                },
                apply() {
                    const html = document.documentElement;
                    if (this.current === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                }
            });
        });
    </script>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen transition-colors duration-300" style="background-color: var(--bloom-background); color: var(--bloom-foreground);">
    @include('frontend.partials.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Toast Container --}}
    <div x-data="toastManager()" x-on:toast.window="add($event.detail)" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none">
        <template x-for="t in items" :key="t.id">
            <div x-show="t.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8" class="pointer-events-auto max-w-sm w-full rounded-lg border shadow-lg px-4 py-3 flex items-center gap-3" :style="t.type === 'error' ? 'border-color: #fca5a5; background-color: #fef2f2; color: #991b1b;' : t.type === 'warning' ? 'border-color: #fcd34d; background-color: #fffbeb; color: #92400e;' : t.type === 'info' ? 'border-color: #93c5fd; background-color: #eff6ff; color: #1e40af;' : 'border-color: #86efac; background-color: #f0fdf4; color: #166534;'">
                <template x-if="t.type === 'success'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </template>
                <template x-if="t.type === 'error'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </template>
                <template x-if="t.type === 'warning'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                </template>
                <template x-if="t.type === 'info'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                </template>
                <span x-text="t.message" class="text-sm font-medium flex-1"></span>
                <button x-on:click="dismiss(t.id)" class="shrink-0 opacity-60 hover:opacity-100">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    @include('frontend.partials.footer')
    @include('frontend.partials.scripts')
    @stack('scripts')
</body>
</html>
