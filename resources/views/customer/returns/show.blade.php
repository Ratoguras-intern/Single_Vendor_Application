@extends('layouts.frontend')

@section('title', 'Return ' . $return->return_number . ' - NBK Vertex')

@php
    use App\Support\ReturnStatuses;
@endphp

@section('content')
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('customer.returns.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mb-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to Returns
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="page-heading">{{ $return->return_number }}</h1>
                    <span class="badge {{ $return->status === 'cancelled' ? 'badge-danger' : ReturnStatuses::frontendBadgeClasses($return->status) }}">
                        {{ $return->status_label }}
                    </span>
                </div>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Order {{ $return->order->order_number }} &bull; Requested {{ $return->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if (ReturnStatuses::canCancel($return->status))
                    <form method="POST" action="{{ route('customer.returns.cancel', $return) }}" onsubmit="return confirm('Cancel this return request? This cannot be undone.');" class="print-hide">
                        @csrf
                        <button type="submit" class="btn-danger !py-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Cancel Return
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                {{-- Return Items --}}
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Return Items</h2>
                    <div class="space-y-3">
                        @foreach ($return->items as $item)
                            <div class="p-4 rounded-card bg-secondary-50 dark:bg-secondary-950">
                                <div class="flex items-center gap-4">
                                    @if ($item->product && $item->product->primaryImage())
                                        <img src="{{ $item->product->primaryImage()->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-card object-cover" />
                                    @else
                                        <div class="w-16 h-16 rounded-card bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-secondary-900 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                                        <p class="text-sm text-secondary-500 dark:text-secondary-400">Qty: {{ $item->quantity }} &bull; <span x-text="$store.currency.format({{ $item->unit_price }})"></span> each</p>
                                    </div>
                                    <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $item->unit_price * $item->quantity }})"></span></p>
                                </div>
                                <div class="mt-3 ml-20 space-y-1">
                                    <p class="text-sm"><span class="text-secondary-500 dark:text-secondary-400">Reason:</span> <span class="font-medium text-secondary-700 dark:text-secondary-300">{{ ReturnStatuses::returnReasons()[$item->reason] ?? $item->reason }}</span></p>
                                    @if ($item->customer_note)
                                        <p class="text-sm"><span class="text-secondary-500 dark:text-secondary-400">Note:</span> <span class="text-secondary-700 dark:text-secondary-300">{{ $item->customer_note }}</span></p>
                                    @endif
                                    @if ($item->evidence && $item->evidence->count())
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach ($item->evidence as $ev)
                                                <a href="{{ $ev->imageUrl() }}" target="_blank" rel="noopener">
                                                    <img src="{{ $ev->imageUrl() }}" alt="Evidence" class="w-16 h-16 rounded-card object-cover border border-secondary-200 dark:border-secondary-700 hover:ring-2 hover:ring-primary-500 transition-shadow" />
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status Timeline --}}
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Return Timeline</h2>
                    <div class="relative space-y-0">
                        @php
                            $timeline = collect([
                                ['label' => 'Return Requested', 'timestamp' => $return->requested_at, 'status' => ReturnStatuses::REQUESTED],
                                ['label' => 'Pending Review', 'timestamp' => $return->created_at, 'status' => ReturnStatuses::PENDING_REVIEW],
                            ]);

                            if ($return->status === ReturnStatuses::MORE_INFO_REQUIRED) {
                                $timeline->push(['label' => 'More Info Required', 'timestamp' => $return->updated_at, 'status' => ReturnStatuses::MORE_INFO_REQUIRED]);
                            }

                            if ($return->approved_at) {
                                $timeline->push(['label' => 'Approved', 'timestamp' => $return->approved_at, 'status' => ReturnStatuses::APPROVED]);
                            }

                            if ($return->rejected_at) {
                                $timeline->push(['label' => 'Rejected', 'timestamp' => $return->rejected_at, 'status' => ReturnStatuses::REJECTED]);
                            }

                            if ($return->status === ReturnStatuses::RETURN_SHIPPED && $return->carrier) {
                                $timeline->push(['label' => 'Return Shipped', 'timestamp' => $return->updated_at, 'status' => ReturnStatuses::RETURN_SHIPPED]);
                            }

                            if ($return->received_at) {
                                $timeline->push(['label' => 'Received', 'timestamp' => $return->received_at, 'status' => ReturnStatuses::RECEIVED]);
                            }

                            if ($return->refunded_at) {
                                $timeline->push(['label' => 'Refunded', 'timestamp' => $return->refunded_at, 'status' => ReturnStatuses::REFUNDED]);
                            }

                            if ($return->status === ReturnStatuses::COMPLETED) {
                                $timeline->push(['label' => 'Completed', 'timestamp' => $return->refunded_at, 'status' => ReturnStatuses::COMPLETED]);
                            }

                            if ($return->status === ReturnStatuses::CANCELLED) {
                                $timeline->push(['label' => 'Cancelled', 'timestamp' => $return->updated_at, 'status' => ReturnStatuses::CANCELLED]);
                            }

                            $timeline = $timeline->sortByDesc('timestamp')->values();
                        @endphp

                        @forelse ($timeline as $event)
                            <div class="relative flex gap-4 pb-6 last:pb-0">
                                <div class="flex flex-col items-center">
                                    <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ ReturnStatuses::dotClasses($event['status']) }}"></span>
                                    @if(! $loop->last)
                                        <span class="mt-1 w-px flex-1 bg-secondary-200 dark:bg-secondary-700"></span>
                                    @endif
                                </div>
                                <div class="flex-1 pb-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-secondary-900 dark:text-white">{{ $event['label'] }}</p>
                                        <span class="text-xs text-secondary-400">{{ $event['timestamp']?->format('M d, Y h:i A') ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">No updates yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Order Info --}}
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Order Information</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Order</span>
                            <a href="{{ route('customer.orders.show', $return->order) }}" class="font-medium text-primary-600 dark:text-primary-400 hover:underline">{{ $return->order->order_number }}</a>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Requested</span>
                            <span class="font-medium text-secondary-900 dark:text-white">{{ $return->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Items</span>
                            <span class="font-medium text-secondary-900 dark:text-white">{{ $return->items->count() }}</span>
                        </div>
                        <div class="divider"></div>
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-secondary-900 dark:text-white">Refund Amount</span>
                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format({{ $return->refund_amount }})"></span></span>
                        </div>
                    </div>
                </div>

                {{-- Tracking Info --}}
                @if ($return->carrier && $return->tracking_number)
                    <div class="card">
                        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Return Shipment</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-secondary-500 dark:text-secondary-400">Carrier</span>
                                <span class="font-semibold text-secondary-900 dark:text-white">{{ $return->carrier }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-secondary-500 dark:text-secondary-400">Tracking #</span>
                                <span class="font-mono font-semibold text-primary-600 dark:text-primary-400">{{ $return->tracking_number }}</span>
                            </div>
                            <a href="https://www.google.com/search?q={{ urlencode($return->carrier . ' ' . $return->tracking_number) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                Track Package
                                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M8 7h9v9"/></svg>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Admin Notes --}}
                @if ($return->admin_notes)
                    <div class="card">
                        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-2">Admin Notes</h2>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400 whitespace-pre-line">{{ $return->admin_notes }}</p>
                    </div>
                @endif

                {{-- Rejection Reason --}}
                @if ($return->rejection_reason)
                    <div class="card border border-red-200 dark:border-red-800">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <h2 class="text-lg font-semibold text-red-700 dark:text-red-400">Rejection Reason</h2>
                        </div>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400 whitespace-pre-line">{{ $return->rejection_reason }}</p>
                    </div>
                @endif

                {{-- Return Instructions --}}
                @if ($return->return_instructions)
                    <div class="card">
                        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-2">Return Instructions</h2>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400 whitespace-pre-line">{{ $return->return_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- More Info Required Form --}}
        @if ($return->status === ReturnStatuses::MORE_INFO_REQUIRED)
            <div class="mt-8 card" x-data="{ files: [] }">
                <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Provide Additional Information</h2>
                <form method="POST" action="{{ route('customer.returns.addInfo', $return) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="message" class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="4" required maxlength="1000" placeholder="Provide the requested information..." class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm placeholder:text-secondary-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white dark:placeholder:text-secondary-500">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="evidence" class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Evidence Images <span class="text-secondary-400">(optional, max 5)</span></label>
                        <input type="file" name="evidence[]" id="evidence" multiple accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-primary-600 hover:file:bg-primary-100 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white dark:file:bg-primary-950/30 dark:file:text-primary-400" />
                        @error('evidence')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary !py-2">Submit Information</button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Ship Return Form --}}
        @if ($return->status === ReturnStatuses::APPROVED)
            <div class="mt-8 card">
                <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Ship Your Return</h2>
                <form method="POST" action="{{ route('customer.returns.ship', $return) }}" class="space-y-4">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="carrier" class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Carrier <span class="text-red-500">*</span></label>
                            <input type="text" name="carrier" id="carrier" required maxlength="100" placeholder="e.g. FedEx, UPS, DHL" value="{{ old('carrier') }}" class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm placeholder:text-secondary-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white dark:placeholder:text-secondary-500" />
                            @error('carrier')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tracking_number" class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Tracking Number <span class="text-red-500">*</span></label>
                            <input type="text" name="tracking_number" id="tracking_number" required maxlength="100" placeholder="Enter tracking number" value="{{ old('tracking_number') }}" class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm placeholder:text-secondary-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white dark:placeholder:text-secondary-500" />
                            @error('tracking_number')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" onclick="return confirm('Submit return shipment details?')" class="btn-primary !py-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75m-7.5-3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                            Submit Shipment Details
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</section>
@endsection
