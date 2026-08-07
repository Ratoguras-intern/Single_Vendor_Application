<div
    x-data
    x-show="$store.quickView.open"
    x-on:keydown.escape.window="$store.quickView.close()"
    x-on:keydown.tab.window="if (!$store.quickView.open) return; const els = $el.querySelectorAll('button, a, [tabindex]'); if (els.length === 0) return; const first = els[0], last = els[els.length - 1]; if (event.shiftKey && document.activeElement === first) { last.focus(); event.preventDefault(); } else if (!event.shiftKey && document.activeElement === last) { first.focus(); event.preventDefault(); }"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-label="Quick view"
    x-cloak
>
    <div x-on:click="$store.quickView.close()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative z-10 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-card bg-white dark:bg-secondary-900 shadow-mega" x-on:click.stop>
        <button x-on:click="$store.quickView.close()" aria-label="Close quick view"
            class="absolute top-3 right-3 z-20 h-10 w-10 rounded-full bg-white/80 dark:bg-secondary-800/80 backdrop-blur-sm text-secondary-600 dark:text-secondary-300 hover:bg-white dark:hover:bg-secondary-700 transition-all flex items-center justify-center shadow-sm">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>

        <template x-if="$store.quickView.product">
            <div class="grid sm:grid-cols-2 gap-6 p-6 sm:p-8">
                <div class="relative rounded-card overflow-hidden bg-secondary-100 dark:bg-white/5 aspect-[4/5]">
                    <img :src="$store.quickView.product.image" :alt="$store.quickView.product.name"
                        class="w-full h-full object-cover" loading="lazy" decoding="async"
                        onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'">
                </div>

                <div class="flex flex-col">
                    <template x-if="$store.quickView.product.brand">
                        <p class="text-[11px] font-semibold text-primary-600 dark:text-primary-400 uppercase tracking-[0.18em]"
                            x-text="$store.quickView.product.brand"></p>
                    </template>
                    <h3 class="mt-1 font-display text-2xl font-semibold text-secondary-900 dark:text-white leading-tight"
                        x-text="$store.quickView.product.name"></h3>

                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-secondary-900 dark:text-white"
                            x-text="$store.currency.format($store.quickView.product.price)"></span>
                        <template x-if="$store.quickView.hasDiscount()">
                            <span class="text-sm text-secondary-400 dark:text-secondary-500 line-through"
                                x-text="$store.currency.format($store.quickView.product.original_price)"></span>
                        </template>
                    </div>

                    <template x-if="$store.quickView.product.description">
                        <p class="mt-4 text-sm leading-relaxed text-secondary-600 dark:text-secondary-400 line-clamp-4"
                            x-text="$store.quickView.product.description"></p>
                    </template>

                    <template x-if="$store.quickView.product.stock !== undefined && $store.quickView.product.stock <= 0">
                        <p class="mt-4 text-sm font-semibold text-red-600 dark:text-red-400">Out of Stock</p>
                    </template>

                    <div class="mt-6 flex items-center gap-3" x-data="{ qty: 1 }">
                        <label class="label mb-0">Quantity</label>
                        <div class="flex items-center border border-secondary-300 dark:border-secondary-600 rounded-input overflow-hidden">
                            <button x-on:click="qty > 1 && qty--" :disabled="qty <= 1"
                                class="min-h-[42px] min-w-[42px] inline-flex items-center justify-center text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors disabled:pointer-events-none disabled:opacity-50">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                            </button>
                            <span x-text="qty" class="px-4 py-2 min-w-[52px] text-center font-medium text-secondary-900 dark:text-white"></span>
                            <button x-on:click="qty++"
                                class="min-h-[42px] min-w-[42px] inline-flex items-center justify-center text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2">
                        <button
                            x-on:click="$store.cart.addToCartQty($store.quickView.product, qty)"
                            :disabled="$store.quickView.product.stock !== undefined && $store.quickView.product.stock <= 0"
                            class="btn-primary w-full">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <span>Add to Cart</span>
                        </button>
                        <button
                            x-on:click="$store.cart.buyNowQty($store.quickView.product, qty)"
                            :disabled="$store.quickView.product.stock !== undefined && $store.quickView.product.stock <= 0"
                            class="btn-outline w-full">
                            <span>Buy Now</span>
                        </button>
                        <a :href="$store.quickView.product.url"
                            class="mt-1 inline-flex items-center justify-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                            View Full Details
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
