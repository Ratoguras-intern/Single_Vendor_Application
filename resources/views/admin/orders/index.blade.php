@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Orders', 'url' => null],
    ];
@endphp

@section('content')
    @php
        use App\Support\OrderStatuses;
        $allStatuses = OrderStatuses::all();
        $allPaymentStatuses = ['pending', 'paid', 'failed', 'cod'];
        $hasFilters = request()->filled('search') || request()->filled('status') || request()->filled('payment_status') || request()->filled('month') || request()->filled('date_from') || request()->filled('date_to');
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Orders</h2>
        @if ($hasFilters)
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                Clear Filters
            </a>
        @endif
    </div>

    {{-- Filter Bar --}}
    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-0 w-full sm:w-auto sm:min-w-[200px]">
                <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Order # or customer name..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[150px]">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    @foreach ($allStatuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[150px]">
                <label for="payment_status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment</label>
                <select name="payment_status" id="payment_status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    @foreach ($allPaymentStatuses as $ps)
                        <option value="{{ $ps }}" {{ request('payment_status') === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[160px]">
                <label for="month" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                <input type="month" name="month" id="month" value="{{ request('month') }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="min-w-0 w-full sm:min-w-[270px] sm:w-[270px]">
                <x-date-picker mode="range" label="Order Date" start-name="date_from" end-name="date_to"
                    value="{{ trim(request('date_from') . ',' . request('date_to'), ',') }}" />
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Filter
                </button>
                @if ($hasFilters)
                    <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div id="search-results" class="flex min-h-[calc(100vh-17rem)] flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]" x-data="orderActions({{ $order->id }}, '{{ $order->status }}', '{{ $order->payment_status }}')">
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">#{{ $order->id }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ format_currency($order->total_amount) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order) }}" title="View Order"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    <div class="relative">
                                        <button @click="openStatus = !openStatus" @keydown.escape.window="openStatus = false"
                                            :class="statusColor"
                                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium shadow-sm transition hover:opacity-85" title="Update Status">
                                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current opacity-70"></span>
                                            <span x-text="statusLabel">{{ ucfirst($order->status) }}</span>
                                            <svg class="h-3 w-3 shrink-0 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="openStatus" @click.away="openStatus = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;"
                                            class="absolute right-0 z-[9999] mt-1.5 w-44 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-900">
                                            @foreach ($allStatuses as $s)
                                                <button @click="updateField('status', '{{ $s }}'); openStatus = false"
                                                    class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-xs transition hover:bg-gray-50 dark:hover:bg-white/[0.06]">
                                                    <span class="h-2 w-2 shrink-0 rounded-full" :class="statusDot('{{ $s }}')"></span>
                                                    <span class="flex-1" :class="currentStatus === '{{ $s }}' ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300'">{{ ucfirst($s) }}</span>
                                                    <svg x-show="currentStatus === '{{ $s }}'" class="h-3.5 w-3.5 shrink-0 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <button @click="openPayment = !openPayment" @keydown.escape.window="openPayment = false"
                                            :class="paymentColor"
                                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium shadow-sm transition hover:opacity-85" title="Update Payment">
                                            <svg class="h-3 w-3 shrink-0 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 7C2.5 5.89543 3.39543 5 4.5 5H19.5C20.6046 5 21.5 5.89543 21.5 7V17C21.5 18.1046 20.6046 19 19.5 19H4.5C3.39543 19 2.5 18.1046 2.5 17V7Z"/><path d="M2.5 9H21.5"/><path d="M7 15H11"/></svg>
                                            <span x-text="paymentLabel">{{ ucfirst($order->payment_status) }}</span>
                                            <svg class="h-3 w-3 shrink-0 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="openPayment" @click.away="openPayment = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;"
                                            class="absolute right-0 z-[9999] mt-1.5 w-44 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-900">
                                            @foreach ($allPaymentStatuses as $ps)
                                                <button @click="updateField('payment_status', '{{ $ps }}'); openPayment = false"
                                                    class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-xs transition hover:bg-gray-50 dark:hover:bg-white/[0.06]">
                                                    <span class="h-2 w-2 shrink-0 rounded-full" :class="paymentDot('{{ $ps }}')"></span>
                                                    <span class="flex-1" :class="currentPayment === '{{ $ps }}' ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300'">{{ ucfirst($ps) }}</span>
                                                    <svg x-show="currentPayment === '{{ $ps }}'" class="h-3.5 w-3.5 shrink-0 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <button type="button" @click="toggleShipped"
                                        class="inline-flex items-center gap-1.5 rounded-lg px-1.5 py-1 text-xs font-medium transition hover:text-gray-600 dark:hover:text-gray-300"
                                        :class="isShipped ? 'text-brand-600 dark:text-brand-400' : 'text-gray-400 dark:text-gray-500'"
                                        :title="isShipped ? 'Mark as not shipped' : 'Mark as shipped'">
                                        <span :class="isShipped ? 'bg-brand-500' : 'bg-gray-300 dark:bg-gray-600'" class="relative inline-flex h-4 w-7 shrink-0 items-center rounded-full transition-colors">
                                            <span :class="isShipped ? 'translate-x-3.5' : 'translate-x-0.5'" class="inline-block h-3 w-3 transform rounded-full bg-white shadow transition-transform"></span>
                                        </span>
                                        <span x-text="isShipped ? 'Shipped' : 'Not shipped'">Not shipped</span>
                                    </button>

                                    @if($order->tracking_number)
                                        <a href="{{ route('admin.orders.show', $order) }}" title="Tracking: {{ $order->tracking_carrier }} {{ $order->tracking_number }}"
                                            class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18h4a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 14.382 8H11"/><path d="M8 18a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/><path d="M20 18a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/></svg>
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-red-500 shadow-sm transition hover:bg-red-50 hover:text-red-700 dark:border-gray-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No orders found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $hasFilters ? 'Try adjusting your filters.' : 'Orders will appear here once customers start purchasing.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-auto px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $orders->links() }}
        </div>
    </div>

    @push('scripts')
        <script data-turbo-eval>
            window.orderActions = function orderActions(orderId, status, paymentStatus) {
                return {
                    currentStatus: status,
                    currentPayment: paymentStatus,
                    openStatus: false,
                    openPayment: false,

                    get statusLabel() {
                        return this.currentStatus.charAt(0).toUpperCase() + this.currentStatus.slice(1);
                    },

                    get paymentLabel() {
                        return this.currentPayment.charAt(0).toUpperCase() + this.currentPayment.slice(1);
                    },

                    get statusColor() {
                        const colors = {
                            pending: 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                            packed: 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                            shipped: 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                            delivered: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                            cancelled: 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                        };
                        return colors[this.currentStatus] || colors.pending;
                    },

                    get paymentColor() {
                        const colors = {
                            pending: 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                            paid: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                            failed: 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                            cod: 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400',
                        };
                        return colors[this.currentPayment] || colors.pending;
                    },

                    get statusDot() {
                        const colors = {
                            pending: 'bg-amber-500',
                            packed: 'bg-blue-500',
                            shipped: 'bg-purple-500',
                            delivered: 'bg-emerald-500',
                            cancelled: 'bg-red-500',
                        };
                        return (s) => colors[s] || colors.pending;
                    },

                    get paymentDot() {
                        const colors = {
                            pending: 'bg-amber-500',
                            paid: 'bg-emerald-500',
                            failed: 'bg-red-500',
                            cod: 'bg-sky-500',
                        };
                        return (s) => colors[s] || colors.pending;
                    },

                    get isShipped() {
                        return ['shipped', 'delivered'].includes(this.currentStatus);
                    },

                    toggleShipped() {
                        this.updateField('status', this.isShipped ? 'pending' : 'shipped');
                    },

                    async updateField(field, value) {
                        try {
                            const response = await fetch(`/admin/orders/${orderId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ field, value }),
                            });

                            const data = await response.json();

                            if (response.ok) {
                                if (field === 'status') {
                                    this.currentStatus = value;
                                } else {
                                    this.currentPayment = value;
                                }
                            }
                        } catch (e) {
                            console.error('Update failed:', e);
                        }
                    },
                };
            };
        </script>
    @endpush
@endsection
