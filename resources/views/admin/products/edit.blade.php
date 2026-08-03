@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => route('admin.products.index')],
        ['label' => $product->name, 'url' => route('admin.products.show', $product)],
        ['label' => 'Edit', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Edit Product</h2>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Auto-generated from name if left empty">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        @error('sku')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $categoryOption)
                                <option value="{{ $categoryOption['id'] }}" {{ old('category_id', $product->category_id) == $categoryOption['id'] ? 'selected' : '' }}>{{ $categoryOption['name'] }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="brand_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Brand <span class="text-red-500">*</span></label>
                        <select name="brand_id" id="brand_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Price <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="discount_price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Price</label>
                        <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price', $product->discount_price) }}" step="0.01" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Must be less than price">
                        @error('discount_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        @error('stock')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-white/[0.02]">
                    <h3 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white">Product Visibility</h3>
                    <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">Control where this product appears on the storefront.</p>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Featured</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_new_arrival" value="0">
                            <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">New Arrival</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_trending" value="0">
                            <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $product->is_trending) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Trending</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_best_seller" value="0">
                            <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Best Seller</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_flash_sale" value="0">
                            <input type="checkbox" name="is_flash_sale" value="1" {{ old('is_flash_sale', $product->is_flash_sale) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Flash Sale</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_recommended" value="0">
                            <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended', $product->is_recommended) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Recommended</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_popular" value="0">
                            <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Popular</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_limited_edition" value="0">
                            <input type="checkbox" name="is_limited_edition" value="1" {{ old('is_limited_edition', $product->is_limited_edition) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Limited Edition</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description', $product->description) }}</textarea>
                </div>

                @if ($product->images->count() > 0)
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Images</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($product->images as $image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="" class="h-20 w-20 rounded-lg object-cover">
                                    <label class="absolute -top-1 -right-1 flex items-center justify-center">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="rounded border-gray-300 text-red-500 focus:ring-red-500" title="Remove">
                                        <span class="sr-only">Remove</span>
                                    </label>
                                    <label class="mt-1 flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                                        <input type="radio" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }} class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                        Primary
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Add More Images (max 5 total)</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                    @error('images')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <div id="image-preview" class="mt-3 flex flex-wrap gap-3"></div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const imagesInput = document.getElementById('images');
            const previewDiv = document.getElementById('image-preview');

            slugInput.dataset.autoGenerated = slugInput.value ? 'false' : 'true';

            nameInput.addEventListener('input', function() {
                if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                    slugInput.value = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-|-$/g, '');
                    slugInput.dataset.autoGenerated = 'true';
                }
            });

            slugInput.addEventListener('input', function() {
                slugInput.dataset.autoGenerated = 'false';
            });

            imagesInput.addEventListener('change', function() {
                previewDiv.innerHTML = '';
                if (this.files) {
                    Array.from(this.files).forEach(function(file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative';
                            wrapper.innerHTML = '<img src="' + e.target.result + '" class="h-20 w-20 rounded-lg object-cover">';
                            previewDiv.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        }
    </script>
    @endpush
@endsection
