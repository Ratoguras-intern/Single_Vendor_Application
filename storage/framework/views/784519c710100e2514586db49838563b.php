<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['position' => 'promotional', 'page' => null, 'limit' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['position' => 'promotional', 'page' => null, 'limit' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $banners = $page
        ? \App\Models\Banner::forTargetPage($page)->active()->ordered()->get()
        : \App\Models\Banner::getCachedForPosition($position);

    if ($limit && $banners->isNotEmpty()) {
        $banners = $banners->take($limit);
    }
?>

<?php if($banners->isNotEmpty()): ?>
    <section class="py-8 sm:py-10 lg:py-12">
        <div class="section flex flex-col gap-6 sm:gap-8">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $textColor = $banner->text_color ?? 'text-white';
                    $alignClass = $banner->text_alignment_class;
                    $overlay = $banner->overlay_opacity !== null ? $banner->overlay_opacity / 100 : 0.45;
                    $endDate = $banner->ends_at?->toIso8601String();
                    $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
                ?>
                <article
                    class="banner-card banner-card--<?php echo e($banner->text_alignment ?? 'left'); ?>"
                    style="--banner-overlay: rgba(0,0,0,<?php echo e($overlay); ?>);"
                    x-data="bannerCountdown('<?php echo e($endDate); ?>', <?php echo e($autoHide); ?>)"
                    x-init="init()"
                    x-show="show"
                >
                    <?php if($banner->image_url): ?>
                        <div class="banner-card__bg hidden md:block" style="--banner-image: url('<?php echo e($banner->image_url); ?>')" aria-hidden="true"></div>
                    <?php endif; ?>
                    <?php if($banner->mobile_image_url || $banner->image_url): ?>
                        <div class="banner-card__bg md:hidden" style="--banner-image: url('<?php echo e($banner->mobile_image_url ?: $banner->image_url); ?>')" aria-hidden="true"></div>
                    <?php endif; ?>

                    <div class="banner-card__overlay" aria-hidden="true"></div>

                    <div class="banner-card__content">
                        <div class="banner-card__inner <?php echo e($alignClass); ?>">
                            <?php if($banner->badge): ?>
                                <span class="inline-flex items-center rounded-full <?php echo e($banner->badge_color ?? 'bg-primary-500'); ?> px-3 py-1 mb-3 <?php echo e($banner->text_alignment === 'center' ? 'mx-auto' : ''); ?> <?php echo e($banner->text_alignment === 'right' ? 'ml-auto' : ''); ?>">
                                    <span class="text-xs font-bold text-white tracking-wider"><?php echo e($banner->badge); ?></span>
                                </span>
                            <?php endif; ?>
                            <?php if($banner->title): ?>
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight <?php echo e($textColor); ?>"><?php echo e($banner->title); ?></h2>
                            <?php endif; ?>
                            <?php if($banner->subtitle): ?>
                                <p class="mt-2 text-sm sm:text-base font-medium <?php echo e($textColor); ?>/70"><?php echo e($banner->subtitle); ?></p>
                            <?php endif; ?>
                            <?php if($banner->description): ?>
                                <p class="mt-2 text-sm sm:text-base max-w-lg <?php echo e($textColor); ?>/80"><?php echo e($banner->description); ?></p>
                            <?php endif; ?>
                            <?php if($banner->show_countdown && $banner->ends_at): ?>
                                <div class="mt-5 flex gap-2">
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="days"></span><span class="text-[10px] uppercase <?php echo e($textColor); ?>/60">Days</span></div>
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="hours"></span><span class="text-[10px] uppercase <?php echo e($textColor); ?>/60">Hours</span></div>
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="minutes"></span><span class="text-[10px] uppercase <?php echo e($textColor); ?>/60">Mins</span></div>
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold <?php echo e($textColor); ?>" x-text="seconds"></span><span class="text-[10px] uppercase <?php echo e($textColor); ?>/60">Secs</span></div>
                                </div>
                            <?php endif; ?>
                            <?php if($banner->button_text || $banner->secondary_button_text): ?>
                                <div class="mt-5 flex flex-wrap gap-3 <?php echo e($banner->text_alignment === 'center' ? 'justify-center' : ''); ?> <?php echo e($banner->text_alignment === 'right' ? 'justify-end' : 'justify-start'); ?>">
                                    <?php if($banner->button_text): ?>
                                        <a href="<?php echo e($banner->link ?? '#'); ?>" class="inline-flex items-center gap-2 btn-primary btn-sm">
                                            <?php echo e($banner->button_text); ?>

                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if($banner->secondary_button_text): ?>
                                        <a href="<?php echo e($banner->secondary_button_url ?? '#'); ?>" class="inline-flex items-center gap-2 btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-sm backdrop-blur-sm">
                                            <?php echo e($banner->secondary_button_text); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/components/frontend/banner.blade.php ENDPATH**/ ?>