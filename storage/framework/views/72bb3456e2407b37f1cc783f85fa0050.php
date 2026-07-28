<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'products',
    'viewMode' => 'grid',
    'ajax' => false,
]));

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

foreach (array_filter(([
    'products',
    'viewMode' => 'grid',
    'ajax' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($products->count() > 0): ?>
    <div :class="viewMode === 'list' ? 'grid grid-cols-1 gap-4' : 'grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 items-stretch'">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('frontend.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-10">
        <?php echo $__env->make('frontend.partials.category-pagination', ['paginator' => $products, 'ajax' => $ajax], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php else: ?>
    <div class="text-center py-20">
        <div class="w-20 h-20 rounded-full bg-secondary-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-secondary-300 dark:text-secondary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-2">No products found</h3>
        <p class="text-secondary-500 dark:text-secondary-400 mb-8 max-w-md mx-auto">We couldn't find any products matching your criteria. Try adjusting your filters or browse our categories.</p>
        <div class="flex items-center justify-center gap-3">
            <?php if(collect(request()->query())->except(['page', 'sort', 'view'])->filter()->count() > 0): ?>
                <a href="<?php echo e(route('frontend.shop')); ?>" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    Clear Filters
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/frontend/partials/shop-products-content.blade.php ENDPATH**/ ?>