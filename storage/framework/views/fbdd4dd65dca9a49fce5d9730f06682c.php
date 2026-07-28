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
            if (this.searchQuery.length < 2) { this.searchResults = []; this.searchCategories = []; return; }
            this.searchLoading = true;
            fetch('<?php echo e(route("api.search")); ?>?q=' + encodeURIComponent(this.searchQuery))
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
            const updateHeight = () => { $el.style.setProperty('--navbar-height', $el.offsetHeight + 'px'); };
            updateHeight();
            window.addEventListener('resize', updateHeight);
        });
    "
    :class="isScrolled ? 'scrolled' : ''"
    class="navbar"
    role="banner">

    <div class="section py-3">
        <div class="flex items-center justify-between gap-4 lg:gap-6">

            
            <div class="flex items-center min-w-0 shrink">
                <?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand-logo','data' => ['textClass' => 'hidden sm:inline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['textClass' => 'hidden sm:inline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $attributes = $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $component = $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>

                
                <nav class="hidden lg:flex items-center ml-8 xl:ml-12" role="navigation" aria-label="Main navigation">
                    <div class="flex items-center gap-1">
                        <a href="<?php echo e(route('frontend.home')); ?>"
                            class="relative px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('frontend.home') ? 'text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5'); ?>"
                            <?php echo e(request()->routeIs('frontend.home') ? 'aria-current="page"' : ''); ?>>
                            <span data-i18n="Home" x-text="$store.i18n.t('Home')">Home</span>
                        </a>

                        <a href="<?php echo e(route('frontend.shop')); ?>"
                            class="relative px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('frontend.shop') ? 'text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5'); ?>"
                            <?php echo e(request()->routeIs('frontend.shop') ? 'aria-current="page"' : ''); ?>>
                            <span data-i18n="Shop" x-text="$store.i18n.t('Shop')">Shop</span>
                        </a>

                        
                        <?php
                            $topCategories = $categories->whereNull('parent_id');
                            $activeCategory = null;
                            if (request()->routeIs('frontend.category')) {
                                $slug = request()->route('slug');
                                $activeCategory = $categories->firstWhere('slug', $slug);
                            }
                        ?>
                        <div class="categories-hover-zone"
                            x-on:mouseenter="openMega()"
                            x-on:mouseleave="closeMega()">
                            <button x-ref="categoriesBtn"
                                x-on:click="megaOpen = !megaOpen"
                                :aria-expanded="megaOpen"
                                aria-haspopup="true"
                                aria-controls="mega-menu-panel"
                                class="relative inline-flex items-center gap-1 px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5">
                                <span data-i18n="Categories" x-text="$store.i18n.t('Categories')">Categories</span>
                                <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="megaOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>

                            
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

                                        
                                        <div class="col-span-3">
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-4">Categories</h3>
                                            <ul class="space-y-0.5">
                                                <?php $__empty_1 = true; $__currentLoopData = $topCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <li class="mega-cat-item" x-on:mouseenter="hoveredCat = <?php echo e($category->id); ?>"
                                                        x-on:mouseleave="hoveredCat = null">
                                                        <a href="<?php echo e(route('frontend.category', $category->slug)); ?>"
                                                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors group
                                                                <?php echo e($activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id)
                                                                    ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                                                                    : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10 hover:text-primary-700 dark:hover:text-primary-400'); ?>"
                                                            role="menuitem"
                                                            <?php echo e($activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id) ? 'aria-current="page"' : ''); ?>>
                                                            <span class="flex items-center gap-2.5">
                                                                <?php if($category->icon_url): ?>
                                                                    <img src="<?php echo e($category->icon_url); ?>" alt="" class="h-5 w-5 rounded object-cover shrink-0" loading="lazy">
                                                                <?php endif; ?>
                                                                <span class="font-medium"><?php echo e($category->name); ?></span>
                                                            </span>
                                                            <?php if($category->children->count()): ?>
                                                                <svg class="mega-cat-arrow h-3.5 w-3.5 text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                                            <?php else: ?>
                                                                <span class="text-xs text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0"><?php echo e($category->total_products_count); ?></span>
                                                            <?php endif; ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <li class="px-3 py-2 text-sm text-secondary-400 dark:text-secondary-500">No categories yet</li>
                                                <?php endif; ?>
                                            </ul>
                                            <a href="<?php echo e(route('frontend.shop')); ?>"
                                                class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                                View All Products
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                            </a>
                                        </div>

                                        
                                        <div class="col-span-5">
                                            <?php $__currentLoopData = $topCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div x-show="hoveredCat === <?php echo e($category->id); ?>" x-transition.opacity.duration.150ms>
                                                    <div class="flex items-center justify-between mb-4">
                                                        <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500"><?php echo e($category->name); ?></h3>
                                                        <span class="text-xs text-secondary-400 dark:text-secondary-500"><?php echo e($category->total_products_count); ?> products</span>
                                                    </div>
                                                    <?php if($category->children->isNotEmpty()): ?>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <a href="<?php echo e(route('frontend.category', $child->slug)); ?>"
                                                                    class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                                                                        <?php echo e($activeCategory && $activeCategory->id === $child->id
                                                                            ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                                                                            : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10'); ?>">
                                                                    <span class="flex items-center gap-2">
                                                                        <?php if($child->icon_url): ?>
                                                                            <img src="<?php echo e($child->icon_url); ?>" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                                                                        <?php endif; ?>
                                                                        <span><?php echo e($child->name); ?></span>
                                                                    </span>
                                                                    <span class="text-xs text-secondary-400 dark:text-secondary-500 shrink-0"><?php echo e($child->total_products_count); ?></span>
                                                                </a>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-sm text-secondary-400 dark:text-secondary-500 py-2">No subcategories yet.</p>
                                                    <?php endif; ?>
                                                    <a href="<?php echo e(route('frontend.category', $category->slug)); ?>"
                                                        class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                                        View All
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                                    </a>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <div x-show="hoveredCat === null">
                                                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-4">Shop by Category</h3>
                                                <div class="grid grid-cols-2 gap-3">
                        <?php $__empty_1 = true; $__currentLoopData = $topCategories->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <a href="<?php echo e(route('frontend.category', $category->slug)); ?>"
                                                        class="mega-cat-card">
                                                        <?php if($category->display_image): ?>
                                                            <img src="<?php echo e($category->display_image); ?>" alt="<?php echo e($category->name); ?>"
                                                                class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                                                        <?php else: ?>
                                                            <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/20"></div>
                                                        <?php endif; ?>
                                                        <div class="mega-cat-overlay"></div>
                                                        <div class="mega-cat-content">
                                                            <span class="text-sm font-semibold text-white"><?php echo e($category->name); ?></span>
                                                            <span class="block text-xs text-white/70"><?php echo e($category->total_products_count); ?> products</span>
                                                        </div>
                                                    </a>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <div class="col-span-2 text-sm text-secondary-400 dark:text-secondary-500 py-4">Browse our collection</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-span-4">
                                            <a href="<?php echo e(route('frontend.shop')); ?>" class="mega-promo-card block h-full">
                                                <div class="relative">
                                                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold mb-3">Featured</span>
                                                    <h4 class="text-xl font-bold text-white mb-2">Explore Our Collection</h4>
                                                    <p class="text-sm text-white/80 mb-4">Discover quality products across all categories.</p>
                                                    <span class="mega-promo-btn">
                                                        Shop Now
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="<?php echo e(route('frontend.shop')); ?>?sort=newest"
                            class="relative px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5">
                            <span data-i18n="New Arrivals" x-text="$store.i18n.t('New Arrivals')">New Arrivals</span>
                        </a>

                        <a href="<?php echo e(route('frontend.shop')); ?>?sort=sale"
                            class="relative px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 text-red-500 hover:text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-500/10">
                            <span data-i18n="Sale" x-text="$store.i18n.t('Sale')">Sale</span>
                        </a>

                    </div>
                </nav>
            </div>

            
            <div class="hidden md:flex flex-1 max-w-xl mx-auto relative">
                <div class="relative w-full"
                    x-on:focusin="searchOpen = true; searchFocused = true"
                    x-on:focusout="setTimeout(() => { searchFocused = false }, 200)">
                    <form action="<?php echo e(route('frontend.shop')); ?>" method="GET">
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
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-secondary-200 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-800 text-sm text-secondary-900 dark:text-white placeholder:text-secondary-400 dark:placeholder:text-secondary-500 focus:border-primary-400 dark:focus:border-primary-400 focus:bg-white dark:focus:bg-secondary-700 focus:ring-2 focus:ring-primary-500/10 dark:focus:ring-primary-400/10 transition-all duration-200"
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

            
            <div class="flex items-center gap-0.5 sm:gap-1 shrink-0">

                
                <button x-on:click.stop="searchOpen = !searchOpen"
                    class="md:hidden p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-600 dark:text-secondary-400"
                    aria-label="Search">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                    </svg>
                </button>

                
                <button x-on:click="$store.theme.toggle()"
                    class="p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-600 dark:text-secondary-400"
                    aria-label="Toggle theme">
                    <template x-if="$store.theme.current === 'light'">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    </template>
                    <template x-if="$store.theme.current === 'dark'">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    </template>
                </button>

                
                <div class="hidden sm:block relative">
                    <button x-on:click="currencyOpen = !currencyOpen"
                        class="p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-600 dark:text-secondary-400"
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

                
                <div class="hidden sm:block relative">
                    <button x-on:click="langOpen = !langOpen"
                        class="p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-600 dark:text-secondary-400"
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
                        <?php $__currentLoopData = ['en' => 'English', 'ja' => '日本語', 'ne' => 'नेपाली']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button x-on:click="$store.i18n.setLocale('<?php echo e($code); ?>'); langOpen = false"
                                class="w-full text-left px-4 py-2 text-sm transition-colors"
                                :class="$store.i18n.locale === '<?php echo e($code); ?>' ? 'font-bold text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5'">
                                <?php echo e($name); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <a href="<?php echo e(route('frontend.favorites')); ?>"
                    class="relative p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-all duration-200 group text-secondary-600 dark:text-secondary-400"
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

                
                <div class="relative"
                    x-on:mouseenter="openCart()"
                    x-on:mouseleave="closeCart()">
                    <a href="<?php echo e(route('frontend.cart')); ?>"
                        class="relative p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-all duration-200 group text-secondary-600 dark:text-secondary-400"
                        aria-label="Shopping cart"
                        @click.prevent="if(window.innerWidth >= 1024) { cartOpen = !cartOpen } else { window.location='<?php echo e(route('frontend.cart')); ?>' }">
                        <svg class="h-5 w-5 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                        <span x-show="$store.cart.count() > 0"
                            x-text="$store.cart.count() > 99 ? '99+' : $store.cart.count()"
                            x-transition
                            class="absolute -top-0.5 -right-0.5 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 bg-primary-500 shadow-sm"></span>
                    </a>
                    <?php echo $__env->make('frontend.partials.cart-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                
                <div class="hidden sm:flex items-center">
                    <?php if(auth()->guard()->check()): ?>
                        <div class="relative">
                            <button x-on:click="userMenuOpen = !userMenuOpen"
                                class="inline-flex items-center gap-2 whitespace-nowrap rounded-btn text-sm font-medium transition-colors h-8 px-2.5 hover:bg-secondary-100 dark:hover:bg-white/5"
                                aria-label="User menu" :aria-expanded="userMenuOpen">
                                <div class="h-7 w-7 rounded-full flex items-center justify-center text-xs font-bold text-white bg-gradient-to-br from-primary-500 to-primary-600 shadow-sm">
                                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                                </div>
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
                                    <p class="text-sm font-semibold text-secondary-900 dark:text-white"><?php echo e(auth()->user()->name); ?></p>
                                    <p class="text-xs text-secondary-500 dark:text-secondary-400 truncate"><?php echo e(auth()->user()->email); ?></p>
                                </div>

                                <?php if(auth()->user()->role === 'customer'): ?>
                                    <a href="<?php echo e(route('customer.orders.index')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                        <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                                        <span data-i18n="My Orders" x-text="$store.i18n.t('My Orders')"><?php echo e(__('My Orders')); ?></span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('frontend.favorites')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                    <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                                    <span data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')"><?php echo e(__('My Favorites')); ?></span>
                                </a>
                                <?php if(auth()->user()->role === 'admin'): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                        <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        <span data-i18n="Admin Panel" x-text="$store.i18n.t('Admin Panel')"><?php echo e(__('Admin Panel')); ?></span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                                    <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                    <span data-i18n="Profile" x-text="$store.i18n.t('Profile')"><?php echo e(__('Profile')); ?></span>
                                </a>
                                <div class="mx-4 my-1.5 border-t border-secondary-100 dark:border-secondary-700"></div>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        <span data-i18n="Sign Out" x-text="$store.i18n.t('Sign Out')"><?php echo e(__('Sign Out')); ?></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-ghost btn-sm">
                            <span data-i18n="Sign In" x-text="$store.i18n.t('Sign In')"><?php echo e(__('Sign In')); ?></span>
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="btn-primary btn-sm">
                            <span data-i18n="Sign Up" x-text="$store.i18n.t('Sign Up')"><?php echo e(__('Sign Up')); ?></span>
                        </a>
                    <?php endif; ?>
                </div>

                
                <button x-on:click="mobileOpen = !mobileOpen"
                    class="lg:hidden p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-600 dark:text-secondary-400"
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

    
    <div class="md:contents" @click.outside="searchOpen = false">
        
        <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
            x-effect="if (searchOpen && window.innerWidth < 768 && $refs.mobileSearchInput) { $nextTick(() => $refs.mobileSearchInput.focus()) }"
            class="md:hidden border-t border-secondary-100 dark:border-secondary-700 bg-white dark:bg-secondary-900 px-4 py-3 relative"
            style="display: none;">
            <form action="<?php echo e(route('frontend.shop')); ?>" method="GET" class="relative">
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

        
        <?php echo $__env->make('frontend.partials.search-overlay', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <?php echo $__env->make('frontend.partials.mobile-drawer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</header>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\partials\header.blade.php ENDPATH**/ ?>