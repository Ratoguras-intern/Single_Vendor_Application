<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator', 'ajax' => false]));

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

foreach (array_filter((['paginator', 'ajax' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($paginator->hasPages()): ?>
    <nav class="flex items-center justify-center gap-2" role="navigation" aria-label="Pagination">
        
        <?php if($paginator->onFirstPage()): ?>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-300 dark:text-secondary-600 cursor-not-allowed" aria-disabled="true">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" <?php if($ajax): ?> @click.prevent="$dispatch('shop:apply', { url: '<?php echo e($paginator->previousPageUrl()); ?>' })" <?php endif; ?> class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white transition-colors" rel="prev" aria-label="Previous page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </a>
        <?php endif; ?>

        
        <?php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $pages = [];
            if ($lastPage <= 7) {
                $pages = range(1, $lastPage);
            } else {
                $pages[] = 1;
                if ($currentPage > 3) {
                    $pages[] = '...';
                }
                $start = max(2, $currentPage - 1);
                $end = min($lastPage - 1, $currentPage + 1);
                foreach (range($start, $end) as $p) {
                    $pages[] = $p;
                }
                if ($currentPage < $lastPage - 2) {
                    $pages[] = '...';
                }
                $pages[] = $lastPage;
            }
        ?>

        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page === '...'): ?>
                <span class="inline-flex items-center justify-center w-10 h-10 text-sm text-secondary-400 dark:text-secondary-500">...</span>
            <?php elseif($page === $currentPage): ?>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-btn bg-primary-500 text-white font-medium text-sm shadow-sm" aria-current="page"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($paginator->url($page)); ?>" <?php if($ajax): ?> @click.prevent="$dispatch('shop:apply', { url: '<?php echo e($paginator->url($page)); ?>' })" <?php endif; ?> class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white transition-colors text-sm" aria-label="Page <?php echo e($page); ?>"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" <?php if($ajax): ?> @click.prevent="$dispatch('shop:apply', { url: '<?php echo e($paginator->nextPageUrl()); ?>' })" <?php endif; ?> class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white transition-colors" rel="next" aria-label="Next page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        <?php else: ?>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-300 dark:text-secondary-600 cursor-not-allowed" aria-disabled="true">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </span>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\partials\category-pagination.blade.php ENDPATH**/ ?>