@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Display Limits', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Display Limits</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Control how many items appear on dashboards, search, and homepage sections.</p>
        </div>
    </div>

    <form action="{{ route('admin.limit-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 max-w-5xl">

            {{-- Search --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Search</h3>
                <div class="space-y-5">
                    <div>
                        <label for="search_products" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Product Results</label>
                        <input type="number" name="search_products" id="search_products" value="{{ old('search_products', $values['search_products']) }}" min="1" max="50"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('search_products') border-red-500 @enderror">
                        @error('search_products')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="search_categories" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category Results</label>
                        <input type="number" name="search_categories" id="search_categories" value="{{ old('search_categories', $values['search_categories']) }}" min="1" max="50"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('search_categories') border-red-500 @enderror">
                        @error('search_categories')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="popular_searches" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Popular Searches</label>
                        <input type="number" name="popular_searches" id="popular_searches" value="{{ old('popular_searches', $values['popular_searches']) }}" min="1" max="50"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('popular_searches') border-red-500 @enderror">
                        @error('popular_searches')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Homepage --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Homepage</h3>
                <div class="space-y-5">
                    <div>
                        <label for="subcategories" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Subcategories Shown</label>
                        <input type="number" name="subcategories" id="subcategories" value="{{ old('subcategories', $values['subcategories']) }}" min="1" max="50"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('subcategories') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Per-collection product cards use Homepage Section settings.</p>
                        @error('subcategories')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="featured_collection_products" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Products per Featured Collection</label>
                        <input type="number" name="featured_collection_products" id="featured_collection_products" value="{{ old('featured_collection_products', $values['featured_collection_products']) }}" min="1" max="20"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('featured_collection_products') border-red-500 @enderror">
                        @error('featured_collection_products')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Admin Dashboard --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Admin Dashboard</h3>
                <div class="space-y-5">
                    <div>
                        <label for="admin_low_stock" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Low Stock Alert Count</label>
                        <input type="number" name="admin_low_stock" id="admin_low_stock" value="{{ old('admin_low_stock', $values['admin_low_stock']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('admin_low_stock') border-red-500 @enderror">
                        @error('admin_low_stock')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="admin_top_products" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Top Selling Products</label>
                        <input type="number" name="admin_top_products" id="admin_top_products" value="{{ old('admin_top_products', $values['admin_top_products']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('admin_top_products') border-red-500 @enderror">
                        @error('admin_top_products')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="admin_top_customers" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Top Customers by Spending</label>
                        <input type="number" name="admin_top_customers" id="admin_top_customers" value="{{ old('admin_top_customers', $values['admin_top_customers']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('admin_top_customers') border-red-500 @enderror">
                        @error('admin_top_customers')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="admin_latest_orders" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Latest Orders</label>
                        <input type="number" name="admin_latest_orders" id="admin_latest_orders" value="{{ old('admin_latest_orders', $values['admin_latest_orders']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('admin_latest_orders') border-red-500 @enderror">
                        @error('admin_latest_orders')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="admin_latest_customers" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Latest Registered Customers</label>
                        <input type="number" name="admin_latest_customers" id="admin_latest_customers" value="{{ old('admin_latest_customers', $values['admin_latest_customers']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('admin_latest_customers') border-red-500 @enderror">
                        @error('admin_latest_customers')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Super Admin Dashboard --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Super Admin Dashboard</h3>
                <div class="space-y-5">
                    <div>
                        <label for="superadmin_low_stock" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Low Stock Alert Count</label>
                        <input type="number" name="superadmin_low_stock" id="superadmin_low_stock" value="{{ old('superadmin_low_stock', $values['superadmin_low_stock']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('superadmin_low_stock') border-red-500 @enderror">
                        @error('superadmin_low_stock')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="superadmin_recent_orders" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Recent Orders</label>
                        <input type="number" name="superadmin_recent_orders" id="superadmin_recent_orders" value="{{ old('superadmin_recent_orders', $values['superadmin_recent_orders']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('superadmin_recent_orders') border-red-500 @enderror">
                        @error('superadmin_recent_orders')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="superadmin_recent_users" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Recent Users</label>
                        <input type="number" name="superadmin_recent_users" id="superadmin_recent_users" value="{{ old('superadmin_recent_users', $values['superadmin_recent_users']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('superadmin_recent_users') border-red-500 @enderror">
                        @error('superadmin_recent_users')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="superadmin_top_products" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Top Selling Products</label>
                        <input type="number" name="superadmin_top_products" id="superadmin_top_products" value="{{ old('superadmin_top_products', $values['superadmin_top_products']) }}" min="1" max="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('superadmin_top_products') border-red-500 @enderror">
                        @error('superadmin_top_products')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Save Limits
                </button>
            </div>
        </div>
    </form>
@endsection
