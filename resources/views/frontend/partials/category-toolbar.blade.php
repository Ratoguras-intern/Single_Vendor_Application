@props([
    'total' => 0,
    'currentPage' => 1,
    'lastPage' => 1,
    'perPage' => 24,
    'sort' => 'latest',
    'viewMode' => 'grid',
    'viewStorageKey' => 'shopView',
    'categoryName' => '',
    'ajax' => false,
])

@php
    $from = max(1, ($currentPage - 1) * $perPage + 1);
    $to = min($currentPage * $perPage, $total);
    $activeFilters = collect(request()->query())->except(['page', 'sort', 'view'])->filter()->isNotEmpty();
@endphp

<div class="flex flex-wrap items-center justify-between gap-4 mb-6" x-data="{ sort: '{{ $sort }}' }">
    <div class="flex items-center gap-3">
        <button
            type="button"
            x-on:click="$dispatch('toggle-filter')"
            class="lg:hidden btn-outline btn-sm"
            aria-label="Open filters"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/></svg>
            <span class="hidden sm:inline">Filters</span>
            @if($activeFilters)
                <span class="badge-danger text-[10px] px-1.5 py-0">!</span>
            @endif
        </button>

        <p class="text-sm text-secondary-500 dark:text-secondary-400">
            <span x-show="total > 0">
                <span x-text="from">1</span>–<span x-text="to">24</span>
                of <span x-text="total">{{ number_format($total) }}</span> Products
            </span>
        </p>
    </div>

    <div class="flex items-center gap-3">
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button
                type="button"
                x-on:click="open = !open"
                class="flex items-center gap-2 btn-outline btn-sm"
                aria-haspopup="listbox"
                :aria-expanded="open"
            >
                <svg class="h-4 w-4 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13-6L16.5 19m0 0L12 14.5m4.5 4.5V10.5"/></svg>
                <span>Sort</span>
            </button>
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 z-30 mt-2 w-56 rounded-card bg-white dark:bg-secondary-800 shadow-dropdown border border-secondary-200 dark:border-secondary-700 py-1"
                role="listbox"
            >
                @foreach([
                    'latest' => 'Latest',
                    'newest' => 'Newest',
                    'price_asc' => 'Price: Low to High',
                    'price_desc' => 'Price: High to Low',
                    'best_selling' => 'Best Selling',
                    'name_asc' => 'Name: A to Z',
                    'name_desc' => 'Name: Z to A',
                    'discount' => 'Biggest Discount',
                ] as $value => $label)
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort' => $value, 'page' => null]) }}"
                        @if($ajax) @click.prevent="$dispatch('shop:apply', { url: '{{ request()->fullUrlWithQuery(['sort' => $value, 'page' => null]) }}' })" @endif
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors {{ request('sort', 'latest') === $value ? 'text-primary-600 dark:text-primary-400 font-medium' : 'text-secondary-700 dark:text-secondary-300' }}"
                        role="option"
                        aria-selected="{{ request('sort', 'latest') === $value }}"
                    >
                        @if(request('sort', 'latest') === $value)
                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        @else
                            <span class="w-4 shrink-0"></span>
                        @endif
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="hidden sm:flex items-center border border-secondary-200 dark:border-secondary-700 rounded-btn overflow-hidden">
            <button
                type="button"
                x-on:click="viewMode = 'grid'; localStorage.setItem('{{ $viewStorageKey }}', 'grid')"
                :class="viewMode === 'grid' ? 'bg-secondary-100 dark:bg-white/10 text-secondary-900 dark:text-white' : 'text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-300'"
                class="p-2 transition-colors"
                aria-label="Grid view"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/></svg>
            </button>
            <button
                type="button"
                x-on:click="viewMode = 'list'; localStorage.setItem('{{ $viewStorageKey }}', 'list')"
                :class="viewMode === 'list' ? 'bg-secondary-100 dark:bg-white/10 text-secondary-900 dark:text-white' : 'text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-300'"
                class="p-2 transition-colors"
                aria-label="List view"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/></svg>
            </button>
        </div>
    </div>
</div>
