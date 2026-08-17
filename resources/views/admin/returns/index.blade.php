@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Returns', 'url' => null],
    ];
    use App\Support\ReturnStatuses;
    $hasFilters = request()->filled('status') || request()->filled('search');
@endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            Returns
            <span class="ml-1.5 text-sm font-normal text-gray-500 dark:text-gray-400">({{ $returns->total() }})</span>
        </h2>
        @if ($hasFilters)
            <a href="{{ route('admin.returns.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                Clear Filters
            </a>
        @endif
    </div>

    {{-- Filter Tabs --}}
    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-wrap gap-2">
            @php
                $tabs = [
                    '' => 'All',
                    ReturnStatuses::PENDING_REVIEW => 'Pending Review',
                    ReturnStatuses::APPROVED => 'Approved',
                    ReturnStatuses::MORE_INFO_REQUIRED => 'More Info Required',
                    ReturnStatuses::RETURN_SHIPPED => 'Return Shipped',
                    ReturnStatuses::RECEIVED => 'Received',
                    ReturnStatuses::REFUNDED => 'Refunded',
                    ReturnStatuses::COMPLETED => 'Completed',
                    ReturnStatuses::REJECTED => 'Rejected',
                ];
            @endphp
            @foreach ($tabs as $key => $label)
                @php
                    $count = $statusCounts[$key] ?? 0;
                    $isActive = request('status', '') === $key;
                @endphp
                <a href="{{ route('admin.returns.index', array_merge(request()->except(['status', 'page']), $key ? ['status' => $key] : [])) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition
                        {{ $isActive
                            ? 'bg-brand-500 text-white shadow-sm'
                            : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 dark:bg-white/[0.03] dark:text-gray-400 dark:border-gray-700 dark:hover:bg-white/[0.06]' }}">
                    {{ $label }}
                    @if ($count > 0)
                        <span class="rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }} px-1.5 py-0.5 text-xs font-medium leading-none">{{ $count }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.returns.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[250px] flex-1">
                <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Return #, order #, customer name or email..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Search
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.returns.index', request()->except(['search', 'page'])) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="flex min-h-[calc(100vh-17rem)] flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Return #</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order #</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Items</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Requested</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($returns as $return)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ $return->return_number }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                @if ($return->order)
                                    <a href="{{ route('admin.orders.show', $return->order) }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">#{{ $return->order->order_number }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $return->user->name ?? '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $return->user->email ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $return->items->count() }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $return->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ ReturnStatuses::badgeClasses($return->status) }}">
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current opacity-70"></span>
                                    {{ ReturnStatuses::label($return->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.returns.show', $return) }}" title="View Return"
                                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No returns found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $hasFilters ? 'Try adjusting your filters.' : 'Returns will appear here when customers request them.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-auto px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $returns->links() }}
        </div>
    </div>
@endsection
