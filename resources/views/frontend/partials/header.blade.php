<header x-data="{
        isScrolled: false,
        mobileOpen: false,
        mobileCategoriesOpen: false,
        searchOpen: false,
        megaOpen: false,
        megaTimer: null,
        cartOpen: false,
        cartTimer: null,
        searchQuery: '',
        searchBrand: '',
        searchResults: [],
        searchCategories: [],
        searchLoading: false,
        searchFocused: false,
        currencyOpen: false,
        langOpen: false,
        userMenuOpen: false,
        recentSearches: [],
        popularSearches: [],

        liveSearch() {
            if (this.searchQuery.length < 2 && !this.searchBrand) { this.searchResults = []; this.searchCategories = []; return; }
            this.searchLoading = true;
            let url = '{{ route("api.search") }}?q=' + encodeURIComponent(this.searchQuery);
            if (this.searchBrand) url += '&brand=' + encodeURIComponent(this.searchBrand);
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    this.searchResults = data.products || [];
                    this.searchCategories = data.categories || [];
                    this.recentSearches = data.recent || [];
                    this.popularSearches = data.popular || this.popularSearches;
                })
                .catch(() => { this.searchResults = []; this.searchCategories = []; })
                .finally(() => { this.searchLoading = false; });
        },

        openMega() { clearTimeout(this.megaTimer); this.megaOpen = true; },
        closeMega() { clearTimeout(this.megaTimer); this.megaTimer = setTimeout(() => { this.megaOpen = false; }, 250); },
        openCart() { clearTimeout(this.cartTimer); this.cartTimer = setTimeout(() => { this.cartOpen = true; }, 150); },
        closeCart() { this.cartTimer = setTimeout(() => { this.cartOpen = false; }, 200); }
    }"
    x-init="
        window.addEventListener('scroll', () => { isScrolled = window.scrollY > 10 });
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                searchOpen = false; mobileOpen = false; megaOpen = false;
                cartOpen = false; currencyOpen = false; langOpen = false; userMenuOpen = false;
            }
        });
        $nextTick(() => {
            const updateHeight = () => {
                const bottom = Math.max($el.getBoundingClientRect().bottom, 0);
                $el.style.setProperty('--navbar-height', Math.round(bottom) + 'px');
            };
            updateHeight();
            window.addEventListener('scroll', updateHeight, { passive: true });
            window.addEventListener('resize', updateHeight);
            const bar = $el.previousElementSibling;
            const targets = bar ? [$el, bar] : [$el];
            const observer = new ResizeObserver(updateHeight);
            targets.forEach(t => observer.observe(t));
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(updateHeight);
            }
            window.addEventListener('announcement:dismissed', updateHeight);
        });
    "
    :class="isScrolled ? 'scrolled' : ''"
    class="navbar"
    @click.window="if (!$el.contains($event.target)) searchOpen = false"
    role="banner">

    <div class="section py-2.5 sm:py-3">
        <div class="flex items-center justify-between gap-4 lg:gap-6">

            {{-- LEFT: Logo + Nav Links --}}
            <div class="flex items-center min-w-0 shrink">
                <x-brand-logo textClass="hidden sm:inline" />

                {{-- Desktop nav links --}}
                <nav class="hidden lg:flex items-center ml-8 xl:ml-12" role="navigation" aria-label="Main navigation">
                    <div class="flex items-center gap-1">
                        @foreach($headerNavItems as $navItem)
                            @if(data_get($navItem, 'config.type') === 'mega-menu')
                                {{-- Categories mega menu --}}
                                @php
                                    $topCategories = $categories->whereNull('parent_id');
                                    $activeCategory = null;
                                    if (request()->routeIs('frontend.category')) {
                                        $slug = request()->route('slug');
                                $activeCategory = $categories->firstWhere('slug', $slug);
                            }
                        @endphp
                        <div class="categories-hover-zone"
                            x-on:mouseenter="openMega()"
                            x-on:mouseleave="closeMega()">
                            <button x-ref="categoriesBtn"
                                x-on:click="megaOpen = !megaOpen"
                                :aria-expanded="megaOpen"
                                aria-haspopup="true"
                                aria-controls="mega-menu-panel"
                                class="relative inline-flex items-center gap-1 px-3 py-1 rounded-btn text-sm font-medium transition-colors duration-150 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5">
                                <span data-i18n="Categories" x-text="$store.i18n.t('Categories')">Categories</span>
                                <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="megaOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>

                            {{-- Mega Menu Panel --}}
                            <div x-show="megaOpen"
                                id="mega-menu-panel"
                                x-on:mouseenter="clearTimeout(megaTimer)"
                                x-on:mouseleave="closeMega()"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="mega-menu-panel mega-menu-panel--fixed"
                                x-cloak
                                role="menu"
                                x-data="{ hoveredCat: null }">
                                <div class="section py-8">
                                    <div class="grid grid-cols-12 gap-8">

                                        {{-- Category List --}}
                                        <div class="col-span-3">
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-4">Categories</h3>
                                            <ul class="space-y-0.5">
                                                @forelse($topCategories as $category)
                                                    <li class="mega-cat-item" x-on:mouseenter="hoveredCat = {{ $category->id }}">
                        <a href="{{ route('frontend.category', $category->slug) }}" wire:navigate
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors group
                                                                {{ $activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id)
                                                                    ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                                                                    : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10 hover:text-primary-700 dark:hover:text-primary-400' }}"
                                                            role="menuitem"
                                                            {{ $activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id) ? 'aria-current="page"' : '' }}>
                                                            <span class="flex items-center gap-2.5">
                                                                @if($category->lucide_icon)
                                                                    <x-lucide :name="$category->lucide_icon" class="h-5 w-5 shrink-0 text-primary-500 dark:text-primary-400" />
                                                                @elseif($category->icon_url)
                                                                    <img src="{{ $category->icon_url }}" alt="" class="h-5 w-5 rounded object-cover shrink-0" loading="lazy">
                                                                @endif
                                                                <span class="font-medium">{{ $category->name }}</span>
                                                            </span>
                                                            @if($category->children->count())
                                                                <svg class="mega-cat-arrow h-3.5 w-3.5 text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                                            @else
                                                                <span class="text-xs text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0">{{ $category->total_products_count }}</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @empty
                                                    <li class="px-3 py-2 text-sm text-secondary-400 dark:text-secondary-500">No categories yet</li>
                                                @endforelse
                                            </ul>
                                            <a href="{{ route('frontend.shop') }}" wire:navigate
                                                class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                                View All Products
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                            </a>
                                        </div>

                                        {{-- Subcategories for hovered parent OR Featured image cards --}}
                                        <div class="col-span-5">
                                            @foreach($topCategories as $category)
                                                <div x-show="hoveredCat === {{ $category->id }}" x-transition.opacity.duration.150ms>
                                                    <div class="flex items-center justify-between mb-4">
                                                        <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500">{{ $category->name }}</h3>
                                                        <span class="text-xs text-secondary-400 dark:text-secondary-500">{{ $category->total_products_count }} products</span>
                                                    </div>
                                                    @if($category->children->isNotEmpty())
                                                        <div class="grid grid-cols-2 gap-2">
                                                            @foreach($category->children as $child)
                                                            <a href="{{ route('frontend.category', $child->slug) }}" wire:navigate
                                                                class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                                                                        {{ $activeCategory && $activeCategory->id === $child->id
                                                                            ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                                                                            : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10' }}">
                                                                    <span class="flex items-center gap-2">
                                                                        @if($child->lucide_icon)
                                                                            <x-lucide :name="$child->lucide_icon" class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" />
                                                                        @elseif($child->icon_url)
                                                                            <img src="{{ $child->icon_url }}" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                                                                        @endif
                                                                        <span>{{ $child->name }}</span>
                                                                    </span>
                                                                    <span class="text-xs text-secondary-400 dark:text-secondary-500 shrink-0">{{ $child->total_products_count }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-sm text-secondary-400 dark:text-secondary-500 py-2">No subcategories yet.</p>
                                                    @endif
                                                    <a href="{{ route('frontend.category', $category->slug) }}" wire:navigate
                                                        class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                                        View All
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                                    </a>
                                                </div>
                                            @endforeach

                                            <div x-show="hoveredCat === null">
                                                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-4">Shop by Category</h3>
                                                <div class="grid grid-cols-2 gap-3">
                        @forelse($topCategories->take(4) as $category)
                                                    <a href="{{ route('frontend.category', $category->slug) }}" wire:navigate
                                                        class="mega-cat-card">
                                                        @if($category->display_image)
                                                            <img src="{{ $category->display_image }}" alt="{{ $category->name }}"
                                                                class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                                                        @else
                                                            <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/20"></div>
                                                        @endif
                                                        <div class="mega-cat-overlay"></div>
                                                        <div class="mega-cat-content">
                                                            <span class="text-sm font-semibold text-white">{{ $category->name }}</span>
                                                            <span class="block text-xs text-white/70">{{ $category->total_products_count }} products</span>
                                                        </div>
                                                    </a>
                                                    @empty
                                                        <div class="col-span-2 text-sm text-secondary-400 dark:text-secondary-500 py-4">Browse our collection</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Promo Banner --}}
                                        <div class="col-span-4">
                                            <a href="{{ $megaMenuPromo['url'] }}" wire:navigate class="mega-promo-card block h-full">
                                                <div class="relative">
                                                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold mb-3">{{ $megaMenuPromo['badge'] }}</span>
                                                    <h4 class="text-xl font-bold text-white mb-2">{{ $megaMenuPromo['heading'] }}</h4>
                                                    <p class="text-sm text-white/80 mb-4">{{ $megaMenuPromo['description'] }}</p>
                                                    <span class="mega-promo-btn">
                                                        {{ $megaMenuPromo['cta_text'] }}
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        @else
                            <a href="{{ $navItem['url'] }}" wire:navigate.prefetch
                                @php
                                    $navPath = parse_url($navItem['url'] ?? '', PHP_URL_PATH) ?: '/';
                                    $currentPath = parse_url(url()->current(), PHP_URL_PATH) ?: '/';
                                    $isActive = $navPath === '/'
                                        ? $currentPath === '/'
                                        : str_starts_with($currentPath, $navPath);
                                @endphp
                                class="relative px-3 py-1 rounded-btn text-sm font-medium transition-colors duration-150 {{ $navItem['css_class'] ?? '' }} {{ $isActive ? 'text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5' }}"
                                @if(data_get($navItem, 'target')) target="{{ $navItem['target'] }}" @endif>
                                <span data-i18n="{{ $navItem['name'] }}" x-text="$store.i18n.t('{{ $navItem['name'] }}')">{{ $navItem['name'] }}</span>
                            </a>
                        @endif
                        @endforeach

                    </div>
                </nav>
            </div>

            {{-- CENTER: Search Bar (desktop) --}}
            <div class="hidden md:flex flex-1 max-w-xl mx-auto relative">
                <div class="relative w-full"
                    x-on:focusin="searchOpen = true; searchFocused = true"
                    x-on:focusout="setTimeout(() => { searchFocused = false }, 200)">
                    <form action="{{ route('frontend.shop') }}" method="GET">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-secondary-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <circle cx="11" cy="11" r="8"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                            </svg>
                            <input type="search" name="search"
                                x-model="searchQuery"
                                x-on:input.debounce.300ms="liveSearch()"
                                x-ref="desktopSearchInput"
                                placeholder="Search products, brands, categories..."
                                data-i18n-placeholder="Search products..."
                                class="w-full pl-11 pr-4 py-1.5 rounded-xl border border-secondary-200 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-800 text-sm text-secondary-900 dark:text-white placeholder:text-secondary-400 dark:placeholder:text-secondary-500 focus:border-primary-400 dark:focus:border-primary-400 focus:bg-white dark:focus:bg-secondary-700 focus:ring-2 focus:ring-primary-500/10 dark:focus:ring-primary-400/10 transition-all duration-200"
                                autocomplete="off"
                                aria-label="Search products">
                            <button type="button" x-show="searchQuery.length > 0" x-on:click="searchQuery = ''; searchResults = []; searchCategories = []"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-full hover:bg-secondary-200 dark:hover:bg-secondary-700 transition-colors text-secondary-400 dark:text-secondary-500"
                                style="display: none;" aria-label="Clear search">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: Icons --}}
            <div class="flex items-center gap-1 shrink-0">

                {{-- Mobile search toggle --}}
                <button x-on:click.stop="searchOpen = !searchOpen"
                    class="navbar-icon-btn md:hidden"
                    aria-label="Search">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                    </svg>
                </button>

                {{-- Dark mode toggle --}}
                <button x-on:click="$store.theme.toggle()"
                    class="navbar-icon-btn"
                    aria-label="Toggle theme">
                    <template x-if="$store.theme.current === 'light'">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    </template>
                    <template x-if="$store.theme.current === 'dark'">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    </template>
                </button>

                {{-- Currency --}}
                <div class="hidden sm:block relative">
                    <button x-on:click="currencyOpen = !currencyOpen"
                        class="navbar-icon-btn"
                        aria-label="Select currency" :aria-expanded="currencyOpen">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </button>
                    <div x-show="currencyOpen" @click.away="currencyOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        class="absolute right-0 z-50 mt-2 w-40 rounded-card border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 py-1.5 shadow-dropdown"
                        style="display: none;">
                        <template x-for="(config, code) in window.currencyConfig" :key="code">
                            <button x-on:click="$store.currency.set(code); currencyOpen = false"
                                class="w-full text-left px-4 py-2 text-sm transition-colors"
                                :class="$store.currency.code === code ? 'font-bold text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5'">
                                <span x-text="config.symbol + ' ' + code"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Language --}}
                <div class="hidden sm:block relative">
                    <button x-on:click="langOpen = !langOpen"
                        class="navbar-icon-btn"
                        aria-label="Select language" :aria-expanded="langOpen">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    </button>
                    <div x-show="langOpen" @click.away="langOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        class="absolute right-0 z-50 mt-2 w-36 rounded-card border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 py-1.5 shadow-dropdown"
                        style="display: none;">
                        @foreach(['en' => 'English', 'ja' => '日本語', 'ne' => 'नेपाली'] as $code => $name)
                            <button x-on:click="$store.i18n.setLocale('{{ $code }}'); langOpen = false"
                                class="w-full text-left px-4 py-2 text-sm transition-colors"
                                :class="$store.i18n.locale === '{{ $code }}' ? 'font-bold text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5'">
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Wishlist --}}
                <a href="{{ route('frontend.favorites') }}" wire:navigate
                    class="navbar-icon-btn group"
                    aria-label="Favorites">
                    <svg class="h-5 w-5 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <span x-show="$store.wishlist.items.length > 0"
                        x-text="$store.wishlist.items.length > 99 ? '99+' : $store.wishlist.items.length"
                        x-transition
                        class="absolute -top-0.5 -right-0.5 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 bg-primary-500 shadow-sm"></span>
                </a>

                {{-- Cart with dropdown --}}
                <div class="relative"
                    x-on:mouseenter="openCart()"
                    x-on:mouseleave="closeCart()">
                    <a href="{{ route('frontend.cart') }}"
                        class="navbar-icon-btn group"
                        aria-label="Shopping cart"
                        @click.prevent="if(window.innerWidth >= 1024) { cartOpen = !cartOpen } else { window.location='{{ route('frontend.cart') }}' }">
                        <span class="relative inline-flex">
                            <svg class="h-5 w-5 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                            <span x-show="$store.cart.count() > 0"
                                x-text="$store.cart.count() > 99 ? '99+' : $store.cart.count()"
                                x-transition
                                class="absolute -top-1.5 -right-1.5 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 bg-primary-500 shadow-sm ring-2 ring-white dark:ring-secondary-900"></span>
                        </span>
                    </a>
                    @include('frontend.partials.cart-dropdown')
                </div>

                {{-- User account --}}
                <div class="hidden sm:flex items-center">
                    @auth
                        <div class="relative">
                            <button x-on:click="userMenuOpen = !userMenuOpen"
                                class="inline-flex items-center gap-2 whitespace-nowrap rounded-btn text-sm font-medium transition-colors h-8 px-2.5 hover:bg-secondary-100 dark:hover:bg-white/5"
                                aria-label="User menu" :aria-expanded="userMenuOpen">
                                <x-user-avatar :user="auth()->user()" size="h-7 w-7" text-size="text-xs" />
                                <svg class="h-3 w-3 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute right-0 z-50 mt-2 w-56 rounded-card border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 py-1.5 shadow-dropdown"
                                style="display: none;">

                                <div class="px-4 py-3 border-b border-secondary-100 dark:border-secondary-700">
                                    <p class="text-sm font-semibold text-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-secondary-500 dark:text-secondary-400 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if (auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.orders.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                        <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                                        <span data-i18n="My Orders" x-text="$store.i18n.t('My Orders')">{{ __('My Orders') }}</span>
                                    </a>
                                    <a href="{{ route('customer.returns.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                        <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                                        <span>My Returns</span>
                                    </a>
                                @endif
                                <a href="{{ route('frontend.favorites') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                    <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                                    <span data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                    <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                    <span data-i18n="Profile" x-text="$store.i18n.t('Profile')">{{ __('Profile') }}</span>
                                </a>
                                <div class="mx-4 my-1.5 border-t border-secondary-100 dark:border-secondary-700"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        <span data-i18n="Sign Out" x-text="$store.i18n.t('Sign Out')">{{ __('Sign Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost btn-sm">
                            <span data-i18n="Sign In" x-text="$store.i18n.t('Sign In')">{{ __('Sign In') }}</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">
                            <span data-i18n="Sign Up" x-text="$store.i18n.t('Sign Up')">{{ __('Sign Up') }}</span>
                        </a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <button x-on:click="mobileOpen = !mobileOpen"
                    class="navbar-icon-btn lg:hidden"
                    aria-label="Toggle navigation menu" :aria-expanded="mobileOpen">
                    <template x-if="!mobileOpen">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                    </template>
                    <template x-if="mobileOpen">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </template>
                </button>
            </div>
        </div>
    </div>

    {{-- Search Area: wraps mobile bar + overlay --}}
    <div class="md:contents">
        {{-- Mobile Search Bar --}}
        <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
            x-effect="if (searchOpen && window.innerWidth < 768 && $refs.mobileSearchInput) { $nextTick(() => $refs.mobileSearchInput.focus()) }"
            class="md:hidden border-t border-secondary-100 dark:border-secondary-700 bg-white dark:bg-secondary-900 px-4 py-2 relative"
            style="display: none;">
            <form action="{{ route('frontend.shop') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-secondary-400 dark:text-secondary-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                </svg>
                <input type="search" name="search" x-model="searchQuery"
                    x-on:input.debounce.300ms="liveSearch()"
                    x-on:focus="searchFocused = true"
                    x-on:blur="setTimeout(() => { searchFocused = false }, 200)"
                    x-ref="mobileSearchInput"
                    placeholder="Search products..." autocomplete="off"
                    class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-secondary-200 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-800 text-sm text-secondary-900 dark:text-white placeholder:text-secondary-400 dark:placeholder:text-secondary-500 focus:border-primary-500 dark:focus:border-primary-400 focus:bg-white dark:focus:bg-secondary-700 transition-all"
                    aria-label="Search products">
                <button type="button" x-show="searchQuery.length > 0" x-on:click="searchQuery = ''; searchResults = []; searchCategories = []"
                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-full hover:bg-secondary-200 dark:hover:bg-secondary-700 transition-colors text-secondary-400 dark:text-secondary-500"
                    style="display: none;" aria-label="Clear search">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </form>
        </div>

        {{-- Search Overlay (works on all breakpoints) --}}
        @include('frontend.partials.search-overlay')
    </div>

    {{-- Mobile Drawer --}}
    @include('frontend.partials.mobile-drawer')
</header>
