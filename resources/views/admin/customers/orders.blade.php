@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Customers', 'url' => route('admin.customers.index')],
        ['label' => $customer->name, 'url' => route('admin.customers.show', $customer)],
        ['label' => 'Orders', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center gap-1 text-sm font-medium mb-2 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Back to Customer
            </a>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Orders by {{ $customer->name }}</h2>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Items</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Subtotal</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Tax</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Shipping</th>
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
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($order->subtotal, 2) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($order->tax, 2) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $order->shipping > 0 ? '$' . number_format($order->shipping, 2) : 'Free' }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $order->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400') }}">
                                    {{ ucfirst($order->status) }}
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
                            <td colspan="9" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No orders found.</td>
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
