@extends('admin.layouts.app')

@php
    $breadcrumbs = [['label' => 'Dashboard', 'url' => null]];
    $activeRange = '30'; // default chart range
@endphp

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">

        {{-- ==================== WELCOME / TITLE ==================== --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-gray-800 sm:text-2xl dark:text-white">
                    Welcome back{{ auth()->user()->name ? ', ' . auth()->user()->name : '' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Here's how your store is doing today, {{ now()->format('l, F j') }}.
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}" class="btn-primary btn-sm hidden sm:inline-flex">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14" /></svg>
                Add Product
            </a>
        </div>

        {{-- ==================== A. BUSINESS OVERVIEW (KPI CARDS) ==================== --}}
        <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">

            {{-- Total Sales --}}
            <a href="{{ route('admin.orders.index') }}"
                class="card group p-4 transition-shadow hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-400 sm:text-[13px] dark:text-gray-500">Total Sales</span>
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18" /><path d="m7 15 4-4 3 3 5-6" /></svg>
                    </div>
                </div>
                <p class="mt-3 truncate text-xl font-bold tracking-tight text-gray-800 sm:text-2xl dark:text-white">
                    {{ format_currency($totalRevenue) }}
                </p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    All-time earnings &middot; {{ number_format($totalOrders) }} orders
                </p>
            </a>

            {{-- Orders --}}
            <a href="{{ route('admin.orders.index') }}"
                class="card group p-4 transition-shadow hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-400 sm:text-[13px] dark:text-gray-500">Orders</span>
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v5M16 2v5M3 10h18" /><path d="M3 12v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8" /></svg>
                    </div>
                </div>
                <p class="mt-3 truncate text-xl font-bold tracking-tight text-gray-800 sm:text-2xl dark:text-white">
                    {{ number_format($totalOrders) }}
                </p>
                <p class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                    <span class="font-medium text-amber-600 dark:text-amber-400">{{ number_format($pendingOrders + $packedOrders) }}</span> in progress
                </p>
            </a>

            {{-- Products --}}
            <a href="{{ route('admin.products.index') }}"
                class="card group p-4 transition-shadow hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-400 sm:text-[13px] dark:text-gray-500">Products</span>
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8" /><path d="M1 3h22v5H1z" /><path d="M10 12h4" /></svg>
                    </div>
                </div>
                <p class="mt-3 truncate text-xl font-bold tracking-tight text-gray-800 sm:text-2xl dark:text-white">
                    {{ number_format($totalProducts) }}
                </p>
                <p class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                    @if ($outOfStockCount > 0)
                        <span class="font-medium text-red-600 dark:text-red-400">{{ number_format($outOfStockCount) }} out of stock</span>
                    @else
                        All products in stock
                    @endif
                </p>
            </a>

            {{-- Customers --}}
            <a href="{{ route('admin.customers.index') }}"
                class="card group p-4 transition-shadow hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-400 sm:text-[13px] dark:text-gray-500">Customers</span>
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
                    </div>
                </div>
                <p class="mt-3 truncate text-xl font-bold tracking-tight text-gray-800 sm:text-2xl dark:text-white">
                    {{ number_format($totalCustomers) }}
                </p>
                <p class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                    {{ number_format($latestCustomers->count()) }} new recently
                </p>
            </a>

        </div>

        {{-- ==================== MAIN ROW: Sales Chart + Inventory ==================== --}}
        <div class="mt-5 grid grid-cols-12 gap-4 md:gap-5">

            {{-- Sales chart --}}
            <div class="col-span-12 min-w-0 xl:col-span-8"
                x-data="salesChart({!! json_encode($salesRanges) !!}, '{{ $activeRange }}')">
                <div class="card p-4 sm:p-5">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-gray-800 dark:text-white">Sales Overview</h2>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                Revenue from paid orders <span x-text="salesRanges[activeRange].title.toLowerCase()" class="font-medium text-gray-500 dark:text-gray-400"></span>
                            </p>
                        </div>

                        {{-- Range switcher --}}
                        <div class="inline-flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-800" role="tablist" aria-label="Sales date range">
                            <template x-for="(range, key) in salesRanges" :key="key">
                                <button
                                    type="button"
                                    role="tab"
                                    :aria-selected="activeRange === key"
                                    @click="activeRange = key; renderChart()"
                                    class="rounded-md px-2.5 py-1 text-xs font-medium transition-colors sm:px-3"
                                    :class="activeRange === key
                                        ? 'bg-white text-gray-800 shadow-sm dark:bg-gray-700 dark:text-white'
                                        : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                                    x-text="range.short">
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Fixed-height canvas prevents layout shift between ranges --}}
                    <div class="relative h-[280px] w-full sm:h-[320px]">
                        <canvas id="salesChart" class="max-w-full"></canvas>
                    </div>
                </div>
            </div>

            {{-- Products / inventory snapshot --}}
            <div class="col-span-12 min-w-0 xl:col-span-4">
                <div class="card flex h-full flex-col">
                    <div class="card-header">
                        <h2 class="card-title">Inventory</h2>
                        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">View all &rarr;</a>
                    </div>

                    <div class="flex flex-1 flex-col p-4">
                        {{-- Best sellers --}}
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Best sellers</p>

                        @forelse ($topProducts->take(4) as $item)
                            <a href="{{ route('admin.products.show', $item->product_id) }}"
                                class="group flex items-center gap-3 rounded-lg px-2 py-2 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-gray-100 text-xs font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="min-w-0 flex-1 truncate text-sm text-gray-700 group-hover:text-brand-500 dark:text-gray-200 dark:group-hover:text-brand-400">
                                    {{ $item->product->name ?? 'Product #' . $item->product_id }}
                                </span>
                                <span class="shrink-0 text-xs font-semibold text-gray-600 dark:text-gray-300">
                                    {{ (int) $item->total_sold }} sold
                                </span>
                            </a>
                        @empty
                            <p class="px-2 py-3 text-xs text-gray-400 dark:text-gray-500">No sales yet.</p>
                        @endforelse

                        {{-- Stock warnings --}}
                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Needs attention</p>
                                @if ($lowStockProducts->count() > 0)
                                    <span class="badge badge-warning">{{ $lowStockProducts->count() }}</span>
                                @endif
                            </div>

                            @forelse ($lowStockProducts->take(4) as $product)
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="flex items-center justify-between gap-2 rounded-lg px-2 py-1.5 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                    <span class="min-w-0 flex-1 truncate text-sm text-gray-700 dark:text-gray-200">{{ $product->name }}</span>
                                    <span class="shrink-0 text-xs font-semibold {{ $product->stock <= 0 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $product->stock <= 0 ? 'Out of stock' : $product->stock . ' left' }}
                                    </span>
                                </a>
                            @empty
                                <p class="px-2 py-2 text-xs text-gray-400 dark:text-gray-500">All products well stocked.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== B. RECENT ORDERS ==================== --}}
        <div class="mt-5 card overflow-hidden">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Recent Orders</h2>
                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Latest activity across your store</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary btn-sm hidden sm:inline-flex">View All Orders</a>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($latestOrders->take(8) as $order)
                            <tr>
                                <td class="font-medium text-gray-800 dark:text-white">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-brand-500 dark:hover:text-brand-400">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <x-user-avatar :user="$order->user" size="h-8 w-8" text-size="text-xs" class="shrink-0" />
                                        <span class="max-w-[160px] truncate text-sm text-gray-700 dark:text-gray-200">
                                            {{ $order->user?->name ?? 'Guest' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge {{ \App\Support\OrderStatuses::badgeClasses($order->status) }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ \App\Support\OrderStatuses::dotClasses($order->status) }}"></span>
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ format_currency($order->total_amount) }}
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="btn-outline btn-sm" title="View order">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                            <svg class="h-7 w-7 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v5M16 2v5M3 10h18" /><path d="M3 12v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8" /></svg>
                                        </div>
                                        <p class="empty-state-title">No orders yet</p>
                                        <p class="empty-state-text">Orders will appear here once customers start purchasing.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ==================== E. QUICK ACTIONS ==================== --}}
        <div class="mt-5 card p-4 sm:p-5">
            <h2 class="card-title mb-3">Quick Actions</h2>
            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                <a href="{{ route('admin.products.create') }}" class="btn-primary justify-start">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14" /></svg>
                    Add Product
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary justify-start">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v5M16 2v5M3 10h18" /><path d="M3 12v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8" /></svg>
                    View Orders
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary justify-start">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8" /><path d="M1 3h22v5H1z" /><path d="M10 12h4" /></svg>
                    Manage Products
                </a>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script type="text/turbo-script">
{
    document.addEventListener('alpine:init', () => {
        Alpine.data('salesChart', (ranges, initial) => ({
            salesRanges: ranges,
            activeRange: initial,
            chart: null,
            renderChart() {
                const range = this.salesRanges[this.activeRange] || this.salesRanges['30'];
                const canvas = document.getElementById('salesChart');
                if (!canvas) return;

                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#e5e7eb' : '#374151';
                const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                const currencySymbol = '{{ currency_symbol() }}';

                if (!this.chart) {
                    this.chart = new Chart(canvas, {
                        type: 'bar',
                        data: { labels: range.labels, datasets: [{
                            label: 'Revenue',
                            data: range.revenue,
                            backgroundColor: 'rgba(232, 155, 45, 0.75)',
                            hoverBackgroundColor: 'rgb(232, 155, 45)',
                            borderRadius: 6,
                            maxBarThickness: 32,
                        }] },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { callbacks: { label: (c) => range.labels[c.dataIndex] + ': ' + currencySymbol + Number(c.parsed.y || 0).toLocaleString() } }
                            },
                            scales: {
                                x: { ticks: { color: textColor, maxRotation: 45, autoSkip: true, maxTicksLimit: 12 }, grid: { display: false } },
                                y: { beginAtZero: true, ticks: { color: textColor, callback: (v) => currencySymbol + Number(v).toLocaleString() }, grid: { color: gridColor } }
                            }
                        }
                    });
                } else {
                    this.chart.data.labels = range.labels;
                    this.chart.data.datasets[0].data = range.revenue;
                    this.chart.update('none');
                }
            },
            init() {
                this.renderChart();
            }
        }));
    });
}
</script>
@endpush
