@php
    $section = $sections->get('hero-carousel');
    $slides = $section?->config['slides'] ?? [
        ['badge' => 'NEW', 'badge_color' => 'bg-green-500', 'heading' => 'Step Into Style', 'description' => 'Discover our latest collection.', 'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80', 'cta_primary' => 'Shop Now', 'cta_secondary' => 'Learn More', 'link_primary' => '/shop', 'link_secondary' => '/about'],
    ];
@endphp

<section
    x-data="{
        current: 0,
        total: {{ count($slides) }},
        paused: false,
        timer: null,
        init() {
            this.startAuto();
            this.$el.addEventListener('mouseenter', () => { this.paused = true; clearInterval(this.timer); });
            this.$el.addEventListener('mouseleave', () => { this.paused = false; this.startAuto(); });
        },
        startAuto() { clearInterval(this.timer); this.timer = setInterval(() => { if (!this.paused) this.next(); }, 5000); },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
        goTo(i) { this.current = i; this.startAuto(); },
    }"
    class="relative h-[500px] sm:h-[600px] lg:h-[700px] overflow-hidden bg-secondary-900"
>
    @foreach($slides as $i => $slide)
        <div
            class="hero-slide"
            :class="{ 'opacity-100 z-10': current === {{ $i }}, 'opacity-0 z-0': current !== {{ $i }} }"
        >
            <img src="{{ $slide['image'] }}" alt="{{ $slide['heading'] }}" class="absolute inset-0 w-full h-full object-cover" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" />
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
            <div class="section relative h-full flex items-center">
                <div class="max-w-2xl animate-in">
                    <span class="inline-flex items-center gap-2 rounded-full {{ $slide['badge_color'] }} px-4 py-1.5 mb-6">
                        <span class="text-xs font-bold text-white tracking-wider">{{ $slide['badge'] }}</span>
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">{{ $slide['heading'] }}</h1>
                    <p class="mt-6 text-lg sm:text-xl text-secondary-200 max-w-xl leading-relaxed">{{ $slide['description'] }}</p>
                    <div class="mt-10 flex flex-col sm:flex-row items-start gap-4">
                        <a href="{{ $slide['link_primary'] }}" class="btn-primary btn-lg shadow-lg shadow-primary-500/25">
                            {{ $slide['cta_primary'] }}
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <a href="{{ $slide['link_secondary'] }}" class="btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-lg backdrop-blur-sm">{{ $slide['cta_secondary'] }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button x-on:click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
    </button>
    <button x-on:click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 h-11 w-11 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/25 transition-all flex items-center justify-center border border-white/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
    </button>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2.5">
        @foreach($slides as $i => $slide)
            <button x-on:click="goTo({{ $i }})" :class="current === {{ $i }} ? 'w-8 bg-primary-500' : 'w-2.5 bg-white/50 hover:bg-white/75'" class="h-2.5 rounded-full transition-all duration-300"></button>
        @endforeach
    </div>
</section>
