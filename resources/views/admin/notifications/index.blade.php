@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Notifications', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Notifications</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                @if($unreadCount > 0)
                    {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}
                @else
                    All caught up
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Mark all read
                    </button>
                </form>
            @endif
            @if($notifications->total() > 0)
                <form method="POST" action="{{ route('admin.notifications.destroyAll') }}" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete All Notifications', message: 'Permanently delete all notifications? This cannot be undone.', form: $el })">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        Delete all
                    </button>
                </form>
            @endif
        </div>
    </div>

    <x-admin.filter-bar action="{{ route('admin.notifications.index') }}" :hasFilters="request()->hasAny(['search','type','read_status'])">
        <x-admin.filter-search name="search" label="Search" placeholder="Search notifications..." />
        <x-admin.filter-select name="type" label="Type" :options="['order_new' => 'New Order', 'order_status' => 'Order Status', 'order_cancelled' => 'Order Cancelled', 'return_request' => 'Return Request', 'return_status' => 'Return Status', 'section_order' => 'Section Reorder']" />
        <x-admin.filter-select name="read_status" label="Status" :options="['unread' => 'Unread', 'read' => 'Read']" />
    </x-admin.filter-bar>

    <div id="search-results" class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse($notifications as $notification)
                @php
                    $type = class_basename($notification->type);
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);

                    $typeConfig = match($type) {
                        'NewOrderNotification' => ['icon' => 'order', 'color' => 'green', 'label' => 'New Order'],
                        'OrderStatusUpdatedNotification' => ['icon' => 'status', 'color' => 'blue', 'label' => 'Order Status'],
                        'OrderCancelledNotification' => ['icon' => 'cancel', 'color' => 'red', 'label' => 'Order Cancelled'],
                        'ReturnRequestNotification' => ['icon' => 'return', 'color' => 'amber', 'label' => 'Return Request'],
                        'ReturnStatusUpdatedNotification' => ['icon' => 'return', 'color' => 'purple', 'label' => 'Return Status'],
                        'HomepageSectionOrderChangedNotification' => ['icon' => 'section', 'color' => 'gray', 'label' => 'Section Reorder'],
                        default => ['icon' => 'info', 'color' => 'gray', 'label' => 'Notification'],
                    };
                @endphp
                <div class="flex items-start gap-4 px-5 py-4 {{ $isUnread ? 'bg-blue-50/50 dark:bg-blue-500/5' : '' }} hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    {{-- Icon --}}
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full
                        {{ $typeConfig['color'] === 'green' ? 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400' : '' }}
                        {{ $typeConfig['color'] === 'blue' ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : '' }}
                        {{ $typeConfig['color'] === 'red' ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' : '' }}
                        {{ $typeConfig['color'] === 'amber' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' : '' }}
                        {{ $typeConfig['color'] === 'purple' ? 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400' : '' }}
                        {{ $typeConfig['color'] === 'gray' ? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' : '' }}">
                        @if($typeConfig['icon'] === 'order')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        @elseif($typeConfig['icon'] === 'status')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        @elseif($typeConfig['icon'] === 'cancel')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        @elseif($typeConfig['icon'] === 'return')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                        @elseif($typeConfig['icon'] === 'section')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                        {{ $typeConfig['color'] === 'green' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : '' }}
                                        {{ $typeConfig['color'] === 'blue' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' : '' }}
                                        {{ $typeConfig['color'] === 'red' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : '' }}
                                        {{ $typeConfig['color'] === 'amber' ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : '' }}
                                        {{ $typeConfig['color'] === 'purple' ? 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400' : '' }}
                                        {{ $typeConfig['color'] === 'gray' ? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' : '' }}">
                                        {{ $typeConfig['label'] }}
                                    </span>
                                    @if($isUnread)
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                    @endif
                                </div>
                                <p class="text-sm {{ $isUnread ? 'font-medium text-gray-800 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $data['message'] ?? 'New notification' }}
                                </p>

                                {{-- Extra details --}}
                                @if(isset($data['order_number']))
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                                        <span>Order #{{ $data['order_number'] }}</span>
                                        @if(isset($data['total']))
                                            <span>&middot;</span>
                                            <span>{{ format_currency($data['total']) }}</span>
                                        @endif
                                        @if(isset($data['customer']))
                                            <span>&middot;</span>
                                            <span>{{ $data['customer'] }}</span>
                                        @endif
                                    </div>
                                @endif
                                @if(isset($data['return_number']))
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                                        <span>Return #{{ $data['return_number'] }}</span>
                                        @if(isset($data['order_number']))
                                            <span>&middot;</span>
                                            <span>Order #{{ $data['order_number'] }}</span>
                                        @endif
                                        @if(isset($data['customer_name']))
                                            <span>&middot;</span>
                                            <span>{{ $data['customer_name'] }}</span>
                                        @endif
                                    </div>
                                @endif
                                @if(isset($data['old_status']) && isset($data['new_status']))
                                    <div class="mt-1 flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                        <span class="rounded bg-gray-100 px-1.5 py-0.5 dark:bg-white/5">{{ ucfirst($data['old_status']) }}</span>
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        <span class="rounded bg-brand-50 px-1.5 py-0.5 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">{{ ucfirst($data['new_status']) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex shrink-0 items-center gap-1">
                                <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$isUnread)
                                    <form method="POST" action="{{ route('admin.notifications.markRead', $notification->id) }}">
                                        @csrf
                                        <button type="submit" title="Mark as unread" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/[0.06] dark:hover:text-gray-300">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Notification', message: 'Delete this notification?', form: $el })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center px-4 py-16 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No notifications</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">You're all caught up! New notifications will appear here.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="border-t border-gray-200 px-5 py-3 dark:border-gray-800">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
