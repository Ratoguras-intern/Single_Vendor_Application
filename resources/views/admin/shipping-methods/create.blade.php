@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Shipping Methods', 'url' => route('admin.shipping-methods.index')],
        ['label' => 'Create', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Create Shipping Method</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a delivery option for the checkout page.</p>
        </div>
        <a href="{{ route('admin.shipping-methods.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            Back to Methods
        </a>
    </div>

    <form action="{{ route('admin.shipping-methods.store') }}" method="POST">
        @csrf

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] max-w-2xl">
            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('name') border-red-500 @enderror"
                        placeholder="e.g. Standard Shipping">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('description') border-red-500 @enderror"
                        placeholder="Short description shown at checkout">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                        <input type="number" name="price" id="price" value="{{ old('price', '0') }}" min="0" step="0.01"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_estimate" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Estimate</label>
                        <input type="text" name="delivery_estimate" id="delivery_estimate" value="{{ old('delivery_estimate') }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('delivery_estimate') border-red-500 @enderror"
                            placeholder="e.g. 3-5 business days">
                        @error('delivery_estimate')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="availability" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Availability</label>
                        <input type="text" name="availability" id="availability" value="{{ old('availability') }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('availability') border-red-500 @enderror"
                            placeholder="e.g. Nationwide">
                        @error('availability')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-800">
                    <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', true))
                        class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-900">
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                        Create Method
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
