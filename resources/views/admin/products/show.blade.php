@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => route('admin.products.index')],
        ['label' => $product->name, 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Product Details</h2>
        <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Back to List
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->name }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Slug</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->slug }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">SKU</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->sku }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->category->name ?? '—' }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Brand</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->brand->name ?? '—' }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Price</label>
                    <p class="text-sm text-gray-800 dark:text-white">
                        ${{ number_format($product->price, 2) }}
                        @if ($product->discount_price)
                            <span class="ml-1 text-red-500">${{ number_format($product->discount_price, 2) }}</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Stock</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->stock }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $product->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->updated_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->description ?: '—' }}</p>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Visibility Flags</label>
                    <div class="flex flex-wrap gap-2">
                        @if($product->is_featured)
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">Featured</span>
                        @endif
                        @if($product->is_new_arrival)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 dark:bg-green-500/10 dark:text-green-400">New Arrival</span>
                        @endif
                        @if($product->is_trending)
                            <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-500/10 dark:text-purple-400">Trending</span>
                        @endif
                        @if($product->is_best_seller)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Best Seller</span>
                        @endif
                        @if($product->is_flash_sale)
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-500/10 dark:text-red-400">Flash Sale</span>
                        @endif
                        @if($product->is_recommended)
                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">Recommended</span>
                        @endif
                        @if($product->is_popular)
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-500/10 dark:text-gray-400">Popular</span>
                        @endif
                        @if($product->is_limited_edition)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">Limited Edition</span>
                        @endif
                        @if(!$product->is_featured && !$product->is_new_arrival && !$product->is_trending && !$product->is_best_seller && !$product->is_flash_sale && !$product->is_recommended && !$product->is_popular && !$product->is_limited_edition)
                            <span class="text-xs text-gray-400 dark:text-gray-500">No visibility flags set</span>
                        @endif
                    </div>
                </div>
            </div>

            @if ($product->images->count() > 0)
                <div class="mt-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Images</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($product->images as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->image) }}" alt="" class="h-24 w-24 rounded-lg object-cover">
                                @if ($image->is_primary)
                                    <span class="absolute -top-1 -left-1 rounded bg-brand-500 px-1.5 py-0.5 text-[10px] font-medium text-white">Primary</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Edit Product
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-500/10">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
