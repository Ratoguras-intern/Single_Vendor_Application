@extends('layouts.frontend')

@section('title', 'My Orders - NBK Vertex')

@section('content')
<div x-data class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold" style="color: var(--bloom-foreground);">My Orders</h1>
        <p class="mt-2" style="color: var(--bloom-muted-foreground);">Track and manage your orders</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-xl border p-5" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
            <p class="text-sm font-medium" style="color: var(--bloom-muted-foreground);">Total Orders</p>
            <p class="mt-1 text-2xl font-bold" style="color: var(--bloom-foreground);">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="rounded-xl border p-5" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
            <p class="text-sm font-medium" style="color: var(--bloom-muted-foreground);">Completed</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
        </div>
        <div class="rounded-xl border p-5" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
            <p class="text-sm font-medium" style="color: var(--bloom-muted-foreground);">Total Spent</p>
            <p class="mt-1 text-2xl font-bold" style="color: var(--bloom-primary);"><span x-text="$store.currency.format({{ $stats['total_spent'] }})"></span></p>
        </div>
        <div class="rounded-xl border p-5" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
            <p class="text-sm font-medium" style="color: var(--bloom-muted-foreground);">Last Purchase</p>
            <p class="mt-1 text-lg font-bold" style="color: var(--bloom-foreground);">{{ $stats['last_order'] ? $stats['last_order']->created_at->format('M d, Y') : 'N/A' }}</p>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="rounded-xl border overflow-hidden" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
        @forelse ($orders as $order)
            <a href="{{ route('customer.orders.show', $order) }}" class="flex items-center justify-between p-5 border-b transition-colors hover:bg-gray-50" style="border-color: var(--bloom-border);">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bloom-primary); color: white;">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold" style="color: var(--bloom-foreground);">{{ $order->order_number }}</p>
                        <p class="text-sm" style="color: var(--bloom-muted-foreground);">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-bold" style="color: var(--bloom-foreground);"><span x-text="$store.currency.format({{ $order->total_amount }})"></span></p>
                        <p class="text-sm" style="color: var(--bloom-muted-foreground);">{{ $order->items->count() }} item(s)</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $order->status === 'completed' ? 'bg-green-50 text-green-700' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <svg class="h-5 w-5" style="color: var(--bloom-muted-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <svg class="h-16 w-16 mx-auto mb-4" style="color: var(--bloom-muted-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                <h3 class="text-lg font-semibold mb-2" style="color: var(--bloom-foreground);">No orders yet</h3>
                <p class="mb-4" style="color: var(--bloom-muted-foreground);">Start shopping to see your orders here.</p>
                <a href="{{ route('frontend.shop') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">Browse Products</a>
            </div>
        @endforelse
    </div>

    @if ($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
