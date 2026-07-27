@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => $category->name, 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Category Details</h2>
        <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                            <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ $category->name }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Slug</label>
                            <p class="text-sm text-gray-800 dark:text-white font-mono">{{ $category->slug }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->description ?: '—' }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Parent</label>
                            @if($category->parent)
                                <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-sm text-brand-500 hover:text-brand-600">{{ $category->parent->name }}</a>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Top Level</p>
                            @endif
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $category->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                            @if($category->featured)
                                <span class="ml-2 inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Featured</span>
                            @endif
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Sort Order</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->sort_order }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Direct Products</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->products_count ?? 0 }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Subcategories</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->children_count ?? 0 }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            Edit Category
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-500/10">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Subcategories --}}
            @if($category->children->isNotEmpty())
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Subcategories ({{ $category->children_count ?? $category->children->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach($category->children as $child)
                        <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            @if($child->thumbnail_url)
                                <img src="{{ $child->display_image }}" alt="{{ $child->name }}" class="h-10 w-10 rounded-lg object-cover">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M2.25 18V9.75A2.25 2.25 0 0 1 4.5 7.5h15A2.25 2.25 0 0 1 21.75 9.75V18A2.25 2.25 0 0 1 19.5 20.25H4.5A2.25 2.25 0 0 1 2.25 18Z"/></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.categories.show', $child) }}" class="text-sm font-medium text-gray-800 dark:text-white hover:text-brand-500">{{ $child->name }}</a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $child->products_count ?? 0 }} products</p>
                            </div>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium {{ $child->status ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                {{ $child->status ? 'Active' : 'Inactive' }}
                            </span>
                            @if($child->featured)
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Featured</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar: Images --}}
        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Images</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Banner</label>
                        @if($category->banner_url)
                            <img src="{{ $category->banner_url }}" alt="Banner" class="w-full aspect-[3/1] rounded-lg object-cover">
                        @else
                            <div class="w-full aspect-[3/1] rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs text-gray-400">No banner</div>
                        @endif
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thumbnail</label>
                        @if($category->thumbnail_url)
                            <img src="{{ $category->thumbnail_url }}" alt="Thumbnail" class="w-full aspect-square rounded-lg object-cover">
                        @else
                            <div class="w-full aspect-square rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs text-gray-400">No thumbnail</div>
                        @endif
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Icon</label>
                        @if($category->icon_url)
                            <img src="{{ $category->icon_url }}" alt="Icon" class="h-16 w-16 rounded-lg object-cover">
                        @else
                            <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs text-gray-400">No icon</div>
                        @endif
                    </div>
                </div>
            </div>

            @if($category->seo_title || $category->seo_description)
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">SEO</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($category->seo_title)
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Title</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->seo_title }}</p>
                        </div>
                    @endif
                    @if($category->seo_description)
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="text-sm text-gray-800 dark:text-white">{{ $category->seo_description }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
