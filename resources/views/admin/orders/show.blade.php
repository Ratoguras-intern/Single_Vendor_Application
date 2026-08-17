@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Orders', 'url' => route('admin.orders.index')],
        ['label' => '#' . $order->order_number, 'url' => null],
    ];

    use App\Support\OrderStatuses;

    $flow = OrderStatuses::FLOW;
    $currentStep = $order->status_step;
    $isCancelled = OrderStatuses::isCancelled($order->status);
@endphp

@section('content')
    <style>
        @media print {
            #sidebar, header, #backdrop, .preloader, .breadcrumb-wrap {
                display: none !important;
            }
            body { background: #fff !important; }
            .print-area {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .print-area .flex-1 { margin-left: 0 !important; }
            .print\:hidden { display: none !important; }
            .print\:shadow-none { box-shadow: none !important; border-color: #e5e7eb !important; }
            .print-break { page-break-before: always; }
            .order-receipt { border: 1px solid #e5e7eb; border-radius: 8px; }
        }
    </style>

    <div
        x-data="orderDetail({{ $order->id }}, '{{ $order->status }}', {{ $currentStep ?? 'null' }}, '{{ $order->payment_status }}')"
        class="print-area space-y-6"
    >
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Order <span class="font-mono">#{{ $order->order_number }}</span></h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Placed {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium {{ $isCancelled ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : OrderStatuses::badgeClasses($order->status) }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                    <span x-text="statusLabel">{{ $order->status_label }}</span>
                </span>
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                    Print Receipt
                </a>
            </div>
        </div>

        {{-- Status stepper --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
            @if($isCancelled)
                <div class="flex items-center gap-3 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>
                    This order was cancelled and will not be fulfilled.
                </div>
            @else
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Order Progress</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="currentStep !== null ? 'Step ' + (currentStep + 1) + ' of ' + {{ count($flow) }} + ' — ' + statusLabel : ''"></p>
                </div>

                <ol class="relative flex items-center justify-between">
                    @foreach($flow as $index => $status)
                        @php
                            $step = $index + 1;
                        @endphp
                        <li class="relative flex flex-1 flex-col items-center" x-data>
                            @if(! $loop->first)
                                <div class="absolute top-5 -left-1/2 right-1/2 h-1 -translate-y-1/2"
                                    :class="({{ $index }} <= currentStep) ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700'"></div>
                            @endif
                            <button type="button" @click="updateStatus('{{ $status }}')"
                                :title="'Set status to {{ ucfirst($status) }}'"
                                class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all"
                                :class="{
                                    'border-brand-500 bg-brand-500 text-white': {{ $index }} < currentStep,
                                    'border-brand-500 bg-white text-brand-600 dark:bg-gray-900 dark:text-brand-400': {{ $index }} === currentStep,
                                    'border-gray-200 bg-white text-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-600': {{ $index }} > currentStep,
                                }">
                                <template x-if="{{ $index }} < currentStep">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <template x-if="{{ $index }} >= currentStep || currentStep === null">
                                    <span class="text-sm font-semibold">{{ $step }}</span>
                                </template>
                            </button>
                            <p class="mt-2 text-center text-xs font-medium"
                                :class="{{ $index }} <= currentStep ? 'text-gray-800 dark:text-white' : 'text-gray-400 dark:text-gray-500'">
                                {{ ucfirst($status) }}
                            </p>
                            <p class="mt-0.5 text-center text-[10px] text-gray-400 dark:text-gray-500"
                                x-text="historyTimestamp('{{ $status }}')">—</p>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-6 flex flex-wrap items-center gap-2 border-t border-gray-200 pt-4 dark:border-gray-800 print:hidden">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Quick actions:</span>
                    <template x-if="currentStep !== null && nextStatus">
                        <button type="button" @click="updateStatus(nextStatus)" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                            Mark as <span class="capitalize" x-text="nextStatusLabel">Packed</span>
                        </button>
                    </template>
                    <template x-if="currentStep === null">
                        <button type="button" @click="updateStatus('pending')" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">
                            Reopen Order
                        </button>
                    </template>
                    <template x-if="currentStep !== null && currentStep < 2 && currentStep !== null">
                        <button type="button" @click="updateStatus('cancelled')" class="inline-flex items-center gap-2 rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400 dark:hover:bg-red-500/10">
                            Cancel Order
                        </button>
                    </template>
                </div>
            @endif
        </div>

        {{-- Main grid --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Left: items + history --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800">
                                    <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Product</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Qty</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td class="py-3">
                                            <div class="flex items-center gap-3">
                                                @if($item->product && $item->product->primaryImage())
                                                    <img src="{{ $item->product->primaryImage()->image }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                                                    @if($item->product)
                                                        <p class="text-xs text-gray-400">{{ $item->product->sku ?? '' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-sm text-gray-600 dark:text-gray-400">{{ format_currency($item->price) }}</td>
                                        <td class="py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }}</td>
                                        <td class="py-3 text-right text-sm font-medium text-gray-800 dark:text-white">{{ format_currency($item->price * $item->quantity) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">No items.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Status History</h3>
                    @forelse ($order->statusHistory as $history)
                        <div class="relative flex gap-4 pb-6 last:pb-0">
                            <div class="flex flex-col items-center">
                                <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ OrderStatuses::dotClasses($history->status) }}"></span>
                                @if(! $loop->last)
                                    <span class="mt-1 w-px flex-1 bg-gray-200 dark:bg-gray-700"></span>
                                @endif
                            </div>
                            <div class="flex-1 pb-1">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $history->status_label }}
                                        @if($loop->first)
                                            <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">CURRENT</span>
                                        @endif
                                    </p>
                                    <span class="text-xs text-gray-400">{{ $history->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($history->comment)
                                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $history->comment }}</p>
                                @endif
                                @if($history->changedBy)
                                    <p class="mt-0.5 text-xs text-gray-400">by {{ $history->changedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No status history yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Right column --}}
            <div class="space-y-6">
                {{-- Tracking --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tracking</h3>
                        <template x-if="{{ $order->tracking_number ? 'true' : 'false' }}">
                            <span class="rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">Provided</span>
                        </template>
                    </div>

                    <div x-show="!editingTracking" class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Carrier</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $order->tracking_carrier ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Tracking #</span>
                            <span class="font-mono font-medium text-gray-800 dark:text-white">{{ $order->tracking_number ?: '—' }}</span>
                        </div>
                        @if($order->tracking_number && in_array($order->status, ['shipped', 'delivered']))
                            <a href="https://www.google.com/search?q={{ urlencode($order->tracking_carrier . ' ' . $order->tracking_number) }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400">
                                Track shipment
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                            </a>
                        @endif
                        <button type="button" @click="editingTracking = true" class="mt-4 inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.06]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                            {{ $order->tracking_number ? 'Edit Tracking' : 'Add Tracking' }}
                        </button>
                    </div>

                    <form x-show="editingTracking" x-cloak @submit.prevent="saveTracking" class="space-y-3">
                        <div>
                            <label for="tracking_carrier" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Carrier</label>
                            <input type="text" name="tracking_carrier" id="tracking_carrier" x-model="trackingCarrier" placeholder="DHL, FedEx, USPS..." list="carriers"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <datalist id="carriers">
                                <option value="DHL"></option>
                                <option value="FedEx"></option>
                                <option value="UPS"></option>
                                <option value="USPS"></option>
                                <option value="Aramex"></option>
                                <option value="Blue Dart"></option>
                            </datalist>
                        </div>
                        <div>
                            <label for="tracking_number" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tracking Number</label>
                            <input type="text" name="tracking_number" id="tracking_number" x-model="trackingNumber" placeholder="e.g. 1Z999AA10123456784"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                Save Tracking
                            </button>
                            <button type="button" @click="editingTracking = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">Cancel</button>
                        </div>
                    </form>
                </div>

                {{-- Addresses --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-6">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
                        <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-white">
                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12a9.5 9.5 0 1 0 19 0 9.5 9.5 0 0 0-19 0"/><path d="M12 7v5l3 2"/></svg>
                            Shipping Address
                        </h3>
                        <p class="whitespace-pre-line text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ $order->shipping_address }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
                        <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-white">
                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                            Billing Address
                        </h3>
                        <p class="whitespace-pre-line text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ $order->billing_address ?: $order->shipping_address }}</p>
                        @if($order->billing_address === $order->shipping_address)
                            <p class="mt-2 inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">Same as shipping</p>
                        @endif
                    </div>
                </div>

                {{-- Order summary --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] print:shadow-none">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Order Summary</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Customer</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $order->user->name ?? 'Guest' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Email</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $order->user->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Phone</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $order->phone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Payment</span>
                            <span class="font-medium text-gray-800 dark:text-white capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Payment Status</span>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : ($order->payment_status === 'failed' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 dark:border-gray-800">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ format_currency($order->subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1.5">
                                <span class="text-gray-500 dark:text-gray-400">Shipping</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $order->shipping > 0 ? format_currency($order->shipping) : 'Free' }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1.5">
                                <span class="text-gray-500 dark:text-gray-400">Tax</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ format_currency($order->tax) }}</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="flex justify-between text-sm mt-1.5">
                                    <span class="text-gray-500 dark:text-gray-400">Discount</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">-{{ format_currency($order->discount) }}</span>
                                </div>
                            @endif
                            <div class="mt-3 flex items-center justify-between border-t border-gray-200 pt-3 dark:border-gray-800">
                                <span class="font-semibold text-gray-800 dark:text-white">Total</span>
                                <span class="text-lg font-bold text-gray-800 dark:text-white">{{ format_currency($order->total_amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script data-turbo-eval>
            window.orderDetail = function orderDetail(orderId, status, step, paymentStatus) {
                return {
                    orderId,
                    status,
                    currentStep: step,
                    paymentStatus,
                    editingTracking: false,
                    trackingCarrier: '{{ addslashes($order->tracking_carrier ?? '') }}',
                    trackingNumber: '{{ addslashes($order->tracking_number ?? '') }}',
                    historyTimestamps: @json($order->statusHistory->pluck('created_at', 'status')->map(fn($d) => $d?->format('M d, H:i'))),

                    get flow() {
                        return ['pending', 'packed', 'shipped', 'delivered'];
                    },

                    get statusLabel() {
                        const labels = { pending: 'Pending', packed: 'Packed', shipped: 'Shipped', delivered: 'Delivered', cancelled: 'Cancelled' };
                        return labels[this.status] || this.status;
                    },

                    get nextStatus() {
                        if (this.currentStep === null || this.currentStep >= this.flow.length - 1) return null;
                        return this.flow[this.currentStep + 1];
                    },

                    get nextStatusLabel() {
                        const labels = { pending: 'Pending', packed: 'Packed', shipped: 'Shipped', delivered: 'Delivered' };
                        return this.nextStatus ? labels[this.nextStatus] : '';
                    },

                    historyTimestamp(status) {
                        return this.historyTimestamps[status] || '';
                    },

                    async updateStatus(value) {
                        try {
                            const response = await fetch(`/admin/orders/${this.orderId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ field: 'status', value }),
                            });
                            const data = await response.json();
                            if (response.ok) {
                                window.location.reload();
                            } else {
                                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: data.message || 'Update failed.' } }));
                            }
                        } catch (e) {
                            console.error('Status update failed:', e);
                        }
                    },

                    async saveTracking() {
                        try {
                            const response = await fetch(`/admin/orders/${this.orderId}/tracking`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    tracking_carrier: this.trackingCarrier,
                                    tracking_number: this.trackingNumber,
                                }),
                            });
                            const data = await response.json();
                            if (response.ok) {
                                window.location.reload();
                            } else {
                                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: data.message || 'Save failed.' } }));
                            }
                        } catch (e) {
                            console.error('Tracking save failed:', e);
                        }
                    },
                };
            };
        </script>
    @endpush
@endsection
