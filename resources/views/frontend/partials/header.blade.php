<header x-data="{ isScrolled: false, isMobileOpen: false, isSearchOpen: false, isUserMenuOpen: false }" x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 10 })" x-on:click.outside="isMobileOpen = false; isUserMenuOpen = false"
    :class="isScrolled ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700 shadow-lg' :
        'bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700/80 shadow-sm'"
    class="sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8 lg:space-x-12">
                <x-brand-logo subtitle="Commerce" />

                <nav class="hidden md:flex items-center space-x-1" role="navigation" aria-label="Main navigation">
                    <a href="{{ route('frontend.shop') }}"
                        class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('frontend.shop') ? 'shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}"
                        style="{{ request()->routeIs('frontend.shop') ? 'background-color: #fef3cd; color: var(--bloom-foreground);' : '' }}"
                        {{ request()->routeIs('frontend.shop') ? 'aria-current="page"' : '' }}>
                        <span data-i18n="Shop" x-text="$store.i18n.t('Shop')">{{ __('Shop') }}</span>
                    </a>
                    <a href="{{ route('frontend.contact') }}"
                        class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('frontend.contact') ? 'shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}"
                        style="{{ request()->routeIs('frontend.contact') ? 'background-color: #fef3cd; color: var(--bloom-foreground);' : '' }}"
                        {{ request()->routeIs('frontend.contact') ? 'aria-current="page"' : '' }}>
                        <span data-i18n="Contact" x-text="$store.i18n.t('Contact')">{{ __('Contact') }}</span>
                    </a>
                    <a href="{{ route('frontend.about') }}"
                        class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('frontend.about') ? 'shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}"
                        style="{{ request()->routeIs('frontend.about') ? 'background-color: #fef3cd; color: var(--bloom-foreground);' : '' }}"
                        {{ request()->routeIs('frontend.about') ? 'aria-current="page"' : '' }}>
                        <span data-i18n="About" x-text="$store.i18n.t('About')">{{ __('About') }}</span>
                    </a>
                </nav>
            </div>

            <div class="hidden lg:flex flex-1 max-w-md mx-8">
                <form class="relative w-full" action="{{ route('frontend.shop') }}" method="GET">
                    <input type="search" name="search" placeholder="{{ __('Search products...') }}"
                        data-i18n-placeholder="Search products..."
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-brand-400 focus:border-transparent transition-all"
                        aria-label="Search products" />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                    </svg>
                </form>
            </div>

            <div class="flex items-center space-x-1 sm:space-x-2">
                <button x-on:click="isSearchOpen = !isSearchOpen"
                    class="lg:hidden p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-700 dark:text-gray-300" aria-label="Search">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                    </svg>
                </button>

                <button x-on:click="$store.theme.toggle()"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-700 dark:text-gray-300"
                    aria-label="Toggle theme">
                    <template x-if="$store.theme.current === 'light'">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    </template>
                    <template x-if="$store.theme.current === 'dark'">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    </template>
                </button>

                <div class="relative" x-data="{ currencyOpen: false }">
                    <button x-on:click="currencyOpen = !currencyOpen" @keydown.escape.window="currencyOpen = false"
                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-700 dark:text-gray-300"
                        aria-label="Select currency">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </button>
                    <div x-show="currencyOpen" @click.away="currencyOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-2 w-40 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-1 shadow-lg"
                        style="display: none;">
                        <template x-for="(config, code) in window.currencyConfig" :key="code">
                            <button x-on:click="$store.currency.set(code); currencyOpen = false"
                                class="w-full text-left px-4 py-2 text-sm transition-colors"
                                :class="$store.currency.code === code ? 'font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                :style="$store.currency.code === code ? 'color: var(--bloom-primary);' : ''">
                                <span x-text="config.symbol + ' ' + code"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="relative" x-data="{ langOpen: false }">
                    <button x-on:click="langOpen = !langOpen" @keydown.escape.window="langOpen = false"
                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-700 dark:text-gray-300"
                        aria-label="Select language">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    </button>
                    <div x-show="langOpen" @click.away="langOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-2 w-36 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-1 shadow-lg"
                        style="display: none;">
                        <button x-on:click="$store.i18n.setLocale('en'); langOpen = false"
                            class="w-full text-left px-4 py-2 text-sm transition-colors"
                            :class="$store.i18n.locale === 'en' ? 'font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            :style="$store.i18n.locale === 'en' ? 'color: var(--bloom-primary);' : ''">
                            English
                        </button>
                        <button x-on:click="$store.i18n.setLocale('ja'); langOpen = false"
                            class="w-full text-left px-4 py-2 text-sm transition-colors"
                            :class="$store.i18n.locale === 'ja' ? 'font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            :style="$store.i18n.locale === 'ja' ? 'color: var(--bloom-primary);' : ''">
                            日本語
                        </button>
                        <button x-on:click="$store.i18n.setLocale('ne'); langOpen = false"
                            class="w-full text-left px-4 py-2 text-sm transition-colors"
                            :class="$store.i18n.locale === 'ne' ? 'font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            :style="$store.i18n.locale === 'ne' ? 'color: var(--bloom-primary);' : ''">
                            नेपाली
                        </button>
                    </div>
                </div>

                <a href="{{ route('frontend.favorites') }}"
                    class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 group text-gray-700 dark:text-gray-300"
                    aria-label="Favorites">
                    <svg class="h-6 w-6 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <span x-show="$store.wishlist.items.length > 0"
                        x-text="$store.wishlist.items.length > 99 ? '99+' : $store.wishlist.items.length" x-transition
                        class="absolute -top-1 -right-1 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1"
                        style="background-color: var(--bloom-primary);"></span>
                </a>

                <a href="{{ route('frontend.cart') }}"
                    class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 group text-gray-700 dark:text-gray-300"
                    aria-label="Shopping cart">
                    <svg class="h-6 w-6 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    <span x-show="$store.cart.count() > 0"
                        x-text="$store.cart.count() > 99 ? '99+' : $store.cart.count()" x-transition
                        class="absolute -top-1 -right-1 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1"
                        style="background-color: var(--bloom-primary);"></span>
                </a>

                <div class="hidden sm:flex items-center space-x-2">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button x-on:click="open = !open" @keydown.escape.window="open = false"
                                class="inline-flex items-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 px-3 hover:bg-gray-100 dark:hover:bg-gray-800">
                                <div class="h-7 w-7 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                    style="background-color: var(--bloom-primary);">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-xs text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                                <svg class="h-3 w-3 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 z-50 mt-2 w-48 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-1 shadow-lg"
                                style="display: none;">
                                @if (auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.orders.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span data-i18n="My Orders" x-text="$store.i18n.t('My Orders')">{{ __('My Orders') }}</span></a>
                                @endif
                                <a href="{{ route('frontend.favorites') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</span></a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span data-i18n="Admin Panel" x-text="$store.i18n.t('Admin Panel')">{{ __('Admin Panel') }}</span></a>
                                @endif
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span data-i18n="Profile" x-text="$store.i18n.t('Profile')">{{ __('Profile') }}</span></a>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span data-i18n="Sign Out" x-text="$store.i18n.t('Sign Out')">{{ __('Sign Out') }}</span></button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 px-3 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                            <span data-i18n="Sign In" x-text="$store.i18n.t('Sign In')">{{ __('Sign In') }}</span>
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 px-3 text-xs text-black shadow hover:opacity-90"
                            style="background-color: var(--bloom-primary);">
                            <span data-i18n="Sign Up" x-text="$store.i18n.t('Sign Up')">{{ __('Sign Up') }}</span>
                        </a>
                    @endauth
                </div>

                <button x-on:click="isMobileOpen = !isMobileOpen"
                    class="md:hidden p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-700 dark:text-gray-300"
                    aria-label="Toggle navigation menu" :aria-expanded="isMobileOpen">
                    <template x-if="!isMobileOpen">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </template>
                    <template x-if="isMobileOpen">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </template>
                </button>
            </div>
        </div>

        <div x-show="isSearchOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden mt-4" style="display: none;">
            <form class="relative" action="{{ route('frontend.shop') }}" method="GET">
                <input type="search" name="search" placeholder="Search products..."
                    value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-brand-400 focus:border-transparent"
                    aria-label="Search products" autofocus />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                </svg>
            </form>
        </div>

        <div x-show="isMobileOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden mt-4" style="display: none;">
            <div class="flex flex-col space-y-1 pb-4 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('frontend.shop') }}"
                    class="text-sm font-medium py-2 px-3 rounded-lg transition-all {{ request()->routeIs('frontend.shop') ? '' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                    style="{{ request()->routeIs('frontend.shop') ? 'background-color: #fef3cd;' : '' }}"
                    {{ request()->routeIs('frontend.shop') ? 'aria-current="page"' : '' }}>
                    <span data-i18n="Shop" x-text="$store.i18n.t('Shop')">{{ __('Shop') }}</span>
                </a>
                <a href="{{ route('frontend.contact') }}"
                    class="text-sm font-medium py-2 px-3 rounded-lg transition-all {{ request()->routeIs('frontend.contact') ? '' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                    style="{{ request()->routeIs('frontend.contact') ? 'background-color: #fef3cd;' : '' }}"
                    {{ request()->routeIs('frontend.contact') ? 'aria-current="page"' : '' }}>
                    <span data-i18n="Contact" x-text="$store.i18n.t('Contact')">{{ __('Contact') }}</span>
                </a>
                <a href="{{ route('frontend.about') }}"
                    class="text-sm font-medium py-2 px-3 rounded-lg transition-all {{ request()->routeIs('frontend.about') ? '' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                    style="{{ request()->routeIs('frontend.about') ? 'background-color: #fef3cd;' : '' }}"
                    {{ request()->routeIs('frontend.about') ? 'aria-current="page"' : '' }}>
                    <span data-i18n="About" x-text="$store.i18n.t('About')">{{ __('About') }}</span>
                </a>
            </div>

            <div class="flex flex-col space-y-2 pt-4 sm:hidden">
                @auth
                    @if (auth()->user()->role === 'customer')
                        <a href="{{ route('customer.orders.index') }}"
                            class="w-full text-sm text-center inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 h-8 px-3 text-gray-700 dark:text-gray-300">
                            <span data-i18n="My Orders" x-text="$store.i18n.t('My Orders')">{{ __('My Orders') }}</span>
                        </a>
                    @endif
                    <a href="{{ route('frontend.favorites') }}"
                        class="w-full text-sm text-center inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 h-8 px-3 text-gray-700 dark:text-gray-300">
                        <span data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</span>
                    </a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="w-full text-sm text-center inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 h-8 px-3 text-gray-700 dark:text-gray-300">
                            <span data-i18n="Admin Panel" x-text="$store.i18n.t('Admin Panel')">{{ __('Admin Panel') }}</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-sm text-center inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 px-3 text-black shadow hover:opacity-90"
                            style="background-color: var(--bloom-primary);">
                            <span data-i18n="Sign Out" x-text="$store.i18n.t('Sign Out')">{{ __('Sign Out') }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full text-sm text-center inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 h-8 px-3 text-gray-700 dark:text-gray-300">
                        <span data-i18n="Sign In" x-text="$store.i18n.t('Sign In')">{{ __('Sign In') }}</span>
                    </a>
                    <a href="{{ route('register') }}"
                        class="w-full text-sm text-center inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors text-black shadow hover:opacity-90 h-8 px-3"
                        style="background-color: var(--bloom-primary);">
                        <span data-i18n="Sign Up" x-text="$store.i18n.t('Sign Up')">{{ __('Sign Up') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
