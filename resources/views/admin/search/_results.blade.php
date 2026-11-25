@php
    $labels = [
        'products' => ['label' => 'Products', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>', 'route' => 'admin.products.edit'],
        'orders' => ['label' => 'Orders', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>', 'route' => 'admin.orders.show'],
        'customers' => ['label' => 'Customers', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', 'route' => 'admin.customers.show'],
        'categories' => ['label' => 'Categories', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>', 'route' => 'admin.categories.edit'],
        'brands' => ['label' => 'Brands', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>', 'route' => 'admin.brands.edit'],
        'pages' => ['label' => 'Pages', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>', 'route' => 'admin.pages.edit'],
        'reviews' => ['label' => 'Reviews', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>', 'route' => 'admin.reviews.show'],
        'returns' => ['label' => 'Returns', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><polyline points="3 3 3 9 9 9"/></svg>', 'route' => 'admin.returns.show'],
        'faqs' => ['label' => 'FAQs', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/><circle cx="12" cy="12" r="10"/></svg>', 'route' => 'admin.faqs.edit'],
        'faq-categories' => ['label' => 'FAQ Categories', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>', 'route' => 'admin.faq-categories.edit'],
        'posts' => ['label' => 'Blog Posts', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="14" y2="18"/></svg>', 'route' => 'admin.posts.edit'],
        'post-categories' => ['label' => 'Blog Categories', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>', 'route' => 'admin.post-categories.edit'],
        'jobs' => ['label' => 'Careers', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>', 'route' => 'admin.jobs.edit'],
        'press-releases' => ['label' => 'Press Releases', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z"/><line x1="12" y1="10" x2="18" y2="10"/></svg>', 'route' => 'admin.press-releases.edit'],
        'shipping-methods' => ['label' => 'Shipping Methods', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 8h14v12H5z"/><path d="M14 3H3v12"/><circle cx="9" cy="19" r="1.5"/><circle cx="16" cy="19" r="1.5"/></svg>', 'route' => 'admin.shipping-methods.edit'],
        'contact-messages' => ['label' => 'Inbox', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>', 'route' => 'admin.contact-messages.show'],
        'banners' => ['label' => 'Banners', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 15l2-3 2 2 3-4"/></svg>', 'route' => 'admin.banners.edit'],
        'sale-banners' => ['label' => 'Sale Banners', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>', 'route' => 'admin.sale-banners.edit'],
        'homepage-sections' => ['label' => 'Homepage Sections', 'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>', 'route' => 'admin.homepage-sections.show'],
    ];

    $rowMeta = function ($item) {
        $name = $item->name ?? $item->title ?? $item->question ?? $item->return_number ?? $item->subject ?? '#' . $item->id;
        $sub = $item->slug
            ?? $item->email
            ?? ($item->tracking_number ?? null)
            ?? (isset($item->department) && isset($item->location) ? trim($item->department . ' · ' . $item->location, ' ·') : null)
            ?? ($item->user->name ?? null)
            ?? ($item->product->name ?? null)
            ?? '';

        return [$name, $sub];
    };

    $statusColor = function ($status) {
        $status = strtolower((string) $status);
        if (in_array($status, ['active', 'approved', 'completed', 'published', 'open', 1, '1', 'true'], true)) {
            return 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400';
        }
        if (in_array($status, ['pending', 'scheduled'], true)) {
            return 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400';
        }
        if (in_array($status, ['cancelled', 'rejected', 'inactive', 'expired', 'failed', 'refunded'], true)) {
            return 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400';
        }
        return 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400';
    };
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
                            <span class="shrink-0 text-xs font-medium text-green-600 dark:text-green-400">{{ format_currency((float) $item->price) }}</span>
                        </a>
                    @elseif($type === 'orders')
                        <a href="{{ route($meta['route'], $item->id) }}" data-turbo="false"
                           class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">{!! $meta['icon'] !!}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $item->order_number }}</span>
                            <span class="shrink-0 text-xs text-gray-400">{{ $item->user->name ?? 'N/A' }}</span>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColor($item->status) }}">
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
                    @else
                        @php
                            [$name, $sub] = $rowMeta($item);
                            $showBadge = isset($item->status)
                                || isset($item->is_published)
                                || isset($item->is_enabled)
                                || isset($item->is_active);
                            if (isset($item->status)) {
                                $badgeText = ucfirst($item->status);
                            } elseif (isset($item->is_enabled) || isset($item->is_active) || isset($item->is_published)) {
                                $badgeText = ($item->is_enabled ?? $item->is_active ?? $item->is_published) ? 'Active' : 'Inactive';
                            }
                        @endphp
                        <a href="{{ route($meta['route'], $item->id) }}" data-turbo="false"
                           class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">{!! $meta['icon'] !!}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $name }}</span>
                            @if($sub)
                                <span class="shrink-0 text-xs text-gray-400">{{ $sub }}</span>
                            @endif
                            @if($showBadge)
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColor($badgeText) }}">{{ $badgeText }}</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>