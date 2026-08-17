@props(['product'])

@php
    $hasDiscount = isset($product['original_price']) && $product['original_price'] > $product['price'];
    $isOutOfStock = isset($product['stock']) && $product['stock'] <= 0;
    $productUrl = route('frontend.product.show', $product['id']);
@endphp

<div class="group flex flex-col h-full rounded-card bg-white dark:bg-secondary-900 border border-secondary-200 dark:border-secondary-800 hover:border-secondary-300 dark:hover:border-secondary-600 hover:shadow-card-hover transition-all duration-200 overflow-hidden">
    <div :class="viewMode === 'list' ? 'flex flex-row' : 'flex flex-col h-full'">

        {{-- Image --}}
        <div :class="viewMode === 'list' ? 'relative w-24 sm:w-32 shrink-0 overflow-hidden' : 'relative overflow-hidden mb-3'">
            {{-- Wishlist (grid only) --}}
            <button x-show="viewMode !== 'list'" x-on:click.stop="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'opacity-100 text-red-500' : 'opacity-0 group-hover:opacity-100'" class="absolute top-2 right-2 z-10 p-1.5 rounded-full bg-white/80 dark:bg-secondary-900/80 backdrop-blur-sm hover:bg-white dark:hover:bg-secondary-900 transition-all duration-200 shadow-sm" aria-label="Add to wishlist">
                <svg class="h-3.5 w-3.5" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
            </button>

            <a href="{{ $productUrl }}" :class="viewMode === 'list' ? 'block relative h-full' : 'block relative'">
                <div :class="viewMode === 'list' ? 'w-full h-full overflow-hidden bg-secondary-100 dark:bg-white/5' : 'aspect-square overflow-hidden bg-secondary-100 dark:bg-white/5'">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover" loading="lazy" decoding="async" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'" />
                </div>

                @if($isOutOfStock)
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                        <span class="badge bg-secondary-800/90 text-white text-xs font-bold px-3 py-1">Out of Stock</span>
                    </div>
                @endif
            </a>
        </div>

        {{-- Info --}}
        <div :class="viewMode === 'list' ? 'flex-1 p-3 sm:p-4 flex flex-col justify-between min-w-0' : 'p-4 pt-0 flex flex-col flex-1'">
            <div :class="viewMode === 'list' ? 'space-y-1' : 'space-y-1'">
                <a href="{{ $productUrl }}">
                    <h2 class="font-medium leading-snug truncate text-sm text-secondary-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-300 transition-colors">{{ $product['name'] }}</h2>
                </a>

                <div class="flex items-baseline gap-1.5 pt-0.5">
                    <span class="text-base font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $product['price'] }})"></span></span>
                    @if($hasDiscount)
                        <span class="text-xs text-secondary-400 dark:text-secondary-500 line-through"><span x-text="$store.currency.format({{ $product['original_price'] }})"></span></span>
                    @endif
                </div>

                @if(!empty($product['description']))
                    <p x-show="viewMode === 'list'" class="text-xs text-secondary-500 dark:text-secondary-400 line-clamp-2 leading-relaxed" style="display: none;">{{ $product['description'] }}</p>
                @endif
            </div>

            {{-- Grid Add to Cart (always visible) --}}
            <div x-show="viewMode !== 'list'" class="mt-auto pt-3">
                <button x-on:click.prevent.stop="$store.cart.add({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' })"
                    class="btn-primary btn-sm w-full" @if($isOutOfStock) disabled @endif>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                </button>
            </div>

            {{-- List view buttons --}}
            <div x-show="viewMode === 'list'" class="flex flex-wrap items-center gap-2 mt-2">
                <button x-on:click="$store.cart.add({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' })" class="btn-primary btn-sm" @if($isOutOfStock) disabled @endif>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                </button>
                <button x-on:click="$store.quickView.openProduct({ id: {{ $product['id'] }}, name: '{{ addslashes($product['name']) }}', brand: '{{ addslashes($product['brand'] ?? '') }}', price: {{ $product['price'] }}, original_price: {{ $product['original_price'] ?? 0 }}, image: '{{ $product['image'] }}', description: '{{ addslashes($product['description'] ?? '') }}', stock: {{ $product['stock'] ?? 0 }}, url: '{{ $productUrl }}' })"
                    class="btn-outline btn-sm" @if($isOutOfStock) disabled @endif>
                    <span>Quick View</span>
                </button>
                <button x-on:click="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'text-red-500' : 'text-secondary-400 hover:text-red-400'" class="p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors" aria-label="Add to wishlist">
                    <svg class="h-4 w-4" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                </button>
            </div>
        </div>

    </div>
</div>
