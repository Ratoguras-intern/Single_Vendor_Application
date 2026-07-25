@extends('layouts.frontend')

@section('title', 'Shop - NBK Vertex')

@section('content')
<div style="background-color: var(--bloom-background);" class="px-4 py-8 sm:py-12 lg:py-16 lg:px-8 min-h-screen">
    <div class="text-center mx-auto mb-12 space-y-3">
        <h1 class="leading-tighter text-4xl font-semibold tracking-tight lg:leading-[1.1] lg:font-semibold xl:text-5xl xl:tracking-tighter" style="color: var(--bloom-primary);">
            <span data-i18n="Our Products" x-text="$store.i18n.t('Our Products')">Our Products</span>
        </h1>
        <p class="text-base max-w-3xl mx-auto sm:text-lg" style="color: var(--bloom-foreground);">
            <span data-i18n="Browse our complete collection of premium sneakers." x-text="$store.i18n.t('Browse our complete collection of premium sneakers.')">Browse our complete collection of premium sneakers.</span>
        </p>
    </div>

    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto">
        @forelse($products as $product)
            @include('frontend.partials.product-card', ['product' => $product])
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <div class="text-6xl mb-4">&#128269;</div>
                <h3 class="text-xl font-semibold mb-2" style="color: var(--bloom-foreground);"><span data-i18n="No products found" x-text="$store.i18n.t('No products found')">No products found</span></h3>
                <p style="color: var(--bloom-muted-foreground);"><span data-i18n="Try adjusting your filters or search terms" x-text="$store.i18n.t('Try adjusting your filters or search terms')">Try adjusting your filters or search terms</span></p>
            </div>
        @endforelse
    </div>
</div>
@endsection
