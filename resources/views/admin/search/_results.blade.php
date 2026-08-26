@php
    $labels = [
        'products' => ['label' => 'Products', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>', 'route' => 'admin.products.edit'],
        'orders' => ['label' => 'Orders', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>', 'route' => 'admin.orders.show'],
        'customers' => ['label' => 'Customers', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', 'route' => 'admin.customers.show'],
        'categories' => ['label' => 'Categories', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>', 'route' => 'admin.categories.edit'],
        'brands' => ['label' => 'Brands', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>', 'route' => 'admin.brands.edit'],
        'pages' => ['label' => 'Pages', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>', 'route' => 'admin.pages.edit'],
    ];
@endphp

<div class="space-y-4 p-4">
    @foreach($results as $type => $items)
        @php $meta = $labels[$type] ?? ['label' => $type, 'icon' => '', 'route' => '#']; @endphp
        <div>
            <h4 class="mb-2 flex items-center gap-2 px-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                <span class="text-gray-400 dark:text-gray-500">{!! $meta['icon'] !!}</span>
                {{ $meta['label'] }}
                <span class="ml-auto text-gray-400 dark:text-gray-500">{{ count($items) }} found</span>
            </h4>
            <div class="space-y-1">
                @foreach($items as $item)
                    @if($type === 'products')
                        <a href="{{ route($meta['route'], $item->id) }}" data-turbo="false"
                           class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">{!! $meta['icon'] !!}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $item->name }}</span>
                            <span class="shrink-0 text-xs text-gray-400">{{ $item->sku }}</span>
                            <span class="shrink-0 text-xs font-medium text-green-600 dark:text-green-400">{{ currency_format($item->price) }}</span>
                        </a>
                    @elseif($type === 'orders')
                        <a href="{{ route($meta['route'], $item->id) }}" data-turbo="false"
                           class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">{!! $meta['icon'] !!}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $item->order_number }}</span>
                            <span class="shrink-0 text-xs text-gray-400">{{ $item->user->name ?? 'N/A' }}</span>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $item->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : '' }}
                                {{ $item->status === 'pending' ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' : '' }}
                                {{ $item->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : '' }}
                                {{ !in_array($item->status, ['completed','pending','cancelled']) ? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' : '' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </a>
                    @elseif($type === 'customers')
                        <a href="{{ route($meta['route'], $item->id) }}" data-turbo="false"
                           class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">{!! $meta['icon'] !!}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $item->name }}</span>
                            <span class="shrink-0 text-xs text-gray-400">{{ $item->email }}</span>
                            @if($item->is_frozen)
                                <span class="shrink-0 rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">Frozen</span>
                            @endif
                        </a>
                    @elseif(in_array($type, ['categories', 'brands', 'pages']))
                        <a href="{{ route($meta['route'], $item->id) }}" data-turbo="false"
                           class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">{!! $meta['icon'] !!}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $item->name ?? $item->title }}</span>
                            @if(isset($item->slug))
                                <span class="shrink-0 text-xs text-gray-400">{{ $item->slug }}</span>
                            @endif
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium
                                {{ ($item->status ?? '') === 'active' || ($item->status ?? '') === 1 ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' }}">
                                {{ ($item->status ?? '') ? 'Active' : 'Inactive' }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
