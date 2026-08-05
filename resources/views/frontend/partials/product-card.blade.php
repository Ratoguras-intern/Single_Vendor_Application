@props(['product'])

@php
    $hasDiscount = isset($product['original_price']) && $product['original_price'] > $product['price'];
    $discountPct = $product['discount_percentage'] ?? ($hasDiscount ? round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) : 0);
    $isOutOfStock = isset($product['stock']) && $product['stock'] <= 0;
    $productUrl = route('frontend.product.show', $product['id']);
    $secondBadge = null;
    foreach (['is_limited_edition' => 'Limited', 'is_new_arrival' => 'New', 'is_flash_sale' => 'Flash Sale', 'is_best_seller' => 'Best Seller'] as $flag => $label) {
        if (!empty($product[$flag])) {
            $secondBadge = $label;
            break;
        }
    }
@endphp

<div x-data="{ viewMode: 'grid' }" class="card-hover group overflow-hidden p-0 h-full">
    <div :class="viewMode === 'list' ? 'flex flex-row h-auto min-h-[160px]' : 'flex flex-col h-full'">

        {{-- Image --}}
        <div :class="viewMode === 'list' ? 'relative w-40 sm:w-48 shrink-0 overflow-hidden' : 'relative overflow-hidden'">
            {{-- Badges (max 2: sale % + one priority flag) --}}
            <div x-show="viewMode !== 'list'" class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
                @if($discountPct > 0)
                    <span class="badge-danger text-[10px] font-bold px-2 py-0.5">-{{ $discountPct }}%</span>
                @endif
                @if($secondBadge)
                    <span class="product-badge-new">{{ $secondBadge }}</span>
                @endif
            </div>

            {{-- Wishlist --}}
            <button x-show="viewMode !== 'list'" x-on:click.stop="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'opacity-100 text-red-500' : 'opacity-0 group-hover:opacity-100'" class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 dark:bg-secondary-900/80 backdrop-blur-sm hover:bg-white dark:hover:bg-secondary-900 transition-all duration-200 shadow-sm" aria-label="Add to wishlist">
                <svg class="h-4 w-4" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
            </button>

            <a href="{{ $productUrl }}" class="block relative">
                <div :class="viewMode === 'list' ? 'aspect-square overflow-hidden bg-secondary-100 dark:bg-white/5' : 'aspect-[4/5] overflow-hidden bg-secondary-100 dark:bg-white/5'">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'" />
                </div>

                @if($isOutOfStock)
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                        <span class="badge bg-secondary-800/90 text-white text-xs font-bold px-3 py-1">Out of Stock</span>
                    </div>
                @else
                    <div x-show="viewMode !== 'list'" class="absolute inset-0 bg-secondary-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <button x-on:click.prevent.stop="$store.quickView.openProduct({ id: {{ $product['id'] }}, name: '{{ addslashes($product['name']) }}', brand: '{{ addslashes($product['brand'] ?? '') }}', price: {{ $product['price'] }}, original_price: {{ $product['original_price'] ?? 0 }}, image: '{{ $product['image'] }}', description: '{{ addslashes($product['description'] ?? '') }}', stock: {{ $product['stock'] ?? 0 }}, url: '{{ $productUrl }}' })"
                            class="btn-primary btn-sm shadow-lg">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            Quick View
                        </button>
                    </div>
                @endif
            </a>
        </div>

        {{-- Info --}}
        <div :class="viewMode === 'list' ? 'flex-1 p-4 flex flex-col justify-between min-w-0' : 'p-3 space-y-1.5 flex flex-col flex-1 mt-auto'">
            <div :class="viewMode === 'list' ? 'space-y-1' : ''">
                @if(!empty($product['brand']))
                    <p class="text-[10px] font-semibold text-primary-600 dark:text-primary-400 uppercase tracking-[0.18em]">{{ $product['brand'] }}</p>
                @endif

                <a href="{{ $productUrl }}">
                    <h2 :class="viewMode === 'list' ? 'font-semibold line-clamp-1 text-sm text-secondary-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-300 transition-colors' : 'font-medium line-clamp-2 text-secondary-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-300 transition-colors'">{{ $product['name'] }}</h2>
                </a>

                <div class="flex items-center gap-2 pt-0.5">
                    <span class="text-base font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $product['price'] }})"></span></span>
                    @if($hasDiscount)
                        <span class="text-xs text-secondary-400 dark:text-secondary-500 line-through"><span x-text="$store.currency.format({{ $product['original_price'] }})"></span></span>
                    @endif
                </div>
            </div>

            {{-- Grid view button --}}
            <div x-show="viewMode !== 'list'" class="pt-1">
                <button x-on:click="$store.cart.add({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' })" class="btn-primary w-full btn-sm" @if($isOutOfStock) disabled @endif>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                </button>
            </div>

            {{-- List view buttons --}}
            <div x-show="viewMode === 'list'" class="flex items-center gap-2 mt-2">
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
