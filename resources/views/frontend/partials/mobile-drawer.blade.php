@php
    $topCategories = $categories->whereNull('parent_id');
@endphp

{{-- Mobile Drawer (slide-out from left) --}}
<template x-teleport="body">
    {{-- Overlay --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="mobileOpen = false"
        x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : ''"
        class="mobile-drawer-overlay"
        style="display: none;"
        aria-hidden="true">
    </div>
</template>

<template x-teleport="body">
    {{-- Panel --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition-transform duration-300 ease-out"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="mobile-drawer-panel"
        style="display: none;"
        role="dialog"
        aria-label="Navigation menu">

        {{-- Drawer Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-secondary-100 dark:border-secondary-700">
            <x-brand-logo compact :show-text="true" />
            <button x-on:click="mobileOpen = false"
                class="p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-500 dark:text-secondary-400"
                aria-label="Close menu">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-5 py-4 border-b border-secondary-100 dark:border-secondary-700">
            <form action="{{ route('frontend.shop') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-secondary-400 dark:text-secondary-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                </svg>
                <input type="search" name="search" placeholder="Search products..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-secondary-200 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-900 text-sm text-secondary-900 dark:text-white placeholder:text-secondary-400 dark:placeholder:text-secondary-500 focus:border-primary-500 dark:focus:border-primary-400 focus:bg-white dark:focus:bg-secondary-800 transition-all"
                    aria-label="Search products">
            </form>
        </div>

        {{-- Nav Links --}}
        <nav class="px-3 py-3 border-b border-secondary-100 dark:border-secondary-700" aria-label="Mobile navigation">
            @foreach($mobileNavItems as $navItem)
                @php
                    $navPath = parse_url($navItem['url'] ?? '', PHP_URL_PATH) ?: '/';
                    $currentPath = parse_url(url()->current(), PHP_URL_PATH) ?: '/';
                    $isNavActive = $navPath === '/'
                        ? $currentPath === '/'
                        : str_starts_with($currentPath, $navPath);
                @endphp
                <a href="{{ $navItem['url'] }}" wire:navigate x-on:click="mobileOpen = false"
                    class="drawer-link {{ $isNavActive ? 'is-active' : '' }} {{ $navItem['css_class'] ?? '' }}">
                    @if($navItem['icon_key'])
                        <span class="h-5 w-5 shrink-0">{!! \App\Helpers\MenuHelper::getIconSvg($navItem['icon_key']) !!}</span>
                    @endif
                    <span class="min-w-0 flex-1 truncate">{{ $navItem['name'] }}</span>
                    @if(($navItem['icon_key'] ?? '') === 'cart')
                        <span x-show="$store.cart.count() > 0" x-text="$store.cart.count()" x-transition
                            class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-primary-500 text-white text-[10px] font-bold"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- Categories: Recursive Accordion --}}
        <div class="px-3 py-3 border-b border-secondary-100 dark:border-secondary-700">
            <button x-on:click="mobileCategoriesOpen = !mobileCategoriesOpen"
                :aria-expanded="mobileCategoriesOpen"
                class="drawer-link w-full">
                <span class="flex items-center justify-between flex-1 min-w-0">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-3.75-2.25v-2.25Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/>
                        </svg>
                        Categories
                    </span>
                    <svg class="h-4 w-4 text-secondary-400 transition-transform duration-200 shrink-0" :class="mobileCategoriesOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </span>
            </button>
            <div x-show="mobileCategoriesOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="mt-1 ml-6 space-y-0.5">
                @forelse($topCategories as $cat)
                    @php $activeCategory = null; @endphp
                    @if(request()->routeIs('frontend.category'))
                        @php $activeCategory = $categories->firstWhere('slug', request()->route('slug')); @endphp
                    @endif
                    @include('frontend.partials.category-tree', [
                        'categories' => collect([$cat]),
                        'level' => 0,
                        'context' => 'mobile',
                        'activeCategory' => $activeCategory,
                    ])
                @empty
                    <p class="px-3 py-2 text-sm text-secondary-400 dark:text-secondary-500">No categories</p>
                @endforelse
            </div>
        </div>

        {{-- Account --}}
        <div class="px-3 py-3 border-b border-secondary-100 dark:border-secondary-700">
            @auth
                <a href="{{ route('profile.edit') }}" wire:navigate x-on:click="mobileOpen = false"
                    class="group relative flex items-center gap-3 p-3 mb-2 rounded-2xl bg-gradient-to-br from-secondary-50 to-white dark:from-white/[0.06] dark:to-transparent border border-secondary-200/80 dark:border-white/10 hover:border-primary-300 dark:hover:border-primary-500/40 transition-all shadow-sm">
                    <span class="ring-2 ring-primary-500/30 group-hover:ring-primary-500/60 rounded-full transition-all">
                        <x-user-avatar :user="auth()->user()" size="h-10 w-10" text-size="text-sm" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-semibold text-secondary-900 dark:text-white">{{ auth()->user()->name }}</span>
                        <span class="block truncate text-xs text-secondary-500 dark:text-secondary-400">{{ auth()->user()->email }}</span>
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-secondary-400 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>

                @if (auth()->user()->role === 'customer')
                    <a href="{{ route('customer.orders.index') }}" wire:navigate x-on:click="mobileOpen = false"
                        class="drawer-link">
                        <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                        </svg>
                        My Orders
                    </a>
                @endif
                <a href="{{ route('frontend.favorites') }}" wire:navigate x-on:click="mobileOpen = false"
                    class="drawer-link">
                    <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                    My Favorites
                </a>
                <div class="mx-3 my-2 border-t border-secondary-100 dark:border-secondary-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" x-on:click="mobileOpen = false"
                        class="drawer-link w-full text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" x-on:click="mobileOpen = false"
                    class="drawer-link">
                    <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    Sign In
                </a>
                <a href="{{ route('register') }}" x-on:click="mobileOpen = false"
                    class="flex items-center justify-center gap-2 mt-2 min-h-[44px] px-4 py-2.5 rounded-btn bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600 active:scale-[0.98] transition-all">
                    Create Account
                </a>
            @endauth
        </div>

        {{-- Preferences --}}
        <div class="px-3 py-3">
            <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-secondary-400 dark:text-secondary-500">{{ __('Preferences') }}</p>
            <button x-on:click="$store.theme.toggle(); mobileOpen = false"
                class="drawer-link w-full">
                <svg x-show="$store.theme.current === 'light'" class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
                <svg x-show="$store.theme.current === 'dark'" class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                </svg>
                <span class="flex-1 text-left" x-text="$store.theme.current === 'light' ? 'Dark Mode' : 'Light Mode'"></span>
            </button>

            <div class="flex items-center gap-2 px-3 min-h-[44px]">
                <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                </svg>
                <div class="flex gap-1">
                    <button x-on:click="$store.i18n.setLocale('en')"
                        class="min-h-[32px] px-3 rounded-lg text-xs font-semibold transition-colors"
                        :class="$store.i18n.locale === 'en' ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'">EN</button>
                    <button x-on:click="$store.i18n.setLocale('ja')"
                        class="min-h-[32px] px-3 rounded-lg text-xs font-semibold transition-colors"
                        :class="$store.i18n.locale === 'ja' ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'">JA</button>
                    <button x-on:click="$store.i18n.setLocale('ne')"
                        class="min-h-[32px] px-3 rounded-lg text-xs font-semibold transition-colors"
                        :class="$store.i18n.locale === 'ne' ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'">NE</button>
                </div>
            </div>

            <div class="flex items-center gap-2 px-3 min-h-[44px]">
                <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <div class="flex flex-wrap gap-1" x-data>
                    <template x-for="(config, code) in window.currencyConfig" :key="code">
                        <button x-on:click="$store.currency.set(code)"
                            class="min-h-[32px] px-3 rounded-lg text-xs font-semibold transition-colors"
                            :class="$store.currency.code === code ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'"
                            x-text="config.symbol + code"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-4 border-t border-secondary-100 dark:border-secondary-700">
            <p class="text-xs text-secondary-400 dark:text-secondary-500 text-center">&copy; {{ date('Y') }} {{ site_name() }}. All rights reserved.</p>
        </div>
    </div>
</template>
