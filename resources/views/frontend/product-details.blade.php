@extends('layouts.frontend')

@section('title', $product['name'] . ' - NBK Vertex')

@section('content')
<section class="py-8 sm:py-12">
    <div class="section">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 mb-10">
            <div class="space-y-4">
                <div class="rounded-card overflow-hidden bg-secondary-100 dark:bg-white/5">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-auto max-h-[500px] object-cover" loading="eager" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'" />
                </div>
            </div>

            <div class="space-y-6">
                <h1 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-white">{{ $product['name'] }}</h1>

                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-0.5">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="h-4 w-4 text-primary-500 dark:text-primary-400 fill-primary-500 dark:fill-primary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm text-secondary-500 dark:text-secondary-400">(4.8) &bull; 127 reviews</span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-3xl font-bold text-secondary-900 dark:text-white">{{ format_currency($product['price']) }}</span>
                </div>

                <p class="text-secondary-600 dark:text-secondary-400 leading-relaxed">{{ $product['description'] }}</p>

                <div class="divider"></div>

                <div class="space-y-4" x-data="{ quantity: 1 }">
                    <div>
                        <label class="label"><span data-i18n="Quantity" x-text="$store.i18n.t('Quantity')">Quantity</span></label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center border border-secondary-300 dark:border-secondary-600 rounded-input overflow-hidden">
                                <button x-on:click="quantity > 1 && quantity--" :disabled="quantity <= 1" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors disabled:pointer-events-none disabled:opacity-50">
                                     <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                                 </button>
                                 <span x-text="quantity" class="px-4 py-2 min-w-[60px] text-center font-medium text-secondary-900 dark:text-white"></span>
                                 <button x-on:click="quantity++" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                 </button>
                            </div>
                            <div x-show="quantity > 1" x-transition class="rounded-lg bg-primary-50 dark:bg-primary-950/30 border border-primary-200 dark:border-primary-800 px-4 py-2">
                                @php $symbol = config('currency.supported.' . admin_currency() . '.symbol', '$'); @endphp
                                <span class="text-lg font-bold text-primary-700 dark:text-primary-300" x-text="'{{ $symbol }}' + ({{ $product['price'] }} * quantity).toFixed(2)"></span>
                                <span class="text-xs text-primary-500 dark:text-primary-400 ml-1">({{ format_currency($product['price']) }} each)</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button x-on:click="$store.cart.addToCartQty({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' }, quantity)" class="btn-primary flex-1">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                        </button>

                        <button x-on:click="$store.cart.buyNowQty({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' }, quantity)" class="btn-outline flex-1">
                            <span data-i18n="Buy Now" x-text="$store.i18n.t('Buy Now')">{{ __('Buy Now') }}</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        <button x-on:click="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'text-red-500' : ''" class="btn-ghost btn-sm">
                            <svg class="h-4 w-4" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                            <span x-text="$store.wishlist.has({{ $product['id'] }}) ? 'Remove from Wishlist' : 'Add to Wishlist'"></span>
                        </button>

                        <button class="btn-ghost btn-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>
                            Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('frontend.partials.features')

        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="section-heading" data-i18n="Related Products" x-text="$store.i18n.t('Related Products')">{{ __('Related Products') }}</h2>
                <a href="{{ route('frontend.shop') }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors"><span data-i18n="View All" x-text="$store.i18n.t('View All')">{{ __('View All') }}</span></a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($relatedProducts as $related)
                    @include('frontend.partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
