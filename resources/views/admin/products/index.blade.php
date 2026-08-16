@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Products</h2>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Product
        </a>
    </div>

    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, slug, SKU..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="min-w-[150px]">
                <label for="category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" id="category_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    @foreach ($categories as $categoryOption)
                        <option value="{{ $categoryOption['id'] }}" {{ request('category_id') == $categoryOption['id'] ? 'selected' : '' }}>{{ $categoryOption['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="brand_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                <select name="brand_id" id="brand_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="visibility" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Visibility</label>
                <select name="visibility" id="visibility"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="is_featured" {{ request('visibility') === 'is_featured' ? 'selected' : '' }}>Featured</option>
                    <option value="is_new_arrival" {{ request('visibility') === 'is_new_arrival' ? 'selected' : '' }}>New Arrivals</option>
                    <option value="is_trending" {{ request('visibility') === 'is_trending' ? 'selected' : '' }}>Trending</option>
                    <option value="is_best_seller" {{ request('visibility') === 'is_best_seller' ? 'selected' : '' }}>Best Sellers</option>
                    <option value="is_flash_sale" {{ request('visibility') === 'is_flash_sale' ? 'selected' : '' }}>Flash Sales</option>
                    <option value="is_recommended" {{ request('visibility') === 'is_recommended' ? 'selected' : '' }}>Recommended</option>
                    <option value="is_popular" {{ request('visibility') === 'is_popular' ? 'selected' : '' }}>Popular</option>
                    <option value="is_limited_edition" {{ request('visibility') === 'is_limited_edition' ? 'selected' : '' }}>Limited Edition</option>
                </select>
            </div>
            <div class="min-w-[130px]">
                <label for="per_page" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Show</label>
                <select name="per_page" id="per_page"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="10" {{ $perPage === '10' ? 'selected' : '' }}>10 / page</option>
                    <option value="25" {{ $perPage === '25' ? 'selected' : '' }}>25 / page</option>
                    <option value="50" {{ $perPage === '50' ? 'selected' : '' }}>50 / page</option>
                    <option value="100" {{ $perPage === '100' ? 'selected' : '' }}>100 / page</option>
                    <option value="all" {{ $perPage === 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Filter
                </button>
                <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"></th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Image</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">SKU</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Category</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Stock</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Visibility</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4"><input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500"></td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->id }}</td>
                            <td class="px-5 py-4">
                                @if ($product->primaryImage())
                                    <img src="{{ product_image_url($product->primaryImage()->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">N/A</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                <a href="{{ route('admin.products.show', $product) }}" class="hover:text-brand-500">{{ $product->name }}</a>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->sku }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ format_currency($product->price) }}
                                @if ($product->discount_price)
                                    <span class="ml-1 text-xs text-red-500">{{ format_currency($product->discount_price) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $product->stock }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="toggleProductStatus({{ $product->id }})"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                            {{ $product->status === 'active' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"
                                        title="{{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                            {{ $product->status === 'active' ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <span class="text-xs font-medium {{ $product->status === 'active' ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($product->is_featured)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_featured')" class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400" title="Click to toggle">Featured</button>
                                    @endif
                                    @if($product->is_new_arrival)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_new_arrival')" class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-semibold text-green-700 hover:bg-green-100 dark:bg-green-500/10 dark:text-green-400" title="Click to toggle">New</button>
                                    @endif
                                    @if($product->is_trending)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_trending')" class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2 py-0.5 text-[10px] font-semibold text-purple-700 hover:bg-purple-100 dark:bg-purple-500/10 dark:text-purple-400" title="Click to toggle">Trending</button>
                                    @endif
                                    @if($product->is_best_seller)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_best_seller')" class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400" title="Click to toggle">Best Seller</button>
                                    @endif
                                    @if($product->is_flash_sale)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_flash_sale')" class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-700 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400" title="Click to toggle">Flash</button>
                                    @endif
                                    @if($product->is_recommended)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_recommended')" class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400" title="Click to toggle">Recommended</button>
                                    @endif
                                    @if($product->is_popular)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_popular')" class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-500/10 dark:text-gray-400" title="Click to toggle">Popular</button>
                                    @endif
                                    @if($product->is_limited_edition)
                                        <button onclick="toggleProductFlag({{ $product->id }}, 'is_limited_edition')" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-800 hover:bg-amber-200 dark:bg-amber-500/10 dark:text-amber-300" title="Click to toggle">Limited</button>
                                    @endif
                                    @if(!$product->is_featured && !$product->is_new_arrival && !$product->is_trending && !$product->is_best_seller && !$product->is_flash_sale && !$product->is_recommended && !$product->is_popular && !$product->is_limited_edition)
                                        <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-brand-500 hover:text-brand-600">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M21 8V21H3V8" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 3H23V8H1V3Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No products found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Get started by adding your first product.</p>
                                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                                        Add Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-gray-200 px-5 py-3 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <button type="button" onclick="bulkDeleteProducts()" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                    Delete Selected
                </button>
                <form action="{{ route('admin.products.destroyAll') }}" method="POST" onsubmit="return confirm('Delete ALL products? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-400">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                        Delete All
                    </button>
                </form>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $products->total() ?? $products->count() }} products
                </span>
                @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $products->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/turbo-script">
function toggleProductStatus(productId) {
    fetch(`/admin/products/${productId}/toggle-status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    }).then(r => r.json()).then(data => {
        Turbo.visit(location.href, { action: 'replace' });
    }).catch(() => alert('Failed to update product status.'));
}

function toggleProductFlag(productId, flag) {
    fetch(`/admin/products/${productId}/toggle-flag/${flag}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    }).then(r => r.json()).then(data => {
        Turbo.visit(location.href, { action: 'replace' });
    });
}

document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.product-cb').forEach(cb => cb.checked = this.checked);
});

function bulkDeleteProducts() {
    const ids = [...document.querySelectorAll('.product-cb:checked')].map(cb => cb.value);

    if (ids.length === 0) {
        alert('Please select at least one product.');
        return;
    }

    if (!confirm(`Delete ${ids.length} selected product(s)? This action cannot be undone.`)) {
        return;
    }

    fetch('{{ route('admin.products.bulkDestroy') }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_ids: ids }),
    }).then(async r => {
        if (!r.ok) {
            const data = await r.json().catch(() => ({}));
            throw new Error(data.message || 'Failed to delete products.');
        }
        Turbo.visit(location.href, { action: 'replace' });
    }).catch(err => alert(err.message));
}
</script>
@endpush
