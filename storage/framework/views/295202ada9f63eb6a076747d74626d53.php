<?php $__env->startSection('title', 'My Favorites - NBK Vertex'); ?>

<?php $__env->startSection('content'); ?>
<script>
    window.location.href = '<?php echo e(route("frontend.favorites")); ?>';
</script>
<div class="section py-8">
    <div class="py-32 text-center">
        <p class="text-secondary-500 dark:text-secondary-400">Redirecting to favorites...</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\wishlist.blade.php ENDPATH**/ ?>