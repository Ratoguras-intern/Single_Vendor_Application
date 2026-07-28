<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Banners', 'url' => route('admin.banners.index')],
        ['label' => $banner->title ?? 'Banner #' . $banner->id, 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Banner Details</h2>
        <div class="flex items-center gap-2">
            <form action="<?php echo e(route('admin.banners.duplicate', $banner)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Duplicate
                </button>
            </form>
            <a href="<?php echo e(route('admin.banners.edit', $banner)); ?>" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
            </a>
            <form action="<?php echo e(route('admin.banners.destroy', $banner)); ?>" method="POST" onsubmit="return confirm('Delete this banner permanently?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 dark:border-red-700 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Content</h3>
                </div>
                <div class="p-6">
                    <?php if($banner->image): ?>
                        <div class="mb-6">
                            <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>" class="rounded-lg max-h-48 w-full object-cover">
                        </div>
                    <?php endif; ?>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Title</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->title ?? '—'); ?></dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Subtitle</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->subtitle ?? '—'); ?></dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500 dark:text-gray-400">Description</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->description ?? '—'); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Images</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Desktop</p>
                            <?php if($banner->image): ?>
                                <img src="<?php echo e($banner->image_url); ?>" alt="" class="rounded-lg max-h-40 w-full object-cover">
                            <?php else: ?>
                                <div class="flex h-32 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-xs text-gray-400">No image</div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Mobile</p>
                            <?php if($banner->mobile_image): ?>
                                <img src="<?php echo e($banner->mobile_image_url); ?>" alt="" class="rounded-lg max-h-40 w-full object-cover">
                            <?php else: ?>
                                <div class="flex h-32 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-xs text-gray-400">Uses desktop image</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Buttons & Links</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Primary Button Text</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->button_text ?? '—'); ?></dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Primary Button Link</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->link ?? '—'); ?></dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Secondary Button Text</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->secondary_button_text ?? '—'); ?></dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Secondary Button URL</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5"><?php echo e($banner->secondary_button_url ?? '—'); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Targeting</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Primary Position</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                    <?php echo e(ucwords(str_replace('-', ' ', $banner->position))); ?>

                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Target Pages</dt>
                            <dd class="font-medium text-gray-800 dark:text-white mt-0.5">
                                <?php if($banner->target_pages): ?>
                                    <div class="flex flex-wrap gap-1.5">
                                        <?php $__currentLoopData = $banner->target_pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300"><?php echo e(ucfirst($page)); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Enabled</span>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                            <?php echo e($banner->is_enabled ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400'); ?>">
                            <?php echo e($banner->is_enabled ? 'Enabled' : 'Disabled'); ?>

                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                            <?php echo e($banner->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : ''); ?>

                            <?php echo e($banner->status === 'expired' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : ''); ?>

                            <?php echo e($banner->status === 'scheduled' ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : ''); ?>

                            <?php echo e($banner->status === 'inactive' ? 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400' : ''); ?>">
                            <?php echo e(ucfirst($banner->status)); ?>

                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Sort Order</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white"><?php echo e($banner->sort_order); ?></span>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Schedule</h3>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <div class="absolute left-2.5 top-2 bottom-2 w-px bg-gray-200 dark:bg-gray-700"></div>
                        <div class="space-y-6">
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-1.5 h-5 w-5 rounded-full border-2 border-green-500 bg-green-50 dark:bg-green-500/10 flex items-center justify-center">
                                    <div class="h-2 w-2 rounded-full bg-green-500"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Start</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white"><?php echo e($banner->starts_at?->format('M d, Y H:i') ?? 'Immediate'); ?></p>
                            </div>
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-1.5 h-5 w-5 rounded-full border-2
                                    <?php echo e($banner->status === 'expired' ? 'border-red-500 bg-red-50 dark:bg-red-500/10' : 'border-gray-400 bg-gray-50 dark:bg-gray-800'); ?>

                                    flex items-center justify-center">
                                    <div class="h-2 w-2 rounded-full <?php echo e($banner->status === 'expired' ? 'bg-red-500' : 'bg-gray-400'); ?>"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">End</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white"><?php echo e($banner->ends_at?->format('M d, Y H:i') ?? 'Never'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php if($banner->show_countdown): ?>
                        <div class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-amber-50 dark:bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-700 dark:text-amber-400">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            Countdown enabled
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Style</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Badge</dt>
                            <dd>
                                <?php if($banner->badge): ?>
                                    <span class="inline-flex items-center rounded-full <?php echo e($banner->badge_color ?? 'bg-green-500'); ?> px-2 py-0.5 text-xs font-bold text-white"><?php echo e($banner->badge); ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Text Alignment</dt>
                            <dd class="font-medium text-gray-800 dark:text-white capitalize"><?php echo e($banner->text_alignment ?? 'Left'); ?></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Overlay Opacity</dt>
                            <dd class="font-medium text-gray-800 dark:text-white"><?php echo e($banner->overlay_opacity ?? '40'); ?>%</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Text Color</dt>
                            <dd class="font-medium text-gray-800 dark:text-white"><?php echo e($banner->text_color ?? 'Default (White)'); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 dark:border-gray-800 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Meta</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                            <dd class="font-medium text-gray-800 dark:text-white">#<?php echo e($banner->id); ?></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="font-medium text-gray-800 dark:text-white"><?php echo e($banner->created_at->format('M d, Y H:i')); ?></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Updated</dt>
                            <dd class="font-medium text-gray-800 dark:text-white"><?php echo e($banner->updated_at->format('M d, Y H:i')); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\banners\show.blade.php ENDPATH**/ ?>