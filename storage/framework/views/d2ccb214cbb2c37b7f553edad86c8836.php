<?php
    $section = $sections->get('featured-products');
?>

<?php if(!empty($featuredProducts) && count($featuredProducts) > 0): ?>
<section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-secondary-900">
    <div class="section">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="section-heading"><?php echo e($section?->title ?? 'Featured Products'); ?></h2>
                <p class="section-subheading"><?php echo e($section?->subtitle ?? 'Handpicked just for you'); ?></p>
            </div>
            <a href="<?php echo e(route('frontend.shop')); ?>" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                View All
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <?php $__currentLoopData = array_slice($featuredProducts, 0, $section?->max_products ?? 8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('frontend.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="<?php echo e(route('frontend.shop')); ?>" class="btn-outline">View All Products</a>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\home\featured-products.blade.php ENDPATH**/ ?>