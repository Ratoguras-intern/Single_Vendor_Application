<?php
    $section = $sections->get('top-brands');
?>

<?php if(!empty($brands) && count($brands) > 0): ?>
<section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-secondary-900">
    <div class="section">
        <div class="text-center mb-10">
            <h2 class="section-heading"><?php echo e($section?->title ?? 'Top Brands'); ?></h2>
            <p class="section-subheading"><?php echo e($section?->subtitle ?? 'Trusted by our customers worldwide'); ?></p>
        </div>
    </div>

    <div class="overflow-hidden">
        <div class="marquee-track">
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex-shrink-0 mx-8 sm:mx-12 flex items-center justify-center h-16 sm:h-20 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300">
                    <?php if($brand['logo']): ?>
                        <img src="<?php echo e($brand['logo']); ?>" alt="<?php echo e($brand['name']); ?>" class="max-h-12 sm:max-h-16 max-w-[120px] object-contain" loading="lazy" />
                    <?php else: ?>
                        <span class="text-xl sm:text-2xl font-bold text-secondary-400 dark:text-secondary-500 whitespace-nowrap"><?php echo e($brand['name']); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex-shrink-0 mx-8 sm:mx-12 flex items-center justify-center h-16 sm:h-20 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300">
                    <?php if($brand['logo']): ?>
                        <img src="<?php echo e($brand['logo']); ?>" alt="<?php echo e($brand['name']); ?>" class="max-h-12 sm:max-h-16 max-w-[120px] object-contain" loading="lazy" />
                    <?php else: ?>
                        <span class="text-xl sm:text-2xl font-bold text-secondary-400 dark:text-secondary-500 whitespace-nowrap"><?php echo e($brand['name']); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH /home/nischal/Nischal/Projects/Single_Vendor_Application/resources/views/frontend/home/top-brands.blade.php ENDPATH**/ ?>