@extends('layouts.frontend')

@section('title', 'My Returns - NBK Vertex')

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mb-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to Orders
                </a>
                <h1 class="page-heading">My Returns <span class="text-lg font-normal text-secondary-500 dark:text-secondary-400">({{ $returns->total() }})</span></h1>
                <p class="section-subheading mt-1">Track and manage your return requests</p>
            </div>
        </div>

        <div class="card p-0 overflow-hidden">
            @forelse ($returns as $return)
                <a href="{{ route('customer.returns.show', $return) }}" class="flex items-center justify-between gap-3 p-5 border-b border-secondary-100 dark:border-secondary-700 transition-colors hover:bg-secondary-50 dark:hover:bg-white/5 last:border-b-0">
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="w-10 h-10 rounded-card flex items-center justify-center bg-orange-500 text-white">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-secondary-900 dark:text-white">{{ $return->return_number }}</p>
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">Order {{ $return->order->order_number }} &bull; {{ $return->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                        <div class="text-right hidden sm:block">
                            <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $return->refund_amount }})"></span></p>
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ $return->items->count() }} item(s)</p>
                        </div>
                        <span class="badge {{ $return->status === 'cancelled' ? 'badge-danger' : App\Support\ReturnStatuses::frontendBadgeClasses($return->status) }}">
                            {{ $return->status_label }}
                        </span>
                        <svg class="h-5 w-5 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-secondary-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 dark:text-white mb-1">No return requests</h3>
                    <p class="text-secondary-500 dark:text-secondary-400 mb-4">You haven't submitted any return requests yet.</p>
                    <a href="{{ route('customer.orders.index') }}" class="btn-primary">View Orders</a>
                </div>
            @endforelse
        </div>

        @if ($returns->hasPages())
            <div class="mt-6">
                {{ $returns->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
