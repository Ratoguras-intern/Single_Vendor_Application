@php
    $section = $sections->get('flash-sale');
    $endDate = $section?->config['ends_at'] ?? now()->addDays(3)->endOfDay()->toIso8601String();
    $endTimestamp = \Carbon\Carbon::parse($endDate)->timestamp;
    $hasProducts = !empty($flashSaleProducts) && count($flashSaleProducts) > 0;
@endphp

<section
    x-data="{
        endDate: {{ $endTimestamp }} * 1000,
        now: Date.now(),
        days: 0, hours: 0, minutes: 0, seconds: 0,
        init() { this.update(); setInterval(() => { this.now = Date.now(); this.update(); }, 1000); },
        update() {
            const diff = Math.max(0, this.endDate - this.now);
            this.days = Math.floor(diff / 86400000);
            this.hours = Math.floor((diff % 86400000) / 3600000);
            this.minutes = Math.floor((diff % 3600000) / 60000);
            this.seconds = Math.floor((diff % 60000) / 1000);
        },
    }"
    class="py-6 sm:py-8 lg:py-10 bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900"
>
    <div class="section">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-4">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center gap-2 rounded-full bg-red-500/10 border border-red-500/20 px-4 py-1.5 mb-4">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-red-400 tracking-wider uppercase">Flash Sale</span>
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $section?->title ?? "Don't Miss Out" }}</h2>
                <p class="mt-2 text-lg text-secondary-300">{{ $section?->subtitle ?? 'Limited time offers on selected products.' }}</p>
            </div>

            @if($hasProducts)
                <div class="flex gap-3 sm:gap-4">
                    <div class="countdown-unit">
                        <span class="text-2xl sm:text-3xl font-bold text-white" x-text="String(days).padStart(2, '0')">00</span>
                        <span class="text-[10px] sm:text-xs text-secondary-400 dark:text-secondary-300 uppercase tracking-wider mt-1">Days</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="text-2xl sm:text-3xl font-bold text-white" x-text="String(hours).padStart(2, '0')">00</span>
                        <span class="text-[10px] sm:text-xs text-secondary-400 dark:text-secondary-300 uppercase tracking-wider mt-1">Hours</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="text-2xl sm:text-3xl font-bold text-white" x-text="String(minutes).padStart(2, '0')">00</span>
                        <span class="text-[10px] sm:text-xs text-secondary-400 dark:text-secondary-300 uppercase tracking-wider mt-1">Mins</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="text-2xl sm:text-3xl font-bold text-white" x-text="String(seconds).padStart(2, '0')">00</span>
                        <span class="text-[10px] sm:text-xs text-secondary-400 dark:text-secondary-300 uppercase tracking-wider mt-1">Secs</span>
                    </div>
                </div>
            @endif
        </div>

        @if($hasProducts)
            <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-7">
                @foreach(array_slice($flashSaleProducts, 0, $section?->max_products ?? 8) as $product)
                    @include('frontend.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="rounded-card border border-white/10 bg-white/5 px-6 py-10 text-center">
                <p class="text-secondary-200 text-base">No flash sale products right now. Check back soon!</p>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('frontend.shop') }}" class="btn-primary btn-lg inline-flex">
                Shop the Sale
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</section>
