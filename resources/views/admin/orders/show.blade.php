@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Orders', 'url' => route('admin.orders.index')],
        ['label' => '#' . $order->order_number, 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Order #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2 space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Product</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Qty</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($order->items as $item)
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-800 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</td>
                                    <td class="py-3 text-sm text-gray-600 dark:text-gray-400">${{ number_format($item->price, 2) }}</td>
                                    <td class="py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }}</td>
                                    <td class="py-3 text-sm font-medium text-gray-800 dark:text-white">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">No items.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Order Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Customer</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $order->user->name ?? 'Guest' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Email</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $order->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Status</span>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $order->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Payment</span>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Date</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-800 pt-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-800 dark:text-white">Total</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-white">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
