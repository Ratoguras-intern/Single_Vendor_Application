@extends('layouts.frontend')

@php
    $seoTitle = 'Shop - ' . config('app.name');
    $seoDesc = 'Browse our complete collection of premium products.';
    $sort = request('sort', 'latest');
    $viewMode = request('view', 'grid');
@endphp

@section('title', $seoTitle)

@push('styles')
<meta name="description" content="{{ $seoDesc }}">
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="section pt-6 pb-2" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm text-secondary-500 dark:text-secondary-400">
        <li><a href="{{ route('frontend.home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Home</a></li>
        <li>/</li>
        <li class="text-secondary-900 dark:text-white font-medium">Shop</li>
    </ol>
</nav>

{{-- Shop Layout --}}
<div class="shop-layout" x-data="xShop({{ $products->total() }}, {{ $products->firstItem() ?? 0 }}, {{ $products->lastItem() ?? 0 }}, {{ $products->perPage() }})">

    {{-- Filter Sidebar (Desktop) --}}
    <aside class="shop-sidebar hidden lg:block">
        @include('frontend.partials.category-filters', [
            'brands' => $brands,
            'priceRange' => $priceRange,
            'ajax' => true,
        ])
    </aside>

    {{-- Main Content --}}
    <div class="shop-content" x-data="{ viewMode: localStorage.getItem('shopView') || 'grid' }">

        {{-- Toolbar --}}
        @include('frontend.partials.category-toolbar', [
            'total' => $products->total(),
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage(),
            'perPage' => $products->perPage(),
            'sort' => $sort,
            'viewMode' => $viewMode,
            'viewStorageKey' => 'shopView',
            'ajax' => true,
        ])

        {{-- Product Grid --}}
        <div id="shop-products">
            @include('frontend.partials.shop-products-content', [
                'products' => $products,
                'viewMode' => $viewMode,
                'ajax' => true,
            ])
        </div>

        {{-- Loading Overlay --}}
        <div x-show="loading" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-white/60 dark:bg-secondary-900/60 backdrop-blur-sm z-10 flex items-center justify-center" style="display: none;">
            <div class="flex items-center gap-3 text-secondary-600 dark:text-secondary-400">
                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                <span class="text-sm font-medium">Loading products...</span>
            </div>
        </div>

    </div>
</div>

{{-- Mobile Filter Drawer (outside flex) --}}
<div class="lg:hidden">
    @include('frontend.partials.category-filters', [
        'brands' => $brands,
        'priceRange' => $priceRange,
        'ajax' => true,
    ])
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('xShop', (total, from, to, perPage) => ({
        loading: false,
        total, from, to, perPage,

        init() {
            window.addEventListener('popstate', () => this.fetchProducts(window.location.href, false));
            window.addEventListener('shop:apply', (e) => this.fetchProducts(e.detail.url));
            window.addEventListener('shop:clear', (e) => this.fetchProducts(e.detail.url));
        },

        async fetchProducts(url, push = true) {
            if (this.loading) return;
            this.loading = true;

            try {
                const separator = url.includes('?') ? '&' : '?';
                const ajaxUrl = url + separator + 'ajax=1';
                const response = await fetch(ajaxUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('shop-products');

                if (newContent) {
                    document.getElementById('shop-products').innerHTML = newContent.innerHTML;
                }

                if (push) {
                    history.pushState({}, '', url);
                }

                this.total = newContent?.querySelector('.sr-only')?.textContent?.match(/\d+/)?.[0] || this.total;
            } catch (e) {
                console.error('Shop fetch error:', e);
                window.location.href = url;
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>

@endsection
