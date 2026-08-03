@php
    $section = $sections->get('hero-carousel');

    $staticSlides = $section?->config['slides'] ?? [];

    if (empty($staticSlides)) {
        $staticSlides = [
            ['badge' => 'NEW', 'badge_color' => 'bg-green-500', 'heading' => 'Step Into Style', 'description' => 'Discover our latest collection.', 'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80', 'mobile_image' => null, 'text_alignment' => 'left', 'text_alignment_class' => 'text-left items-start', 'text_color' => 'text-white', 'overlay_opacity' => 40, 'image_position' => 'center', 'overlay_enabled' => true, 'overlay_color' => '#000000', 'overlay_rgba' => 'rgba(0,0,0,0.4)', 'image_fit' => 'cover', 'image_repeat' => 'no-repeat', 'image_size' => 'auto', 'image_width' => null, 'image_height' => null, 'background_css' => 'background-size: cover; background-position: center; background-repeat: no-repeat;', 'banner_height_css' => '', 'border_radius_css' => '', 'content_padding_css' => '', 'section_margin_css' => '', 'content_vertical' => null, 'text_width_css' => '', 'visibility_classes' => '', 'show_countdown' => false, 'ends_at' => null, 'cta_primary' => 'Shop Now', 'cta_secondary' => 'Learn More', 'link_primary' => '/shop', 'link_secondary' => '/about'],
        ];
    }

    $bannerSlides = !empty($heroBanners) && $heroBanners->isNotEmpty()
        ? $heroBanners->map(function ($b) {
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
                'image_position' => $b->image_position ?? 'center',
                'overlay_enabled' => $b->overlay_enabled,
                'overlay_color' => $b->overlay_color,
                'overlay_rgba' => $b->overlay_rgba,
                'background_css' => $b->background_css,
                'banner_height_css' => $b->banner_height_css,
                'border_radius_css' => $b->border_radius_css,
                'content_padding_css' => $b->content_padding_css,
                'section_margin_css' => $b->section_margin_css,
                'content_vertical' => $b->content_vertical,
                'text_width_css' => $b->text_width_css,
                'visibility_classes' => $b->visibility_classes,
                'show_countdown' => $b->show_countdown,
                'ends_at' => $b->ends_at?->toIso8601String(),
                'cta_primary' => $b->button_text ?? 'Shop Now',
                'cta_secondary' => $b->secondary_button_text ?? '',
                'link_primary' => $b->link ?? '/shop',
                'link_secondary' => $b->secondary_button_url ?? '#',
            ];
        })->toArray()
        : [];

    $slides = array_merge($staticSlides, $bannerSlides);

    $slides = array_map(function ($s) {
        return array_merge([
            'mobile_image' => null,
            'text_alignment' => 'left',
            'text_alignment_class' => 'text-left items-start',
            'text_color' => 'text-white',
            'overlay_opacity' => 40, 'image_position' => 'center',
            'overlay_enabled' => true,
            'overlay_color' => '#000000',
            'overlay_rgba' => 'rgba(0,0,0,0.4)',
            'background_css' => 'background-size: cover; background-position: center; background-repeat: no-repeat;',
            'banner_height_css' => '',
            'border_radius_css' => '',
            'content_padding_css' => '',
            'section_margin_css' => '',
            'content_vertical' => null,
            'text_width_css' => '',
            'visibility_classes' => '',
            'show_countdown' => false,
            'ends_at' => null,
            'cta_secondary' => '',
            'link_secondary' => '#',
        ], $s);
    }, $slides);

    $slides = array_map(function ($s) {
        $s['content_vertical_class'] = ($s['content_vertical'] ?? null) === 'top'
            ? 'items-start'
            : (($s['content_vertical'] ?? null) === 'bottom' ? 'items-end' : 'items-center');

        return $s;
    }, $slides);

    $heroContainerStyle = '';
    $heroSectionMargin = '';
    foreach ($slides as $slide) {
        $style = trim(($slide['banner_height_css'] ?? '') . ' ' . ($slide['border_radius_css'] ?? ''));
        if ($style) {
            $heroContainerStyle = $style;
        }
        if (!empty($slide['section_margin_css'])) {
            $heroSectionMargin = $slide['section_margin_css'];
        }
        if ($heroContainerStyle && $heroSectionMargin) {
            break;
        }
    }

    if (empty($slides)) {
        $slides = [
            ['badge' => 'NEW', 'badge_color' => 'bg-green-500', 'heading' => 'Step Into Style', 'description' => 'Discover our latest collection.', 'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80', 'mobile_image' => null, 'text_alignment' => 'left', 'text_alignment_class' => 'text-left items-start', 'text_color' => 'text-white', 'overlay_opacity' => 40, 'image_position' => 'center', 'overlay_enabled' => true, 'overlay_color' => '#000000', 'overlay_rgba' => 'rgba(0,0,0,0.4)', 'image_fit' => 'cover', 'image_repeat' => 'no-repeat', 'image_size' => 'auto', 'image_width' => null, 'image_height' => null, 'background_css' => 'background-size: cover; background-position: center; background-repeat: no-repeat;', 'banner_height_css' => '', 'border_radius_css' => '', 'content_padding_css' => '', 'section_margin_css' => '', 'content_vertical' => null, 'text_width_css' => '', 'visibility_classes' => '', 'show_countdown' => false, 'ends_at' => null, 'cta_primary' => 'Shop Now', 'cta_secondary' => 'Learn More', 'link_primary' => '/shop', 'link_secondary' => '/about'],
        ];
    }
@endphp

<section
    x-data="{
        current: 0,
        total: {{ count($slides) }},
        paused: false,
        timer: null,
        touchX: 0,
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
        touchStart(e) { this.touchX = e.touches[0].clientX; },
        touchEnd(e) { const dx = e.changedTouches[0].clientX - this.touchX; if (Math.abs(dx) > 50) { dx < 0 ? this.next() : this.prev(); this.startAuto(); } },
    }"
    @touchstart.passive="touchStart($event)"
    @touchend.passive="touchEnd($event)"
    class="section pt-6 sm:pt-8 lg:pt-10 pb-4 sm:pb-6 lg:pb-8"
    @if($heroSectionMargin) style="{{ $heroSectionMargin }}" @endif
>
    <div class="relative w-full min-h-[280px] sm:min-h-[350px] md:min-h-[400px] lg:min-h-[450px] xl:min-h-[500px] bg-secondary-900 overflow-hidden rounded-[22px] shadow-mega" @if($heroContainerStyle) style="{{ $heroContainerStyle }}" @endif>
    @foreach($slides as $i => $slide)
        <div
            class="hero-slide {{ $slide['visibility_classes'] }}"
            :class="{ 'opacity-100 z-10': current === {{ $i }}, 'opacity-0 z-0 pointer-events-none': current !== {{ $i }} }"
        >
            <div class="hero-slide-bg hidden md:block" :class="current === {{ $i }} ? 'ken-burns' : ''" style="background-image: url('{{ $slide['image'] }}'); {{ $slide['background_css'] }}"></div>
            <div class="hero-slide-bg md:hidden" :class="current === {{ $i }} ? 'ken-burns' : ''" style="background-image: url('{{ $slide['mobile_image'] ?: $slide['image'] }}'); {{ $slide['background_css'] }}"></div>
            @if($slide['overlay_enabled'])
                <div class="absolute inset-0 z-[1]" style="background: linear-gradient(to right, {{ $slide['overlay_rgba'] }} {{ $slide['overlay_opacity'] > 50 ? '60%' : '40%' }}, rgba(0,0,0,0.1) 100%);"></div>
            @endif
            <div class="section relative z-10 w-full h-full flex {{ $slide['content_vertical_class'] }}" @if($slide['content_padding_css']) style="{{ $slide['content_padding_css'] }}" @endif>
                <div class="w-full flex flex-col {{ $slide['text_alignment_class'] }} {{ !empty($slide['text_width_css']) && $slide['text_alignment'] === 'center' ? 'mx-auto' : '' }} {{ !empty($slide['text_width_css']) && $slide['text_alignment'] === 'right' ? 'ml-auto' : '' }}" @if($slide['text_width_css']) style="{{ $slide['text_width_css'] }}" @endif>
                    @if($slide['badge'])
                        <span class="inline-flex items-center gap-2 rounded-full {{ $slide['badge_color'] }} px-4 py-1.5 mb-5 self-start" :class="current === {{ $i }} ? 'animate-in' : 'opacity-0'">
                            <span class="text-xs font-bold text-white tracking-wider">{{ $slide['badge'] }}</span>
                        </span>
                    @endif
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight leading-tight {{ $slide['text_alignment'] === 'center' ? 'mx-auto' : '' }} {{ $slide['text_alignment'] === 'right' ? 'ml-auto' : '' }} {{ $slide['text_color'] }}" :class="current === {{ $i }} ? 'animate-in' : 'opacity-0'">{{ $slide['heading'] }}</h1>
                    <p class="mt-4 sm:mt-5 text-base sm:text-lg lg:text-xl max-w-xl leading-relaxed {{ $slide['text_alignment'] === 'center' ? 'mx-auto' : '' }} {{ $slide['text_alignment'] === 'right' ? 'ml-auto' : '' }} {{ $slide['text_color'] }}/80" :class="current === {{ $i }} ? 'animate-in-delay-1' : 'opacity-0'">{{ $slide['description'] }}</p>
                    @if($slide['show_countdown'] && $slide['ends_at'])
                        <div class="mt-6 sm:mt-7" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'" x-data="bannerCountdown('{{ $slide['ends_at'] }}', true)" x-init="init()" x-show="show">
                            <div class="flex flex-wrap gap-2 sm:gap-3">
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="days"></span><span class="text-[10px] uppercase text-white/60">Days</span></div>
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="hours"></span><span class="text-[10px] uppercase text-white/60">Hours</span></div>
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="minutes"></span><span class="text-[10px] uppercase text-white/60">Mins</span></div>
                                <div class="countdown-unit"><span class="text-lg sm:text-2xl font-bold text-white" x-text="seconds"></span><span class="text-[10px] uppercase text-white/60">Secs</span></div>
                            </div>
                        </div>
                    @endif
                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row w-full sm:w-auto items-start gap-4 {{ $slide['text_alignment'] === 'center' ? 'mx-auto' : '' }} {{ $slide['text_alignment'] === 'right' ? 'ml-auto' : '' }}" :class="current === {{ $i }} ? 'animate-in-delay-3' : 'opacity-0'">
                        <a href="{{ $slide['link_primary'] }}" class="btn-primary btn-lg w-full sm:w-auto justify-center shadow-lg shadow-primary-500/25">
                            {{ $slide['cta_primary'] }}
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                        @if(!empty($slide['cta_secondary']))
                            <a href="{{ $slide['link_secondary'] }}" class="btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-lg w-full sm:w-auto justify-center backdrop-blur-sm">{{ $slide['cta_secondary'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button x-on:click="prev()" aria-label="Previous slide" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
    </button>
    <button x-on:click="next()" aria-label="Next slide" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
    </button>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2.5">
        @foreach($slides as $i => $slide)
            <button x-on:click="goTo({{ $i }})" :aria-label="'Go to slide ' + ({{ $i }} + 1)" :aria-current="current === {{ $i }} ? 'true' : undefined" :class="current === {{ $i }} ? 'w-8 bg-primary-500' : 'w-2.5 bg-white/50 hover:bg-white/75'" class="h-2.5 rounded-full transition-all duration-300"></button>
        @endforeach
    </div>
    </div>
</section>