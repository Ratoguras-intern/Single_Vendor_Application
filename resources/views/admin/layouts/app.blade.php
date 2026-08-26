<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbo-cache-control" content="no-preview">

    <title>{{ $title ?? 'Dashboard' }} | {{ site_name() }}</title>
    @include('partials.favicon')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/admin.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <!-- Sortable.js -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <!-- Alpine.js -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: false,
                isMobileOpen: false,
                isHovered: false,

                _saveScroll() {
                    const el = document.getElementById('admin-content-area');
                    return el ? el.scrollTop : 0;
                },

                _restoreScroll(top) {
                    const el = document.getElementById('admin-content-area');
                    if (el) {
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                el.scrollTop = top;
                            });
                        });
                    }
                },

                toggleExpanded() {
                    const top = this._saveScroll();
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                    this._restoreScroll(top);
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        const top = this._saveScroll();
                        this.isHovered = val;
                        this._restoreScroll(top);
                    }
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

            Alpine.store('promptModal', {
                show: false,
                title: '',
                message: '',
                value: '',
                loading: false,
                _resolve: null,
                open(opts) {
                    this.title = opts.title || 'Input Required';
                    this.message = opts.message || '';
                    this.value = opts.defaultValue || '';
                    this.loading = false;
                    this.show = true;
                    return new Promise(resolve => { this._resolve = resolve; });
                },
                confirm() {
                    if (this._resolve) this._resolve(this.value);
                    this.show = false;
                    this._resolve = null;
                },
                cancel() {
                    if (this._resolve) this._resolve(null);
                    this.show = false;
                    this._resolve = null;
                }
            });

            Alpine.data('adminSearch', () => ({
                query: '',
                open: false,
                loading: false,
                resultsHtml: '',
                async fetchResults() {
                    if (this.query.length < 2) {
                        this.resultsHtml = '';
                        return;
                    }
                    this.loading = true;
                    try {
                        const resp = await fetch('{{ route("admin.search") }}?q=' + encodeURIComponent(this.query), {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await resp.json();
                        this.resultsHtml = data.html || '';
                    } catch (e) {
                        this.resultsHtml = '';
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>

    <script>
        window.showPrompt = function(title, defaultValue) {
            return Alpine.store('promptModal').open({ title: title, defaultValue: defaultValue });
        };
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('theme');
            var systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            var theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
</head>

<body
    class="bg-white dark:bg-gray-900"
    x-data="{ 'loaded': true}"
    x-init="window.__initAdminSidebar?.()">

    {{-- preloader --}}
    @include('common.preloader')
    {{-- preloader end --}}

    <div class="xl:flex h-screen overflow-hidden">
        @include('admin.layouts.partials.backdrop')
        @include('admin.layouts.sidebar')

        <div id="admin-content-area" class="flex-1 h-screen overflow-y-auto transition-[margin-left] duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('admin.layouts.header')
            <!-- app header end -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @include('admin.layouts.partials.breadcrumbs')
                @yield('content')
            </div>
        </div>

    </div>

    <x-toast />
    <x-confirm-modal />
    <x-prompt-modal />
    <x-media-picker.modal />

    <script>
        (function() {
            var timer;
            function liveFilter(form) {
                var searchInput = document.querySelector('#search-results input[name="search"]') || document.querySelector('input[name="search"]');
                var searchVal = searchInput ? searchInput.value : '';
                var selStart = searchInput ? searchInput.selectionStart : null;
                var selEnd = searchInput ? searchInput.selectionEnd : null;
                var params = new URLSearchParams(new FormData(form));
                var url = form.action + '?' + params.toString();
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.text(); })
                    .then(function(html) {
                        var doc = new DOMParser().parseFromString(html, 'text/html');
                        var results = doc.getElementById('search-results');
                        if (results) {
                            var container = document.getElementById('search-results');
                            if (container) container.innerHTML = results.innerHTML;
                        }
                        history.replaceState(null, '', url);
                        var searchInput = document.querySelector('#search-results input[name="search"]') || document.querySelector('input[name="search"]');
                        if (searchInput) {
                            searchInput.value = searchVal;
                            searchInput.focus();
                            if (selStart !== null) {
                                try { searchInput.setSelectionRange(selStart, selEnd); } catch(e) {}
                            }
                        }
                    });
            }
            document.addEventListener('input', function(e) {
                if (e.target.name === 'search') {
                    clearTimeout(timer);
                    var form = e.target.closest('form');
                    if (form) timer = setTimeout(function() { liveFilter(form); }, 400);
                }
            });
            document.addEventListener('change', function(e) {
                if (e.target.tagName === 'SELECT') {
                    var form = e.target.closest('form');
                    if (form) { clearTimeout(timer); liveFilter(form); }
                }
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
