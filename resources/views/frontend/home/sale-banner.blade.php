@php
    $section = $sections->get('sale-banner');
    $banners = $saleBanners ?? collect();

    $autoplay = (bool) ($section?->getConfig('autoplay', true) ?? true);
    $transitionSpeed = max(1500, min(30000, (int) ($section?->getConfig('transition_speed', 5000) ?? 5000)));
    $pauseOnHover = (bool) ($section?->getConfig('pause_on_hover', true) ?? true);
    $total = $banners->count();

    $verticalClasses = ['top' => 'justify-start', 'center' => 'justify-center', 'bottom' => 'justify-end'];
@endphp

@if($total > 0)
<section
    x-data="{
        current: 0,
        total: {{ $total }},
        active: new Set(Array.from({length: {{ $total }}}, (_, i) => i)),
        paused: false,
        timer: null,
        touchX: 0,
        init() {
            this.$nextTick(() => {
                this.$el.addEventListener('banner-expired', (e) => this.slideExpired(e.detail.index));
                if (this.active.size > 0 && {{ $autoplay ? 'true' : 'false' }} && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    this.startAuto();
                }
                if ({{ $pauseOnHover ? 'true' : 'false' }}) {
                    this.$el.addEventListener('mouseenter', () => { this.paused = true; clearInterval(this.timer); });
                    this.$el.addEventListener('mouseleave', () => { this.paused = false; this.startAuto(); });
                }
            });
        },
        startAuto() {
            if (this.active.size === 0) return;
            if ({{ $autoplay ? 'true' : 'false' }}) {
                clearInterval(this.timer);
                this.timer = setInterval(() => { if (!this.paused) this.next(); }, {{ $transitionSpeed }});
            }
        },
        next() {
            if (this.active.size === 0) return;
            let next = (this.current + 1) % this.total;
            let tries = 0;
            while (!this.active.has(next) && tries < this.total) { next = (next + 1) % this.total; tries++; }
            if (this.active.has(next)) this.current = next;
        },
        prev() {
            if (this.active.size === 0) return;
            let prev = (this.current - 1 + this.total) % this.total;
            let tries = 0;
            while (!this.active.has(prev) && tries < this.total) { prev = (prev - 1 + this.total) % this.total; tries++; }
            if (this.active.has(prev)) this.current = prev;
        },
        goTo(i) { if (this.active.has(i)) { this.current = i; this.startAuto(); } },
        slideExpired(index) {
            this.active.delete(index);
            if (this.active.size === 0) { clearInterval(this.timer); return; }
            if (this.current === index) this.next();
        },
        touchStart(e) { this.touchX = e.touches[0].clientX; },
        touchEnd(e) { const dx = e.changedTouches[0].clientX - this.touchX; if (Math.abs(dx) > 50) { dx < 0 ? this.next() : this.prev(); this.startAuto(); } },
    }"
    @touchstart.passive="touchStart($event)"
    @touchend.passive="touchEnd($event)"
    x-show="active.size > 0"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    aria-roledescription="carousel"
    aria-label="Sale offers"
    class="home-section"
>
    <div class="section">
        <div class="relative overflow-hidden rounded-card shadow-mega bg-secondary-900 dark:bg-secondary-800" role="region" aria-roledescription="carousel">
            <div
                class="flex transition-transform duration-700 ease-out"
                :style="'transform: translateX(-' + (current * 100) + '%)'"
            >
                @foreach($banners as $i => $banner)
                    @php
                        $textColor = $banner->text_color ?? 'text-white';
                        $endIso = $banner->countdown_ends_at_iso ?? null;
                        $autoHide = $banner->show_countdown && $endIso ? 'true' : 'false';
                        $bgImage = $banner->image_url;
                        $productImage = $banner->enable_product_image ? $banner->product_image_url : null;
                        $currentPrice = $banner->current_price !== null ? (float) $banner->current_price : null;
                        $originalPrice = $banner->original_price !== null ? (float) $banner->original_price : null;
                        $discount = $banner->discount_percentage;
                        $showOverlay = $banner->enable_overlay && $banner->overlay_enabled;

                        $oc = ltrim($banner->overlay_color ?? '#000000', '#');
                        if (strlen($oc) === 3) {
                            $oc = $oc[0] . $oc[0] . $oc[1] . $oc[1] . $oc[2] . $oc[2];
                        }
                        $r = hexdec(substr($oc, 0, 2) ?: '00');
                        $g = hexdec(substr($oc, 2, 2) ?: '00');
                        $b = hexdec(substr($oc, 4, 2) ?: '00');
                        $alpha = max(0, min(100, (int) ($banner->overlay_opacity ?? 40))) / 100;
                        $alphaBottom = $alpha * 0.35;
                        $overlayGradient = $bgImage
                            ? "linear-gradient(90deg, rgba({$r},{$g},{$b},{$alpha}) 0%, rgba({$r},{$g},{$b},0) 100%)"
                            : "linear-gradient(180deg, rgba({$r},{$g},{$b},{$alpha}) 0%, rgba({$r},{$g},{$b},{$alphaBottom}) 100%)";

                        $link = $banner->link ?? ($banner->featuredProduct ? route('frontend.product.show', $banner->featuredProduct->id) : '#');
                        $productName = $banner->featuredProduct?->name ?? ($banner->title ?? '');
                    @endphp
                    <div
                        :class="active.has({{ $i }}) ? 'opacity-100' : 'opacity-0 pointer-events-none'"
                        class="relative w-full shrink-0 min-h-[480px] sm:min-h-[440px] lg:min-h-[560px] overflow-hidden {{ $banner->visibility_classes }} transition-opacity duration-500"
                        role="group"
                        :aria-roledescription="'slide'"
                        :aria-label="'Slide {{ $i + 1 }} of {{ $total }}'"
                        aria-hidden="{{ $i > 0 ? 'true' : 'false' }}"
                        :aria-hidden="current === {{ $i }} ? 'false' : 'true'"
                        @if($banner->section_margin_css) style="{{ $banner->section_margin_css }}" @endif
                    >
                        <div class="absolute inset-0" @if($bgImage) style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: {{ $banner->image_position_css }};" @endif></div>

                        @if(! $bgImage && $banner->background_style)
                            <div class="absolute inset-0" style="{{ $banner->background_style }}"></div>
                        @endif

                        @if($showOverlay)
                            <div class="absolute inset-0 z-[1]" aria-hidden="true" style="background: {{ $overlayGradient }};"></div>
                        @endif

                        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>
                        <div class="absolute right-1/4 bottom-0 h-64 w-64 rounded-full bg-white/5 blur-3xl" aria-hidden="true"></div>

                        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-8 lg:gap-12 px-6 py-10 sm:px-10 sm:py-14 lg:px-16 lg:py-14">
                            <div class="w-full lg:flex-1 {{ $verticalClasses[$banner->content_vertical ?? 'center'] ?? 'justify-center' }} flex flex-col">
                                <div
                                    class="w-full max-w-xl"
                                    @if($banner->content_padding_css) style="{{ $banner->content_padding_css }}" @endif
                                    x-data="bannerCountdown('{{ $endIso }}', {{ $autoHide }}, {{ $i }})"
                                >
                                    @if($banner->enable_badge && $banner->badge)
                                        <span class="inline-flex items-center gap-2 rounded-full {{ $banner->badge_color ?? 'bg-primary-500' }} px-4 py-1.5 mb-4 sm:mb-5 self-start" :class="current === {{ $i }} ? 'animate-in' : 'opacity-0'">
                                            <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse" aria-hidden="true"></span>
                                            <span class="text-xs font-bold text-white tracking-wider uppercase">{{ $banner->badge }}</span>
                                        </span>
                                    @endif

                                    <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-semibold tracking-tight leading-[1.1] {{ $textColor }}" :class="current === {{ $i }} ? 'animate-in' : 'opacity-0'">{{ $banner->title }}</h2>

                                    @if($banner->description)
                                        <p class="mt-3 sm:mt-4 text-base sm:text-lg lg:text-xl leading-relaxed {{ $textColor }}/85" :class="current === {{ $i }} ? 'animate-in-delay-1' : 'opacity-0'">{{ $banner->description }}</p>
                                    @endif

                                    @if($banner->show_countdown && $endIso)
                                        <div class="mt-5 sm:mt-6" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'" x-show="show">
                                            <div class="banner-countdown">
                                                <div class="banner-countdown-unit"><span class="banner-countdown-value {{ $textColor }}" x-text="days"></span><span class="banner-countdown-label">Days</span></div>
                                                <div class="banner-countdown-unit"><span class="banner-countdown-value {{ $textColor }}" x-text="hours"></span><span class="banner-countdown-label">Hours</span></div>
                                                <div class="banner-countdown-unit"><span class="banner-countdown-value {{ $textColor }}" x-text="minutes"></span><span class="banner-countdown-label">Mins</span></div>
                                                <div class="banner-countdown-unit"><span class="banner-countdown-value {{ $textColor }}" x-text="seconds"></span><span class="banner-countdown-label">Secs</span></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-5 sm:mt-6" x-show="expired">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-red-500/15 border border-red-400/40 px-4 py-1.5 text-sm font-semibold {{ $textColor }}">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                                            Sale Ended
                                        </span>
                                    </div>

                                    @if($banner->enable_prices && $currentPrice !== null)
                                        <div class="mt-5 sm:mt-6 flex items-center gap-3 flex-wrap" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'">
                                            <span class="font-display text-3xl sm:text-4xl font-bold {{ $textColor }}"><span x-text="$store.currency.format({{ $currentPrice }})"></span></span>
                                            @if($originalPrice !== null)
                                                <span class="text-lg sm:text-xl {{ $textColor }}/60 line-through"><span x-text="$store.currency.format({{ $originalPrice }})"></span></span>
                                            @endif
                                            @if($discount)
                                                <span class="inline-flex items-center rounded-full bg-red-500 px-2.5 py-1 text-xs font-bold text-white animate-countdown-pulse">-{{ $discount }}% OFF</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if($banner->enable_buttons)
                                        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row w-full sm:w-auto items-start gap-4" :class="current === {{ $i }} ? 'animate-in-delay-3' : 'opacity-0'">
                                            <a href="{{ $link }}" :disabled="expired" :class="expired ? 'opacity-40 pointer-events-none' : ''" class="btn-primary btn-lg w-full sm:w-auto justify-center shadow-lg shadow-primary-500/25">
                                                {{ $banner->button_text ?: 'Shop Now' }}
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                            </a>
                                            @if($banner->secondary_button_text)
                                                <a href="{{ $banner->secondary_button_url ?: '#' }}" class="btn bg-white/10 {{ $textColor }} hover:bg-white/20 border border-white/20 btn-lg w-full sm:w-auto justify-center backdrop-blur-sm">{{ $banner->secondary_button_text }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($productImage)
                                <div class="relative w-full max-w-[240px] sm:max-w-xs lg:w-[38%] lg:max-w-none flex items-center justify-center shrink-0" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'">
                                    <div class="absolute inset-0 m-auto h-56 w-56 sm:h-72 sm:w-72 lg:h-[380px] lg:w-[380px] rounded-full bg-white/15 blur-3xl animate-pulse" aria-hidden="true"></div>
                                    @if($discount)
                                        <span class="absolute -top-3 right-6 z-20 inline-flex items-center rounded-full bg-red-500 px-3 py-1.5 text-xs font-bold text-white shadow-lg animate-countdown-pulse">-{{ $discount }}%</span>
                                    @endif
                                    <img
                                        src="{{ $productImage }}"
                                        alt="{{ $productName }}"
                                        class="relative z-10 w-56 h-56 sm:w-72 sm:h-72 lg:h-[380px] lg:w-[380px] rounded-full object-cover shadow-2xl ring-4 ring-white/20 animate-float"
                                        @if($i === 0) fetchpriority="high" @else loading="lazy" decoding="async" @endif
                                        onerror="this.onerror=null;this.src='{{ asset('frontend-assets/images/no-image.jpg') }}';"
                                    >
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <button x-on:click="prev()" aria-label="Previous sale banner" class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm {{ $banners->first()->text_color ?? 'text-white' }} hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </button>
            <button x-on:click="next()" aria-label="Next sale banner" class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm {{ $banners->first()->text_color ?? 'text-white' }} hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </button>

            <div class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2.5" role="tablist" aria-label="Sale banners">
                @foreach($banners as $i => $banner)
                    <button x-show="active.has({{ $i }})" x-on:click="goTo({{ $i }})" :aria-label="'Go to sale banner ' + ({{ $i }} + 1)" :aria-current="current === {{ $i }} ? 'true' : undefined" :class="current === {{ $i }} ? 'w-8 bg-primary-500' : 'w-2.5 bg-white/50 hover:bg-white/75'" class="h-2.5 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
