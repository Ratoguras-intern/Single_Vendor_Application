@extends('layouts.frontend')

@section('title', 'Order Confirmed - ' . site_name())

@section('content')
<section x-data class="py-16 sm:py-24">
    <div class="section max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <div class="w-20 h-20 mx-auto rounded-full bg-green-100 dark:bg-green-950/30 flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-secondary-900 dark:text-white mb-3">Order Confirmed!</h1>
        <p class="text-lg text-secondary-500 dark:text-secondary-400 mb-8">Thank you for your order. You'll pay when it arrives.</p>

        <div class="card text-left mb-8">
            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Order Number</p>
                    <p class="font-mono font-bold text-lg text-secondary-900 dark:text-white">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Payment Method</p>
                    <p class="font-medium text-secondary-900 dark:text-white">{{ strtoupper($order->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Status</p>
                    <span class="badge {{ App\Support\OrderStatuses::frontendBadgeClasses($order->status) }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Total</p>
                    <p class="font-bold text-lg text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format({{ $order->total_amount }})"></span></p>
                </div>
            </div>

            <div class="divider my-6"></div>

            <div>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-1">Shipping To</p>
                <p class="whitespace-pre-line text-sm text-secondary-900 dark:text-white">{{ $order->shipping_address }}</p>
            </div>

            @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                <div class="mt-6 pt-6 border-t border-secondary-100 dark:border-secondary-700">
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-1">Billing To</p>
                    <p class="whitespace-pre-line text-sm text-secondary-900 dark:text-white">{{ $order->billing_address }}</p>
                </div>
            @endif
        </div>

        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('frontend.shop') }}" class="btn-primary">
                Continue Shopping
            </a>
            <a href="{{ route('customer.orders.index') }}" class="btn-outline">
                View My Orders
            </a>
        </div>
    </div>
</section>
@endsection
