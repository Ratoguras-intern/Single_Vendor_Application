<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => $category->name, 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Category Details</h2>
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
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
                            <p class="text-sm text-gray-800 dark:text-white font-semibold"><?php echo e($category->name); ?></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Slug</label>
                            <p class="text-sm text-gray-800 dark:text-white font-mono"><?php echo e($category->slug); ?></p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->description ?: '—'); ?></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Parent</label>
                            <?php if($category->parent): ?>
                                <a href="<?php echo e(route('admin.categories.show', $category->parent)); ?>" class="text-sm text-brand-500 hover:text-brand-600"><?php echo e($category->parent->name); ?></a>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Top Level</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo e($category->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400'); ?>">
                                <?php echo e(ucfirst($category->status)); ?>

                            </span>
                            <?php if($category->featured): ?>
                                <span class="ml-2 inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Featured</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Sort Order</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->sort_order); ?></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Direct Products</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->products_count ?? 0); ?></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Subcategories</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->children_count ?? 0); ?></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->created_at->format('M d, Y h:i A')); ?></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->updated_at->format('M d, Y h:i A')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            Edit Category
                        </a>
                        <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" onsubmit="return confirm('Delete this category?')" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-500/10">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            
            <?php if($category->children->isNotEmpty()): ?>
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Subcategories (<?php echo e($category->children_count ?? $category->children->count()); ?>)</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <?php if($child->thumbnail_url): ?>
                                <img src="<?php echo e($child->display_image); ?>" alt="<?php echo e($child->name); ?>" class="h-10 w-10 rounded-lg object-cover">
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M2.25 18V9.75A2.25 2.25 0 0 1 4.5 7.5h15A2.25 2.25 0 0 1 21.75 9.75V18A2.25 2.25 0 0 1 19.5 20.25H4.5A2.25 2.25 0 0 1 2.25 18Z"/></svg>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <a href="<?php echo e(route('admin.categories.show', $child)); ?>" class="text-sm font-medium text-gray-800 dark:text-white hover:text-brand-500"><?php echo e($child->name); ?></a>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($child->products_count ?? 0); ?> products</p>
                            </div>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium <?php echo e($child->status ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400'); ?>">
                                <?php echo e($child->status ? 'Active' : 'Inactive'); ?>

                            </span>
                            <?php if($child->featured): ?>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Featured</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Images</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Banner</label>
                        <?php if($category->banner_url): ?>
                            <img src="<?php echo e($category->banner_url); ?>" alt="Banner" class="w-full aspect-[3/1] rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-full aspect-[3/1] rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs text-gray-400">No banner</div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thumbnail</label>
                        <?php if($category->thumbnail_url): ?>
                            <img src="<?php echo e($category->thumbnail_url); ?>" alt="Thumbnail" class="w-full aspect-square rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-full aspect-square rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs text-gray-400">No thumbnail</div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Icon</label>
                        <?php if($category->icon_url): ?>
                            <img src="<?php echo e($category->icon_url); ?>" alt="Icon" class="h-16 w-16 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs text-gray-400">No icon</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if($category->seo_title || $category->seo_description): ?>
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">SEO</h3>
                </div>
                <div class="p-6 space-y-3">
                    <?php if($category->seo_title): ?>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Title</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->seo_title); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($category->seo_description): ?>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="text-sm text-gray-800 dark:text-white"><?php echo e($category->seo_description); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\categories\show.blade.php ENDPATH**/ ?>