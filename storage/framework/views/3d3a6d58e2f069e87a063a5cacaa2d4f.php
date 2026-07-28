
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['categories' => collect(), 'level' => 0, 'context' => 'mega', 'activeCategory' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['categories' => collect(), 'level' => 0, 'context' => 'mega', 'activeCategory' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($categories->isNotEmpty()): ?>
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $hasChildren = $category->children && $category->children->isNotEmpty();
            $isActive = $activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id);
        ?>

        <?php if($context === 'mega'): ?>
            
            <li x-data="{ open: false }"
                x-on:mouseenter="open = true; hoveredCat = <?php echo e($category->id); ?>"
                x-on:mouseleave="open = false; hoveredCat = null">
                <a href="<?php echo e(route('frontend.category', $category->slug)); ?>"
                    class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors group
                        <?php echo e($isActive
                            ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                            : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10 hover:text-primary-700 dark:hover:text-primary-400'); ?>"
                    role="menuitem"
                    <?php echo e($isActive ? 'aria-current="page"' : ''); ?>>
                    <span class="flex items-center gap-2.5">
                        <?php if($category->icon_url): ?>
                            <img src="<?php echo e($category->icon_url); ?>" alt="" class="h-5 w-5 rounded object-cover shrink-0" loading="lazy">
                        <?php endif; ?>
                        <span class="font-medium"><?php echo e($category->name); ?></span>
                    </span>
                    <?php if($hasChildren): ?>
                        <svg class="h-3.5 w-3.5 text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    <?php else: ?>
                        <span class="text-xs text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0"><?php echo e($category->total_products_count); ?></span>
                    <?php endif; ?>
                </a>
            </li>

        <?php elseif($context === 'mobile'): ?>
            
            <div x-data="{ open: false }">
                <?php if($hasChildren): ?>
                    <button x-on:click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-sm transition-colors
                            <?php echo e($isActive
                                ? 'text-primary-700 dark:text-primary-400 font-semibold'
                                : 'text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5'); ?>">
                        <span class="flex items-center gap-2">
                            <?php if($category->icon_url): ?>
                                <img src="<?php echo e($category->icon_url); ?>" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                            <?php endif; ?>
                            <a href="<?php echo e(route('frontend.category', $category->slug)); ?>" x-on:click.stop="mobileOpen = false" class="hover:text-primary-600"><?php echo e($category->name); ?></a>
                            <span class="text-xs text-secondary-400 dark:text-secondary-500"><?php echo e($category->total_products_count); ?></span>
                        </span>
                        <svg class="h-3.5 w-3.5 text-secondary-400 dark:text-secondary-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 space-y-0.5">
                        <?php echo $__env->make('frontend.partials.category-tree', [
                            'categories' => $category->children->filter(fn($c) => $c->status ?? true),
                            'level' => $level + 1,
                            'context' => 'mobile',
                            'activeCategory' => $activeCategory,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('frontend.category', $category->slug)); ?>" x-on:click="mobileOpen = false"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                            <?php echo e($isActive
                                ? 'text-primary-700 dark:text-primary-400 font-semibold'
                                : 'text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5'); ?>">
                        <span class="flex items-center gap-2">
                            <?php if($category->icon_url): ?>
                                <img src="<?php echo e($category->icon_url); ?>" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                            <?php endif; ?>
                            <span class="font-medium"><?php echo e($category->name); ?></span>
                            <span class="text-xs text-secondary-400 dark:text-secondary-500"><?php echo e($category->total_products_count); ?></span>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\partials\category-tree.blade.php ENDPATH**/ ?>