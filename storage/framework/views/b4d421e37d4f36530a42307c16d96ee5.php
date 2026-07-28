<?php
    $section = $sections->get('instagram-gallery');
    $galleryImages = $section?->config['images'] ?? [
        ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Watch'],
        ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80', 'span' => 'col-span-1 row-span-2', 'alt' => 'Headphones'],
        ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Sneakers'],
        ['url' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Camera'],
        ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&q=80', 'span' => 'col-span-1 row-span-2', 'alt' => 'Fashion'],
        ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Sunglasses'],
    ];
?>

<?php if(!empty($galleryImages)): ?>
<section class="py-12 sm:py-16 lg:py-20">
    <div class="section">
        <div class="text-center mb-10">
            <h2 class="section-heading"><?php echo e($section?->title ?? 'Follow Us on Instagram'); ?></h2>
            <p class="section-subheading"><?php echo e($section?->subtitle ?? '@nbkvertex'); ?></p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4" style="grid-auto-rows: 200px;">
            <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="#" target="_blank" rel="noopener noreferrer" class="gallery-item group <?php echo e($img['span']); ?>">
                    <img src="<?php echo e($img['url']); ?>" alt="<?php echo e($img['alt']); ?>" loading="lazy" />
                    <div class="gallery-overlay">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/frontend/home/instagram-gallery.blade.php ENDPATH**/ ?>