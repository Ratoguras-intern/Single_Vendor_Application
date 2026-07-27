@extends('layouts.frontend')

@section('title', 'Order #' . $order->order_number . ' - NBK Vertex')

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mb-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to Orders
                </a>
                <h1 class="page-heading">Order {{ $order->order_number }}</h1>
            </div>
            <span class="badge {{ $order->status === 'completed' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-300') }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Order Items</h2>
                    <div class="space-y-3">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-4 p-4 rounded-card bg-secondary-50 dark:bg-secondary-950">
                                @if ($item->product && $item->product->primaryImage())
                                    <img src="{{ $item->product->primaryImage()->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-card object-cover" />
                                @else
                                    <div class="w-16 h-16 rounded-card bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-secondary-900 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Qty: {{ $item->quantity }} &bull; <span x-text="$store.currency.format({{ $item->price }})"></span> each</p>
                                </div>
                                <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $item->price * $item->quantity }})"></span></p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Subtotal</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $order->subtotal }})"></span></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Shipping</span>
                            <span class="font-medium text-secondary-900 dark:text-white">{{ $order->shipping > 0 ? '' : 'Free' }}@if($order->shipping > 0)<span x-text="$store.currency.format({{ $order->shipping }})"></span>@endif</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Tax</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $order->tax }})"></span></span>
                        </div>
                        @if ($order->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-secondary-500 dark:text-secondary-400">Discount</span>
                                <span class="font-medium text-green-600 dark:text-green-400">-<span x-text="$store.currency.format({{ $order->discount }})"></span></span>
                            </div>
                        @endif
                        <div class="divider"></div>
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-secondary-900 dark:text-white">Total</span>
                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format({{ $order->total_amount }})"></span></span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Date</span>
                            <span class="font-medium text-secondary-900 dark:text-white">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Payment</span>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-300' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Method</span>
                            <span class="font-medium text-secondary-900 dark:text-white uppercase">{{ $order->payment_method }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Phone</span>
                            <span class="font-medium text-secondary-900 dark:text-white">{{ $order->phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Shipping Address</h2>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
