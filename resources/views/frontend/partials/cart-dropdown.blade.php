{{-- Mini Cart Dropdown (desktop) --}}
<div x-show="cartOpen"
    x-on:mouseenter="clearTimeout(cartTimer)"
    x-on:mouseleave="cartOpen = false"
    @click.outside="cartOpen = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
    class="absolute right-0 top-full mt-2 w-[min(380px,calc(100vw-2rem))] rounded-card border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 shadow-dropdown z-50"
    style="display: none;">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-secondary-100 dark:border-secondary-700">
        <h3 class="text-sm font-bold text-secondary-900 dark:text-white">Shopping Cart</h3>
        <span class="text-xs text-secondary-500 dark:text-secondary-400" x-text="$store.cart.count() + ' item(s)'"></span>
    </div>

    {{-- Items --}}
    <div class="max-h-[320px] overflow-y-auto">
        <template x-if="$store.cart.count() === 0">
            <div class="flex flex-col items-center justify-center py-10 px-5">
                <svg class="h-12 w-12 text-secondary-300 dark:text-secondary-600 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75-12.75 2.14 2.14"/>
                </svg>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 font-medium">Your cart is empty</p>
                <a href="{{ route('frontend.shop') }}" class="mt-3 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">Start Shopping</a>
            </div>
        </template>
        <template x-for="item in $store.cart.items" :key="item.product_id">
            <div class="mini-cart-item">
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-secondary-100 dark:bg-secondary-700">
                    <img :src="item.image" :alt="item.name" class="h-full w-full object-cover" loading="lazy">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-secondary-900 dark:text-white truncate" x-text="item.name"></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-secondary-500 dark:text-secondary-400" x-text="'Qty: ' + item.quantity"></span>
                        <span class="text-sm font-semibold text-primary-600 dark:text-primary-400" x-text="$store.currency.format(item.price * item.quantity)"></span>
                    </div>
                </div>
                <button x-on:click="$store.cart.remove(item.product_id)"
                    class="p-1.5 rounded-lg text-secondary-400 dark:text-secondary-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                    aria-label="Remove item">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    {{-- Footer --}}
    <template x-if="$store.cart.count() > 0">
        <div class="border-t border-secondary-100 dark:border-secondary-700 px-5 py-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-secondary-600 dark:text-secondary-400">Subtotal</span>
                <span class="text-base font-bold text-secondary-900 dark:text-white" x-text="$store.currency.format($store.cart.subtotal)"></span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('frontend.cart') }}"
                    class="flex-1 text-center px-4 py-2.5 rounded-btn border-2 border-secondary-200 dark:border-secondary-600 text-sm font-semibold text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                    View Cart
                </a>
                <a href="{{ route('frontend.checkout') }}"
                    class="flex-1 text-center px-4 py-2.5 rounded-btn bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600 transition-colors">
                    Checkout
                </a>
            </div>
        </div>
    </template>
</div>
