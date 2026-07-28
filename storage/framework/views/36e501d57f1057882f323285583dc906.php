<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Banners', 'url' => route('admin.banners.index')],
        ['label' => 'Create', 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Create Banner</h2>
    </div>

    <div x-data="{ tab: 'content' }" class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 dark:border-gray-800">
            <nav class="flex gap-0 px-6" role="tablist">
                <button @click="tab = 'content'" :class="tab === 'content' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">Content</button>
                <button @click="tab = 'images'" :class="tab === 'images' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">Images</button>
                <button @click="tab = 'buttons'" :class="tab === 'buttons' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">Buttons</button>
                <button @click="tab = 'schedule'" :class="tab === 'schedule' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">Schedule</button>
                <button @click="tab = 'targeting'" :class="tab === 'targeting' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">Targeting</button>
                <button @click="tab = 'style'" :class="tab === 'style' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">Style</button>
            </nav>
        </div>

        <form action="<?php echo e(route('admin.banners.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div x-show="tab === 'content'" x-cloak>
                    <div class="space-y-5">
                        <div>
                            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="subtitle" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle" value="<?php echo e(old('subtitle')); ?>"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div>
                            <label for="link" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Link URL</label>
                            <input type="text" name="link" id="link" value="<?php echo e(old('link')); ?>" placeholder="/shop"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'images'" x-cloak>
                    <div class="space-y-6">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Desktop Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100 dark:file:bg-brand-500/10 dark:file:text-brand-400 dark:hover:file:bg-brand-500/20">
                            <div id="image-preview" class="mt-3 hidden">
                                <img id="preview-img" src="" alt="Preview" class="h-32 w-64 rounded-lg object-cover">
                            </div>
                            <?php $__errorArgs = ['image'];
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
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Image <span class="text-xs text-gray-400">(optional)</span></label>
                            <input type="file" name="mobile_image" id="mobile_image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100 dark:file:bg-brand-500/10 dark:file:text-brand-400 dark:hover:file:bg-brand-500/20">
                            <div id="mobile-image-preview" class="mt-3 hidden">
                                <img id="preview-mobile-img" src="" alt="Mobile Preview" class="h-32 w-32 rounded-lg object-cover">
                            </div>
                            <?php $__errorArgs = ['mobile_image'];
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
                    </div>
                </div>

                <div x-show="tab === 'buttons'" x-cloak>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="button_text" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Button Text</label>
                                <input type="text" name="button_text" id="button_text" value="<?php echo e(old('button_text')); ?>" placeholder="Shop Now"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="link" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Button Link</label>
                                <input type="text" name="link" id="link2" value="<?php echo e(old('link')); ?>" placeholder="/shop"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-800 pt-5">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Secondary Button (Optional)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="secondary_button_text" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Button Text</label>
                                    <input type="text" name="secondary_button_text" id="secondary_button_text" value="<?php echo e(old('secondary_button_text')); ?>" placeholder="Learn More"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label for="secondary_button_url" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">URL</label>
                                    <input type="text" name="secondary_button_url" id="secondary_button_url" value="<?php echo e(old('secondary_button_url')); ?>" placeholder="/about"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'schedule'" x-cloak>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="<?php echo e(old('sort_order', 0)); ?>" min="0"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="starts_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" value="<?php echo e(old('starts_at')); ?>"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="ends_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                <input type="datetime-local" name="ends_at" id="ends_at" value="<?php echo e(old('ends_at')); ?>"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-4">
                            <input type="checkbox" name="show_countdown" id="show_countdown" value="1" <?php echo e(old('show_countdown') ? 'checked' : ''); ?>

                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            <label for="show_countdown" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show countdown timer based on end date</label>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'targeting'" x-cloak>
                    <div class="space-y-5">
                        <div>
                            <label for="position" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Position <span class="text-red-500">*</span></label>
                            <select name="position" id="position" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($pos); ?>" <?php echo e(old('position') === $pos ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('-', ' ', $pos))); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Also show on pages (optional)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pageKey => $pageLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" name="target_pages[]" value="<?php echo e($pageKey); ?>" <?php echo e(in_array($pageKey, old('target_pages', [])) ? 'checked' : ''); ?>

                                            class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                                        <?php echo e($pageLabel); ?>

                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">Banner already shows on its primary position. Check additional pages to cross-display.</p>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'style'" x-cloak>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="badge" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Badge Text</label>
                                <input type="text" name="badge" id="badge" value="<?php echo e(old('badge', 'NEW')); ?>" placeholder="NEW"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="badge_color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Badge Color</label>
                                <select name="badge_color" id="badge_color"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="bg-green-500" <?php echo e(old('badge_color', 'bg-green-500') === 'bg-green-500' ? 'selected' : ''); ?>>Green</option>
                                    <option value="bg-blue-500" <?php echo e(old('badge_color') === 'bg-blue-500' ? 'selected' : ''); ?>>Blue</option>
                                    <option value="bg-purple-500" <?php echo e(old('badge_color') === 'bg-purple-500' ? 'selected' : ''); ?>>Purple</option>
                                    <option value="bg-orange-500" <?php echo e(old('badge_color') === 'bg-orange-500' ? 'selected' : ''); ?>>Orange</option>
                                    <option value="bg-red-500" <?php echo e(old('badge_color') === 'bg-red-500' ? 'selected' : ''); ?>>Red</option>
                                    <option value="bg-pink-500" <?php echo e(old('badge_color') === 'bg-pink-500' ? 'selected' : ''); ?>>Pink</option>
                                </select>
                            </div>
                            <div>
                                <label for="text_alignment" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Text Alignment</label>
                                <select name="text_alignment" id="text_alignment"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="left" <?php echo e(old('text_alignment', 'left') === 'left' ? 'selected' : ''); ?>>Left</option>
                                    <option value="center" <?php echo e(old('text_alignment') === 'center' ? 'selected' : ''); ?>>Center</option>
                                    <option value="right" <?php echo e(old('text_alignment') === 'right' ? 'selected' : ''); ?>>Right</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="overlay_opacity" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Overlay Opacity (%)</label>
                                <input type="number" name="overlay_opacity" id="overlay_opacity" value="<?php echo e(old('overlay_opacity', 40)); ?>" min="0" max="100"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <p class="mt-1 text-xs text-gray-400">0 = no overlay, 100 = fully dark</p>
                            </div>
                            <div>
                                <label for="text_color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Text Color</label>
                                <select name="text_color" id="text_color"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="">Default (White)</option>
                                    <option value="text-white" <?php echo e(old('text_color') === 'text-white' ? 'selected' : ''); ?>>White</option>
                                    <option value="text-gray-900" <?php echo e(old('text_color') === 'text-gray-900' ? 'selected' : ''); ?>>Dark</option>
                                    <option value="text-primary-500" <?php echo e(old('text_color') === 'text-primary-500' ? 'selected' : ''); ?>>Primary</option>
                                    <option value="text-amber-400" <?php echo e(old('text_color') === 'text-amber-400' ? 'selected' : ''); ?>>Amber</option>
                                    <option value="text-green-400" <?php echo e(old('text_color') === 'text-green-400' ? 'selected' : ''); ?>>Green</option>
                                </select>
                            </div>
                            <div>
                                <label for="is_enabled" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">&nbsp;</label>
                                <div class="flex items-center gap-2 pt-1.5">
                                    <input type="checkbox" name="is_enabled" id="is_enabled" value="1" <?php echo e(old('is_enabled', '1') === '1' ? 'checked' : ''); ?>

                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                                    <label for="is_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">Enabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-800 px-6 py-4">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Save Banner</button>
                <a href="<?php echo e(route('admin.banners.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">Cancel</a>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        ['image', 'mobile_image'].forEach(function(id) {
            document.getElementById(id)?.addEventListener('change', function() {
                const previewId = id === 'image' ? 'preview-img' : 'preview-mobile-img';
                const containerId = id === 'image' ? 'image-preview' : 'mobile-image-preview';
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(previewId).src = e.target.result;
                        document.getElementById(containerId).classList.remove('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\banners\create.blade.php ENDPATH**/ ?>