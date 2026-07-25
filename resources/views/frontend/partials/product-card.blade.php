@props(['product'])

<div x-data class="group overflow-hidden rounded-xl border bg-white shadow hover:shadow-lg transition-all duration-300 hover:-translate-y-1" style="border-color: var(--bloom-border); background-color: var(--bloom-card); color: var(--bloom-card-foreground);">
    <div class="relative overflow-hidden">
        <button x-on:click.stop="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'opacity-100 text-red-500' : 'opacity-0 group-hover:opacity-100'" class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white transition-all duration-200">
            <svg class="h-4 w-4" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
        </button>

        <a href="{{ route('frontend.product.show', $product['id']) }}" class="block relative">
            <div class="aspect-square overflow-hidden bg-gray-100">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'" />
            </div>

            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                <span class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-xs font-medium text-white h-8 px-3 shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    Quick View
                </span>
            </div>
        </a>
    </div>

    <div class="p-4 space-y-3">
        <a href="{{ route('frontend.product.show', $product['id']) }}">
            <h2 class="font-semibold line-clamp-2 hover:text-gray-900 transition-colors" style="color: var(--bloom-card-foreground);">{{ $product['name'] }}</h2>
        </a>

        <div class="flex items-center gap-2">
            <span class="text-lg font-bold" style="color: var(--bloom-card-foreground);"><span x-text="$store.currency.format({{ $product['price'] }})"></span></span>
        </div>

        <div class="flex flex-col gap-2">
            <button x-on:click="$store.cart.add({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' })" class="w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-all duration-300 h-9 px-4 py-2 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
            </button>
            <button x-on:click="$store.cart.buyNow({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' })" class="w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-all duration-300 h-9 px-4 py-2 border hover:bg-gray-50 dark:hover:bg-gray-700" style="border-color: var(--bloom-border); color: var(--bloom-foreground);">
                <span data-i18n="Buy Now" x-text="$store.i18n.t('Buy Now')">{{ __('Buy Now') }}</span>
            </button>
        </div>
    </div>
</div>
