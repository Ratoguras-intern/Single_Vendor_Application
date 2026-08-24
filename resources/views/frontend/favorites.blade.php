@extends('layouts.frontend')

@section('title', 'My Favorites - ' . site_name())

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-6">
            <h1 class="page-heading" data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</h1>
            <p class="section-subheading mt-1">
                <span x-text="$store.wishlist.items.length"></span>
                <span data-i18n="item(s) in your favorites" x-text="$store.i18n.t('item(s) in your favorites')">{{ __('item(s) in your favorites') }}</span>
            </p>
        </div>

        @if($favorites->isEmpty())
            @include('frontend.partials.favorites-empty')
        @else
            <div class="grid gap-3 sm:gap-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($favorites as $product)
                    @if($product)
                        @php
                            $img = $product->primaryImage();
                            $discountPct = $product->discount_price ? round((($product->price - $product->discount_price) / $product->price) * 100) : 0;
                            $favProduct = [
                                'id' => $product->id,
                                'name' => $product->name,
                                'slug' => $product->slug,
                                'price' => (float) ($product->discount_price ?? $product->price),
                                'original_price' => $product->discount_price ? (float) $product->price : null,
                                'image' => product_image_url($img?->image),
                                'description' => $product->description,
                                'brand' => $product->brand?->name,
                                'stock' => $product->stock,
                                'discount_percentage' => $discountPct > 0 ? $discountPct : null,
                                'is_new_arrival' => $product->is_new_arrival,
                                'is_flash_sale' => $product->is_flash_sale,
                                'is_best_seller' => $product->is_best_seller,
                                'is_limited_edition' => $product->is_limited_edition,
                            ];
                        @endphp
                        <div class="h-full" x-show="$store.wishlist.has({{ $product->id }})" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                            <div class="relative">
                                @include('frontend.partials.product-card', ['product' => $favProduct])
                                <button x-on:click.stop="$store.wishlist.toggle({{ $product->id }})" class="absolute top-2 left-2 z-10 flex items-center gap-1.5 rounded-full bg-white/90 dark:bg-secondary-900/90 backdrop-blur-sm px-2.5 py-1.5 text-xs font-medium text-red-500 shadow-sm hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors border border-red-200 dark:border-red-500/30">
                                    <svg class="h-3.5 w-3.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div x-show="$store.wishlist.items.length === 0" x-transition>
                @include('frontend.partials.favorites-empty')
            </div>
        @endif
    </div>
</section>
@endsection
