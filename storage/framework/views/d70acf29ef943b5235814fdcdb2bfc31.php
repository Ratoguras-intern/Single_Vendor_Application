<?php if(isset($breadcrumbs) && count($breadcrumbs) > 0): ?>
<nav class="mb-6 flex items-center text-sm text-gray-500 dark:text-gray-400">
    <ol class="flex items-center gap-1.5 flex-wrap">
        <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($index > 0): ?>
                <li class="flex items-center">
                    <svg class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                </li>
            <?php endif; ?>
            <li class="flex items-center">
                <?php if($loop->last || empty($crumb['url'])): ?>
                    <span class="font-medium text-gray-800 dark:text-white"><?php echo e($crumb['label']); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($crumb['url']); ?>" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors"><?php echo e($crumb['label']); ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\layouts\partials\breadcrumbs.blade.php ENDPATH**/ ?>