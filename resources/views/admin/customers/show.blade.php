@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Customers', 'url' => route('admin.customers.index')],
        ['label' => $customer->name, 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="flex items-center gap-3 text-xl font-bold text-gray-800 dark:text-white">
            <x-user-avatar :user="$customer" size="h-10 w-10" text-size="text-sm" />
            Customer: {{ $customer->name }}
            @if ($customer->is_frozen)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                    Frozen
                    @if ($customer->frozen_reason)
                        <span class="text-xs font-normal opacity-75">— {{ $customer->frozen_reason }}</span>
                    @endif
                </span>
            @endif
        </h2>
        <div class="flex items-center gap-2">
            @if ($customer->id !== Auth::id())
                @if ($customer->is_frozen)
                    <form action="{{ route('admin.customers.unfreeze', $customer) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-300 bg-white px-4 py-2.5 text-sm font-medium text-green-700 hover:bg-green-50 dark:border-green-700 dark:text-green-400 dark:hover:bg-green-500/10">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                            Unfreeze Account
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.customers.freeze', $customer) }}" method="POST" x-data="{ show: false }">
                        @csrf
                        <input type="hidden" name="frozen_reason" :value="reason || null">
                        <button type="button" @click="show = !show" class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-white px-4 py-2.5 text-sm font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-700 dark:text-blue-400 dark:hover:bg-blue-500/10">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                            Freeze Account
                        </button>
                        <template x-if="show">
                            <div class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
                                <div class="fixed inset-0 bg-black/50" @click="show = false"></div>
                                <div x-transition class="relative z-10 w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
                                    <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Freeze Account</h3>
                                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Frozen users cannot log in until unfrozen.</p>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Reason (optional)</label>
                                    <textarea x-model="reason" maxlength="500" rows="3" placeholder="Why freeze this account?" class="mb-4 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="show = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">Cancel</button>
                                        <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Freeze</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </form>
                @endif
            @endif
            <a href="{{ route('admin.customers.orders', $customer) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                View Orders
            </a>
            <a href="{{ route('admin.customers.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                Back to Customers
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivered Orders</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ format_currency($stats['total_spent']) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Order</p>
            <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white">{{ $stats['last_order'] ? $stats['last_order']->created_at->format('M d, Y') : 'N/A' }}</p>
        </div>
    </div>

    {{-- Customer Info --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Customer Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                <p class="font-medium text-gray-800 dark:text-white">{{ $customer->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                <p class="font-medium text-gray-800 dark:text-white">{{ $customer->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                <p class="font-medium text-gray-800 dark:text-white">{{ $customer->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                <p class="font-medium text-gray-800 dark:text-white">{{ $customer->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Purchase History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Items</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Payment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $order->items->count() }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ format_currency($order->total_amount) }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ \App\Support\OrderStatuses::badgeClasses($order->status) }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
