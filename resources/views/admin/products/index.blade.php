@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Products</h2>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Product
        </a>
    </div>

    @php
        $catOptions = collect($categories)->pluck('name', 'id')->toArray();
        $brandOptions = $brands->pluck('name', 'id')->toArray();
    @endphp
    <x-admin.filter-bar action="{{ route('admin.products.index') }}" :hasFilters="request()->hasAny(['search','category_id','brand_id','status','visibility','per_page'])">
        <x-admin.filter-search name="search" label="Search" placeholder="Search by name, slug, SKU..." />
        <x-admin.filter-select name="category_id" label="Category" :options="$catOptions" />
        <x-admin.filter-select name="brand_id" label="Brand" :options="$brandOptions" />
        <x-admin.filter-select name="status" label="Status" :options="['active' => 'Active', 'inactive' => 'Inactive']" />
        <x-admin.filter-select name="visibility" label="Visibility" :options="['is_featured' => 'Featured', 'is_new_arrival' => 'New Arrivals', 'is_trending' => 'Trending', 'is_best_seller' => 'Best Sellers', 'is_flash_sale' => 'Flash Sales', 'is_recommended' => 'Recommended', 'is_popular' => 'Popular', 'is_limited_edition' => 'Limited Edition', 'on_sale' => 'On Sale', 'sale_expired' => 'Sale Expired']" />
        <x-admin.filter-per-page value="{{ $perPage }}" />
    </x-admin.filter-bar>

    <div id="search-results">
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
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Sale</th>
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
                                @php $primaryImage = $product->images->firstWhere('is_primary') ?? $product->images->first(); @endphp
                                <div class="relative inline-block">
                                    @if ($primaryImage)
                                        <img src="{{ product_image_url($primaryImage->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.41a2.25 2.25 0 013.182 0l2.909 2.91M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/></svg>
                                        </div>
                                    @endif
                                    @if ($product->images->count() > 1)
                                        <span class="absolute -bottom-1 -right-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-500 px-1 text-[10px] font-bold text-white">{{ $product->images->count() }}</span>
                                    @endif
                                </div>
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
                                        class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors
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
                                @if($product->sale_ends_at)
                                    @if($product->sale_ends_at->isPast())
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                            Expired {{ $product->sale_ends_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                            Ends {{ $product->sale_ends_at->diffForHumans() }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                @endif
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
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.products.show', $product) }}" title="View Product"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" title="Edit Product"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-brand-500 shadow-sm transition hover:bg-brand-50 hover:text-brand-600 dark:border-gray-700 dark:bg-gray-900 dark:text-brand-400 dark:hover:bg-brand-500/10">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Product', message: 'Are you sure you want to delete {{ addslashes($product->name) }}? This action cannot be undone.', form: $el })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Product"
                                            class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-red-500 shadow-sm transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-gray-200 px-5 py-3 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <button type="button" onclick="bulkDeleteProducts()" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                    Delete Selected
                </button>
                <form action="{{ route('admin.products.destroyAll') }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete All Products', message: 'Delete ALL products? This cannot be undone.', form: $el })">
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
    }).catch(() => {
        window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Failed to update product status.' } }));
    });
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
        window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'warning', message: 'Please select at least one product.' } }));
        return;
    }

    Alpine.store('confirmModal').open({
        title: 'Delete Products',
        message: `Delete ${ids.length} selected product(s)? This action cannot be undone.`,
        onConfirm: async () => {
            try {
                const r = await fetch('{{ route('admin.products.bulkDestroy') }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_ids: ids }),
                });
                if (!r.ok) {
                    const data = await r.json().catch(() => ({}));
                    throw new Error(data.message || 'Failed to delete products.');
                }
                Turbo.visit(location.href, { action: 'replace' });
            } catch (err) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: err.message } }));
            }
        }
    });
}
</script>
@endpush
