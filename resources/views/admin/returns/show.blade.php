@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Returns', 'url' => route('admin.returns.index')],
        ['label' => $return->return_number, 'url' => null],
    ];
@endphp

@section('content')
    @php
        use App\Support\ReturnStatuses;
        $currency = admin_currency();
    @endphp

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Return <span class="font-mono">#{{ $return->return_number }}</span></h2>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ ReturnStatuses::badgeClasses($return->status) }}">
                        <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                        {{ ReturnStatuses::label($return->status) }}
                    </span>
                </div>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Requested {{ $return->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('admin.returns.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Returns
            </a>
        </div>

        {{-- Admin Notes / Rejection Reason / Return Instructions --}}
        @if ($return->admin_notes)
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/5">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Admin Notes</p>
                        <p class="mt-0.5 text-sm text-amber-700 dark:text-amber-400">{{ $return->admin_notes }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($return->rejection_reason)
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/5">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">Rejection Reason</p>
                        <p class="mt-0.5 text-sm text-red-700 dark:text-red-400">{{ $return->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($return->return_instructions)
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-500/20 dark:bg-blue-500/5">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2Z"/><polyline points="14,2 14,8 20,8"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Return Instructions</p>
                        <p class="mt-0.5 text-sm text-blue-700 dark:text-blue-400">{{ $return->return_instructions }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Return Items --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Return Items</h3>
            <div class="space-y-4">
                @forelse ($return->items as $item)
                    <div class="flex gap-4 rounded-lg border border-gray-100 p-4 dark:border-gray-800">
                        <div class="shrink-0">
                            @if ($item->product && $item->product->primaryImage())
                                <img src="{{ $item->product->primaryImage()->image }}" alt="" class="h-16 w-16 rounded-lg object-cover">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-6 w-6 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                                    @if ($item->product && $item->product->sku)
                                        <p class="text-xs text-gray-400">SKU: {{ $item->product->sku }}</p>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-800 dark:text-white">{{ format_currency($item->unit_price) }}</span>
                            </div>
                            <div class="mt-1 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <span>Qty: {{ $item->quantity }}</span>
                                @if ($item->reason)
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                        {{ ReturnStatuses::returnReasons()[$item->reason] ?? $item->reason }}
                                    </span>
                                @endif
                            </div>
                            @if ($item->customer_note)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 italic">"{{ $item->customer_note }}"</p>
                            @endif
                        </div>
                    </div>
                    @if ($item->evidenceImages && $item->evidenceImages->count())
                        <div class="ml-20 flex flex-wrap gap-2">
                            @foreach ($item->evidenceImages as $image)
                                <a href="{{ $image->image }}" target="_blank" class="block h-14 w-14 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                    <img src="{{ $image->image }}" alt="Evidence" class="h-full w-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No items in this return.</p>
                @endforelse
            </div>
        </div>

        {{-- Action Panels --}}
        @if (ReturnStatuses::canApprove($return->status) || ReturnStatuses::canReject($return->status) || ReturnStatuses::canRequestMoreInfo($return->status))
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                @if (ReturnStatuses::canApprove($return->status))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-500/20 dark:bg-emerald-500/5">
                        <h3 class="mb-4 text-lg font-semibold text-emerald-700 dark:text-emerald-400">Approve Return</h3>
                        <form action="{{ route('admin.returns.approve', $return) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="approve_instructions" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Return Instructions</label>
                                <textarea name="return_instructions" id="approve_instructions" rows="3" placeholder="Where to ship, packaging requirements..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                            </div>
                            <div>
                                <label for="approve_notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Notes</label>
                                <textarea name="admin_notes" id="approve_notes" rows="2" placeholder="Internal notes..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-600">Approve</button>
                        </form>
                    </div>
                @endif
                @if (ReturnStatuses::canReject($return->status))
                    <div class="rounded-lg border border-red-200 bg-red-50 p-6 dark:border-red-500/20 dark:bg-red-500/5">
                        <h3 class="mb-4 text-lg font-semibold text-red-700 dark:text-red-400">Reject Return</h3>
                        <form action="{{ route('admin.returns.reject', $return) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="rejection_reason" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Rejection Reason <span class="text-red-500">*</span></label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" required placeholder="Reason for rejecting this return..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400 dark:hover:bg-red-500/10">Reject</button>
                        </form>
                    </div>
                @endif
                @if (ReturnStatuses::canRequestMoreInfo($return->status))
                    <div class="rounded-lg border border-purple-200 bg-purple-50 p-6 dark:border-purple-500/20 dark:bg-purple-500/5">
                        <h3 class="mb-4 text-lg font-semibold text-purple-700 dark:text-purple-400">Request More Info</h3>
                        <form action="{{ route('admin.returns.moreInfo', $return) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="moreinfo_notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Message to Customer <span class="text-red-500">*</span></label>
                                <textarea name="admin_notes" id="moreinfo_notes" rows="3" required placeholder="What additional information do you need?"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-purple-300 bg-white px-4 py-2.5 text-sm font-medium text-purple-600 hover:bg-purple-50 dark:border-purple-500/30 dark:text-purple-400 dark:hover:bg-purple-500/10">Request Info</button>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        @if ($return->status === ReturnStatuses::APPROVED)
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-500/20 dark:bg-emerald-500/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-500/20">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">Awaiting Customer Shipment</p>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400">Return has been approved. Waiting for the customer to ship items back.</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($return->status === ReturnStatuses::RETURN_SHIPPED)
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Mark as Received</h3>
                <form action="{{ route('admin.returns.receive', $return) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="restock" id="restock" value="1" checked class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500/20">
                        <label for="restock" class="text-sm font-medium text-gray-700 dark:text-gray-300">Restock items to inventory</label>
                    </div>
                    <div>
                        <label for="receive_notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Notes</label>
                        <textarea name="admin_notes" id="receive_notes" rows="2" placeholder="Condition of returned items..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-cyan-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-cyan-600">Mark as Received</button>
                </form>
            </div>
        @endif

        @if ($return->status === ReturnStatuses::RECEIVED)
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Process Refund</h3>
                <form action="{{ route('admin.returns.refund', $return) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="refund_amount" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Amount ({{ $currency }})</label>
                        <input type="number" name="refund_amount" id="refund_amount" step="0.01" min="0" max="{{ $return->items->sum(fn($i) => $i->unit_price * $i->quantity) }}" value="{{ number_format($return->items->sum(fn($i) => $i->unit_price * $i->quantity), 2, '.', '') }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <p class="mt-1 text-xs text-gray-400">Max: {{ format_currency($return->items->sum(fn($i) => $i->unit_price * $i->quantity)) }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="refund_method" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Method</label>
                            <select name="refund_method" id="refund_method" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="original">Original Payment Method</option>
                                <option value="store_credit">Store Credit</option>
                                <option value="manual">Manual Refund</option>
                            </select>
                        </div>
                        <div>
                            <label for="refund_reference" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Reference</label>
                            <input type="text" name="refund_reference" id="refund_reference" placeholder="Transaction ID or reference number..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label for="refund_notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Notes</label>
                        <textarea name="admin_notes" id="refund_notes" rows="2" placeholder="Refund details..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600">Process Refund</button>
                </form>
            </div>
        @endif

        @if ($return->status === ReturnStatuses::REFUNDED)
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Complete Return</h3>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Refund has been processed. Mark this return as completed.</p>
                    </div>
                    <form action="{{ route('admin.returns.complete', $return) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                            Complete
                        </button>
                    </form>
                </div>
            </div>
        @endif

        @if ($return->status === ReturnStatuses::MORE_INFO_REQUIRED)
            <div class="rounded-lg border border-purple-200 bg-purple-50 p-6 dark:border-purple-500/20 dark:bg-purple-500/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-500/20">
                        <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-purple-800 dark:text-purple-300">Awaiting Customer Response</p>
                        <p class="text-sm text-purple-600 dark:text-purple-400">More information has been requested from the customer.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Order, Customer, Return Details --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @if ($return->order)
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Order Info</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Order</span>
                            <a href="{{ route('admin.orders.show', $return->order) }}" class="font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">#{{ $return->order->order_number }}</a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Order Total</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ format_currency($return->order->total_amount) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Payment Status</span>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $return->order->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                                {{ ucfirst($return->order->payment_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Payment Method</span>
                            <span class="font-medium text-gray-800 dark:text-white capitalize">{{ str_replace('_', ' ', $return->order->payment_method) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Customer</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Name</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $return->user->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Email</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $return->user->email ?? '—' }}</span>
                    </div>
                    @if ($return->customer_notes)
                        <div class="border-t border-gray-200 pt-3 dark:border-gray-800">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Customer Notes</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $return->customer_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Return Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Return #</span>
                        <span class="font-mono font-medium text-gray-800 dark:text-white">{{ $return->return_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Requested</span>
                        <span class="text-gray-800 dark:text-white">{{ $return->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if ($return->approved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Approved</span>
                            <span class="text-gray-800 dark:text-white">{{ $return->approved_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                    @if ($return->shipped_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Shipped</span>
                            <span class="text-gray-800 dark:text-white">{{ $return->shipped_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                    @if ($return->received_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Received</span>
                            <span class="text-gray-800 dark:text-white">{{ $return->received_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                    @if ($return->refund_amount)
                        <div class="border-t border-gray-200 pt-3 dark:border-gray-800">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-800 dark:text-white">Refund Amount</span>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ format_currency($return->refund_amount) }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
