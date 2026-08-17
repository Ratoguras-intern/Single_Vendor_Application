@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Brands', 'url' => route('admin.brands.index')],
        ['label' => $brand->name, 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Brand Details</h2>
        <a href="{{ route('admin.brands.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Back to List
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $brand->name }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Slug</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $brand->slug }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $brand->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                        {{ ucfirst($brand->status) }}
                    </span>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Products</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $brand->products_count ?? 0 }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Logo</label>
                    @if ($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-20 w-20 rounded-lg object-cover">
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No logo</p>
                    @endif
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $brand->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $brand->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.brands.edit', $brand) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Edit Brand
                </a>
                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Brand', message: 'Are you sure you want to delete this brand?', form: $el })">
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
