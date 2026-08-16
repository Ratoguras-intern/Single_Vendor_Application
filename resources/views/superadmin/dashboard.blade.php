@extends('admin.layouts.app')

@php
    $breadcrumbs = [['label' => 'Super Admin Dashboard', 'url' => null]];
@endphp

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        @php
            $cards = [
                ['label' => 'Total Admins', 'value' => number_format($totalAdmins), 'icon' => 'ShieldCheck', 'color' => 'blue', 'url' => route('superadmin.admins.index')],
                ['label' => 'Total Users', 'value' => number_format($totalUsers), 'icon' => 'Users', 'color' => 'green', 'url' => route('superadmin.users.index')],
                ['label' => 'Total Products', 'value' => number_format($totalProducts), 'icon' => 'Package', 'color' => 'purple', 'url' => route('admin.products.index')],
                ['label' => 'Total Categories', 'value' => number_format($totalCategories), 'icon' => 'Tag', 'color' => 'orange', 'url' => route('admin.categories.index')],
                ['label' => 'Total Orders', 'value' => number_format($totalOrders), 'icon' => 'ShoppingCart', 'color' => 'indigo', 'url' => route('admin.orders.index')],
                ['label' => 'Pending Orders', 'value' => number_format($pendingOrders), 'icon' => 'Clock', 'color' => 'yellow', 'url' => route('admin.orders.index', ['status' => 'pending'])],
                ['label' => 'Delivered Orders', 'value' => number_format($deliveredOrders), 'icon' => 'Activity', 'color' => 'green', 'url' => route('admin.orders.index', ['status' => 'delivered'])],
                ['label' => 'Total Revenue', 'value' => format_currency($totalRevenue), 'icon' => 'DollarSign', 'color' => 'emerald', 'url' => route('admin.orders.index')],
            ];
            $palette = [
                'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10',
                'green' => 'bg-green-50 text-green-600 dark:bg-green-500/10',
                'purple' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10',
                'orange' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10',
                'indigo' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10',
                'yellow' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10',
                'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10',
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <a href="{{ $card['url'] }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                            <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ $card['value'] }}</h4>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $palette[$card['color']] }}">
                            <x-lucide :name="$card['icon']" class="w-6 h-6" />
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Sales Overview (Last 12 Months)</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 font-medium">View All Orders &rarr;</a>
                </div>
                <div class="relative h-[350px]">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Top 5 Best Selling Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 font-medium">View All &rarr;</a>
                </div>
                <div class="space-y-3">
                    @forelse ($topProducts as $product)
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white bg-brand-500">{{ $loop->iteration }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $product->product->name ?? 'Product #' . $product->product_id }}</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ $product->total_sold }} sold</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No product sales data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 xl:col-span-7">
            @include('superadmin.partials.recent-orders')
        </div>
        <div class="col-span-12 xl:col-span-5">
            @include('superadmin.partials.low-stock')
        </div>
    </div>

    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 xl:col-span-6">
            @include('superadmin.partials.recent-users')
        </div>
        <div class="col-span-12 xl:col-span-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Revenue This Month</h3>
                </div>
                <div class="p-6 flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10">
                        <x-lucide name="TrendingUp" class="w-7 h-7" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ format_currency($revenueThisMonth) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Paid orders revenue for {{ now()->format('F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/turbo-script">
{
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#e5e7eb' : '#374151';
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';

    const ctx = document.getElementById('monthlySalesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($salesLabels) !!},
                datasets: [
                    {
                        label: 'Revenue ($)',
                        data: {!! json_encode($salesRevenue->map(fn($v) => round($v, 2))) !!},
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'y',
                        order: 2,
                    },
                    {
                        label: 'Orders',
                        data: {!! json_encode($salesOrderCount) !!},
                        type: 'line',
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgba(234, 88, 12, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(234, 88, 12)',
                        tension: 0.3,
                        fill: false,
                        yAxisID: 'y1',
                        order: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { color: textColor, usePointStyle: true, pointStyle: 'circle' } },
                    tooltip: {
                        callbacks: {
                            label: function(c) {
                                return c.dataset.label === 'Revenue ($)'
                                    ? 'Revenue: $' + c.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})
                                    : 'Orders: ' + c.parsed.y;
                            }
                        }
                    },
                },
                scales: {
                    x: { ticks: { color: textColor, maxRotation: 45 }, grid: { color: gridColor } },
                    y: { type: 'linear', position: 'left', ticks: { color: textColor, callback: function(v) { return '$' + v.toLocaleString(); } }, grid: { color: gridColor } },
                    y1: { type: 'linear', position: 'right', ticks: { color: textColor }, grid: { drawOnChartArea: false } },
                },
            },
        });
    }
}
</script>
@endpush
