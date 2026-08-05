@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => 'Create', 'url' => null],
    ];

    $objectFits = config('categories.object_fits', ['cover']);
    $objectPositions = config('categories.object_positions', ['center']);
@endphp

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Create Category</h2>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" x-data="{ featured: {{ old('featured') ? 'true' : 'false' }} }">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Basic Information</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Auto-generated from name if left empty">
                            @error('slug') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="parent_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Parent Category</label>
                            <select name="parent_id" id="parent_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">None (Top Level)</option>
                                @foreach($parentCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ (old('parent_id', $selectedParent?->id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Media</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Main image --}}
                        <div>
                            <label for="image" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Main Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                            @error('image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 aspect-square rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                <img id="main-preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Used on category cards and the homepage. A 300x300 optimized thumbnail is generated automatically.</p>
                        </div>

                        {{-- Icon picker --}}
                        <div x-data="categoryIconPicker('{{ old('icon') ?? '' }}')">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Icon (Lucide)</label>
                            <input type="hidden" name="icon" :value="selected">
                            <button type="button" @click="open = !open"
                                class="w-full flex items-center gap-3 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 hover:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:hover:border-brand-500 text-left">
                                <span class="text-brand-500 shrink-0" x-show="selected" x-html="svg(selected, 22)"></span>
                                <span x-text="selected || 'Select an icon…'" :class="selected ? '' : 'text-gray-400'"></span>
                                <svg class="ml-auto h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            @error('icon') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror

                            <div x-show="open" x-collapse x-transition class="mt-2 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 p-3">
                                <input type="text" x-model="query" placeholder="Search icons…"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <div class="mt-2 grid grid-cols-6 sm:grid-cols-8 gap-1.5 max-h-64 overflow-y-auto">
                                    <template x-for="icon in filtered" :key="icon">
                                        <button type="button" @click="select(icon)"
                                            class="flex h-10 items-center justify-center rounded-lg border text-gray-500 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600 dark:text-gray-400 dark:hover:border-brand-500 dark:hover:bg-brand-500/10"
                                            :class="selected === icon ? 'border-brand-500 bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'border-gray-200 dark:border-gray-700'"
                                            :title="icon">
                                            <span x-html="svg(icon, 20)"></span>
                                        </button>
                                    </template>
                                    <p x-show="filtered.length === 0" class="col-span-full py-4 text-center text-xs text-gray-400">No icons match your search</p>
                                </div>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Shown in navigation, mega menu, and category pages.</p>
                        </div>

                        {{-- Banner desktop --}}
                        <div>
                            <label for="banner_image" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Banner Image <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="file" name="banner_image" id="banner_image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                            @error('banner_image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 aspect-[3/1] rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                <img id="banner-preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                            </div>
                        </div>

                        {{-- Banner mobile --}}
                        <div>
                            <label for="banner_mobile_image" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Banner Image (Mobile) <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="file" name="banner_mobile_image" id="banner_mobile_image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                            @error('banner_mobile_image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 aspect-[3/4] max-h-40 w-full rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                <img id="banner-mobile-preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                            </div>
                        </div>

                        {{-- Banner display controls --}}
                        <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="banner_image_fit" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Banner Fit</label>
                                <select name="banner_image_fit" id="banner_image_fit"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    @foreach($objectFits as $fit)
                                        <option value="{{ $fit }}" {{ old('banner_image_fit', 'cover') === $fit ? 'selected' : '' }}>{{ ucfirst($fit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="banner_image_position" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Banner Position</label>
                                <select name="banner_image_position" id="banner_image_position"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    @foreach($objectPositions as $position)
                                        <option value="{{ $position }}" {{ old('banner_image_position', 'center') === $position ? 'selected' : '' }}>{{ ucfirst($position) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">SEO</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="seo_title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Title</label>
                            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Override page title for SEO">
                            @error('seo_title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="seo_description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Description</label>
                            <textarea name="seo_description" id="seo_description" rows="2"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Meta description for search engines">{{ old('seo_description') }}</textarea>
                            @error('seo_description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Settings</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
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

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Save Category
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
            const iconMarkup = @json(config('categories.icons', []));

            window.categoryIconPicker = function (current = '') {
                const names = Object.keys(iconMarkup);
                return {
                    open: false,
                    query: '',
                    selected: current,
                    names,
                    svg(name, size = 24) {
                        const markup = iconMarkup[name] || '';
                        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:' + size + 'px;height:' + size + 'px">' + markup + '</svg>';
                    },
                    get filtered() {
                        const q = this.query.toLowerCase();
                        return this.names.filter(n => n.toLowerCase().includes(q));
                    },
                    select(name) {
                        this.selected = name;
                        this.open = false;
                        this.query = '';
                    },
                };
            };

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

            [['image', 'main-preview-img'],
             ['banner_image', 'banner-preview-img'],
             ['banner_mobile_image', 'banner-mobile-preview-img']].forEach(([input, img]) => {
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
