<?php $__env->startSection('title', 'Home - NBK Vertex'); ?>

<?php
    $sortedSections = $sections->sortBy('sort_order');
?>

<?php $__env->startSection('content'); ?>
<?php $__currentLoopData = $sortedSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $slug = $section->slug;
    ?>

    <?php if($slug === 'hero-carousel'): ?>
        <?php echo $__env->make('frontend.home.hero-carousel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'trust-bar'): ?>
        <?php echo $__env->make('frontend.home.trust-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('frontend.partials.banner-promotional', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'shop-by-category'): ?>
        <?php echo $__env->make('frontend.home.shop-by-category', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'featured-products'): ?>
        <?php echo $__env->make('frontend.home.featured-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('frontend.partials.banner-featured-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'new-arrivals'): ?>
        <?php echo $__env->make('frontend.home.new-arrivals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'trending-products'): ?>
        <?php echo $__env->make('frontend.home.trending-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'flash-sale'): ?>
        <?php echo $__env->make('frontend.home.flash-sale', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'best-sellers'): ?>
        <?php echo $__env->make('frontend.home.best-sellers', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'recommended-products'): ?>
        <?php echo $__env->make('frontend.home.recommended-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'popular-products'): ?>
        <?php echo $__env->make('frontend.home.popular-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('frontend.partials.banner-middle', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'top-brands'): ?>
        <?php echo $__env->make('frontend.home.top-brands', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'why-choose-us'): ?>
        <?php echo $__env->make('frontend.home.why-choose-us', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'testimonials'): ?>
        <?php echo $__env->make('frontend.home.testimonials', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'newsletter-cta'): ?>
        <?php echo $__env->make('frontend.home.newsletter-cta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($slug === 'instagram-gallery'): ?>
        <?php echo $__env->make('frontend.home.instagram-gallery', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php echo $__env->make('frontend.partials.banner-bottom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\home.blade.php ENDPATH**/ ?>