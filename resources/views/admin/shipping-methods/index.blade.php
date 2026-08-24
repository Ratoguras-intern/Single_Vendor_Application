@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Shipping Methods', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Shipping Methods</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Delivery options shown to customers at checkout.</p>
        </div>
        <a href="{{ route('admin.shipping-methods.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Shipping Method
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Delivery Estimate</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Availability</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Active</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Sort</th>
                        <th class="px-5 py-3 text-right text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($shippingMethods as $method)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $method->name }}</span>
                                @if ($method->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 max-w-md truncate">{{ $method->description }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($method->price, 2) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $method->delivery_estimate ?? '—' }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $method->availability ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $method->is_active
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                        : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $method->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $method->sort_order }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.shipping-methods.edit', $method) }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <form action="{{ route('admin.shipping-methods.destroy', $method) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Shipping Method', message: 'Are you sure you want to delete this shipping method?', form: $el })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600 dark:hover:text-red-400">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No shipping methods found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Add your first shipping method.</p>
                                    <a href="{{ route('admin.shipping-methods.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                                        Add Shipping Method
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $shippingMethods->links() }}
        </div>
    </div>
@endsection
