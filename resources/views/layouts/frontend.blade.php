<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#e89b2d">
    <title>@yield('title', config('app.name', 'NBK Vertex'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" as="style">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <script>
        window.i18nTranslations = {
            'en': {!! file_get_contents(lang_path('en.json')) !!},
            'ja': {!! file_get_contents(lang_path('ja.json')) !!},
            'ne': {!! file_get_contents(lang_path('ne.json')) !!}
        };
        window.defaultLocale = '{{ config("app.locale", "en") }}';
    </script>

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
        function bannerCountdown(endDate, autoHide) {
            autoHide = autoHide !== undefined ? autoHide : true;
            return {
                show: !autoHide, days: 0, hours: 0, minutes: 0, seconds: 0, expired: false, timer: null,
                init() {
                    if (!endDate) return;
                    this.tick();
                    this.timer = setInterval(() => this.tick(), 1000);
                },
                tick() {
                    if (!endDate) return;
                    const diff = new Date(endDate) - new Date();
                    if (diff <= 0) {
                        this.expired = true;
                        this.show = !autoHide;
                        clearInterval(this.timer);
                        return;
                    }
                    this.days = Math.floor(diff / 86400000);
                    this.hours = Math.floor((diff % 86400000) / 3600000);
                    this.minutes = Math.floor((diff % 3600000) / 60000);
                    this.seconds = Math.floor((diff % 60000) / 1000);
                    if (autoHide) this.show = true;
                }
            };
        }
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

            Alpine.store('confirmModal', {
                show: false,
                title: '',
                message: '',
                confirmText: 'Confirm',
                confirmClass: 'bg-red-600 hover:bg-red-700',
                loading: false,
                form: null,
                onConfirm: null,
                open(opts) {
                    this.title = opts.title || 'Confirm Action';
                    this.message = opts.message || 'Are you sure?';
                    this.confirmText = opts.confirmText || 'Confirm';
                    this.confirmClass = opts.confirmClass || 'bg-red-600 hover:bg-red-700';
                    this.form = opts.form || null;
                    this.onConfirm = opts.onConfirm || null;
                    this.loading = false;
                    this.show = true;
                },
                async confirm() {
                    this.loading = true;
                    try {
                        if (this.onConfirm) {
                            await this.onConfirm();
                        } else if (this.form) {
                            this.form.submit();
                        }
                    } finally {
                        this.loading = false;
                        this.show = false;
                    }
                },
                cancel() {
                    if (this.loading) return;
                    this.show = false;
                    this.onConfirm = null;
                    this.form = null;
                }
            });
        });
    </script>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-clip">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[9999] focus:bg-primary-500 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:font-semibold">Skip to content</a>
    @include('frontend.partials.announcement-bar')
    @include('frontend.partials.header')

    <main id="main-content" class="flex-grow">
        @yield('content')
    </main>

    <x-toast position="top-right" top-offset="calc(var(--navbar-height, 0px) + 1rem)" />
    <x-confirm-modal />

    @hasSection('footer')
        @yield('footer')
    @else
        @include('frontend.partials.footer')
    @endif
    @include('frontend.partials.scripts')
    @livewireScriptConfig
    @stack('scripts')
</body>
</html>
