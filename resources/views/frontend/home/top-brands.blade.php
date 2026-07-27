@php
    $section = $sections->get('top-brands');
@endphp

@if(!empty($brands) && count($brands) > 0)
<section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-secondary-900">
    <div class="section">
        <div class="text-center mb-10">
            <h2 class="section-heading">{{ $section?->title ?? 'Top Brands' }}</h2>
            <p class="section-subheading">{{ $section?->subtitle ?? 'Trusted by our customers worldwide' }}</p>
        </div>
    </div>

    <div class="overflow-hidden">
        <div class="marquee-track">
            @foreach($brands as $brand)
                <div class="flex-shrink-0 mx-8 sm:mx-12 flex items-center justify-center h-16 sm:h-20 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300">
                    @if($brand['logo'])
                        <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" class="max-h-12 sm:max-h-16 max-w-[120px] object-contain" loading="lazy" />
                    @else
                        <span class="text-xl sm:text-2xl font-bold text-secondary-400 dark:text-secondary-500 whitespace-nowrap">{{ $brand['name'] }}</span>
                    @endif
                </div>
            @endforeach
            @foreach($brands as $brand)
                <div class="flex-shrink-0 mx-8 sm:mx-12 flex items-center justify-center h-16 sm:h-20 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300">
                    @if($brand['logo'])
                        <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" class="max-h-12 sm:max-h-16 max-w-[120px] object-contain" loading="lazy" />
                    @else
                        <span class="text-xl sm:text-2xl font-bold text-secondary-400 dark:text-secondary-500 whitespace-nowrap">{{ $brand['name'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
