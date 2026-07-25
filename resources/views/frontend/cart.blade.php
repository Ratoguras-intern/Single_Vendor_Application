@extends('layouts.frontend')

@section('title', 'Shopping Cart - NBK Vertex')

@section('content')
<div x-data class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div x-show="$store.cart.loading" class="py-32">
        <div class="max-w-2xl mx-auto text-center">
            <p style="color: var(--bloom-muted-foreground);">Loading your cart...</p>
        </div>
    </div>

    <div x-show="!$store.cart.loading && $store.cart.count() === 0" style="display: none;" class="py-32">
        <div class="max-w-2xl mx-auto text-center">
            <div class="mb-8">
                <svg class="h-24 w-24 mx-auto mb-4" style="color: var(--bloom-muted-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                <h1 class="text-3xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Your cart is empty" x-text="$store.i18n.t('Your cart is empty')">{{ __('Your cart is empty') }}</span></h1>
                <p class="text-lg" style="color: var(--bloom-muted-foreground);"><span data-i18n="Looks like you haven't added anything to your cart yet." x-text="$store.i18n.t(&quot;Looks like you haven't added anything to your cart yet.&quot;)">{{ __("Looks like you haven't added anything to your cart yet.") }}</span></p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('frontend.shop') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);"><span data-i18n="Continue Shopping" x-text="$store.i18n.t('Continue Shopping')">{{ __('Continue Shopping') }}</span></a>

                <div class="flex items-center justify-center gap-6 text-sm" style="color: var(--bloom-muted-foreground);">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        Free shipping over $50
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                        Secure checkout
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="!$store.cart.loading && $store.cart.count() > 0" style="display: none;">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold" style="color: var(--bloom-foreground);"><span data-i18n="Shopping Cart" x-text="$store.i18n.t('Shopping Cart')">{{ __('Shopping Cart') }}</span></h1>
                <p class="mt-2" style="color: var(--bloom-muted-foreground);">
                    <span x-text="$store.cart.count()"></span> <span data-i18n="item(s) in your cart" x-text="$store.i18n.t('item(s) in your cart')">{{ __('item(s) in your cart') }}</span>
                </p>
            </div>
            <a href="{{ route('frontend.shop') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-colors hover:text-gray-900" style="color: var(--bloom-muted-foreground);">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                <span data-i18n="Continue Shopping" x-text="$store.i18n.t('Continue Shopping')">{{ __('Continue Shopping') }}</span>
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border bg-white shadow" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <div class="flex items-center justify-between p-6 pb-4">
                        <h2 class="text-lg font-semibold"><span data-i18n="Cart Items" x-text="$store.i18n.t('Cart Items')">{{ __('Cart Items') }}</span></h2>
                        <button x-on:click="$store.cart.clear()" class="inline-flex items-center gap-2 whitespace-nowrap rounded-sm text-xs font-medium transition-colors h-8 px-3 hover:bg-gray-100" style="color: var(--bloom-muted-foreground);">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            <span data-i18n="Clear All" x-text="$store.i18n.t('Clear All')">{{ __('Clear All') }}</span>
                        </button>
                    </div>

                    <div class="px-6 pb-6 space-y-4">
                        <template x-for="(item, index) in $store.cart.items" :key="item.id + '-' + index">
                            <div>
                                <div class="flex items-start gap-4">
                                    <div class="relative w-[100px] h-[100px] shrink-0">
                                        <img :src="item.image" :alt="item.name" class="rounded-lg object-cover w-full h-full bg-gray-100" />
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0 pr-4">
                                                <h2 class="font-semibold line-clamp-2" style="color: var(--bloom-foreground);" x-text="item.name"></h2>
                                                <p class="text-sm mt-1" style="color: var(--bloom-muted-foreground);">
                                                    <span x-text="$store.currency.format(item.price)"></span> <span data-i18n="each" x-text="$store.i18n.t('each')">each</span>
                                                </p>
                                            </div>
                                            <button x-on:click="$store.cart.remove(item.id)" class="shrink-0 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 w-8 hover:bg-gray-100 hover:text-red-500" style="color: var(--bloom-muted-foreground);">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            </button>
                                        </div>

                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center border rounded-lg" style="border-color: var(--bloom-border);">
                                                <button x-on:click="$store.cart.updateQuantity(item.id, item.quantity - 1)" :disabled="item.quantity <= 1" class="h-8 w-8 inline-flex items-center justify-center text-sm font-medium transition-colors hover:bg-gray-100 rounded-r-none disabled:pointer-events-none disabled:opacity-50">
                                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                                                </button>
                                                <span x-text="item.quantity" class="px-4 py-2 min-w-[50px] text-center text-sm font-medium"></span>
                                                <button x-on:click="$store.cart.updateQuantity(item.id, item.quantity + 1)" class="h-8 w-8 inline-flex items-center justify-center text-sm font-medium transition-colors hover:bg-gray-100 rounded-l-none">
                                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                                </button>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-lg font-bold" style="color: var(--bloom-foreground);">
                                                    <span x-text="$store.currency.format(item.price * item.quantity)"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="index < $store.cart.items.length - 1" class="mt-4 h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="rounded-xl border bg-white shadow sticky top-4" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold"><span data-i18n="Order Summary" x-text="$store.i18n.t('Order Summary')">{{ __('Order Summary') }}</span></h2>
                    </div>

                    <div class="px-6 pb-6 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--bloom-muted-foreground);"><span data-i18n="Subtotal" x-text="$store.i18n.t('Subtotal')">{{ __('Subtotal') }}</span> (<span x-text="$store.cart.count()"></span> <span data-i18n="items" x-text="$store.i18n.t('items')">items</span>)</span>
                                <span class="font-medium"><span x-text="$store.currency.format($store.cart.subtotal)"></span></span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span style="color: var(--bloom-muted-foreground);"><span data-i18n="Shipping" x-text="$store.i18n.t('Shipping')">{{ __('Shipping') }}</span></span>
                                <span class="font-medium">
                                    <template x-if="$store.cart.shipping === 0">
                                        <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold border-transparent shadow" style="background-color: var(--bloom-secondary); color: var(--bloom-secondary-foreground);"><span data-i18n="Free" x-text="$store.i18n.t('Free')">{{ __('Free') }}</span></span>
                                    </template>
                                    <template x-if="$store.cart.shipping > 0">
                                        <span x-text="$store.currency.format($store.cart.shipping)"></span>
                                    </template>
                                </span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span style="color: var(--bloom-muted-foreground);"><span data-i18n="Tax" x-text="$store.i18n.t('Tax')">{{ __('Tax') }}</span></span>
                                <span class="font-medium"><span x-text="$store.currency.format($store.cart.tax)"></span></span>
                            </div>

                            <div class="h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>

                            <div class="flex justify-between">
                                <span class="text-lg font-semibold"><span data-i18n="Total" x-text="$store.i18n.t('Total')">{{ __('Total') }}</span></span>
                                <span class="text-lg font-bold" style="color: var(--bloom-primary);"><span x-text="$store.currency.format($store.cart.total)"></span></span>
                            </div>
                        </div>

                        <div x-show="$store.cart.shipping > 0" style="display: none;" class="p-3 rounded-lg" style="background-color: rgba(253, 246, 227, 0.1); border: 1px solid rgba(253, 246, 227, 0.2);">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4" style="color: var(--bloom-accent-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                <span class="text-sm font-medium" style="color: var(--bloom-accent-foreground);"><span data-i18n="Free shipping on orders over" x-text="$store.i18n.t('Free shipping on orders over')">Free shipping on orders over</span> <span x-text="$store.currency.format(50)"></span></span>
                            </div>
                            <p class="text-xs" style="color: var(--bloom-muted-foreground);"><span data-i18n="Add" x-text="$store.i18n.t('Add')">Add</span> <span x-text="$store.currency.format(50 - $store.cart.subtotal)"></span> <span data-i18n="more to qualify!" x-text="$store.i18n.t('more to qualify!')">more to qualify!</span></p>
                        </div>

                        <a href="{{ route('frontend.checkout') }}" class="w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                            <span data-i18n="Proceed to Checkout" x-text="$store.i18n.t('Proceed to Checkout')">{{ __('Proceed to Checkout') }}</span>
                        </a>

                        <div class="space-y-3 pt-4 border-t" style="border-color: var(--bloom-border);">
                            <div class="flex items-center gap-3 text-sm" style="color: var(--bloom-muted-foreground);">
                                <svg class="h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                <span><span data-i18n="Secure SSL checkout" x-text="$store.i18n.t('Secure SSL checkout')">{{ __('Secure SSL checkout') }}</span></span>
                            </div>
                            <div class="flex items-center gap-3 text-sm" style="color: var(--bloom-muted-foreground);">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                <span><span data-i18n="Free returns within 30 days" x-text="$store.i18n.t('Free returns within 30 days')">{{ __('Free returns within 30 days') }}</span></span>
                            </div>
                            <div class="flex items-center gap-3 text-sm" style="color: var(--bloom-muted-foreground);">
                                <svg class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                <span><span data-i18n="24/7 customer support" x-text="$store.i18n.t('24/7 customer support')">{{ __('24/7 customer support') }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <div class="rounded-xl border bg-white shadow" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                <div class="p-6">
                    <h2 class="text-lg font-semibold"><span data-i18n="You might also like" x-text="$store.i18n.t('You might also like')">{{ __('You might also like') }}</span></h2>
                </div>
                <div class="px-6 pb-6">
                    <div class="text-center py-8">
                        <p class="mb-4" style="color: var(--bloom-muted-foreground);"><span data-i18n="Discover more products that match your style" x-text="$store.i18n.t('Discover more products that match your style')">{{ __('Discover more products that match your style') }}</span></p>
                        <a href="{{ route('frontend.shop') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-9 px-4 border border-gray-300 bg-white shadow-sm hover:bg-gray-50"><span data-i18n="Browse Products" x-text="$store.i18n.t('Browse Products')">{{ __('Browse Products') }}</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
