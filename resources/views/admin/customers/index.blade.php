@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Customers', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Customers</h2>
    </div>

    <x-admin.filter-bar action="{{ route('admin.customers.index') }}" :hasFilters="request()->hasAny(['search','status','is_frozen','per_page'])">
        <x-admin.filter-search name="search" label="Search" placeholder="Name, email, phone..." />
        <x-admin.filter-select name="status" label="Status" :options="['active' => 'Active', 'inactive' => 'Inactive']" />
        <x-admin.filter-select name="is_frozen" label="Frozen" :options="['1' => 'Frozen', '0' => 'Not Frozen']" allLabel="All" />
        <x-admin.filter-per-page value="{{ request('per_page', '25') }}" />
    </x-admin.filter-bar>

    <div id="search-results" class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"></th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Orders</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total Spent</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Joined</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] {{ $customer->is_frozen ? 'bg-red-50/50 dark:bg-red-500/5' : '' }}">
                            <td class="px-5 py-4">
                                @if ($customer->id !== Auth::id())
                                    <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <x-user-avatar :user="$customer" size="h-9 w-9" text-size="text-sm" />
                                    <div>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $customer->name }}</span>
                                        @if ($customer->is_frozen)
                                            <span class="ml-1.5 inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                                                Frozen
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $customer->email }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $customer->orders_count }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ format_currency($customer->orders_sum_total_amount ?? 0) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $customer->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.customers.show', $customer) }}"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200"
                                        title="View Details">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('admin.customers.orders', $customer) }}"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200"
                                        title="View Orders">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </a>
                                    @if ($customer->id !== Auth::id())
                                        @if ($customer->is_frozen)
                                            <form action="{{ route('admin.customers.unfreeze', $customer) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-green-500 shadow-sm transition hover:bg-green-50 hover:text-green-700 dark:border-gray-700 dark:bg-gray-900 dark:text-green-400 dark:hover:bg-green-500/10"
                                                    title="Unfreeze Customer">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.customers.freeze', $customer) }}" method="POST" x-data="{ show: false, reason: '' }">
                                                @csrf
                                                <input type="hidden" name="frozen_reason" :value="reason || null">
                                                <button type="button" @click="show = !show"
                                                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-blue-500 shadow-sm transition hover:bg-blue-50 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-900 dark:text-blue-400 dark:hover:bg-blue-500/10"
                                                    title="Freeze Customer">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                                                </button>
                                                <template x-if="show">
                                                    <div class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
                                                        <div class="fixed inset-0 bg-black/50" @click="show = false"></div>
                                                        <div x-transition class="relative z-10 w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
                                                            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Freeze Account</h3>
                                                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Frozen users cannot log in until unfrozen.</p>
                                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Reason (optional)</label>
                                                            <input type="text" x-model="reason" maxlength="500" placeholder="Why freeze this account?" class="mb-4 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="show = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">Cancel</button>
                                                                <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Freeze</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Customer', message: 'Are you sure you want to delete this customer? This will permanently remove all their data including orders, addresses, and favorites.', form: $el })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-red-500 shadow-sm transition hover:bg-red-50 hover:text-red-700 dark:border-gray-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-500/10"
                                                title="Delete Customer">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No customers found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Customers will appear here once they register.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-gray-200 px-5 py-3 dark:border-gray-800">
            <button type="button" onclick="bulkDeleteCustomers()" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                Delete Selected
            </button>
            {{ $customers->links() }}
        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script type="text/turbo-script">
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.customer-cb').forEach(cb => cb.checked = this.checked);
});

function bulkDeleteCustomers() {
    const ids = [...document.querySelectorAll('.customer-cb:checked')].map(cb => cb.value);

    if (ids.length === 0) {
        window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'warning', message: 'Please select at least one customer.' } }));
        return;
    }

    Alpine.store('confirmModal').open({
        title: 'Delete Customers',
        message: `Delete ${ids.length} selected customer(s)? This will permanently remove all their data including orders, addresses, and favorites.`,
        onConfirm: async () => {
            try {
                const r = await fetch('{{ route('admin.customers.bulkDestroy') }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ customer_ids: ids }),
                });
                if (!r.ok) {
                    const data = await r.json().catch(() => ({}));
                    throw new Error(data.message || 'Failed to delete customers.');
                }
                Turbo.visit(location.href, { action: 'replace' });
            } catch (err) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: err.message } }));
            }
        }
    });
}
</script>
@endpush
