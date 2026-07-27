@php
    $section = $sections->get('featured-products');
@endphp

@if(!empty($featuredProducts) && count($featuredProducts) > 0)
<section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-secondary-900">
    <div class="section">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="section-heading">{{ $section?->title ?? 'Featured Products' }}</h2>
                <p class="section-subheading">{{ $section?->subtitle ?? 'Handpicked just for you' }}</p>
            </div>
            <a href="{{ route('frontend.shop') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                View All
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach(array_slice($featuredProducts, 0, $section?->max_products ?? 8) as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('frontend.shop') }}" class="btn-outline">View All Products</a>
        </div>
    </div>
</section>
@endif
