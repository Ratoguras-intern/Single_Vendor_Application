<?php
    $section = $sections->get('testimonials');
    $testimonialItems = $section?->config['testimonials'] ?? [];
?>

<?php if(!empty($testimonialItems)): ?>
<section class="py-12 sm:py-16 lg:py-20">
    <div class="section">
        <div class="text-center mb-12">
            <h2 class="section-heading"><?php echo e($section?->title ?? 'What Our Customers Say'); ?></h2>
            <p class="section-subheading"><?php echo e($section?->subtitle ?? 'Real reviews from real people'); ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php $__currentLoopData = $testimonialItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="testimonial-card">
                    <div class="flex items-center gap-1 mb-4">
                        <?php for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++): ?>
                            <svg class="h-5 w-5 text-primary-500 fill-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"/></svg>
                        <?php endfor; ?>
                    </div>

                    <p class="text-secondary-600 dark:text-secondary-400 leading-relaxed mb-6">"<?php echo e($testimonial['review']); ?>"</p>

                    <div class="flex items-center gap-3 pt-4 border-t border-secondary-100 dark:border-secondary-700/50">
                        <img src="<?php echo e($testimonial['avatar']); ?>" alt="<?php echo e($testimonial['name']); ?>" class="h-10 w-10 rounded-full object-cover" loading="lazy" />
                        <div>
                            <p class="text-sm font-semibold text-secondary-900 dark:text-white"><?php echo e($testimonial['name']); ?></p>
                            <p class="text-xs text-secondary-500 dark:text-secondary-400"><?php echo e($testimonial['role'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH /home/nischal/Nischal/Projects/Single_Vendor_Application/resources/views/frontend/home/testimonials.blade.php ENDPATH**/ ?>