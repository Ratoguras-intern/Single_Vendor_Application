@extends('layouts.frontend')

@section('title', $product['name'] . ' - NBK Vertex')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('frontend.partials.breadcrumb', ['backUrl' => route('frontend.shop'), 'backLabel' => 'Back to Shop'])

    <div class="grid lg:grid-cols-2 gap-12 mb-16">
        <div class="space-y-4">
            <div class="w-full max-w-[500px] mx-auto flex flex-col items-center px-4">
                <div class="rounded-xl shadow-lg overflow-hidden mb-4 w-full">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="rounded-xl object-cover w-full h-auto max-h-[500px]" loading="eager" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'" />
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h1 class="text-3xl lg:text-4xl font-bold mb-4" style="color: var(--bloom-foreground);">{{ $product['name'] }}</h1>
            <div class="flex items-center gap-2 mb-4">
                <div class="flex items-center gap-1">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="h-4 w-4" style="color: var(--bloom-primary); fill: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>
                <span class="text-sm" style="color: var(--bloom-muted-foreground);">(4.8) &bull; 127 reviews</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-3xl font-bold" style="color: var(--bloom-foreground);"><span x-text="$store.currency.format({{ $product['price'] }})"></span></span>
            </div>

            <p class="leading-relaxed" style="color: var(--bloom-muted-foreground);">{{ $product['description'] }}</p>

            <div class="h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>

            <div class="space-y-4" x-data="{ quantity: 1 }">
                <div>
                    <label class="text-sm font-medium mb-2 block" style="color: var(--bloom-foreground);"><span data-i18n="Quantity" x-text="$store.i18n.t('Quantity')">Quantity</span></label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border rounded-lg" style="border-color: var(--bloom-border);">
                            <button x-on:click="quantity > 1 && quantity--" :disabled="quantity <= 1" class="h-10 w-10 inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors hover:bg-gray-100 rounded-r-none disabled:pointer-events-none disabled:opacity-50">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                            </button>
                            <span x-text="quantity" class="px-4 py-2 min-w-[60px] text-center font-medium"></span>
                            <button x-on:click="quantity++" class="h-10 w-10 inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors hover:bg-gray-100 rounded-l-none">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button x-on:click="$store.cart.addToCartQty({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' }, quantity)" class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-all duration-300 h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                    </button>

                    <button x-on:click="$store.cart.buyNowQty({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' }, quantity)" class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <span data-i18n="Buy Now" x-text="$store.i18n.t('Buy Now')">{{ __('Buy Now') }}</span>
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <button x-on:click="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'text-red-500' : ''" class="inline-flex items-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 px-3 hover:bg-gray-100" style="color: var(--bloom-muted-foreground);">
                        <svg class="h-4 w-4" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                        <span x-text="$store.wishlist.has({{ $product['id'] }}) ? 'Remove from Wishlist' : 'Add to Wishlist'"></span>
                    </button>

                    <button class="inline-flex items-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-8 px-3 hover:bg-gray-100" style="color: var(--bloom-muted-foreground);">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>
                        Share
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.partials.features')

    {{-- Related Products --}}
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold" style="color: var(--bloom-foreground);"><span data-i18n="Related Products" x-text="$store.i18n.t('Related Products')">{{ __('Related Products') }}</span></h2>
            <a href="{{ route('frontend.shop') }}" class="text-sm font-medium hover:opacity-80 transition-colors" style="color: var(--bloom-primary);"><span data-i18n="View All" x-text="$store.i18n.t('View All')">{{ __('View All') }}</span></a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                @include('frontend.partials.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
</div>
@endsection
