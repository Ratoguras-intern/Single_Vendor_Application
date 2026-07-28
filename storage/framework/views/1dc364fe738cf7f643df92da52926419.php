<?php
    $section = $sections->get('new-arrivals');
?>

<?php if(!empty($newArrivals) && count($newArrivals) > 0): ?>
<section class="py-12 sm:py-16 lg:py-20">
    <div class="section">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="section-heading"><?php echo e($section?->title ?? 'New Arrivals'); ?></h2>
                <p class="section-subheading"><?php echo e($section?->subtitle ?? 'Fresh drops, just landed'); ?></p>
            </div>
            <a href="<?php echo e(route('frontend.shop')); ?>" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                View All
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>
    </div>

    <div class="relative group">
        <div class="section">
            <div class="flex gap-6 overflow-x-auto scrollbar-hide snap-x snap-mandatory pb-4">
                <?php $__currentLoopData = array_slice($newArrivals, 0, $section?->max_products ?? 10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-shrink-0 w-[280px] sm:w-[300px] snap-start">
                        <?php echo $__env->make('frontend.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/frontend/home/new-arrivals.blade.php ENDPATH**/ ?>