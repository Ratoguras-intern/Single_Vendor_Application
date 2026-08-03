@php
    $section = $sections->get('recommended-products');
@endphp

@if(!empty($recommendedProducts) && count($recommendedProducts) > 0)
<section class="home-section">
    <div class="section">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="section-heading">{{ $section?->title ?? 'Recommended For You' }}</h2>
                <p class="section-subheading">{{ $section?->subtitle ?? 'Picked based on popular taste' }}</p>
            </div>
            <a href="{{ route('frontend.shop') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                View All
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>

        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach(array_slice($recommendedProducts, 0, $section?->max_products ?? 8) as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif
