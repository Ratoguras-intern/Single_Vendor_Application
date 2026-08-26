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
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.homepage-sections.show', $section) }}"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.homepage-sections.destroy', $section) }}" onsubmit="return confirm('Delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-red-500 shadow-sm transition hover:bg-red-50 hover:text-red-700 dark:border-gray-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        function showToast(message, type = 'info') {
            const colors = {
                success: 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                error: 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/30 text-red-800 dark:text-red-300',
            };
            const icons = {
                success: '<div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-green-500 text-white"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg></div>',
                error: '<div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-500 text-white"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg></div>',
            };
            const wrap = document.createElement('div');
            wrap.className = 'fixed top-5 right-5 z-[100001] pointer-events-none';
            wrap.innerHTML = `<div class="pointer-events-auto flex items-start gap-3 rounded-lg border p-4 shadow-lg backdrop-blur-sm ${colors[type] || colors.success} opacity-0 translate-x-8 transition-all duration-300">${icons[type] || icons.success}<p class="flex-1 text-sm font-medium">${message}</p></div>`;
            document.body.appendChild(wrap);
            const el = wrap.firstElementChild;
            requestAnimationFrame(() => { el.classList.remove('opacity-0', 'translate-x-8'); el.classList.add('opacity-100', 'translate-x-0'); });
            setTimeout(() => { el.classList.add('opacity-0', 'translate-x-8'); setTimeout(() => wrap.remove(), 300); }, 3000);
        }

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
                            if (d.message) showToast(d.message, 'success');
                        });
                    }
                });
            }
        }
    </script>
    @endpush
@endsection
