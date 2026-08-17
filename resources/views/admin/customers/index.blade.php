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

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                @if ($customer->id !== Auth::id())
                                    <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold text-white bg-gray-400">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $customer->email }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $customer->orders_count }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ format_currency($customer->orders_sum_total_amount ?? 0) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $customer->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="View Details">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                    </a>
                                    <a href="{{ route('admin.customers.orders', $customer) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="View Orders">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12ZM3 17V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    @if ($customer->id !== Auth::id())
                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Customer', message: 'Are you sure you want to delete this customer? This will permanently remove all their data including orders, addresses, and favorites.', form: $el })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400" title="Delete Customer">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M8 6V4C8 3.44772 8.44772 3 9 3H15C15.5523 3 16 3.44772 16 4V6M19 6V20C19 21.1046 18.1046 22 17 22H7C5.89543 22 5 21.1046 5 20V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
