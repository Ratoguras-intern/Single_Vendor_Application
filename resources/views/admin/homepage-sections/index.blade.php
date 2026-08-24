@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Homepage Sections', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Homepage Sections</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Drag to reorder. Toggle visibility on/off.</p>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Section</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Products</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Layout</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Enabled</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800" id="sortable-container">
                    @foreach($sections as $section)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] cursor-move sortable-row" data-id="{{ $section->id }}">
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="drag-handle cursor-grab text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </span>
                                <span class="ml-1 text-xs text-gray-400 dark:text-gray-500 sort-order-badge">{{ $section->sort_order }}</span>
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ (str_starts_with($section->slug, 'featured') || str_starts_with($section->slug, 'new'))
                                        ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                        : (str_starts_with($section->slug, 'flash')
                                            ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400'
                                            : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}">
                                    {{ ucwords(str_replace('-', ' ', $section->slug)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $section->title ?? '—' }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $section->max_products }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($section->layout) }}</td>
                            <td class="px-5 py-4">
                                <button onclick="toggleSection({{ $section->id }})" class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors
                                    {{ $section->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                        {{ $section->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.homepage-sections.show', $section) }}"
                                    class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        function toggleSection(id) {
            fetch(`/admin/homepage-sections/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => Turbo.visit(location.href, { action: 'replace' }));
        }

        {
            const container = document.getElementById('sortable-container');
            if (container && container.children.length > 1) {
                new Sortable(container, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        document.querySelectorAll('.sortable-row').forEach((row, index) => {
                            order.push(parseInt(row.dataset.id));
                            const badge = row.querySelector('.sort-order-badge');
                            if (badge) badge.textContent = index;
                        });
                        fetch('{{ route("admin.homepage-sections.updateOrder") }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order })
                        }).then(r => r.json()).then(d => {
                            if (d.message) console.log(d.message);
                        });
                    }
                });
            }
        }
    </script>
    @endpush
@endsection
