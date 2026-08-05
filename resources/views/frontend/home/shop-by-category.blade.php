@php
    $section = $sections->get('shop-by-category');
@endphp

@if(!empty($categories) && count($categories) > 0)
<section class="home-section">
    <div class="section">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="section-heading">{{ $section?->title ?? 'Shop by Category' }}</h2>
                <p class="section-subheading">{{ $section?->subtitle ?? 'Browse our curated collections' }}</p>
            </div>
            <a href="{{ route('frontend.shop') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                View All
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                @php
                    $cat = $category instanceof \App\Models\Category ? $category : null;
                    $displayImage = $cat ? $cat->display_image : ($category['image'] ?? asset('frontend-assets/images/no-image.jpg'));
                    $catName = $cat ? $cat->name : ($category['name'] ?? '');
                    $catSlug = $cat ? $cat->slug : ($category['slug'] ?? '');
                    $catCount = $cat ? $cat->total_products_count : ($category['products_count'] ?? 0);
                    $catDesc = $cat ? $cat->description : null;
                    $catIcon = $cat?->icon_url;
                    $catLucide = $cat?->lucide_icon;
                @endphp
                <a href="{{ route('frontend.category', $catSlug) }}" class="category-card group aspect-[4/3]">
                    <img src="{{ $displayImage }}" alt="{{ $catName }}" loading="lazy" />
                    <div class="category-overlay">
                        @if($catLucide)
                            <span class="inline-flex items-center justify-center h-6 w-6 mb-1.5 text-white ring-2 ring-white/30 rounded-lg">
                                <x-lucide :name="$catLucide" class="h-4 w-4" />
                            </span>
                        @elseif($catIcon)
                            <img src="{{ $catIcon }}" alt="" class="h-6 w-6 rounded-lg object-cover mb-1.5 ring-2 ring-white/30">
                        @endif
                        <h3 class="text-white font-bold text-sm sm:text-base">{{ $catName }}</h3>
                        @if($catDesc)
                            <p class="text-white/60 text-[11px] mt-0.5 line-clamp-1">{{ $catDesc }}</p>
                        @endif
                        <p class="text-white/70 text-xs mt-0.5">{{ $catCount }} {{ Str::plural('product', $catCount) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
