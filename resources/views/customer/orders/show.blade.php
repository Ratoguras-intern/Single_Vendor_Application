@extends('layouts.frontend')

@section('title', 'Order #' . $order->order_number . ' - NBK Vertex')

@php
    use App\Support\OrderStatuses;
    use App\Models\ProductReview;
    $flow = OrderStatuses::FLOW;
    $currentStep = $order->status_step;
    $isCancelled = OrderStatuses::isCancelled($order->status);
    $activeReturns = $order->returns()->whereIn('status', ['requested', 'pending_review', 'more_information_required', 'approved', 'return_shipped'])->count();
    $historyByStatus = $order->statusHistory->keyBy('status');
    $isDelivered = $order->status === 'delivered';
    $reviewedProductIds = $isDelivered ? ProductReview::where('user_id', auth()->id())
        ->whereIn('product_id', $order->items->pluck('product_id'))
        ->pluck('product_id')
        ->toArray() : [];
@endphp

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <style>
            @media print {
                header, footer, .print-hide { display: none !important; }
                body { background: #fff !important; }
                .section { max-width: 100% !important; padding: 0 !important; }
                .card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
            }
        </style>

        <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mb-2 print-hide">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to Orders
                </a>
                <h1 class="page-heading">Order {{ $order->order_number }}</h1>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Placed {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge {{ $isCancelled ? 'badge-danger' : ($activeReturns > 0 ? 'bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300' : OrderStatuses::frontendBadgeClasses($order->status)) }}">
                    {{ $order->status_label }}
                    @if ($activeReturns > 0)
                        <span class="ml-1 text-[10px]">({{ $activeReturns }} return{{ $activeReturns > 1 ? 's' : '' }})</span>
                    @endif
                </span>
                @if ($order->status === 'shipped')
                    <form method="POST" action="{{ route('customer.orders.confirmDelivery', $order) }}" x-data @submit.prevent="$store.confirmModal.open({ title: 'Confirm Delivery', message: 'Confirm you have received this order?', confirmText: 'Confirm', confirmClass: 'bg-green-600 hover:bg-green-700', form: $el })" class="print-hide">
                        @csrf
                        <button type="submit" class="btn-primary !py-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            Confirm Delivery
                        </button>
                    </form>
                @elseif ($order->status === 'delivered' && $activeReturns === 0)
                    <a href="{{ route('customer.returns.create', $order) }}" class="btn-outline !py-2 !border-orange-300 !text-orange-700 hover:!bg-orange-50 dark:!border-orange-700 dark:!text-orange-400 dark:hover:!bg-orange-950/30 print-hide">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                        Request Return
                    </a>
                @elseif (! OrderStatuses::isTerminal($order->status))
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" x-data @submit.prevent="$store.confirmModal.open({ title: 'Cancel Order', message: 'Cancel this order? This cannot be undone.', form: $el })" class="print-hide">
                        @csrf
                        <button type="submit" class="btn-danger !py-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Cancel Order
                        </button>
                    </form>
                @endif
                <a href="{{ route('customer.orders.receipt', $order) }}" target="_blank" class="btn-outline !py-2 print-hide">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Z"/></svg>
                    Print Receipt
                </a>
            </div>
        </div>

        {{-- Order tracking stepper --}}
        <div class="card mb-8">
            @if($isCancelled)
                <div class="flex items-center gap-3 rounded-card bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>
                    This order was cancelled and will not be fulfilled.
                </div>
            @elseif($activeReturns > 0)
                <div class="flex items-center gap-3 rounded-card bg-orange-50 dark:bg-orange-950/30 border border-orange-200 dark:border-orange-800 px-4 py-3 text-sm text-orange-700 dark:text-orange-400">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                    You have {{ $activeReturns }} active return request{{ $activeReturns > 1 ? 's' : '' }} for this order.
                    <a href="{{ route('customer.returns.index') }}" class="ml-2 font-semibold underline">View Returns</a>
                </div>
            @else
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white">Order Progress</h2>
                    <span class="text-xs font-medium text-secondary-500 dark:text-secondary-400">{{ $currentStep !== null ? 'Step ' . ($currentStep + 1) . ' of ' . count($flow) : '' }}</span>
                </div>

                <div class="overflow-x-auto -mx-5 px-5 sm:mx-0 sm:px-0">
                <ol class="relative flex items-start justify-between min-w-[480px]">
                    @foreach($flow as $index => $status)
                        @php
                            $history = $historyByStatus->get($status);
                            $isDone = $currentStep !== null && $index < $currentStep;
                            $isCurrent = $currentStep !== null && $index === $currentStep;
                        @endphp
                        <li class="relative flex flex-1 flex-col items-center text-center">
                            @if(! $loop->first)
                                <div class="absolute top-4 -left-1/2 right-1/2 h-0.5 -translate-y-1/2 {{ $currentStep !== null && $index <= $currentStep ? 'bg-primary-500' : 'bg-secondary-200 dark:bg-secondary-700' }}"></div>
                            @endif
                            <div class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full border-2
                                {{ $isDone ? 'border-primary-500 bg-primary-500 text-white' : ($isCurrent ? 'border-primary-500 bg-white text-primary-600 dark:bg-secondary-900 dark:text-primary-400' : 'border-secondary-200 bg-white text-secondary-300 dark:border-secondary-700 dark:bg-secondary-900 dark:text-secondary-600') }}">
                                @if($isDone)
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                @else
                                    <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <p class="mt-2 text-xs font-semibold {{ $currentStep !== null && $index <= $currentStep ? 'text-secondary-900 dark:text-white' : 'text-secondary-400 dark:text-secondary-500' }}">{{ ucfirst($status) }}</p>
                            <p class="mt-0.5 text-[10px] text-secondary-400 dark:text-secondary-500">{{ $history?->created_at?->format('M d, H:i') ?? '—' }}</p>
                        </li>
                    @endforeach
                </ol>
                </div>

                @if($order->tracking_number && in_array($order->status, ['shipped', 'delivered']))
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2 rounded-card bg-secondary-50 dark:bg-secondary-950 px-4 py-3 text-sm">
                        <span class="text-secondary-500 dark:text-secondary-400">Carrier:</span>
                        <span class="font-semibold text-secondary-900 dark:text-white">{{ $order->tracking_carrier ?: 'Shipper' }}</span>
                        <span class="text-secondary-300 dark:text-secondary-600">•</span>
                        <span class="text-secondary-500 dark:text-secondary-400">Tracking #:</span>
                        <span class="font-mono font-semibold text-primary-600 dark:text-primary-400">{{ $order->tracking_number }}</span>
                        <a href="https://www.google.com/search?q={{ urlencode(($order->tracking_carrier ?: '') . ' ' . $order->tracking_number) }}" target="_blank" rel="noopener" class="ml-1 inline-flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                            Track
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M8 7h9v9"/></svg>
                        </a>
                    </div>
                @endif
            @endif
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                {{-- Order items --}}
                <div id="reviews" class="card">
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
                                    <a href="{{ route('frontend.product.show', $item->product_id) }}" class="font-semibold text-secondary-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $item->product->name ?? 'Product #' . $item->product_id }}</a>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Qty: {{ $item->quantity }} &bull; <span x-text="$store.currency.format({{ $item->price }})"></span> each</p>
                                    @if($item->product)
                                        @if(in_array($item->product_id, $reviewedProductIds))
                                            <a href="{{ route('frontend.product.show', $item->product_id) }}#reviews" class="inline-flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400 mt-1">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                                Review submitted
                                            </a>
                                        @elseif($isDelivered)
                                            <button x-on:click="$store.reviewModal.open({{ $item->product_id }}, '{{ addslashes($item->product->name) }}')" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mt-1">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25"/></svg>
                                                Write a Review
                                            </button>
                                        @else
                                            <a href="{{ route('frontend.product.show', $item->product_id) }}#reviews" class="inline-flex items-center gap-1 text-xs font-medium text-secondary-400 dark:text-secondary-500 mt-1 cursor-default">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25"/></svg>
                                                Review after delivery
                                            </a>
                                        @endif
                                    @endif
                                </div>
                                <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $item->price * $item->quantity }})"></span></p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status history timeline --}}
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Order Timeline</h2>
                    @forelse ($order->statusHistory as $history)
                        <div class="relative flex gap-4 pb-6 last:pb-0">
                            <div class="flex flex-col items-center">
                                <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ OrderStatuses::dotClasses($history->status) }}"></span>
                                @if(! $loop->last)
                                    <span class="mt-1 w-px flex-1 bg-secondary-200 dark:bg-secondary-700"></span>
                                @endif
                            </div>
                            <div class="flex-1 pb-1">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-secondary-900 dark:text-white">{{ $history->status_label }}</p>
                                    <span class="text-xs text-secondary-400">{{ $history->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($history->comment)
                                    <p class="mt-0.5 text-sm text-secondary-500 dark:text-secondary-400">{{ $history->comment }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-secondary-500 dark:text-secondary-400">No updates yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                {{-- Summary --}}
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

                {{-- Addresses --}}
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Delivery Address</h2>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Billing Address</h2>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 whitespace-pre-line">{{ $order->billing_address ?: $order->shipping_address }}</p>
                    @if($order->billing_address === $order->shipping_address)
                        <p class="mt-2 inline-flex items-center rounded-full bg-secondary-100 dark:bg-white/5 px-2 py-0.5 text-[10px] font-medium text-secondary-500 dark:text-secondary-400">Same as shipping</p>
                    @endif
                </div>

                {{-- Details --}}
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Details</h2>
                    <div class="space-y-3">
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
            </div>
        </div>
    </div>
</section>

{{-- Review Modal --}}
<div x-data="{ hover: 0, error: '' }" x-show="$store.reviewModal.show" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="fixed inset-0 bg-black/50" x-on:click="$store.reviewModal.close()"></div>
    <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-secondary-900 shadow-xl p-6" x-on:click.stop>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-secondary-900 dark:text-white">Review Product</h3>
            <button x-on:click="$store.reviewModal.close()" class="text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-4" x-text="$store.reviewModal.productName"></p>

        <form method="POST" action="{{ route('customer.reviews.store') }}" x-on:submit.prevent="if($store.reviewModal.rating === 0) { error = 'Please select a rating'; return; } error = ''; $el.submit();">
            @csrf
            <input type="hidden" name="product_id" :value="$store.reviewModal.productId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Your Rating *</label>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" x-on:click="$store.reviewModal.rating = {{ $i }}" x-on:mouseenter="hover = {{ $i }}" x-on:mouseleave="hover = 0"
                                class="focus:outline-none transition-colors">
                                <svg class="h-7 w-7 transition-colors" :class="(hover >= {{ $i }} || $store.reviewModal.rating >= {{ $i }}) ? 'text-primary-500 fill-primary-500' : 'text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            </button>
                        @endfor
                        <input type="hidden" name="rating" :value="$store.reviewModal.rating">
                        <span x-show="$store.reviewModal.rating > 0" x-text="$store.reviewModal.rating + '/5'" class="text-sm text-secondary-500 dark:text-secondary-400 ml-2"></span>
                    </div>
                    <p x-show="error" x-text="error" class="text-sm text-red-500 mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Review Title (optional)</label>
                    <input type="text" name="title" maxlength="255" placeholder="Summarize your experience"
                        class="w-full rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm text-secondary-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Your Review *</label>
                    <textarea name="comment" rows="3" maxlength="2000" required placeholder="Share your experience with this product..."
                        class="w-full rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm text-secondary-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 resize-none"></textarea>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">Submit Review</button>
                    <button type="button" x-on:click="$store.reviewModal.close()" class="btn-outline">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('reviewModal', {
        show: false,
        productId: null,
        productName: '',
        rating: 0,

        open(id, name) {
            this.productId = id;
            this.productName = name;
            this.rating = 0;
            this.show = true;
        },
        close() {
            this.show = false;
        },
    });
});
</script>
@endpush
