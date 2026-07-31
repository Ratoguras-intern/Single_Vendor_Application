
<div x-show="searchOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="bg-white dark:bg-secondary-800 border-b border-secondary-200 dark:border-secondary-700 shadow-dropdown z-[60] relative md:absolute md:left-0 md:right-0 md:top-full"
    style="display: none;">

    <div class="section py-4">
        
        <div x-show="searchQuery.length >= 2 && searchFocused" class="mt-4" style="display: none;">
            
            <template x-if="searchLoading">
                <div class="flex items-center justify-center py-6">
                    <svg class="animate-spin h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-2 text-sm text-secondary-500 dark:text-secondary-400">Searching...</span>
                </div>
            </template>

            
            <template x-if="!searchLoading && searchResults.length === 0 && searchQuery.length >= 2">
                <div class="text-center py-6">
                    <svg class="h-10 w-10 text-secondary-300 dark:text-secondary-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">No results found for "<span x-text="searchQuery" class="font-medium"></span>"</p>
                </div>
            </template>

            <template x-if="!searchLoading && searchResults.length > 0">
                <div>
                    
                    <template x-if="searchCategories.length > 0">
                        <div class="mb-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 px-4 mb-2">Categories</h4>
                            <template x-for="cat in searchCategories" :key="cat.id">
                                <a :href="cat.url" class="search-result-item">
                                    <svg class="h-4 w-4 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z"/>
                                    </svg>
                                    <span class="text-sm text-secondary-700 dark:text-secondary-300" x-text="cat.name"></span>
                                </a>
                            </template>
                        </div>
                    </template>

                    
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 px-4 mb-2">Products</h4>
                        <template x-for="product in searchResults" :key="product.id">
                            <a :href="'/product/' + product.id" class="search-result-item">
                                <div class="h-10 w-10 shrink-0 rounded-lg overflow-hidden bg-secondary-100 dark:bg-secondary-700">
                                    <img :src="product.image" :alt="product.name" class="h-full w-full object-cover" loading="lazy">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-secondary-900 dark:text-white truncate" x-text="product.name"></p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-primary-600 dark:text-primary-400" x-text="$store.currency.format(product.price)"></span>
                                        <template x-if="product.original_price > product.price">
                                            <span class="text-xs text-secondary-400 dark:text-secondary-500 line-through" x-text="$store.currency.format(product.original_price)"></span>
                                        </template>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    <a :href="'<?php echo e(route('frontend.shop')); ?>?search=' + encodeURIComponent(searchQuery)"
                        class="block text-center text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 py-3 mt-2 border-t border-secondary-100 dark:border-secondary-700 transition-colors">
                        View all results
                    </a>
                </div>
            </template>
        </div>

        
        <div x-show="searchQuery.length < 2 && searchFocused" class="mt-4 grid grid-cols-2 gap-8" style="display: none;">
            
            <div x-show="recentSearches.length > 0">
                <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-3">Recent Searches</h4>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(term, i) in recentSearches" :key="i">
                        <button x-on:click="searchQuery = term; liveSearch()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary-100 dark:bg-white/10 text-sm text-secondary-600 dark:text-secondary-400 hover:bg-secondary-200 dark:hover:bg-white/15 transition-colors">
                            <svg class="h-3 w-3 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span x-text="term"></span>
                        </button>
                    </template>
                </div>
            </div>

            
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-3">Popular Searches</h4>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(term, i) in popularSearches" :key="i">
                        <button x-on:click="searchQuery = term; liveSearch()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-50 dark:bg-primary-900/20 text-sm text-primary-700 dark:text-primary-400 hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 5.514-5.514l4.306 4.306L21.75 9"/>
                            </svg>
                            <span x-text="term"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        
        <div x-show="searchQuery.length < 2 && !searchFocused" class="mt-4 pt-4 border-t border-secondary-100 dark:border-secondary-700">
            <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-3">Quick Links</h4>
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('frontend.shop')); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary-100 dark:bg-white/10 text-sm text-secondary-600 dark:text-secondary-400 hover:bg-secondary-200 dark:hover:bg-white/15 transition-colors">
                    All Products
                </a>
                <?php $__empty_1 = true; $__currentLoopData = ($categories ?? collect())->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('frontend.shop')); ?>?category=<?php echo e($cat->slug); ?>"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary-100 dark:bg-white/10 text-sm text-secondary-600 dark:text-secondary-400 hover:bg-secondary-200 dark:hover:bg-white/15 transition-colors">
                        <?php echo e($cat->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/nischal/Nischal/Projects/Single_Vendor_Application/resources/views/frontend/partials/search-overlay.blade.php ENDPATH**/ ?>