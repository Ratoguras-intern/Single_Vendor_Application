{{-- Recursive Category Tree Partial (Mobile Drawer Accordion)
     Usage: @include('frontend.partials.category-tree', ['categories' => $topCategories, 'level' => 0, 'context' => 'mobile', 'activeCategory' => null])
--}}
@props(['categories' => collect(), 'level' => 0, 'context' => 'mobile', 'activeCategory' => null])

@if($categories->isNotEmpty())
    @foreach($categories as $category)
        @php
            $hasChildren = $category->children && $category->children->isNotEmpty();
            $isActive = $activeCategory && ($activeCategory->id === $category->id || $activeCategory->parent_id === $category->id);
        @endphp

        {{-- Mobile Drawer: Accordion category item --}}
        <div x-data="{ open: false }">
            @if($hasChildren)
                <button x-on:click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $isActive
                            ? 'text-primary-700 dark:text-primary-400 font-semibold'
                            : 'text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                    <span class="flex items-center gap-2">
                        @if($category->lucide_icon)
                            <x-lucide :name="$category->lucide_icon" class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" />
                        @elseif($category->icon_url)
                            <img src="{{ $category->icon_url }}" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                        @endif
                        <a href="{{ route('frontend.category', $category->slug) }}" x-on:click.stop="mobileOpen = false" class="hover:text-primary-600">{{ $category->name }}</a>
                        <span class="text-xs text-secondary-400 dark:text-secondary-500">{{ $category->total_products_count }}</span>
                    </span>
                    <svg class="h-3.5 w-3.5 text-secondary-400 dark:text-secondary-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="ml-4 space-y-0.5">
                    @include('frontend.partials.category-tree', [
                        'categories' => $category->children->filter(fn($c) => $c->status === 'active'),
                        'level' => $level + 1,
                        'context' => 'mobile',
                        'activeCategory' => $activeCategory,
                    ])
                </div>
            @else
                <a href="{{ route('frontend.category', $category->slug) }}" x-on:click="mobileOpen = false"
                    class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $isActive
                            ? 'text-primary-700 dark:text-primary-400 font-semibold'
                            : 'text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                    <span class="flex items-center gap-2">
                        @if($category->lucide_icon)
                            <x-lucide :name="$category->lucide_icon" class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" />
                        @elseif($category->icon_url)
                            <img src="{{ $category->icon_url }}" alt="" class="h-4 w-4 rounded object-cover shrink-0" loading="lazy">
                        @endif
                        <span class="font-medium">{{ $category->name }}</span>
                        <span class="text-xs text-secondary-400 dark:text-secondary-500">{{ $category->total_products_count }}</span>
                    </span>
                </a>
            @endif
        </div>
    @endforeach
@endif