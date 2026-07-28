<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => route('admin.products.index')],
        ['label' => $product->name, 'url' => route('admin.products.show', $product)],
        ['label' => 'Edit', 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Edit Product</h2>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name', $product->name)); ?>"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                        <input type="text" name="slug" id="slug" value="<?php echo e(old('slug', $product->slug)); ?>"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Auto-generated from name if left empty">
                        <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="sku" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" id="sku" value="<?php echo e(old('sku', $product->sku)); ?>"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                            <option value="">Select Category</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="brand_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Brand <span class="text-red-500">*</span></label>
                        <select name="brand_id" id="brand_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                            <option value="">Select Brand</option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id', $product->brand_id) == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Price <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="price" value="<?php echo e(old('price', $product->price)); ?>" step="0.01" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="discount_price" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Price</label>
                        <input type="number" name="discount_price" id="discount_price" value="<?php echo e(old('discount_price', $product->discount_price)); ?>" step="0.01" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Must be less than price">
                        <?php $__errorArgs = ['discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="stock" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="stock" value="<?php echo e(old('stock', $product->stock)); ?>" min="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="active" <?php echo e(old('status', $product->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(old('status', $product->status) === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-white/[0.02]">
                    <h3 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white">Product Visibility</h3>
                    <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">Control where this product appears on the storefront.</p>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured', $product->is_featured) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Featured</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_new_arrival" value="0">
                            <input type="checkbox" name="is_new_arrival" value="1" <?php echo e(old('is_new_arrival', $product->is_new_arrival) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">New Arrival</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_trending" value="0">
                            <input type="checkbox" name="is_trending" value="1" <?php echo e(old('is_trending', $product->is_trending) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Trending</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_best_seller" value="0">
                            <input type="checkbox" name="is_best_seller" value="1" <?php echo e(old('is_best_seller', $product->is_best_seller) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Best Seller</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_flash_sale" value="0">
                            <input type="checkbox" name="is_flash_sale" value="1" <?php echo e(old('is_flash_sale', $product->is_flash_sale) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Flash Sale</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_recommended" value="0">
                            <input type="checkbox" name="is_recommended" value="1" <?php echo e(old('is_recommended', $product->is_recommended) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Recommended</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_popular" value="0">
                            <input type="checkbox" name="is_popular" value="1" <?php echo e(old('is_popular', $product->is_popular) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Popular</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_limited_edition" value="0">
                            <input type="checkbox" name="is_limited_edition" value="1" <?php echo e(old('is_limited_edition', $product->is_limited_edition) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Limited Edition</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"><?php echo e(old('description', $product->description)); ?></textarea>
                </div>

                <?php if($product->images->count() > 0): ?>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Images</label>
                        <div class="flex flex-wrap gap-3">
                            <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative">
                                    <img src="<?php echo e(asset('storage/' . $image->image)); ?>" alt="" class="h-20 w-20 rounded-lg object-cover">
                                    <label class="absolute -top-1 -right-1 flex items-center justify-center">
                                        <input type="checkbox" name="remove_images[]" value="<?php echo e($image->id); ?>" class="rounded border-gray-300 text-red-500 focus:ring-red-500" title="Remove">
                                        <span class="sr-only">Remove</span>
                                    </label>
                                    <label class="mt-1 flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                                        <input type="radio" name="primary_image" value="<?php echo e($image->id); ?>" <?php echo e($image->is_primary ? 'checked' : ''); ?> class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                        Primary
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Add More Images (max 5 total)</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100">
                    <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div id="image-preview" class="mt-3 flex flex-wrap gap-3"></div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Update Product
                    </button>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\products\edit.blade.php ENDPATH**/ ?>