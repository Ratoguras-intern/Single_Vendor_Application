@extends('layouts.frontend')

@section('title', 'Order Confirmed - NBK Vertex')

@section('content')
<div x-data class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center" style="background-color: #d1fae5;">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold mb-4" style="color: var(--bloom-foreground);">Order Confirmed!</h1>
        <p class="text-lg mb-8" style="color: var(--bloom-muted-foreground);">Thank you for your order. You'll pay when it arrives.</p>

        <div class="rounded-xl border bg-white shadow p-6 mb-8 text-left" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm" style="color: var(--bloom-muted-foreground);">Order Number</p>
                    <p class="font-mono font-bold text-lg" style="color: var(--bloom-foreground);">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm" style="color: var(--bloom-muted-foreground);">Payment Method</p>
                    <p class="font-medium" style="color: var(--bloom-foreground);">{{ strtoupper($order->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-sm" style="color: var(--bloom-muted-foreground);">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm" style="color: var(--bloom-muted-foreground);">Total</p>
                    <p class="font-bold text-lg" style="color: var(--bloom-primary);"><span x-text="$store.currency.format({{ $order->total_amount }})"></span></p>
                </div>
            </div>

            <div class="h-[1px] w-full shrink-0 my-6" style="background-color: var(--bloom-border);"></div>

            <div>
                <p class="text-sm mb-2" style="color: var(--bloom-muted-foreground);">Shipping To</p>
                <p class="whitespace-pre-line text-sm" style="color: var(--bloom-foreground);">{{ $order->shipping_address }}</p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('frontend.shop') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                Continue Shopping
            </a>
            <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 border border-gray-300 bg-white shadow-sm hover:bg-gray-50">
                View My Orders
            </a>
        </div>
    </div>
</div>
@endsection
