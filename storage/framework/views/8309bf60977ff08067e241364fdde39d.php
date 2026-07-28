<?php if(!empty($bottomBanners) && $bottomBanners->isNotEmpty()): ?>
    <?php $__currentLoopData = $bottomBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $textColor = $banner->text_color ?? 'text-white';
            $alignClass = $banner->text_alignment_class;
            $overlayRatio = $banner->overlay_opacity !== null ? $banner->overlay_opacity / 100 : null;
            $overlayStyle = $overlayRatio !== null ? "rgba(0,0,0,{$overlayRatio})" : null;
            $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
            $endDate = $banner->ends_at?->toIso8601String();
        ?>
        <section class="border-t border-secondary-200 dark:border-secondary-800"
            x-data="bannerCountdown('<?php echo e($endDate); ?>', <?php echo e($autoHide); ?>)"
            x-init="init()"
            x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="section py-0">
                <a href="<?php echo e($banner->link ?? '#'); ?>" class="group relative block overflow-hidden bg-secondary-900 min-h-[160px] sm:min-h-[200px] lg:min-h-[250px]">
                    <?php if($banner->image_url): ?>
                        <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 hidden md:block" loading="lazy">
                    <?php endif; ?>
                    <?php if($banner->mobile_image_url): ?>
                        <img src="<?php echo e($banner->mobile_image_url); ?>" alt="<?php echo e($banner->title); ?>" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 md:hidden" loading="lazy">
                    <?php endif; ?>
                    <div class="absolute inset-0" <?php if($overlayStyle): ?> style="background: linear-gradient(to right, <?php echo e($overlayStyle); ?> 40%, transparent 100%);" <?php else: ?> class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent" <?php endif; ?>></div>
                    <div class="relative h-full flex items-center px-6 sm:px-10 lg:px-14 py-8 sm:py-10">
                        <div class="max-w-xl flex flex-col <?php echo e($alignClass); ?>">
                            <?php if($banner->badge): ?>
                                <span class="inline-flex items-center rounded-full <?php echo e($banner->badge_color ?? 'bg-primary-500'); ?> px-3 py-1 mb-3 <?php echo e($banner->text_alignment === 'center' ? 'mx-auto' : ''); ?> <?php echo e($banner->text_alignment === 'right' ? 'ml-auto' : ''); ?>">
                                    <span class="text-xs font-bold text-white tracking-wider"><?php echo e($banner->badge); ?></span>
                                </span>
                            <?php endif; ?>
                            <?php if($banner->title): ?>
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold leading-tight <?php echo e($textColor); ?>"><?php echo e($banner->title); ?></h2>
                            <?php endif; ?>
                            <?php if($banner->description): ?>
                                <p class="mt-2 text-sm sm:text-base max-w-lg <?php echo e(str_replace('text-', 'text-', $textColor)); ?>/80"><?php echo e($banner->description); ?></p>
                            <?php elseif($banner->subtitle): ?>
                                <p class="mt-2 text-sm sm:text-base max-w-lg <?php echo e(str_replace('text-', 'text-', $textColor)); ?>/80"><?php echo e($banner->subtitle); ?></p>
                            <?php endif; ?>
                            <?php if($banner->button_text || $banner->secondary_button_text): ?>
                                <div class="mt-4 flex flex-wrap gap-3 <?php echo e($banner->text_alignment === 'center' ? 'justify-center' : ''); ?> <?php echo e($banner->text_alignment === 'right' ? 'justify-end' : ''); ?>">
                                    <?php if($banner->button_text): ?>
                                        <span class="inline-flex items-center gap-2 btn-primary btn-sm">
                                            <?php echo e($banner->button_text); ?>

                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                        </span>
                                    <?php endif; ?>
                                    <?php if($banner->secondary_button_text): ?>
                                        <span class="inline-flex items-center gap-2 btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-sm backdrop-blur-sm">
                                            <?php echo e($banner->secondary_button_text); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($banner->show_countdown && $banner->ends_at): ?>
                                <div class="mt-5" x-show="show">
                                    <div class="flex gap-2">
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="days"></span><span class="text-[10px] uppercase <?php echo e(str_replace('text-', 'text-', $textColor)); ?>/60">Days</span></div>
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="hours"></span><span class="text-[10px] uppercase <?php echo e(str_replace('text-', 'text-', $textColor)); ?>/60">Hours</span></div>
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="minutes"></span><span class="text-[10px] uppercase <?php echo e(str_replace('text-', 'text-', $textColor)); ?>/60">Mins</span></div>
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="seconds"></span><span class="text-[10px] uppercase <?php echo e(str_replace('text-', 'text-', $textColor)); ?>/60">Secs</span></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\partials\banner-bottom.blade.php ENDPATH**/ ?>