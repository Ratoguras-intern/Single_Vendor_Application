@extends('admin.layouts.app')

@php
    $breadcrumbs = [['label' => 'Dashboard', 'url' => null]];
@endphp

@section('content')
    {{-- ==================== REVENUE BAND ==================== --}}
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        {{-- Total Revenue --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}"
                class="group block rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-500/5 dark:hover:border-brand-500/30">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 transition-transform duration-200 group-hover:scale-110">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 2V22M17 5H9.5C8.11929 5 7 6.11929 7 7.5C7 8.88071 8.11929 10 9.5 10H14.5C15.8807 10 17 11.1193 17 12.5C17 13.8807 15.8807 15 14.5 15H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <h4 class="mt-3 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">{{ format_currency($totalRevenue) }}</h4>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">All time earnings</p>
            </a>
        </div>

        {{-- Revenue This Month --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}"
                class="group block rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-500/5 dark:hover:border-brand-500/30">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">This Month</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400 transition-transform duration-200 group-hover:scale-110">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 12H7L10 20L14 4L17 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <h4 class="mt-3 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">{{ format_currency($revenueThisMonth) }}</h4>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ now()->format('F Y') }}</p>
            </a>
        </div>

        {{-- Revenue Today --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}"
                class="group block rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-500/5 dark:hover:border-brand-500/30">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 dark:bg-cyan-500/10 dark:text-cyan-400 transition-transform duration-200 group-hover:scale-110">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 7V12L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <h4 class="mt-3 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">{{ format_currency($revenueToday) }}</h4>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ now()->format('D, M d') }}</p>
            </a>
        </div>

        {{-- Average Order Value --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index', ['payment_status' => 'paid']) }}"
                class="group block rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-500/5 dark:hover:border-brand-500/30">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Order Value</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600 dark:bg-pink-500/10 dark:text-pink-400 transition-transform duration-200 group-hover:scale-110">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M16 4H8C5.79086 4 4 5.79086 4 8V16C4 18.2091 5.79086 20 8 20H16C18.2091 20 20 18.2091 20 16V8C20 5.79086 18.2091 4 16 4Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 9V15M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <h4 class="mt-3 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">{{ format_currency($averageOrderValue) }}</h4>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Per paid order</p>
            </a>
        </div>
    </div>

    {{-- ==================== ORDERS PIPELINE + CATALOG ==================== --}}
    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">

        {{-- Orders Overview --}}
        <div class="col-span-12 xl:col-span-8">
            <div class="h-full rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-baseline gap-3">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white">Orders</h3>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">{{ number_format($totalOrders) }}</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">total</span>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300">View all &rarr;</a>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                        class="rounded-lg border border-yellow-100 bg-yellow-50/60 p-3 transition-colors hover:bg-yellow-100/70 dark:border-yellow-500/20 dark:bg-yellow-500/5 dark:hover:bg-yellow-500/10">
                        <div class="flex items-center gap-2 text-xs font-medium text-yellow-700 dark:text-yellow-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span> Pending
                        </div>
                        <p class="mt-1 text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ number_format($pendingOrders) }}</p>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'packed']) }}"
                        class="rounded-lg border border-blue-100 bg-blue-50/60 p-3 transition-colors hover:bg-blue-100/70 dark:border-blue-500/20 dark:bg-blue-500/5 dark:hover:bg-blue-500/10">
                        <div class="flex items-center gap-2 text-xs font-medium text-blue-700 dark:text-blue-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Packed
                        </div>
                        <p class="mt-1 text-xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($packedOrders) }}</p>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
                        class="rounded-lg border border-green-100 bg-green-50/60 p-3 transition-colors hover:bg-green-100/70 dark:border-green-500/20 dark:bg-green-500/5 dark:hover:bg-green-500/10">
                        <div class="flex items-center gap-2 text-xs font-medium text-green-700 dark:text-green-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Delivered
                        </div>
                        <p class="mt-1 text-xl font-bold text-green-700 dark:text-green-300">{{ number_format($deliveredOrders) }}</p>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
                        class="rounded-lg border border-red-100 bg-red-50/60 p-3 transition-colors hover:bg-red-100/70 dark:border-red-500/20 dark:bg-red-500/5 dark:hover:bg-red-500/10">
                        <div class="flex items-center gap-2 text-xs font-medium text-red-700 dark:text-red-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Cancelled
                        </div>
                        <p class="mt-1 text-xl font-bold text-red-700 dark:text-red-300">{{ number_format($cancelledOrders) }}</p>
                    </a>
                </div>

                {{-- Quick actions inside the orders card footer --}}
                <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600 transition-colors">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                        Add Product
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-transparent dark:text-gray-300 dark:hover:bg-white/[0.05] transition-colors">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                        Add Category
                    </a>
                    <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-transparent dark:text-gray-300 dark:hover:bg-white/[0.05] transition-colors">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                        Add Brand
                    </a>
                </div>
            </div>
        </div>

        {{-- Catalog Snapshot --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="h-full rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Catalog</h3>
                </div>
                <div class="mt-3 space-y-2">
                    <a href="{{ route('admin.products.index') }}" class="group flex items-center gap-3 rounded-lg p-2.5 -mx-2.5 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.04]">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M21 8V21H3V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 3H23V8H1V3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-brand-500 dark:group-hover:text-brand-400">Products</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Manage inventory</p>
                        </div>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalProducts) }}</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="group flex items-center gap-3 rounded-lg p-2.5 -mx-2.5 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.04]">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9.10927 2.55078H5.09927C3.89927 2.55078 2.91927 3.53078 2.91927 4.73078V8.74078C2.91927 9.94078 3.89927 10.9208 5.09927 10.9208H9.10927C10.3093 10.9208 11.2893 9.94078 11.2893 8.74078V4.73078C11.2893 3.53078 10.3093 2.55078 9.10927 2.55078Z" stroke="currentColor" stroke-width="1.5"/><path d="M18.9007 2.55078H14.8907C13.6907 2.55078 12.7107 3.53078 12.7107 4.73078V8.74078C12.7107 9.94078 13.6907 10.9208 14.8907 10.9208H18.9007C20.1007 10.9208 21.0807 9.94078 21.0807 8.74078V4.73078C21.0807 3.53078 20.1007 2.55078 18.9007 2.55078Z" stroke="currentColor" stroke-width="1.5"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-brand-500 dark:group-hover:text-brand-400">Categories</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Organize products</p>
                        </div>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalCategories) }}</span>
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="group flex items-center gap-3 rounded-lg p-2.5 -mx-2.5 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.04]">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-brand-500 dark:group-hover:text-brand-400">Brands</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Partner labels</p>
                        </div>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalBrands) }}</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="group flex items-center gap-3 rounded-lg p-2.5 -mx-2.5 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.04]">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-brand-500 dark:group-hover:text-brand-400">Customers</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Registered buyers</p>
                        </div>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalCustomers) }}</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- ==================== CHARTS ROW ==================== --}}
    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">

        {{-- Monthly Sales Chart --}}
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Monthly Sales</h3>
                    <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500 dark:bg-white/[0.06] dark:text-gray-400">Last 12 months</span>
                </div>
                <div class="relative h-[320px]">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top 10 Products Chart --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Top Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">View all &rarr;</a>
                </div>
                @if ($topProducts->count() > 0)
                    <div class="relative h-[320px]">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                @else
                    <div class="flex h-[320px] flex-col items-center justify-center text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400 dark:text-gray-500"><path d="M21 8V21H3V8" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 3H23V8H1V3Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">No product sales data yet.</p>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                            Add your first product
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ==================== TABLES ROW 1 ==================== --}}
    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">

        {{-- Top 10 Customers --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Top Customers</h3>
                    <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">View all &rarr;</a>
                </div>
                <div class="min-h-0 flex-1 overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="sticky top-0 z-10 bg-gray-50 dark:bg-gray-800/90 backdrop-blur-sm">
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">#</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Customer</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Orders</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($topCustomers as $index => $customer)
                                <tr class="transition-colors hover:bg-gray-50/70 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-3 text-sm text-gray-400 dark:text-gray-500">
                                        @if ($index < 3)
                                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-amber-100 text-[11px] font-bold text-amber-700 dark:bg-amber-500/15 dark:text-amber-400">{{ $index + 1 }}</span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="group flex items-center gap-3">
                                            <x-user-avatar :user="$customer" size="h-8 w-8" text-size="text-xs" class="shrink-0" />
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-medium text-gray-800 group-hover:text-brand-500 dark:text-white dark:group-hover:text-brand-400">{{ $customer->name }}</p>
                                                <p class="truncate text-xs text-gray-400 dark:text-gray-500">{{ $customer->email }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $customer->orders_count }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-gray-800 dark:text-white">{{ format_currency($customer->total_spent ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center">
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No customer data yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Latest 10 Orders --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Latest Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">View all &rarr;</a>
                </div>
                <div class="min-h-0 flex-1 divide-y divide-gray-100 overflow-auto dark:divide-gray-800">
                    @forelse ($latestOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center gap-3 px-5 py-3 transition-colors hover:bg-gray-50/70 dark:hover:bg-white/[0.02]">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white">#{{ $order->order_number }}</span>
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ \App\Support\OrderStatuses::badgeClasses($order->status) }}">{{ $order->status_label }}</span>
                                </div>
                                <p class="mt-0.5 truncate text-xs text-gray-400 dark:text-gray-500">
                                    {{ $order->user?->name ?? 'Guest' }} &middot; {{ $order->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ format_currency($order->total_amount) }}</p>
                                <p class="text-[11px] font-medium {{ $order->payment_status === 'paid' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">{{ ucfirst($order->payment_status) }}</p>
                            </div>
                            <svg class="ml-1 shrink-0 text-gray-300 dark:text-gray-600" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18L15 12L9 6"/></svg>
                        </a>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="text-sm text-gray-400 dark:text-gray-500">No orders yet.</p>
                            <p class="mt-1 text-xs text-gray-400/70 dark:text-gray-600">Orders will appear here once customers start purchasing.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ==================== TABLES ROW 2 ==================== --}}
    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">

        {{-- Latest Registered Customers --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">New Customers</h3>
                    <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">View all &rarr;</a>
                </div>
                <div class="min-h-0 flex-1 overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="sticky top-0 z-10 bg-gray-50 dark:bg-gray-800/90 backdrop-blur-sm">
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Customer</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Orders</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Spent</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($latestCustomers as $customer)
                                <tr class="transition-colors hover:bg-gray-50/70 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="group flex items-center gap-3">
                                            <x-user-avatar :user="$customer" size="h-8 w-8" text-size="text-xs" class="shrink-0" />
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-medium text-gray-800 group-hover:text-brand-500 dark:text-white dark:group-hover:text-brand-400">{{ $customer->name }}</p>
                                                <p class="truncate text-xs text-gray-400 dark:text-gray-500">{{ $customer->email }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $customer->orders_count }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-gray-800 dark:text-white">{{ format_currency($customer->total_spent ?? 0) }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-400 dark:text-gray-500">{{ $customer->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center">
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No customers registered yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Low Stock Products --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white">Low Stock</h3>
                        @if ($lowStockProducts->isNotEmpty())
                            <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-100 px-1.5 text-[11px] font-bold text-red-600 dark:bg-red-500/15 dark:text-red-400">{{ $lowStockProducts->count() }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">View all &rarr;</a>
                </div>
                <div class="min-h-0 flex-1 overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="sticky top-0 z-10 bg-gray-50 dark:bg-gray-800/90 backdrop-blur-sm">
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Product</th>
                                <th class="px-5 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Stock</th>
                                <th class="px-5 py-2.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($lowStockProducts as $product)
                                <tr class="transition-colors hover:bg-gray-50/70 dark:hover:bg-white/[0.02]">
                                    <td class="max-w-xs truncate px-5 py-3">
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-sm font-medium text-gray-800 hover:text-brand-500 dark:text-white dark:hover:text-brand-400">{{ $product->name }}</a>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $product->stock <= 5 ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                                            {{ $product->stock }} left
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">Restock &rarr;</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-12 text-center">
                                        <p class="text-sm text-gray-400 dark:text-gray-500">All products well stocked.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
    const currencySymbol = '{{ currency_symbol() }}';

    // --- Monthly Sales Chart ---
    const salesCtx = document.getElementById('monthlySalesChart');
    if (salesCtx) {
        const salesLabels = {!! json_encode($salesLabels) !!};
        const monthKeys = Object.keys({!! json_encode($salesData->toArray()) !!});
        const ordersUrl = '{{ route("admin.orders.index") }}';

        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: salesLabels,
                datasets: [
                    {
                        label: 'Revenue (' + currencySymbol + ')',
                        data: {!! json_encode($salesRevenue->map(fn($v) => round(convert_amount((float) $v), 2))) !!},
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1,
                        borderRadius: 6,
                        maxBarThickness: 36,
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
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const monthKey = monthKeys[index];
                        if (monthKey) {
                            Turbo.visit(ordersUrl + '?month=' + monthKey);
                        }
                    }
                },
                onHover: function(event, elements) {
                    event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                },
                plugins: {
                    legend: { labels: { color: textColor, usePointStyle: true, pointStyle: 'circle' } },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.dataset.yAxisID === 'y') {
                                    return 'Revenue: ' + currencySymbol + ctx.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                                return 'Orders: ' + ctx.parsed.y;
                            }
                        }
                    },
                },
                scales: {
                    x: { ticks: { color: textColor, maxRotation: 45 }, grid: { display: false } },
                    y: {
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            color: textColor,
                            callback: function(val) { return currencySymbol + val.toLocaleString(); }
                        },
                        grid: { color: gridColor },
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        ticks: { color: textColor },
                        grid: { drawOnChartArea: false },
                    },
                },
            },
        });
    }

    // --- Top 10 Products Chart ---
    const prodCtx = document.getElementById('topProductsChart');
    if (prodCtx) {
        const productData = {!! json_encode($topProducts->map(fn($item) => ['id' => $item->product_id, 'name' => Str::limit($item->product->name ?? 'Product #' . $item->product_id, 20), 'sold' => $item->total_sold])) !!};
        new Chart(prodCtx, {
            type: 'bar',
            data: {
                labels: productData.map(p => p.name),
                datasets: [{
                    label: 'Units Sold',
                    data: productData.map(p => p.sold),
                    backgroundColor: 'rgba(99, 102, 241, 0.75)',
                    hoverBackgroundColor: 'rgb(99, 102, 241)',
                    borderWidth: 0,
                    borderRadius: 6,
                    maxBarThickness: 22,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const productId = productData[index]?.id;
                        if (productId) {
                            Turbo.visit('{{ url("admin/products") }}/' + productId);
                        }
                    }
                },
                onHover: function(event, elements) {
                    event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ctx.parsed.x + ' units sold'; }
                        }
                    },
                },
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, font: { size: 11 } }, grid: { display: false } },
                },
            },
        });
    }
}
</script>
@endpush
