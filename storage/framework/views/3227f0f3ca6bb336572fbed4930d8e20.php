<?php
    $topCategories = $categories->whereNull('parent_id');
    $activeCategory = null;
    if (request()->routeIs('frontend.category')) {
        $slug = request()->route('slug');
        $activeCategory = $categories->firstWhere('slug', $slug);
        if (!$activeCategory && $categories->where('slug', $slug)->first()) {
            $activeCategory = $categories->where('slug', $slug)->first();
        }
    }
?>


<div x-show="megaOpen"
    x-on:mouseenter="clearTimeout(megaTimer)"
    x-on:mouseleave="megaOpen = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1"
    class="mega-menu-panel"
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
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\partials\mega-menu.blade.php ENDPATH**/ ?>