@extends('layouts.frontend')

@section('title', 'Order #' . $order->order_number . ' - NBK Vertex')

@section('content')
<div x-data class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1 text-sm font-medium mb-2 hover:opacity-80" style="color: var(--bloom-primary);">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Back to Orders
            </a>
            <h1 class="text-3xl font-bold" style="color: var(--bloom-foreground);">Order {{ $order->order_number }}</h1>
        </div>
        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
            {{ $order->status === 'completed' ? 'bg-green-50 text-green-700' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Order Items --}}
        <div class="lg:col-span-2">
            <div class="rounded-xl border p-6" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--bloom-foreground);">Order Items</h2>
                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-4 p-4 rounded-lg" style="background-color: var(--bloom-background);">
                            @if ($item->product && $item->product->primaryImage())
                                <img src="{{ $item->product->primaryImage()->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-lg object-cover" />
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold" style="color: var(--bloom-foreground);">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                                <p class="text-sm" style="color: var(--bloom-muted-foreground);">Qty: {{ $item->quantity }} &bull; <span x-text="$store.currency.format({{ $item->price }})"></span> each</p>
                            </div>
                            <p class="font-bold" style="color: var(--bloom-foreground);"><span x-text="$store.currency.format({{ $item->price * $item->quantity }})"></span></p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="space-y-6">
            <div class="rounded-xl border p-6" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--bloom-foreground);">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Subtotal</span>
                        <span class="font-medium"><span x-text="$store.currency.format({{ $order->subtotal }})"></span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Shipping</span>
                        <span class="font-medium">{{ $order->shipping > 0 ? '' : 'Free' }}@if($order->shipping > 0)<span x-text="$store.currency.format({{ $order->shipping }})"></span>@endif</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Tax</span>
                        <span class="font-medium"><span x-text="$store.currency.format({{ $order->tax }})"></span></span>
                    </div>
                    @if ($order->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--bloom-muted-foreground);">Discount</span>
                            <span class="font-medium text-green-600">-<span x-text="$store.currency.format({{ $order->discount }})"></span></span>
                        </div>
                    @endif
                    <div class="h-[1px] w-full" style="background-color: var(--bloom-border);"></div>
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-lg font-bold" style="color: var(--bloom-primary);"><span x-text="$store.currency.format({{ $order->total_amount }})"></span></span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border p-6" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--bloom-foreground);">Details</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Date</span>
                        <span class="font-medium">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Payment</span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Method</span>
                        <span class="font-medium uppercase">{{ $order->payment_method }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--bloom-muted-foreground);">Phone</span>
                        <span class="font-medium">{{ $order->phone }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border p-6" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--bloom-foreground);">Shipping Address</h2>
                <p class="text-sm whitespace-pre-line" style="color: var(--bloom-muted-foreground);">{{ $order->shipping_address }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
