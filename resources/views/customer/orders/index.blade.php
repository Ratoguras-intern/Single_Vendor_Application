@extends('layouts.frontend')

@section('title', 'My Orders - NBK Vertex')

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8">
            <h1 class="page-heading">My Orders</h1>
            <p class="section-subheading mt-1">Track and manage your orders</p>
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-2">
            <a href="{{ route('customer.orders.index') }}"
               class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold transition-colors {{ is_null($status) ? 'bg-primary-500 text-white' : 'bg-secondary-100 text-secondary-600 dark:bg-white/5 dark:text-secondary-400 hover:bg-secondary-200 dark:hover:bg-white/10' }}">
                All
            </a>
            @foreach (App\Support\OrderStatuses::all() as $st)
                <a href="{{ route('customer.orders.index', ['status' => $st]) }}"
                   class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold transition-colors {{ $status === $st ? 'bg-primary-500 text-white' : 'bg-secondary-100 text-secondary-600 dark:bg-white/5 dark:text-secondary-400 hover:bg-secondary-200 dark:hover:bg-white/10' }}">
                    {{ App\Support\OrderStatuses::label($st) }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="card">
                <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">Total Orders</p>
                <p class="mt-1 text-2xl font-bold text-secondary-900 dark:text-white">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="card">
                <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">Delivered</p>
                <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['delivered_orders'] }}</p>
            </div>
            <div class="card">
                <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">Total Spent</p>
                <p class="mt-1 text-2xl font-bold text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format({{ $stats['total_spent'] }})"></span></p>
            </div>
            <div class="card">
                <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">Last Purchase</p>
                <p class="mt-1 text-lg font-bold text-secondary-900 dark:text-white">{{ $stats['last_order'] ? $stats['last_order']->created_at->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        <div class="card p-0 overflow-hidden">
            @forelse ($orders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="flex items-center justify-between gap-3 p-5 border-b border-secondary-100 dark:border-secondary-700 transition-colors hover:bg-secondary-50 dark:hover:bg-white/5 last:border-b-0">
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="w-10 h-10 rounded-card flex items-center justify-center bg-primary-500 text-white">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-secondary-900 dark:text-white">{{ $order->order_number }}</p>
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                        <div class="text-right hidden sm:block">
                            <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $order->total_amount }})"></span></p>
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ $order->items->count() }} item(s)</p>
                        </div>
                        <span class="badge {{ $order->status === 'cancelled' ? 'badge-danger' : App\Support\OrderStatuses::frontendBadgeClasses($order->status) }}">
                            {{ $order->status_label }}
                        </span>
                        <svg class="h-5 w-5 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-secondary-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 dark:text-white mb-1">No orders yet</h3>
                    <p class="text-secondary-500 dark:text-secondary-400 mb-4">Start shopping to see your orders here.</p>
                    <a href="{{ route('frontend.shop') }}" class="btn-primary">Browse Products</a>
                </div>
            @endforelse
        </div>

        @if ($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
