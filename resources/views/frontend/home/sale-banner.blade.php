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
        <div class="group/carousel relative overflow-hidden rounded-card bg-secondary-950 shadow-mega dark:bg-secondary-900" role="region" aria-roledescription="carousel">
            <div
                class="flex transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
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
                        $alpha = max(20, min(90, (int) ($banner->overlay_opacity ?? 55))) / 100;

                        $overlayGradient = $bgImage
                            ? "linear-gradient(90deg, rgba({$r},{$g},{$b},{$alpha}) 0%, rgba({$r},{$g},{$b},{$alpha}) 38%, rgba({$r},{$g},{$b}," . round($alpha * 0.25, 3) . ") 100%)"
                            : null;

                        $link = $banner->link ?? ($banner->featuredProduct ? route('frontend.product.show', $banner->featuredProduct->id) : '#');
                        $productName = $banner->featuredProduct?->name ?? ($banner->title ?? '');
                    @endphp
                    <div
                        :class="active.has({{ $i }}) ? 'opacity-100' : 'opacity-0 pointer-events-none'"
                        class="relative w-full shrink-0 min-h-[440px] sm:min-h-[420px] lg:min-h-[500px] overflow-hidden transition-opacity duration-700 {{ $banner->visibility_classes }}"
                        role="group"
                        :aria-roledescription="'slide'"
                        :aria-label="'Slide {{ $i + 1 }} of {{ $total }}'"
                        :aria-hidden="current === {{ $i }} ? 'false' : 'true'"
                        @if($banner->section_margin_css) style="{{ $banner->section_margin_css }}" @endif
                    >
                        {{-- Backdrop --}}
                        <div class="absolute inset-0" @if($bgImage) style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: {{ $banner->image_position_css }};" @endif></div>

                        @if(! $bgImage && $banner->background_style)
                            <div class="absolute inset-0" style="{{ $banner->background_style }}"></div>
                        @endif

                        @if(! $bgImage)
                            <div class="absolute inset-0" aria-hidden="true" style="background:
                                radial-gradient(ellipse 60% 80% at 85% 15%, rgba(255,255,255,0.08) 0%, transparent 55%),
                                radial-gradient(ellipse 45% 65% at 10% 95%, rgba(255,255,255,0.05) 0%, transparent 60%);"></div>
                        @endif

                        @if($showOverlay && $overlayGradient)
                            <div class="absolute inset-0 z-[1]" aria-hidden="true" style="background: {{ $overlayGradient }};"></div>
                        @elseif($showOverlay)
                            <div class="absolute inset-0 z-[1] bg-secondary-950/{{ max(10, (int) ($banner->overlay_opacity ?? 55)) }}"></div>
                        @endif

                        <div class="absolute inset-x-0 top-0 z-[2] h-px bg-gradient-to-r from-transparent via-white/20 to-transparent" aria-hidden="true"></div>

                        {{-- Content --}}
                        <div class="relative z-10 mx-auto flex h-full min-h-[inherit] w-full max-w-7xl flex-col justify-center px-6 py-12 sm:px-10 lg:flex-row lg:items-center lg:gap-16 lg:px-14 lg:py-16">
                            <div class="w-full lg:flex-1 {{ $verticalClasses[$banner->content_vertical ?? 'center'] ?? 'justify-center' }} flex flex-col">
                                <div
                                    class="w-full max-w-xl"
                                    @if($banner->content_padding_css) style="{{ $banner->content_padding_css }}" @endif
                                    x-data="bannerCountdown('{{ $endIso }}', {{ $autoHide }}, {{ $i }})"
                                >
                                    @if($banner->enable_badge && $banner->badge)
                                        <p class="mb-5 flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-white/70" :class="current === {{ $i }} ? 'animate-in' : 'opacity-0'">
                                            <span class="h-px w-8 bg-amber-400/80" aria-hidden="true"></span>
                                            {{ $banner->badge }}
                                        </p>
                                    @endif

                                    <h2 class="font-display text-4xl font-semibold leading-[1.05] tracking-tight {{ $textColor }} sm:text-5xl lg:text-[3.4rem]" :class="current === {{ $i }} ? 'animate-in' : 'opacity-0'">{{ $banner->title }}</h2>

                                    @if($banner->subtitle)
                                        <p class="mt-4 text-lg font-medium tracking-wide {{ $textColor }}/90 sm:text-xl" :class="current === {{ $i }} ? 'animate-in-delay-1' : 'opacity-0'">{{ $banner->subtitle }}</p>
                                    @endif

                                    @if($banner->description)
                                        <p class="mt-3 max-w-md text-base leading-relaxed {{ $textColor }}/65" :class="current === {{ $i }} ? 'animate-in-delay-1' : 'opacity-0'">{{ $banner->description }}</p>
                                    @endif

                                    @if($banner->show_countdown && $endIso)
                                        <div class="mt-7" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'" x-show="show">
                                            <div class="flex items-end gap-6 sm:gap-7">
                                                <div><span class="block font-display text-2xl font-semibold tabular-nums leading-none {{ $textColor }} sm:text-3xl" x-text="days"></span><span class="mt-1.5 block text-[10px] font-medium uppercase tracking-[0.18em] {{ $textColor }}/50">Days</span></div>
                                                <span class="pb-3.5 text-lg {{ $textColor }}/25" aria-hidden="true">&middot;</span>
                                                <div><span class="block font-display text-2xl font-semibold tabular-nums leading-none {{ $textColor }} sm:text-3xl" x-text="hours"></span><span class="mt-1.5 block text-[10px] font-medium uppercase tracking-[0.18em] {{ $textColor }}/50">Hours</span></div>
                                                <span class="pb-3.5 text-lg {{ $textColor }}/25" aria-hidden="true">&middot;</span>
                                                <div><span class="block font-display text-2xl font-semibold tabular-nums leading-none {{ $textColor }} sm:text-3xl" x-text="minutes"></span><span class="mt-1.5 block text-[10px] font-medium uppercase tracking-[0.18em] {{ $textColor }}/50">Mins</span></div>
                                                <span class="pb-3.5 text-lg {{ $textColor }}/25" aria-hidden="true">&middot;</span>
                                                <div><span class="block font-display text-2xl font-semibold tabular-nums leading-none {{ $textColor }} sm:text-3xl" x-text="seconds"></span><span class="mt-1.5 block text-[10px] font-medium uppercase tracking-[0.18em] {{ $textColor }}/50">Secs</span></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-7" x-show="expired">
                                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-1.5 text-sm font-medium {{ $textColor }}">
                                            This offer has ended
                                        </span>
                                    </div>

                                    @if($banner->enable_prices && $currentPrice !== null)
                                        <div class="mt-7 flex flex-wrap items-baseline gap-x-4 gap-y-1" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'">
                                            <span class="font-display text-3xl font-bold tracking-tight {{ $textColor }} tabular-nums sm:text-4xl"><span x-text="$store.currency.format({{ $currentPrice }})"></span></span>
                                            @if($originalPrice !== null)
                                                <span class="text-lg {{ $textColor }}/45 line-through tabular-nums"><span x-text="$store.currency.format({{ $originalPrice }})"></span></span>
                                            @endif
                                            @if($discount)
                                                <span class="self-center rounded-sm bg-amber-400 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-secondary-950">{{ $discount }}% off</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if($banner->enable_buttons)
                                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4" :class="current === {{ $i }} ? 'animate-in-delay-3' : 'opacity-0'">
                                            <a href="{{ $link }}" :disabled="expired" :class="expired ? 'pointer-events-none opacity-40' : ''" class="btn-primary btn-lg w-full justify-center text-sm font-semibold uppercase tracking-[0.12em] sm:w-auto">
                                                {{ $banner->button_text ?: 'Shop Now' }}
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                            </a>
                                            @if($banner->secondary_button_text)
                                                <a href="{{ $banner->secondary_button_url ?: '#' }}" class="btn btn-lg w-full justify-center border border-white/25 bg-transparent {{ $textColor }} backdrop-blur-sm hover:border-white/50 hover:bg-white/10 sm:w-auto">{{ $banner->secondary_button_text }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($productImage)
                                <div class="relative mt-10 flex w-full shrink-0 items-center justify-center lg:mt-0 lg:w-[34%]" :class="current === {{ $i }} ? 'animate-in-delay-2' : 'opacity-0'">
                                    <div class="absolute h-64 w-64 rounded-full bg-white/[0.06] blur-2xl sm:h-80 sm:w-80" aria-hidden="true"></div>
                                    @if($discount)
                                        <span class="absolute -top-2 right-4 z-20 flex h-16 w-16 rotate-6 items-center justify-center rounded-full border border-secondary-950/10 bg-amber-400 text-center font-display text-sm font-bold leading-tight text-secondary-950 shadow-xl sm:right-10 lg:-top-4">-{{ $discount }}%</span>
                                    @endif
                                    <img
                                        src="{{ $productImage }}"
                                        alt="{{ $productName }}"
                                        class="relative z-10 aspect-square w-56 rounded-2xl object-cover shadow-[0_32px_64px_-24px_rgba(0,0,0,0.55)] ring-1 ring-white/15 sm:w-72 lg:w-[340px]"
                                        @if($i === 0) fetchpriority="high" @else loading="lazy" decoding="async" @endif
                                        onerror="this.onerror=null;this.src='{{ asset('frontend-assets/images/no-image.jpg') }}';"
                                    >
                                    <div class="absolute bottom-2 left-1/2 z-10 h-4 w-40 -translate-x-1/2 rounded-[100%] bg-black/40 blur-md" aria-hidden="true"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Controls --}}
            <button x-on:click="prev()" aria-label="Previous sale banner" class="absolute left-4 top-1/2 z-20 hidden h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-secondary-950/30 text-white opacity-0 backdrop-blur-md transition-all duration-300 hover:bg-secondary-950/60 focus-visible:opacity-100 group-hover/carousel:opacity-100 md:flex">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </button>
            <button x-on:click="next()" aria-label="Next sale banner" class="absolute right-4 top-1/2 z-20 hidden h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-secondary-950/30 text-white opacity-0 backdrop-blur-md transition-all duration-300 hover:bg-secondary-950/60 focus-visible:opacity-100 group-hover/carousel:opacity-100 md:flex">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </button>

            <div class="absolute bottom-5 left-6 z-20 flex items-center gap-4 sm:left-10 lg:left-14" role="tablist" aria-label="Sale banners">
                <span class="font-display text-xs font-semibold tabular-nums text-white/80" aria-hidden="true">
                    <span x-text="String(current + 1).padStart(2, '0')"></span>&thinsp;/&thinsp;<span x-text="String(total).padStart(2, '0')"></span>
                </span>
                <div class="flex items-center gap-2">
                    @foreach($banners as $i => $banner)
                        <button x-show="active.has({{ $i }})" x-on:click="goTo({{ $i }})" :aria-label="'Go to sale banner ' + ({{ $i }} + 1)" :aria-current="current === {{ $i }} ? 'true' : undefined" :class="current === {{ $i }} ? 'w-7 bg-amber-400' : 'w-2 bg-white/35 hover:bg-white/60'" class="h-1 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
