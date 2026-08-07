@extends('layouts.frontend')

@section('title', 'My Favorites - NBK Vertex')

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-6">
            <h1 class="page-heading" data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</h1>
            <p class="section-subheading mt-1">
                {{ $favorites->count() }} <span data-i18n="item(s) in your favorites" x-text="$store.i18n.t('item(s) in your favorites')">{{ __('item(s) in your favorites') }}</span>
            </p>
        </div>

        @if($favorites->isEmpty())
            <div class="py-20">
                <div class="max-w-2xl mx-auto text-center">
                    <div class="w-20 h-20 rounded-full bg-secondary-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-secondary-400 dark:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                    </div>
                    <h1 class="text-3xl font-bold text-secondary-900 dark:text-white mb-3" data-i18n="Your favorites list is empty" x-text="$store.i18n.t('Your favorites list is empty')">{{ __('Your favorites list is empty') }}</h1>
                    <p class="text-lg text-secondary-500 dark:text-secondary-400 mb-6" data-i18n="Start adding products you love!" x-text="$store.i18n.t('Start adding products you love!')">{{ __('Start adding products you love!') }}</p>
                    <a href="{{ route('frontend.shop') }}" class="btn-primary"><span data-i18n="Browse Products" x-text="$store.i18n.t('Browse Products')">{{ __('Browse Products') }}</span></a>
                </div>
            </div>
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
                                'image' => $img ? Storage::disk('public')->url($img->image) : asset('frontend-assets/images/no-image.jpg'),
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
                        <div class="h-full">
                            @include('frontend.partials.product-card', ['product' => $favProduct])
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
