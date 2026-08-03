@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => $category->name, 'url' => route('admin.categories.show', $category)],
        ['label' => 'Edit', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Edit Category</h2>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" x-data="{ featured: {{ $category->featured ? 'true' : 'false' }} }">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Basic Information</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('slug') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description', $category->description) }}</textarea>
                        </div>
                        <div>
                            <label for="parent_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Parent Category</label>
                            <select name="parent_id" id="parent_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">None (Top Level)</option>
                                @foreach($parentCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Images</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Banner --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Banner (1200x400)</label>
                            @if($category->banner_url)
                                <div class="mb-2 relative group">
                                    <img src="{{ $category->banner_url }}" alt="Banner" class="w-full h-24 rounded-lg object-cover">
                                    <label class="absolute bottom-1 right-1 flex items-center gap-1.5 text-xs bg-white/90 dark:bg-gray-900/90 rounded px-2 py-1 cursor-pointer">
                                        <input type="checkbox" name="remove_banner_image" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                        <span class="text-red-600 dark:text-red-400">Remove</span>
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="banner_image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                            @error('banner_image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 aspect-[3/1] rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                <img id="banner-preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                            </div>
                        </div>
                        {{-- Thumbnail --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail (600x600)</label>
                            @if($category->thumbnail_url)
                                <div class="mb-2 relative group">
                                    <img src="{{ $category->thumbnail_url }}" alt="Thumbnail" class="w-full aspect-square rounded-lg object-cover">
                                    <label class="absolute bottom-1 right-1 flex items-center gap-1.5 text-xs bg-white/90 dark:bg-gray-900/90 rounded px-2 py-1 cursor-pointer">
                                        <input type="checkbox" name="remove_thumbnail_image" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                        <span class="text-red-600 dark:text-red-400">Remove</span>
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="thumbnail_image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                            @error('thumbnail_image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 aspect-square rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                <img id="thumb-preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                            </div>
                        </div>
                        {{-- Icon --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Icon (200x200)</label>
                            @if($category->icon_url)
                                <div class="mb-2 relative group">
                                    <img src="{{ $category->icon_url }}" alt="Icon" class="w-16 h-16 rounded-lg object-cover">
                                    <label class="absolute bottom-1 right-1 flex items-center gap-1.5 text-xs bg-white/90 dark:bg-gray-900/90 rounded px-2 py-1 cursor-pointer">
                                        <input type="checkbox" name="remove_icon" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                        <span class="text-red-600 dark:text-red-400">Remove</span>
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="icon" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                            @error('icon') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 aspect-square rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden w-16">
                                <img id="icon-preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">SEO</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="seo_title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Title</label>
                            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $category->seo_title) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('seo_title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="seo_description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Description</label>
                            <textarea name="seo_description" id="seo_description" rows="2"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('seo_description', $category->seo_description) }}</textarea>
                            @error('seo_description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Settings</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" name="featured" value="1" x-model="featured"
                                    class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured on Homepage</span>
                            </label>
                        </div>
                    </div>
                </div>

                @if($category->children_count > 0)
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                    <p class="text-sm text-blue-700 dark:text-blue-300">This category has {{ $category->children_count }} {{ Str::plural('subcategory', $category->children_count) }}.</p>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Update Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script type="text/turbo-script">
        {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            slugInput.dataset.autoGenerated = slugInput.value ? 'false' : 'true';
            nameInput.addEventListener('input', function() {
                if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                    slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                    slugInput.dataset.autoGenerated = 'true';
                }
            });
            slugInput.addEventListener('input', function() { slugInput.dataset.autoGenerated = 'false'; });

            [['banner_image', 'banner-preview-img'],
             ['thumbnail_image', 'thumb-preview-img'],
             ['icon', 'icon-preview-img']].forEach(([input, img]) => {
                document.querySelector(`input[name="${input}"]`).addEventListener('change', function() {
                    const imgEl = document.getElementById(img);
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = e => { imgEl.src = e.target.result; imgEl.classList.remove('hidden'); };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
        }
    </script>
    @endpush
@endsection
