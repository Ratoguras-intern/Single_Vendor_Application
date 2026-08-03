@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Product Sections', 'url' => route('admin.product-sections.index', 'featured-products')],
        ['label' => $sectionLabel, 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $sectionLabel }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Assign products by toggling their visibility flag. Max: {{ $maxProducts }} products.</p>
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
        @foreach(['featured-products' => 'Featured', 'new-arrivals' => 'New Arrivals', 'trending' => 'Trending', 'best-sellers' => 'Best Sellers', 'flash-sale' => 'Flash Sale', 'recommended' => 'Recommended', 'popular' => 'Popular'] as $key => $label)
            <a href="{{ route('admin.product-sections.index', $key) }}"
                class="inline-flex items-center gap-1.5 rounded-lg px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors
                    {{ $sectionKey === $key ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Assigned Products -->
    <div class="mb-8 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Assigned Products ({{ $assigned->total() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Product</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">SKU</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Stock</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($assigned as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product->primaryImage())
                                        <img src="{{ asset('storage/' . $product->primaryImage()->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                            <span class="text-xs text-gray-500">N/A</span>
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->sku }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($product->discount_price ?? $product->price, 2) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->stock }}</td>
                            <td class="px-5 py-4">
                                <form action="{{ route('admin.product-sections.remove', [$sectionKey, $product->id]) }}" method="POST" onsubmit="return confirm('Remove?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 text-sm font-medium">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No products assigned yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $assigned->links() }}
        </div>
    </div>

    <!-- Available Products -->
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-5 py-3 dark:border-gray-800">
            <form action="{{ route('admin.product-sections.index', $sectionKey) }}" method="GET" class="flex items-end gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products to add..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">Search</button>
            </form>
        </div>
        @if($available->count() > 0)
            <form action="{{ route('admin.product-sections.bulkAssign', $sectionKey) }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"></th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Product</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">SKU</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($available as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-4"><input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500"></td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($product->primaryImage())
                                                <img src="{{ asset('storage/' . $product->primaryImage()->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800"><span class="text-xs text-gray-500">N/A</span></div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->sku }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($product->discount_price ?? $product->price, 2) }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-5 py-3 dark:border-gray-800">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Add Selected</button>
                </div>
            </form>
        @else
            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No available products to add.</div>
        @endif
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.product-cb').forEach(cb => cb.checked = this.checked);
        });
    </script>
    @endpush
@endsection
