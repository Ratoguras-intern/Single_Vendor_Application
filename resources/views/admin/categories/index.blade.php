@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Categories', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Categories</h2>
            @if(isset($trashed) && $trashed > 0)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $trashed }} deleted {{ Str::plural('category', $trashed) }} ({{ $trashed > 10 ? '10' : $trashed }} recoverable from trash)</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index', ['view' => 'flat'] + request()->query()) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                Flat View
            </a>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Add Category
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search categories..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="min-w-[150px]">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="featured" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Featured</label>
                <select name="featured" id="featured"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="parent" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select name="parent" id="parent"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="top" {{ request('parent') === 'top' ? 'selected' : '' }}>Top Level</option>
                    <option value="child" {{ request('parent') === 'child' ? 'selected' : '' }}>Subcategories</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Filter
                </button>
                <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div id="search-results">
    @if (request('view') === 'flat')
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"></th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Slug</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Parent</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-5 py-4"><input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="category-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500"></td>
                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $category->id }}</td>
                                <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="hover:text-brand-500">{{ $category->name }}</a>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $category->slug }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $category->parent->name ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $category->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                        {{ $category->status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-500 hover:text-brand-600">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M9.10927 2.55078H5.09927C3.89927 2.55078 2.91927 3.53078 2.91927 4.73078V8.74078C2.91927 9.94078 3.89927 10.9208 5.09927 10.9208H9.10927C10.3093 10.9208 11.2893 9.94078 11.2893 8.74078V4.73078C11.2893 3.53078 10.3093 2.55078 9.10927 2.55078Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.10927 13.0808H5.09927C3.89927 13.0808 2.91927 14.0608 2.91927 15.2608V19.2708C2.91927 20.4708 3.89927 21.4508 5.09927 21.4508H9.10927C10.3093 21.4508 11.2893 20.4708 11.2893 19.2708V15.2608C11.2893 14.0608 10.3093 13.0808 9.10927 13.0808Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No categories found</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Create a category to organize your products.</p>
                                        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                                            Add Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between border-t border-gray-200 px-5 py-3 dark:border-gray-800">
                <button type="button" onclick="bulkDeleteCategories()" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                    Delete Selected
                </button>
                {{ $categories->links() }}
            </div>
        </div>
    @else
    {{-- Tree View --}}
    @php
        $expandableTopIds = $topLevel->filter(fn ($c) => $c->children->isNotEmpty())->pluck('id')->values();
    @endphp
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Category Tree</h3>
            <div class="flex items-center gap-1.5">
                <button type="button" @click="expanded = Object.fromEntries(@json($expandableTopIds).map(id => [id, true]))"
                    class="rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Expand All
                </button>
                <button type="button" @click="expanded = {}"
                    class="rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Collapse All
                </button>
            </div>
        </div>
        <div class="p-4 space-y-2" x-data="{ expanded: {} }" data-sort-group="top">
            @forelse($topLevel as $category)
                @php
                    $filteredChildren = $category->children;
                    if (request('status')) {
                        $filteredChildren = $filteredChildren->filter(fn($c) => $c->status === (request('status') === 'active' ? 'active' : 'inactive'));
                    }
                    if (request('featured')) {
                        $filteredChildren = $filteredChildren->filter(fn($c) => $c->featured === request()->boolean('featured'));
                    }
                    $showChildren = $filteredChildren->isNotEmpty();
                @endphp
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden" data-category-id="{{ $category->id }}">
                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-white/[0.02] {{ $showChildren ? 'cursor-pointer' : '' }}"
                        @if($showChildren) @click="expanded[{{ $category->id }}] = !expanded[{{ $category->id }}]" @endif>
                        @if($showChildren)
                            <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200" :class="expanded[{{ $category->id }}] ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        @else
                            <span class="w-4 shrink-0"></span>
                        @endif

                        @if($category->thumbnail_url)
                            <img src="{{ $category->thumbnail_url }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-lg object-cover">
                        @elseif($category->lucide_icon)
                            <div class="h-10 w-10 rounded-lg bg-brand-100 dark:bg-brand-900/20 flex items-center justify-center text-brand-500">
                                <x-lucide :name="$category->lucide_icon" class="h-5 w-5" />
                            </div>
                        @elseif($category->display_image)
                            <img src="{{ $category->display_image }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-lg object-cover">
                        @else
                            <div class="h-10 w-10 rounded-lg bg-brand-100 dark:bg-brand-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/></svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.show', $category) }}" class="text-sm font-semibold text-gray-800 dark:text-white hover:text-brand-500 truncate">{{ $category->name }}</a>
                                @if($category->featured)
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Featured</span>
                                @endif
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium {{ $category->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                    {{ $category->status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $category->slug }} @if($category->parent) &middot; Parent: {{ $category->parent->name }} @endif</p>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <button x-on:click.stop="toggleStatus({{ $category->id }}, this)"
                                class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors {{ $category->status === 'active' ? 'text-green-500' : 'text-gray-400' }}"
                                title="{{ $category->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                            </button>
                            <button x-on:click.stop="toggleFeatured({{ $category->id }}, this)"
                                class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors {{ $category->featured ? 'text-amber-500' : 'text-gray-400' }}"
                                title="{{ $category->featured ? 'Remove featured' : 'Mark featured' }}">
                                <svg class="w-4 h-4" fill="{{ $category->featured ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                            </button>
                            <button x-on:click.stop="reorderCategory({{ $category->id }}, -1)"
                                class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-gray-400" title="Move up">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                            </button>
                            <button x-on:click.stop="reorderCategory({{ $category->id }}, 1)"
                                class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-gray-400" title="Move down">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <a href="{{ route('admin.categories.create', ['parent' => $category->id]) }}" class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-primary-600" title="Add subcategory">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-brand-500" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/10 transition-colors text-red-500" title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Children --}}
                    @if($showChildren)
                        <div x-show="expanded[{{ $category->id }}]" x-collapse class="border-t border-gray-100 dark:border-gray-700/50">
                            <div class="pl-10 py-1 space-y-1" data-sort-group="children-{{ $category->id }}">
                                @foreach($filteredChildren as $child)
                                    <div class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-category-id="{{ $child->id }}">
                                        @if($child->thumbnail_url)
                                            <img src="{{ $child->thumbnail_url }}" alt="{{ $child->name }}" class="h-8 w-8 rounded-lg object-cover">
                                        @elseif($child->lucide_icon)
                                            <div class="h-8 w-8 rounded-lg bg-brand-100 dark:bg-brand-900/20 flex items-center justify-center text-brand-500">
                                                <x-lucide :name="$child->lucide_icon" class="h-4 w-4" />
                                            </div>
                                        @else
                                            <div class="h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M2.25 18V9.75A2.25 2.25 0 0 1 4.5 7.5h15A2.25 2.25 0 0 1 21.75 9.75V18A2.25 2.25 0 0 1 19.5 20.25H4.5A2.25 2.25 0 0 1 2.25 18Z"/></svg>
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.categories.show', $child) }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-brand-500">{{ $child->name }}</a>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $child->slug }} &middot; {{ $child->products_count ?? 0 }} products</p>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium {{ $child->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                                {{ $child->status === 'active' ? 'Active' : 'Inactive' }}
                                            </span>
                                            <button x-on:click.stop="toggleFeatured({{ $child->id }}, this)"
                                                class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors {{ $child->featured ? 'text-amber-500' : 'text-gray-400' }}"
                                                title="{{ $child->featured ? 'Remove featured' : 'Mark featured' }}">
                                                <svg class="w-3.5 h-3.5" fill="{{ $child->featured ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                                            </button>
                                            <button x-on:click.stop="reorderCategory({{ $child->id }}, -1)"
                                                class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-gray-400" title="Move up">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                                            </button>
                                            <button x-on:click.stop="reorderCategory({{ $child->id }}, 1)"
                                                class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-gray-400" title="Move down">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                            </button>
                                            <a href="{{ route('admin.categories.edit', $child) }}" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-brand-500" title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Delete this subcategory?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded hover:bg-red-100 dark:hover:bg-red-500/10 transition-colors text-red-500" title="Delete">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center py-12">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M9.10927 2.55078H5.09927C3.89927 2.55078 2.91927 3.53078 2.91927 4.73078V8.74078C2.91927 9.94078 3.89927 10.9208 5.09927 10.9208H9.10927C10.3093 10.9208 11.2893 9.94078 11.2893 8.74078V4.73078C11.2893 3.53078 10.3093 2.55078 9.10927 2.55078Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.10927 13.0808H5.09927C3.89927 13.0808 2.91927 14.0608 2.91927 15.2608V19.2708C2.91927 20.4708 3.89927 21.4508 5.09927 21.4508H9.10927C10.3093 21.4508 11.2893 20.4708 11.2893 19.2708V15.2608C11.2893 14.0608 10.3093 13.0808 9.10927 13.0808Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No categories found</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Create a category to organize your products.</p>
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                        Add Category
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    @endif
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        function toggleStatus(id, el) {
            fetch(`/admin/categories/${id}/toggle-status`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            }).then(r => r.json()).then(data => {
                Turbo.visit(location.href, { action: 'replace' });
            });
        }

        function toggleFeatured(id, el) {
            fetch(`/admin/categories/${id}/toggle-featured`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            }).then(r => r.json()).then(data => {
                Turbo.visit(location.href, { action: 'replace' });
            });
        }

        function reorderCategory(id, direction) {
            const row = document.querySelector(`[data-category-id="${id}"]`);
            const group = row.closest('[data-sort-group]');
            const rows = [...group.querySelectorAll(':scope > [data-category-id]')];
            const ids = rows.map(r => r.dataset.categoryId);
            const index = ids.indexOf(String(id));
            const target = index + direction;

            if (index < 0 || target < 0 || target >= ids.length) {
                return;
            }

            [ids[index], ids[target]] = [ids[target], ids[index]];

            fetch('{{ route('admin.categories.updateOrder') }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ order: ids }),
            }).then(async r => {
                if (!r.ok) {
                    const data = await r.json().catch(() => ({}));
                    throw new Error(data.message || 'Failed to reorder.');
                }
                Turbo.visit(location.href, { action: 'replace' });
            }).catch(err => alert(err.message));
        }

        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.category-cb').forEach(cb => cb.checked = this.checked);
        });

        function bulkDeleteCategories() {
            const ids = [...document.querySelectorAll('.category-cb:checked')].map(cb => cb.value);

            if (ids.length === 0) {
                alert('Please select at least one category.');
                return;
            }

            if (!confirm(`Delete ${ids.length} selected category(s)?`)) {
                return;
            }

            fetch('{{ route('admin.categories.bulkDestroy') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ category_ids: ids }),
            }).then(async r => {
                if (!r.ok) {
                    const data = await r.json().catch(() => ({}));
                    throw new Error(data.message || 'Failed to delete categories.');
                }
                Turbo.visit(location.href, { action: 'replace' });
            }).catch(err => alert(err.message));
        }
    </script>
    @endpush
@endsection
