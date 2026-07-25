@extends('layouts.frontend')

@section('title', 'My Favorites - NBK Vertex')

@section('content')
<div x-data class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold" style="color: var(--bloom-foreground);"><span data-i18n="My Favorites" x-text="$store.i18n.t('My Favorites')">{{ __('My Favorites') }}</span></h1>
        <p class="mt-2" style="color: var(--bloom-muted-foreground);">
            {{ $favorites->count() }} <span data-i18n="item(s) in your favorites" x-text="$store.i18n.t('item(s) in your favorites')">{{ __('item(s) in your favorites') }}</span>
        </p>
    </div>

    @if($favorites->isEmpty())
        <div class="py-32">
            <div class="max-w-2xl mx-auto text-center">
                <div class="mb-8">
                    <svg class="h-24 w-24 mx-auto mb-4" style="color: var(--bloom-muted-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                    <h1 class="text-3xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Your favorites list is empty" x-text="$store.i18n.t('Your favorites list is empty')">{{ __('Your favorites list is empty') }}</span></h1>
                    <p class="text-lg" style="color: var(--bloom-muted-foreground);"><span data-i18n="Start adding products you love!" x-text="$store.i18n.t('Start adding products you love!')">{{ __('Start adding products you love!') }}</span></p>
                </div>
                <a href="{{ route('frontend.shop') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);"><span data-i18n="Browse Products" x-text="$store.i18n.t('Browse Products')">{{ __('Browse Products') }}</span></a>
            </div>
        </div>
    @else
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-7xl">
            @foreach($favorites as $product)
                @if($product)
                <div class="rounded-xl border bg-white shadow overflow-hidden group" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <div class="relative">
                        <a href="{{ route('frontend.product.show', $product->id) }}" class="block">
                            <div class="aspect-square overflow-hidden bg-gray-100">
                                @php
                                    $img = $product->primaryImage();
                                @endphp
                                <img src="{{ $img ? $img->image : asset('frontend-assets/images/no-image.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" />
                            </div>
                        </a>
                        <button x-on:click="$store.wishlist.remove({{ $product->id }})" class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white transition-all duration-200 text-red-500">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('frontend.product.show', $product->id) }}">
                            <h2 class="font-semibold line-clamp-2 hover:text-gray-900 transition-colors" style="color: var(--bloom-card-foreground);">{{ $product->name }}</h2>
                        </a>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold" style="color: var(--bloom-card-foreground);"><span x-text="$store.currency.format({{ $product->price }})"></span></span>
                        </div>
                        <button x-on:click="$store.cart.add({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $img ? $img->image : asset('frontend-assets/images/no-image.jpg') }}' })" class="w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-9 px-4 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                            <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                        </button>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
