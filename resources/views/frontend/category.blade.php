@extends('layouts.frontend')

@php
    $seoTitle = $category->seo_title ?: $category->name . ' - ' . config('app.name');
    $seoDesc = $category->seo_description ?: $category->description;
    $sort = request('sort', 'latest');
    $viewMode = request('view', 'grid');
    $activeFilterCount = collect(request()->query())->except(['page', 'sort', 'view'])->filter()->count();
@endphp

@section('title', $seoTitle)

@push('styles')
<meta name="description" content="{{ $seoDesc }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDesc }}">
@if($category->banner_url)
<meta property="og:image" content="{{ $category->banner_url }}">
@endif
@endpush

@section('content')

{{-- ═══ PHASE 1: Hero Section ═══ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-secondary-900 via-secondary-800 to-primary-900">
    @if($category->banner_url)
        <img src="{{ $category->banner_url }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover opacity-30" loading="lazy">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
    <div class="section relative py-12 sm:py-16 lg:py-20">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-sm text-white/60 mb-6" aria-label="Breadcrumb">
            @foreach($breadcrumbs as $i => $crumb)
                @if($i > 0)
                    <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                @endif
                @if($crumb['url'])
                    <a href="{{ $crumb['url'] }}" class="hover:text-white transition-colors">{{ $crumb['name'] }}</a>
                @else
                    <span class="text-white" aria-current="page">{{ $crumb['name'] }}</span>
                @endif
            @endforeach
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            @if($category->icon_url)
                <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" class="h-16 w-16 rounded-xl object-cover shadow-lg shrink-0 hidden sm:block">
            @endif
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">{{ $category->name }}</h1>
                    @if($category->featured)
                        <span class="badge bg-primary-500/20 text-primary-300 text-xs font-semibold border border-primary-400/30 hidden sm:inline-flex">Featured</span>
                    @endif
                </div>
                @if($category->description)
                    <p class="text-white/70 max-w-2xl text-sm sm:text-base">{{ $category->description }}</p>
                @endif
                <div class="mt-4 flex items-center gap-4 text-sm text-white/60">
                    <span class="flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        {{ number_format($products->total()) }} Products
                    </span>
                    @if($children->isNotEmpty())
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z"/></svg>
                            {{ $children->count() }} Subcategories
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('frontend.shop') }}" class="btn-primary btn-lg shrink-0 self-start hidden sm:inline-flex">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21h.008v.008H3.75V21Zm16.5 0h.008v.008H20.25V21Z"/></svg>
                Shop All
            </a>
        </div>
    </div>
</section>

{{-- ═══ PHASE 4: Subcategory Cards ═══ --}}
@if($children->isNotEmpty())
<section class="py-8 sm:py-10 border-b border-secondary-200 dark:border-secondary-800">
    <div class="section">
        <h2 class="text-lg font-bold text-secondary-900 dark:text-white mb-5">Browse Subcategories</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($children as $child)
                <a href="{{ route('frontend.category', $child->slug) }}" class="group relative overflow-hidden rounded-card bg-white dark:bg-secondary-800 shadow-card hover:shadow-card-hover transition-all duration-200 hover:-translate-y-0.5">
                    <div class="aspect-[4/3] overflow-hidden bg-secondary-100 dark:bg-secondary-700">
                        @if($child->thumbnail_url)
                            <img src="{{ $child->display_image }}" alt="{{ $child->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/20 flex items-center justify-center">
                                <svg class="w-10 h-10 text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-secondary-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $child->name }}</h3>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">{{ $child->products_count }} {{ Str::plural('product', $child->products_count) }}</p>
                        <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-primary-600 dark:text-primary-400 group-hover:gap-2 transition-all">
                            Shop Now
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ PHASE 2+3+5: Products Section (Toolbar + Filters + Grid) ═══ --}}
<section class="py-8 sm:py-10 lg:py-12" x-data="{
    total: {{ $products->total() }},
    from: {{ $products->firstItem() ?? 0 }},
    to: {{ $products->lastItem() ?? 0 }},
    perPage: {{ $products->perPage() }},
    viewMode: localStorage.getItem('categoryView') || 'grid',
}">
    <div class="section">

        {{-- Toolbar --}}
        @include('frontend.partials.category-toolbar', [
            'total' => $products->total(),
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage(),
            'perPage' => $products->perPage(),
            'sort' => $sort,
            'viewMode' => $viewMode,
            'viewStorageKey' => 'categoryView',
            'categoryName' => $category->name,
        ])

        <div class="flex gap-8">
            {{-- Filter Sidebar (Desktop) --}}
            <div class="hidden lg:block w-64 shrink-0 sticky top-24 self-start max-h-[calc(100vh-8rem)] overflow-y-auto scrollbar-hide">
                @include('frontend.partials.category-filters', [
                    'brands' => $brands,
                    'priceRange' => $priceRange,
                ])
                @include('frontend.partials.banner-sidebar')
            </div>

            {{-- Filter Drawer (Mobile) --}}
            <div class="lg:hidden">
                @include('frontend.partials.category-filters', [
                    'brands' => $brands,
                    'priceRange' => $priceRange,
                ])
            </div>

            {{-- Product Grid --}}
            <div class="flex-1 min-w-0">
                @if($products->count() > 0)
                    <div :class="viewMode === 'list' ? 'grid grid-cols-1 gap-4' : 'grid gap-6 grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4'">
                        @foreach($products as $product)
                            @include('frontend.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        @include('frontend.partials.category-pagination', ['paginator' => $products])
                    </div>
                @else
                    {{-- ═══ PHASE 6: Empty State ═══ --}}
                    <div class="text-center py-20">
                        <div class="w-20 h-20 rounded-full bg-secondary-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-6">
                            <svg class="h-10 w-10 text-secondary-300 dark:text-secondary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-2">No products found</h3>
                        <p class="text-secondary-500 dark:text-secondary-400 mb-8 max-w-md mx-auto">We couldn't find any products matching your criteria. Try adjusting your filters or browse our other categories.</p>
                        <div class="flex items-center justify-center gap-3">
                            @if($activeFilterCount > 0)
                                <a href="{{ route('frontend.category', $category->slug) }}" class="btn-primary">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                    Clear Filters
                                </a>
                            @endif
                            <a href="{{ route('frontend.shop') }}" class="btn-outline">
                                Browse All Products
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══ PHASE 8: Recommendations ═══ --}}
@if(!empty($recommendations['trending']) && count($recommendations['trending']) > 1)
<section class="py-10 sm:py-12 border-t border-secondary-200 dark:border-secondary-800">
    <div class="section">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white">Trending in {{ $category->name }}</h2>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Most popular products in this category</p>
            </div>
        </div>
        <div class="grid gap-6 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
            @foreach(array_slice($recommendations['trending'], 0, 8) as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($recommendations['new_arrivals']) && count($recommendations['new_arrivals']) > 1)
<section class="py-10 sm:py-12 border-t border-secondary-200 dark:border-secondary-800">
    <div class="section">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white">New Arrivals in {{ $category->name }}</h2>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Fresh products just added</p>
            </div>
        </div>
        <div class="grid gap-6 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
            @foreach(array_slice($recommendations['new_arrivals'], 0, 8) as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ PHASE 9: Trust Section ═══ --}}
<section class="py-10 sm:py-12 border-t border-secondary-200 dark:border-secondary-800">
    <div class="section">
        @include('frontend.partials.features')
    </div>
</section>

{{-- ═══ PHASE 10: Newsletter ═══ --}}
@include('frontend.home.newsletter-cta')

@endsection
