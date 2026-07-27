@extends('layouts.frontend')

@section('title', 'My Favorites - NBK Vertex')

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8">
            <h1 class="page-heading" data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</h1>
            <p class="section-subheading mt-1">
                {{ $favorites->count() }} <span data-i18n="item(s) in your favorites" x-text="$store.i18n.t('item(s) in your favorites')">{{ __('item(s) in your favorites') }}</span>
            </p>
        </div>

        @if($favorites->isEmpty())
            <div class="py-32">
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
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($favorites as $product)
                    @if($product)
                    <div class="card-hover group p-0 overflow-hidden">
                        <div class="relative">
                            <a href="{{ route('frontend.product.show', $product->id) }}" class="block">
                                <div class="aspect-square overflow-hidden bg-secondary-100 dark:bg-white/5">
                                    @php
                                        $img = $product->primaryImage();
                                    @endphp
                                    <img src="{{ $img ? $img->image : asset('frontend-assets/images/no-image.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" />
                                </div>
                            </a>
                            <button x-on:click="$store.wishlist.remove({{ $product->id }})" class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 dark:bg-secondary-900/80 backdrop-blur-sm hover:bg-white dark:hover:bg-secondary-900 transition-all duration-200 text-red-500">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('frontend.product.show', $product->id) }}">
                                <h2 class="font-semibold text-secondary-900 dark:text-white line-clamp-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $product->name }}</h2>
                            </a>
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $product->price }})"></span></span>
                            </div>
                            <button x-on:click="$store.cart.add({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $img ? $img->image : asset('frontend-assets/images/no-image.jpg') }}' })" class="btn-primary w-full btn-sm">
                                <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                            </button>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
