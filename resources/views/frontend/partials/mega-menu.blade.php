@php
    $topCategories = $categories->whereNull('parent_id');
    $activeCategory = null;
    if (request()->routeIs('frontend.category')) {
        $slug = request()->route('slug');
        $activeCategory = $categories->firstWhere('slug', $slug);
        if (!$activeCategory && $categories->where('slug', $slug)->first()) {
            $activeCategory = $categories->where('slug', $slug)->first();
        }
    }
@endphp

{{-- Mega Menu Panel (desktop only) --}}
<div x-show="megaOpen"
    x-on:mouseenter="clearTimeout(megaTimer)"
    x-on:mouseleave="megaOpen = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1"
    class="mega-menu-panel"
    x-cloak
    role="menu"
    x-data="{ hoveredCat: null }">
    <div class="section py-8">
        <div class="grid grid-cols-12 gap-8">

            {{-- Category List --}}
            <div class="col-span-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-4">Categories</h3>
                <ul class="space-y-0.5">
                    @forelse($topCategories as $category)
                        <li class="mega-cat-item" x-on:mouseenter="hoveredCat = {{ $category->id }}"
                            x-on:mouseleave="hoveredCat = null">
                            <a href="{{ route('frontend.category', $category->slug) }}"
                                class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors group
                                    {{ $activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id)
                                        ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                                        : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10 hover:text-primary-700 dark:hover:text-primary-400' }}"
                                role="menuitem"
                                {{ $activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id) ? 'aria-current="page"' : '' }}>
                                <span class="flex items-center gap-2.5">
                                    @if($category->lucide_icon)
                                        <x-lucide :name="$category->lucide_icon" class="h-5 w-5 shrink-0 text-primary-500 dark:text-primary-400" />
                                    @elseif($category->icon_url)
                                        <img src="{{ $category->icon_url }}" alt="" class="h-5 w-5 rounded object-cover shrink-0" loading="lazy">
                                    @endif
                                    <span class="font-medium">{{ $category->name }}</span>
                                </span>
                                @if($category->children->count())
                                    <svg class="mega-cat-arrow h-3.5 w-3.5 text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                @else
                                    <span class="text-xs text-secondary-400 dark:text-secondary-500 group-hover:text-primary-500 shrink-0">{{ $category->total_products_count }}</span>
                                @endif
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-sm text-secondary-400 dark:text-secondary-500">No categories yet</li>
                    @endforelse
                </ul>
                <a href="{{ route('frontend.shop') }}"
                    class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                    View All Products
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>

            {{-- Subcategories for hovered parent OR Featured image cards --}}
            <div class="col-span-5">
                {{-- Subcategory panels (one per top category, shown/hidden via Alpine) --}}
                @foreach($topCategories as $category)
                    <div x-show="hoveredCat === {{ $category->id }}" x-transition.opacity.duration.150ms>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500">{{ $category->name }}</h3>
                            <span class="text-xs text-secondary-400 dark:text-secondary-500">{{ $category->total_products_count }} products</span>
                        </div>
                        @if($category->children->isNotEmpty())
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($category->children as $child)
                                    <a href="{{ route('frontend.category', $child->slug) }}"
                                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                                            {{ $activeCategory && $activeCategory->id === $child->id
                                                ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 font-semibold'
                                                : 'text-secondary-700 dark:text-secondary-300 hover:bg-primary-50 dark:hover:bg-primary-500/10' }}">
                                        <span class="flex items-center gap-2">
                                            @if($child->lucide_icon)
                                                <x-lucide :name="$child->lucide_icon" class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" />
                                            @elseif($child->icon_url)
                                                <img src="{{ $child->icon_url }}" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                                            @endif
                                            <span>{{ $child->name }}</span>
                                        </span>
                                        <span class="text-xs text-secondary-400 dark:text-secondary-500 shrink-0">{{ $child->total_products_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-secondary-400 dark:text-secondary-500 py-2">No subcategories yet.</p>
                        @endif
                        <a href="{{ route('frontend.category', $category->slug) }}"
                            class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                            View All
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>
                @endforeach

                {{-- Featured image cards (shown when no parent hovered) --}}
                <div x-show="hoveredCat === null">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-4">Shop by Category</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @forelse($topCategories->take(4) as $category)
                            <a href="{{ route('frontend.category', $category->slug) }}"
                                class="mega-cat-card">
                                @if($category->display_image)
                                    <img src="{{ $category->display_image }}" alt="{{ $category->name }}"
                                        class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/20"></div>
                                @endif
                                <div class="mega-cat-overlay"></div>
                                <div class="mega-cat-content">
                                    <span class="text-sm font-semibold text-white">{{ $category->name }}</span>
                                    <span class="block text-xs text-white/70">{{ $category->total_products_count }} products</span>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-2 text-sm text-secondary-400 dark:text-secondary-500 py-4">Browse our collection</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Promo Banner --}}
            <div class="col-span-4">
                <a href="{{ $megaMenuPromo['url'] }}" class="mega-promo-card block h-full">
                    <div class="relative">
                        <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold mb-3">{{ $megaMenuPromo['badge'] }}</span>
                        <h4 class="text-xl font-bold text-white mb-2">{{ $megaMenuPromo['heading'] }}</h4>
                        <p class="text-sm text-white/80 mb-4">{{ $megaMenuPromo['description'] }}</p>
                        <span class="mega-promo-btn">
                            {{ $megaMenuPromo['cta_text'] }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        </span>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>
