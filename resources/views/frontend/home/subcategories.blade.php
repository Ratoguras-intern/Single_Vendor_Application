@php
    $section = $sections->get('shop-by-category');
@endphp

@if(!empty($subcategories) && count($subcategories) > 0)
<section class="home-section">
    <div class="section">
        <div class="flex items-end justify-between mb-4">
            <div>
                <h2 class="section-heading">{{ $section?->title ?? 'Browse Subcategories' }}</h2>
                <p class="section-subheading">{{ $section?->subtitle ?? 'Explore categories within our collections' }}</p>
            </div>
            <a href="{{ route('frontend.shop') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                View All
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($subcategories as $subcategory)
                @php
                    $sub = $subcategory instanceof \App\Models\Category ? $subcategory : null;
                    $displayImage = $sub ? $sub->display_image : ($subcategory['image'] ?? asset('frontend-assets/images/no-image.jpg'));
                    $subName = $sub ? $sub->name : ($subcategory['name'] ?? '');
                    $subSlug = $sub ? $sub->slug : ($subcategory['slug'] ?? '');
                    $subCount = $sub ? $sub->total_products_count : ($subcategory['products_count'] ?? 0);
                    $parentName = $sub ? $sub->parent?->name : ($subcategory['parent_name'] ?? null);
                    $parentSlug = $sub ? $sub->parent?->slug : ($subcategory['parent_slug'] ?? null);
                @endphp
                <a href="{{ route('frontend.category', $subSlug) }}" class="group block overflow-hidden rounded-card bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ $displayImage }}" alt="{{ $subName }}" loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    </div>
                    <div class="p-3">
                        @if($parentName)
                            <p class="text-[11px] font-medium text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-0.5">{{ $parentName }}</p>
                        @endif
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-1">{{ $subName }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $subCount }} {{ Str::plural('product', $subCount) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
