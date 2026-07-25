@extends('admin.layouts.app')

@php
    $breadcrumbs = [['label' => 'Dashboard', 'url' => null]];
@endphp

@section('content')
    {{-- ==================== STAT CARDS ==================== --}}
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- Total Customers --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.customers.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Customers</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalCustomers) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Products --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.products.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalProducts) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M21 8V21H3V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 3H23V8H1V3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Categories --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.categories.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Categories</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalCategories) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-50 text-purple-600 dark:bg-purple-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M9.10927 2.55078H5.09927C3.89927 2.55078 2.91927 3.53078 2.91927 4.73078V8.74078C2.91927 9.94078 3.89927 10.9208 5.09927 10.9208H9.10927C10.3093 10.9208 11.2893 9.94078 11.2893 8.74078V4.73078C11.2893 3.53078 10.3093 2.55078 9.10927 2.55078Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.10927 13.0808H5.09927C3.89927 13.0808 2.91927 14.0608 2.91927 15.2608V19.2708C2.91927 20.4708 3.89927 21.4508 5.09927 21.4508H9.10927C10.3093 21.4508 11.2893 20.4708 11.2893 19.2708V15.2608C11.2893 14.0608 10.3093 13.0808 9.10927 13.0808Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9007 2.55078H14.8907C13.6907 2.55078 12.7107 3.53078 12.7107 4.73078V8.74078C12.7107 9.94078 13.6907 10.9208 14.8907 10.9208H18.9007C20.1007 10.9208 21.0807 9.94078 21.0807 8.74078V4.73078C21.0807 3.53078 20.1007 2.55078 18.9007 2.55078Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9007 13.0808H14.8907C13.6907 13.0808 12.7107 14.0608 12.7107 15.2608V19.2708C12.7107 20.4708 13.6907 21.4508 14.8907 21.4508H18.9007C20.1007 21.4508 21.0807 20.4708 21.0807 19.2708V15.2608C21.0807 14.0608 20.1007 13.0808 18.9007 13.0808Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Brands --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.brands.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Brands</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalBrands) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 text-orange-600 dark:bg-orange-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Orders --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalOrders) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Pending Orders --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Orders</p>
                        <h4 class="mt-1 text-2xl font-bold text-yellow-600">{{ number_format($pendingOrders) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 6V12L16 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Completed Orders --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Orders</p>
                        <h4 class="mt-1 text-2xl font-bold text-green-600">{{ number_format($completedOrders) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M9 12.75L11.25 15L15 9.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Cancelled Orders --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cancelled Orders</p>
                        <h4 class="mt-1 text-2xl font-bold text-red-600">{{ number_format($cancelledOrders) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600 dark:bg-red-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Revenue --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($totalRevenue, 2) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2V22M17 5H9.5C8.11929 5 7 6.11929 7 7.5C7 8.88071 8.11929 10 9.5 10H14.5C15.8807 10 17 11.1193 17 12.5C17 13.8807 15.8807 15 14.5 15H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Revenue Today --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue Today</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($revenueToday, 2) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-cyan-50 text-cyan-600 dark:bg-cyan-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6.5 2.25C5.5335 2.25 4.75 3.0335 4.75 4V5.75C4.75 6.7165 3.9665 7.5 3 7.5H2.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 21.75C18.4665 21.75 19.25 20.9665 19.25 20V18.25C19.25 17.2835 20.0335 16.5 21 16.5H21.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 7V12L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Revenue This Month --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index') }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue This Month</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($revenueThisMonth, 2) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-violet-50 text-violet-600 dark:bg-violet-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 12H7L10 20L14 4L17 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Average Order Value --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <a href="{{ route('admin.orders.index', ['payment_status' => 'paid']) }}" class="block rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Order Value</p>
                        <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($averageOrderValue, 2) }}</h4>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-pink-50 text-pink-600 dark:bg-pink-500/10">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M16 4H8C5.79086 4 4 5.79086 4 8V16C4 18.2091 5.79086 20 8 20H16C18.2091 20 20 18.2091 20 16V8C20 5.79086 18.2091 4 16 4Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 9V15M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>
        </div>

    </div>

    {{-- ==================== QUICK ACTIONS ==================== --}}
    <div class="mt-6 flex flex-wrap items-center gap-3">
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Quick Actions:</span>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
            Add Product
        </a>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06] transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
            Add Category
        </a>
        <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06] transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
            Add Brand
        </a>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06] transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z"/></svg>
            View Orders
        </a>
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06] transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21"/><circle cx="12" cy="7" r="4"/></svg>
            View Customers
        </a>
    </div>

    {{-- ==================== CHARTS ROW ==================== --}}
    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">

        {{-- Monthly Sales Chart --}}
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Monthly Sales (Last 12 Months)</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">View All Orders &rarr;</a>
                </div>
                <div class="relative h-[350px]">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top 10 Products Chart --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Top 10 Best Selling Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">View All &rarr;</a>
                </div>
                @if ($topProducts->count() > 0)
                    <div class="relative h-[350px]">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-[350px] text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400 dark:text-gray-500"><path d="M21 8V21H3V8" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 3H23V8H1V3Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">No product sales data yet.</p>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                            Add Your First Product
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
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Top 10 Customers by Spending</h3>
                    <a href="{{ route('admin.customers.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">View All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Orders</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($topCustomers as $index => $customer)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center gap-3 group">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white bg-gray-400">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-brand-500 dark:group-hover:text-brand-400">{{ $customer->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->email }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $customer->orders_count }}</td>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-800 dark:text-white">${{ number_format($customer->total_spent ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21"/><circle cx="12" cy="7" r="4"/></svg>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No customer data yet.</p>
                                        </div>
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
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Latest 10 Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">View All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Payment</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($latestOrders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">#{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($order->user)
                                            <a href="{{ route('admin.customers.show', $order->user) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-brand-500 dark:hover:text-brand-400">{{ $order->user->name }}</a>
                                        @else
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Guest</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-800 dark:text-white">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $order->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z"/></svg>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">No orders yet.</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Orders will appear here once customers start purchasing.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- ==================== TABLES ROW 2 ==================== --}}
    <div class="mt-6 grid grid-cols-12 gap-4 md:gap-6">

        {{-- Latest 10 Registered Customers --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Latest 10 Registered Customers</h3>
                    <a href="{{ route('admin.customers.index') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">View All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Orders</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total Spent</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Last Purchase</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Registered</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($latestCustomers as $customer)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center gap-3 group">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white bg-gray-400">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-brand-500 dark:group-hover:text-brand-400">{{ $customer->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->email }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $customer->orders_count }}</td>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-800 dark:text-white">${{ number_format($customer->total_spent ?? 0, 2) }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $customer->last_order_date ? \Carbon\Carbon::parse($customer->last_order_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $customer->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21"/><circle cx="12" cy="7" r="4"/></svg>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">No customers registered yet.</p>
                                        </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#e5e7eb' : '#374151';
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';

    // --- Monthly Sales Chart ---
    const salesCtx = document.getElementById('monthlySalesChart');
    if (salesCtx) {
        const salesLabels = {!! json_encode($salesLabels) !!};
        const salesMonthKeys = {!! json_encode($salesLabels->keys()->values()) !!};
        const ordersUrl = '{{ route("admin.orders.index") }}';

        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: salesLabels,
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
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const monthKey = Object.keys({!! json_encode($salesData->toArray()) !!})[index];
                        if (monthKey) {
                            window.location.href = ordersUrl + '?month=' + monthKey;
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
                                if (ctx.dataset.label === 'Revenue ($)') {
                                    return 'Revenue: $' + ctx.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                                return 'Orders: ' + ctx.parsed.y;
                            }
                        }
                    },
                },
                scales: {
                    x: { ticks: { color: textColor, maxRotation: 45 }, grid: { color: gridColor } },
                    y: {
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            color: textColor,
                            callback: function(val) { return '$' + val.toLocaleString(); }
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
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(234, 88, 12, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(20, 184, 166, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(244, 63, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                    ],
                    borderWidth: 0,
                    borderRadius: 4,
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
                            window.location.href = '{{ url("admin/products") }}/' + productId;
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
});
</script>
@endpush
