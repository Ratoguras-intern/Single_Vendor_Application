@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Featured Categories', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Featured Homepage Categories</h2>
    </div>

    <!-- Add Category Form -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.featured-categories.store') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[250px]">
                <label for="category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Add Category</label>
                <select name="category_id" id="category_id" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Select category...</option>
                    @foreach($availableCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}{{ $cat->parent ? ' (' . $cat->parent->name . ')' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Add
            </button>
        </form>
    </div>

    <!-- Categories List -->
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Category</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Image</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Display Style</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Enabled</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($categories as $fc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-id="{{ $fc->id }}">
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="drag-handle cursor-grab text-gray-400 hover:text-gray-600">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </span>
                                <span class="ml-1 text-xs text-gray-400">{{ $fc->sort_order }}</span>
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                {{ $fc->category->name ?? 'Deleted' }}
                                @if($fc->category?->parent)
                                    <span class="text-xs text-gray-500 dark:text-gray-400"> / {{ $fc->category->parent->name }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($fc->category?->display_image)
                                    <img src="{{ $fc->category->display_image }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                        <span class="text-xs text-gray-500">N/A</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <select onchange="updateStyle({{ $fc->id }}, this.value)" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="grid" {{ $fc->display_style === 'grid' ? 'selected' : '' }}>Grid</option>
                                    <option value="list" {{ $fc->display_style === 'list' ? 'selected' : '' }}>List</option>
                                    <option value="carousel" {{ $fc->display_style === 'carousel' ? 'selected' : '' }}>Carousel</option>
                                </select>
                            </td>
                            <td class="px-5 py-4">
                                <button onclick="toggleCategory({{ $fc->id }})" class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors
                                    {{ $fc->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                        {{ $fc->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-5 py-4">
                                <form action="{{ route('admin.featured-categories.destroy', $fc) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Remove from Homepage', message: 'Remove this category from the homepage?', form: $el })">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No featured categories. Add one above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        function toggleCategory(id) {
            fetch(`/admin/featured-categories/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => Turbo.visit(location.href, { action: 'replace' }));
        }

        function updateStyle(id, style) {
            fetch(`/admin/featured-categories/${id}/style`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ display_style: style })
            }).then(r => r.json()).then(d => showToast(d.message));
        }

        function showToast(msg) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 z-50 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white shadow-lg';
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
    </script>
    @endpush
@endsection
