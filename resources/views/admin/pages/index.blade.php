@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Pages', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Pages</h2>
        <a href="{{ route('admin.pages.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Page
        </a>
    </div>

    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.pages.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-0 w-full sm:w-auto sm:min-w-[200px]">
                <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search pages..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[150px]">
                <label for="footer_section" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Section</label>
                <select name="footer_section" id="footer_section"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All Sections</option>
                    <option value="customer-care" {{ request('footer_section') === 'customer-care' ? 'selected' : '' }}>Customer Care</option>
                    <option value="company" {{ request('footer_section') === 'company' ? 'selected' : '' }}>Company</option>
                    <option value="legal" {{ request('footer_section') === 'legal' ? 'selected' : '' }}>Legal</option>
                </select>
            </div>
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[150px]">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="min-w-0 w-full sm:w-auto sm:min-w-[150px]">
                <label for="show_in_footer" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Footer</label>
                <select name="show_in_footer" id="show_in_footer"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="1" {{ request('show_in_footer') === '1' ? 'selected' : '' }}>In Footer</option>
                    <option value="0" {{ request('show_in_footer') === '0' ? 'selected' : '' }}>Not in Footer</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Filter
                </button>
                <a href="{{ route('admin.pages.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div id="search-results">
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"></th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Slug</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Section</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Footer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($pages as $page)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4"><input type="checkbox" name="page_ids[]" value="{{ $page->id }}" class="page-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500"></td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                <a href="{{ route('admin.pages.show', $page) }}" class="hover:text-brand-500">{{ $page->title }}</a>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $page->slug }}</td>
                            <td class="px-5 py-4">
                                @if($page->footer_section)
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                        {{ str_replace('-', ' ', ucfirst($page->footer_section)) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($page->show_in_footer)
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">Yes</span>
                                @else
                                    <span class="text-xs text-gray-400">No</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $page->footer_order }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="togglePage({{ $page->id }})"
                                        class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors
                                            {{ $page->status === 'published' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"
                                        title="{{ $page->status === 'published' ? 'Unpublish' : 'Publish' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                            {{ $page->status === 'published' ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <span class="whitespace-nowrap text-xs font-medium {{ $page->status === 'published' ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.pages.show', $page) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="text-brand-500 hover:text-brand-600">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Page', message: 'Are you sure you want to delete this page?', form: $el })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No pages found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Create a page to manage footer links and content.</p>
                                    <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                                        Add Page
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-gray-200 px-5 py-3 dark:border-gray-800">
            <button type="button" onclick="bulkDeletePages()" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                Delete Selected
            </button>
            {{ $pages->links() }}
        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script type="text/turbo-script">
function togglePage(id) {
    fetch(`/admin/pages/${id}/toggle-status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    }).then(r => r.json()).then(data => {
        Turbo.visit(location.href, { action: 'replace' });
    }).catch(() => {
        window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Failed to update page status.' } }));
    });
}

document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.page-cb').forEach(cb => cb.checked = this.checked);
});

function bulkDeletePages() {
    const ids = [...document.querySelectorAll('.page-cb:checked')].map(cb => cb.value);

    if (ids.length === 0) {
        window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'warning', message: 'Please select at least one page.' } }));
        return;
    }

    Alpine.store('confirmModal').open({
        title: 'Delete Pages',
        message: `Delete ${ids.length} selected page(s)? This action cannot be undone.`,
        onConfirm: async () => {
            try {
                const r = await fetch('{{ route('admin.pages.bulkDestroy') }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ page_ids: ids }),
                });
                if (!r.ok) {
                    const data = await r.json().catch(() => ({}));
                    throw new Error(data.message || 'Failed to delete pages.');
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
