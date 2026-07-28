<?php
    $section = $sections->get('hero-carousel');

    if (!empty($heroBanners) && $heroBanners->isNotEmpty()) {
        $slides = $heroBanners->map(function ($b) {
            return [
                'badge' => $b->badge ?? 'NEW',
                'badge_color' => $b->badge_color ?? 'bg-green-500',
                'heading' => $b->title ?? '',
                'description' => $b->description ?? $b->subtitle ?? '',
                'image' => $b->image_url ?? asset('frontend-assets/images/no-image.jpg'),
                'mobile_image' => $b->mobile_image_url ?? null,
                'text_alignment' => $b->text_alignment ?? 'left',
                'text_alignment_class' => $b->text_alignment_class,
                'text_color' => $b->text_color ?? 'text-white',
                'overlay_opacity' => $b->overlay_opacity ?? 40,
                'show_countdown' => $b->show_countdown,
                'ends_at' => $b->ends_at?->toIso8601String(),
                'cta_primary' => $b->button_text ?? 'Shop Now',
                'cta_secondary' => $b->secondary_button_text ?? '',
                'link_primary' => $b->link ?? '/shop',
                'link_secondary' => $b->secondary_button_url ?? '#',
            ];
        })->toArray();
    } else {
        $slides = $section?->config['slides'] ?? [
            ['badge' => 'NEW', 'badge_color' => 'bg-green-500', 'heading' => 'Step Into Style', 'description' => 'Discover our latest collection.', 'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80', 'mobile_image' => null, 'text_alignment' => 'left', 'text_alignment_class' => 'text-left items-start', 'text_color' => 'text-white', 'overlay_opacity' => 40, 'show_countdown' => false, 'ends_at' => null, 'cta_primary' => 'Shop Now', 'cta_secondary' => 'Learn More', 'link_primary' => '/shop', 'link_secondary' => '/about'],
        ];
    }

    $slides = array_map(function ($s) {
        return array_merge([
            'mobile_image' => null,
            'text_alignment' => 'left',
            'text_alignment_class' => 'text-left items-start',
            'text_color' => 'text-white',
            'overlay_opacity' => 40,
            'show_countdown' => false,
            'ends_at' => null,
            'cta_secondary' => '',
            'link_secondary' => '#',
        ], $s);
    }, $slides);
?>

<section
    x-data="{
        current: 0,
        total: <?php echo e(count($slides)); ?>,
        paused: false,
        timer: null,
        init() {
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.startAuto();
            }
            this.$el.addEventListener('mouseenter', () => { this.paused = true; clearInterval(this.timer); });
            this.$el.addEventListener('mouseleave', () => { this.paused = false; this.startAuto(); });
        },
        startAuto() { clearInterval(this.timer); this.timer = setInterval(() => { if (!this.paused) this.next(); }, 5000); },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
        goTo(i) { this.current = i; this.startAuto(); },
    }"
    class="relative min-h-[280px] sm:min-h-[350px] md:min-h-[400px] lg:min-h-[450px] xl:min-h-[500px] overflow-hidden bg-secondary-900"
>
    <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div
            class="hero-slide"
            :class="{ 'opacity-100 z-10': current === <?php echo e($i); ?>, 'opacity-0 z-0': current !== <?php echo e($i); ?> }"
        >
            <img src="<?php echo e($slide['image']); ?>" alt="<?php echo e($slide['heading']); ?>" class="absolute inset-0 w-full h-full object-cover object-center hidden md:block" loading="<?php echo e($i === 0 ? 'eager' : 'lazy'); ?>" />
            <?php if($slide['mobile_image']): ?>
                <img src="<?php echo e($slide['mobile_image']); ?>" alt="<?php echo e($slide['heading']); ?>" class="absolute inset-0 w-full h-full object-cover object-center md:hidden" loading="lazy" />
            <?php else: ?>
                <img src="<?php echo e($slide['image']); ?>" alt="<?php echo e($slide['heading']); ?>" class="absolute inset-0 w-full h-full object-cover object-center md:hidden" loading="lazy" />
            <?php endif; ?>
            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(0,0,0,<?php echo e($slide['overlay_opacity'] / 100); ?>) <?php echo e($slide['overlay_opacity'] > 50 ? '60%' : '40%'); ?>, rgba(0,0,0,0.1) 100%);"></div>
            <div class="section relative h-full flex items-center">
                <div class="max-w-2xl animate-in flex flex-col <?php echo e($slide['text_alignment_class']); ?>">
                    <?php if($slide['badge']): ?>
                        <span class="inline-flex items-center gap-2 rounded-full <?php echo e($slide['badge_color']); ?> px-4 py-1.5 mb-6 self-start">
                            <span class="text-xs font-bold text-white tracking-wider"><?php echo e($slide['badge']); ?></span>
                        </span>
                    <?php endif; ?>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight <?php echo e($slide['text_alignment'] === 'center' ? 'mx-auto' : ''); ?> <?php echo e($slide['text_alignment'] === 'right' ? 'ml-auto' : ''); ?> <?php echo e($slide['text_color']); ?>"><?php echo e($slide['heading']); ?></h1>
                    <p class="mt-6 text-lg sm:text-xl max-w-xl leading-relaxed <?php echo e($slide['text_alignment'] === 'center' ? 'mx-auto' : ''); ?> <?php echo e($slide['text_alignment'] === 'right' ? 'ml-auto' : ''); ?> <?php echo e(str_replace('text-', 'text-', $slide['text_color'])); ?>/80"><?php echo e($slide['description']); ?></p>
                    <div class="mt-10 flex flex-col sm:flex-row items-start gap-4 <?php echo e($slide['text_alignment'] === 'center' ? 'mx-auto' : ''); ?> <?php echo e($slide['text_alignment'] === 'right' ? 'ml-auto' : ''); ?>">
                        <a href="<?php echo e($slide['link_primary']); ?>" class="btn-primary btn-lg shadow-lg shadow-primary-500/25">
                            <?php echo e($slide['cta_primary']); ?>

                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <?php if(!empty($slide['cta_secondary'])): ?>
                            <a href="<?php echo e($slide['link_secondary']); ?>" class="btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-lg backdrop-blur-sm"><?php echo e($slide['cta_secondary']); ?></a>
                        <?php endif; ?>
                    </div>
                    <?php if($slide['show_countdown'] && $slide['ends_at']): ?>
                        <div class="mt-8" x-data="bannerCountdown('<?php echo e($slide['ends_at']); ?>')" x-init="init()" x-show="show">
                            <div class="flex gap-2 sm:gap-3">
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="days"></span><span class="text-[10px] uppercase text-white/60">Days</span></div>
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="hours"></span><span class="text-[10px] uppercase text-white/60">Hours</span></div>
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="minutes"></span><span class="text-[10px] uppercase text-white/60">Mins</span></div>
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="seconds"></span><span class="text-[10px] uppercase text-white/60">Secs</span></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <button x-on:click="prev()" aria-label="Previous slide" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
    </button>
    <button x-on:click="next()" aria-label="Next slide" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
    </button>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2.5">
        <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button x-on:click="goTo(<?php echo e($i); ?>)" :aria-label="'Go to slide ' + (<?php echo e($i); ?> + 1)" :aria-current="current === <?php echo e($i); ?> ? 'true' : undefined" :class="current === <?php echo e($i); ?> ? 'w-8 bg-primary-500' : 'w-2.5 bg-white/50 hover:bg-white/75'" class="h-2.5 rounded-full transition-all duration-300"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\home\hero-carousel.blade.php ENDPATH**/ ?>