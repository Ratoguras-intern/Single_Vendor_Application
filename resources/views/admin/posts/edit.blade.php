@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Blog Posts', 'url' => route('admin.posts.index')],
        ['label' => $post->title, 'url' => null],
        ['label' => 'Edit', 'url' => null],
    ];
@endphp

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Edit Post</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update post content, publishing, and SEO options.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                All Posts
            </a>
            <button type="submit" form="post-form" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                Update Post
            </button>
        </div>
    </div>

    <form id="post-form" action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main Content Column --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Details --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Post Details</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('title') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                required>
                            @error('title')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('slug') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                placeholder="auto-generated-from-title">
                            <div class="mt-1.5 flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                <span>URL preview: <span id="slug-preview" class="font-medium text-gray-600 dark:text-gray-300">/blog/{{ old('slug', $post->slug) }}</span></span>
                            </div>
                            @error('slug')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="post_category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                            <select name="post_category_id" id="post_category_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">No category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('post_category_id', $post->post_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('post_category_id')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="excerpt" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
                            <textarea name="excerpt" id="excerpt" rows="2"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('excerpt') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Content Editor --}}
                <div class="flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]" style="height: 750px;">
                    <div class="shrink-0 border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Content</h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Use the editor below to write your post content.</p>
                    </div>

                    {{-- Toolbar --}}
                    <div id="toolbar" class="relative z-10 shrink-0 flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 px-4 py-2 dark:border-gray-800 dark:bg-gray-900/50">
                        <button type="button" data-action="heading" class="toolbar-btn group" title="Heading">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                        </button>
                        <button type="button" data-action="bold" class="toolbar-btn group" title="Bold">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"/></svg>
                        </button>
                        <button type="button" data-action="italic" class="toolbar-btn group" title="Italic">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 5.25h-9m9 0v13.5m0-13.5H7.5m9 0H7.5"/></svg>
                        </button>
                        <button type="button" data-action="underline" class="toolbar-btn group" title="Underline">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75h15"/></svg>
                        </button>
                        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-700"></div>
                        <button type="button" data-action="bulletList" class="toolbar-btn group" title="Bullet List">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </button>
                        <button type="button" data-action="orderedList" class="toolbar-btn group" title="Numbered List">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                        </button>
                        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-700"></div>
                        <button type="button" data-action="blockquote" class="toolbar-btn group" title="Blockquote">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                        </button>
                        <button type="button" data-action="horizontalRule" class="toolbar-btn group" title="Horizontal Line">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5"/></svg>
                        </button>
                        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-700"></div>
                        <button type="button" data-action="link" class="toolbar-btn group" title="Insert Link">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                        </button>
                        <button type="button" data-action="undo" class="toolbar-btn group" title="Undo">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                        </button>
                        <button type="button" data-action="redo" class="toolbar-btn group" title="Redo">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3"/></svg>
                        </button>
                    </div>

                    {{-- Editor --}}
                    <div id="editor" class="prose-admin min-h-0 flex-1 overflow-y-auto px-6 py-4 focus:outline-none"></div>

                    {{-- Hidden field for form submission --}}
                    <textarea name="content" id="content-hidden" class="hidden">{!! old('content', $post->content) !!}</textarea>

                    @error('content')
                        <div class="border-t border-gray-200 px-6 py-3 dark:border-gray-800">
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="space-y-6">

                {{-- Publishing --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Publishing</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <div>
                            <label for="published_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Publish Date</label>
                            <input type="date" name="published_at" id="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('published_at') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Defaults to now when publishing without a date.</p>
                            @error('published_at')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="is_featured" class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Post</label>
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $post->is_featured))
                                class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-900">
                        </div>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Featured Image</h3>
                    <div>
                        @php
                            $featuredMedia = $post->featured_image
                                ? \App\Models\Media::where('path', $post->featured_image)->first()
                                : null;
                        @endphp
                        <x-media-picker.picker name="featured_image_media_id" folder="posts"
                            :value="$featuredMedia?->id" :preview="$post->featured_image_url"
                            remove-name="remove_featured_image"
                            help="Selecting a new image replaces the current one." />
                        @error('featured_image_media_id')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Author --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Author</h3>
                    <div>
                        <label for="author_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Display Name</label>
                        <input type="text" name="author_name" id="author_name" value="{{ old('author_name', $post->author_name) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('author_name') border-red-500 @enderror"
                            placeholder="Defaults to your account name">
                        @error('author_name')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SEO --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <button type="button" x-data="{ open: false }" x-on:click="open = !open" class="flex w-full items-center justify-between px-6 py-4 text-left">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">SEO Settings</h3>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Search engine optimization</p>
                        </div>
                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-data="{ open: false }" x-show="open" x-collapse x-cloak class="px-6 pb-6">
                        <div class="space-y-4">
                            <div>
                                <label for="seo_title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Title</label>
                                <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $post->seo_title) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                    placeholder="Custom title for search engines">
                            </div>
                            <div>
                                <label for="seo_description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Description</label>
                                <textarea name="seo_description" id="seo_description" rows="3"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                    placeholder="Meta description for search engines">{{ old('seo_description', $post->seo_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 0.375rem;
            color: #6b7280;
            transition: all 0.15s;
        }
        .toolbar-btn:hover {
            background-color: #f3f4f6;
            color: #111827;
        }
        .dark .toolbar-btn:hover {
            background-color: rgba(255,255,255,0.08);
            color: #f3f4f6;
        }
        .toolbar-btn.active {
            background-color: #fef3c7;
            color: #d97706;
        }
        .dark .toolbar-btn.active {
            background-color: rgba(217,119,6,0.15);
            color: #fbbf24;
        }

        .prose-admin {
            font-size: 0.9375rem;
            line-height: 1.7;
            color: #374151;
        }
        .dark .prose-admin {
            color: #d1d5db;
        }
        .prose-admin:focus {
            outline: none;
        }
        .prose-admin h1 { font-size: 1.875rem; font-weight: 700; margin: 1.5rem 0 0.75rem; color: #111827; }
        .prose-admin h2 { font-size: 1.5rem; font-weight: 700; margin: 1.25rem 0 0.625rem; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; }
        .prose-admin h3 { font-size: 1.25rem; font-weight: 600; margin: 1rem 0 0.5rem; color: #1f2937; }
        .dark .prose-admin h1, .dark .prose-admin h2, .dark .prose-admin h3 { color: #f9fafb; border-color: #374151; }
        .prose-admin p { margin: 0.5rem 0; }
        .prose-admin ul, .prose-admin ol { margin: 0.5rem 0; padding-left: 1.5rem; }
        .prose-admin ul { list-style-type: disc; }
        .prose-admin ol { list-style-type: decimal; }
        .prose-admin li { margin: 0.25rem 0; }
        .prose-admin blockquote {
            margin: 1rem 0;
            padding: 0.75rem 1rem;
            border-left: 4px solid #d97706;
            background: #fffbeb;
            border-radius: 0 0.5rem 0.5rem 0;
            color: #92400e;
        }
        .dark .prose-admin blockquote { background: rgba(217,119,6,0.08); color: #fbbf24; border-color: #f59e0b; }
        .prose-admin hr { margin: 1.5rem 0; border: 0; border-top: 2px solid #e5e7eb; }
        .dark .prose-admin hr { border-color: #374151; }
        .prose-admin a { color: #2563eb; text-decoration: underline; }
        .prose-admin a:hover { color: #1d4ed8; }
        .prose-admin strong { font-weight: 600; }
        .prose-admin em { font-style: italic; }
        .prose-admin u { text-decoration: underline; }
    </style>

    @push('scripts')
    <script type="text/turbo-script">
    {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const slugPreview = document.getElementById('slug-preview');

        slugInput.dataset.autoGenerated = slugInput.value ? 'false' : 'true';

        function toSlug(text) {
            return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        }

        function updateSlugPreview() {
            const val = slugInput.value.trim();
            slugPreview.textContent = val ? '/blog/' + val : '/blog/post-slug';
        }

        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                slugInput.value = toSlug(this.value);
                slugInput.dataset.autoGenerated = 'true';
                updateSlugPreview();
            }
        });

        slugInput.addEventListener('input', function() {
            slugInput.dataset.autoGenerated = 'false';
            updateSlugPreview();
        });

        // Tiptap Editor auto-initialized by admin.js
    }
    </script>
    @endpush
@endsection
