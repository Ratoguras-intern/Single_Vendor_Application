@props([
    'brands' => collect(),
    'priceRange' => null,
])

@php
    $minBound = $priceRange && $priceRange->min_price !== null ? floor($priceRange->min_price) : 0;
    $maxBound = $priceRange && $priceRange->max_price !== null ? ceil($priceRange->max_price) : 10000;
    $minValue = ($this->minPrice !== null && $this->minPrice !== '') ? (int) $this->minPrice : $minBound;
    $maxValue = ($this->maxPrice !== null && $this->maxPrice !== '') ? (int) $this->maxPrice : $maxBound;
@endphp

<div
    x-data="{ mobileOpen: false }"
    x-on:toggle-filter.window="mobileOpen = !mobileOpen"
    x-on:keydown.escape.window="mobileOpen = false"
>

    {{-- Overlay --}}
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="mobileOpen = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
        aria-hidden="true"
    ></div>

    {{-- Sidebar --}}
    <aside
        x-show="mobileOpen || true"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full lg:translate-x-0"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full lg:translate-x-0"
        :class="mobileOpen ? 'fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] shadow-2xl bg-white dark:bg-secondary-900 overflow-y-auto p-6 lg:relative lg:w-full lg:shadow-none lg:z-auto lg:bg-transparent lg:dark:bg-transparent lg:p-0' : 'hidden lg:block lg:w-full'"
        role="navigation"
        aria-label="Product filters"
    >
        {{-- Mobile header --}}
        <div class="flex items-center justify-between mb-6 lg:hidden">
            <h2 class="text-lg font-bold text-secondary-900 dark:text-white">Filters</h2>
            <button type="button" x-on:click="mobileOpen = false" class="p-2 -mr-2 text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-300" aria-label="Close filters">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-6">

            {{-- Active Filters Summary --}}
            <div x-show="($wire.brand || []).length > 0 || $wire.inStock || $wire.onSale || $wire.featured || $wire.newArrivals || !!$wire.minPrice || !!$wire.maxPrice" class="flex items-center justify-between">
                <span class="text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Active Filters</span>
                <button type="button" x-on:click="$wire.call('resetFilters')" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Clear All</button>
            </div>

            {{-- Brands --}}
            @if($brands->isNotEmpty())
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-secondary-900 dark:text-white">Brands</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto scrollbar-hide">
                        @foreach($brands as $brand)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    value="{{ $brand->slug }}"
                                    wire:model.live="brand"
                                    class="h-4 w-4 rounded border-secondary-300 dark:border-secondary-600 text-primary-500 focus:ring-primary-500/20"
                                >
                                <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors flex-1">{{ $brand->name }}</span>
                                <span class="text-xs text-secondary-400 dark:text-secondary-500">{{ $brand->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Price Range --}}
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-secondary-900 dark:text-white">Price Range</h3>
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <label for="min-price" class="sr-only">Minimum price</label>
                        <input
                            type="number"
                            id="min-price"
                            wire:model.live.debounce.500ms="minPrice"
                            min="{{ $minBound }}"
                            max="{{ $maxBound }}"
                            placeholder="{{ $minValue }}"
                            class="input text-sm py-2"
                        >
                    </div>
                    <span class="text-secondary-400 dark:text-secondary-500">—</span>
                    <div class="flex-1">
                        <label for="max-price" class="sr-only">Maximum price</label>
                        <input
                            type="number"
                            id="max-price"
                            wire:model.live.debounce.500ms="maxPrice"
                            min="{{ $minBound }}"
                            max="{{ $maxBound }}"
                            placeholder="{{ $maxValue }}"
                            class="input text-sm py-2"
                        >
                    </div>
                </div>
            </div>

            {{-- Toggles --}}
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-secondary-900 dark:text-white">Availability</h3>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">In Stock Only</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="$wire.inStock = ! $wire.inStock"
                        :aria-checked="$wire.inStock"
                        :class="$wire.inStock ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="$wire.inStock ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">On Sale</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="$wire.onSale = ! $wire.onSale"
                        :aria-checked="$wire.onSale"
                        :class="$wire.onSale ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="$wire.onSale ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">Featured</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="$wire.featured = ! $wire.featured"
                        :aria-checked="$wire.featured"
                        :class="$wire.featured ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="$wire.featured ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">New Arrivals</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="$wire.newArrivals = ! $wire.newArrivals"
                        :aria-checked="$wire.newArrivals"
                        :class="$wire.newArrivals ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="$wire.newArrivals ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
            </div>

            {{-- Clear --}}
            <div class="flex gap-3 pt-2">
                <button type="button" x-on:click="$wire.call('resetFilters')" class="btn-outline flex-1">
                    Clear
                </button>
            </div>
        </div>
    </aside>
</div>
