<?php if(!empty($sidebarBanners) && $sidebarBanners->isNotEmpty()): ?>
    <?php $__currentLoopData = $sidebarBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $textColor = $banner->text_color ?? 'text-white';
            $overlayRatio = $banner->overlay_opacity !== null ? $banner->overlay_opacity / 100 : null;
            $overlayStyle = $overlayRatio !== null ? "rgba(0,0,0,{$overlayRatio})" : null;
            $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
            $endDate = $banner->ends_at?->toIso8601String();
        ?>
        <div class="mb-6"
            x-data="bannerCountdown('<?php echo e($endDate); ?>', <?php echo e($autoHide); ?>)"
            x-init="init()"
            x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <a href="<?php echo e($banner->link ?? '#'); ?>" class="group relative block overflow-hidden rounded-card bg-secondary-900 aspect-[4/5] sm:aspect-[3/4] lg:aspect-[4/3]">
                <?php if($banner->image_url): ?>
                    <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 hidden md:block" loading="lazy">
                <?php endif; ?>
                <?php if($banner->mobile_image_url): ?>
                    <img src="<?php echo e($banner->mobile_image_url); ?>" alt="<?php echo e($banner->title); ?>" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 md:hidden" loading="lazy">
                <?php elseif($banner->image_url): ?>
                    <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 md:hidden" loading="lazy">
                <?php endif; ?>
                <div class="absolute inset-0" <?php if($overlayStyle): ?> style="background: linear-gradient(to top, <?php echo e($overlayStyle); ?> 60%, transparent 100%);" <?php else: ?> class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent" <?php endif; ?>></div>
                <div class="relative h-full flex flex-col justify-end p-4">
                    <div class="flex flex-col <?php echo e($banner->text_alignment_class); ?>">
                        <?php if($banner->badge): ?>
                            <span class="inline-flex items-center self-start rounded-full <?php echo e($banner->badge_color ?? 'bg-primary-500'); ?> px-2 py-0.5 mb-2">
                                <span class="text-xs font-bold text-white"><?php echo e($banner->badge); ?></span>
                            </span>
                        <?php endif; ?>
                        <?php if($banner->title): ?>
                            <h3 class="text-sm font-bold <?php echo e($textColor); ?>"><?php echo e($banner->title); ?></h3>
                        <?php endif; ?>
                        <?php if($banner->description): ?>
                            <p class="text-xs mt-0.5 <?php echo e($textColor); ?>/80"><?php echo e($banner->description); ?></p>
                        <?php elseif($banner->subtitle): ?>
                            <p class="text-xs mt-0.5 <?php echo e($textColor); ?>/80"><?php echo e($banner->subtitle); ?></p>
                        <?php endif; ?>
                        <?php if($banner->show_countdown && $banner->ends_at): ?>
                            <div class="mt-2" x-show="show">
                                <div class="flex gap-1">
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold <?php echo e($textColor); ?>" x-text="days"></span><span class="text-[8px] uppercase <?php echo e($textColor); ?>/60">D</span></div>
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold <?php echo e($textColor); ?>" x-text="hours"></span><span class="text-[8px] uppercase <?php echo e($textColor); ?>/60">H</span></div>
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold <?php echo e($textColor); ?>" x-text="minutes"></span><span class="text-[8px] uppercase <?php echo e($textColor); ?>/60">M</span></div>
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold <?php echo e($textColor); ?>" x-text="seconds"></span><span class="text-[8px] uppercase <?php echo e($textColor); ?>/60">S</span></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/frontend/partials/banner-sidebar.blade.php ENDPATH**/ ?>