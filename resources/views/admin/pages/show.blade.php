@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Pages', 'url' => route('admin.pages.index')],
        ['label' => $page->title, 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Page Details</h2>
        <a href="{{ route('admin.pages.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Back to List
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Title</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->title }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Slug</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->slug }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">URL</label>
                    <a href="{{ route('frontend.page', $page->slug) }}" target="_blank" class="text-sm text-brand-500 hover:text-brand-600">{{ route('frontend.page', $page->slug) }}</a>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $page->status === 'published' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                        {{ ucfirst($page->status) }}
                    </span>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Footer Section</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->footer_section ? str_replace('-', ' ', ucfirst($page->footer_section)) : '-' }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Show in Footer</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->show_in_footer ? 'Yes' : 'No' }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Footer Order</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->footer_order }}</p>
                </div>

                @if($page->short_description)
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Short Description</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $page->short_description }}</p>
                    </div>
                @endif

                @if($page->featured_image)
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Featured Image</label>
                        <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="h-32 w-auto rounded-lg object-cover">
                    </div>
                @endif

                @if($page->seo_title)
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">SEO Title</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $page->seo_title }}</p>
                    </div>
                @endif

                @if($page->seo_description)
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">SEO Description</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $page->seo_description }}</p>
                    </div>
                @endif

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $page->updated_at->format('M d, Y h:i A') }}</p>
                </div>

                @if($page->content)
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Content Preview</label>
                        <div class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <div class="prose prose-sm dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300">
                                {!! Str::limit(strip_tags($page->content), 500) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Edit Page
                </a>
                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Page', message: 'Are you sure you want to delete this page?', form: $el })">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-500/10">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
