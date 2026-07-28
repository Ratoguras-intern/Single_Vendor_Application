<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Product Sections', 'url' => route('admin.product-sections.index', 'featured-products')],
        ['label' => $sectionLabel, 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white"><?php echo e($sectionLabel); ?></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Assign products by toggling their visibility flag. Max: <?php echo e($maxProducts); ?> products.</p>
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
        <?php $__currentLoopData = ['featured-products' => 'Featured', 'new-arrivals' => 'New Arrivals', 'trending' => 'Trending', 'best-sellers' => 'Best Sellers', 'flash-sale' => 'Flash Sale', 'recommended' => 'Recommended', 'popular' => 'Popular']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.product-sections.index', $key)); ?>"
                class="inline-flex items-center gap-1.5 rounded-lg px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors
                    <?php echo e($sectionKey === $key ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'); ?>">
                <?php echo e($label); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Assigned Products -->
    <div class="mb-8 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Assigned Products (<?php echo e($assigned->total()); ?>)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Product</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">SKU</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Stock</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if($product->primaryImage()): ?>
                                        <img src="<?php echo e(asset('storage/' . $product->primaryImage()->image)); ?>" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    <?php else: ?>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                            <span class="text-xs text-gray-500">N/A</span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white"><?php echo e($product->name); ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($product->sku); ?></td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">$<?php echo e(number_format($product->discount_price ?? $product->price, 2)); ?></td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($product->stock); ?></td>
                            <td class="px-5 py-4">
                                <form action="<?php echo e(route('admin.product-sections.remove', [$sectionKey, $product->id])); ?>" method="POST" onsubmit="return confirm('Remove?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-500 hover:text-red-600 text-sm font-medium">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No products assigned yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            <?php echo e($assigned->links()); ?>

        </div>
    </div>

    <!-- Available Products -->
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-5 py-3 dark:border-gray-800">
            <form action="<?php echo e(route('admin.product-sections.index', $sectionKey)); ?>" method="GET" class="flex items-end gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search products to add..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">Search</button>
            </form>
        </div>
        <?php if($available->count() > 0): ?>
            <form action="<?php echo e(route('admin.product-sections.bulkAssign', $sectionKey)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"></th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Product</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">SKU</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Price</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <?php $__currentLoopData = $available; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-5 py-4"><input type="checkbox" name="product_ids[]" value="<?php echo e($product->id); ?>" class="product-cb rounded border-gray-300 text-brand-500 focus:ring-brand-500"></td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <?php if($product->primaryImage()): ?>
                                                <img src="<?php echo e(asset('storage/' . $product->primaryImage()->image)); ?>" alt="" class="h-10 w-10 rounded-lg object-cover">
                                            <?php else: ?>
                                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800"><span class="text-xs text-gray-500">N/A</span></div>
                                            <?php endif; ?>
                                            <span class="text-sm font-medium text-gray-800 dark:text-white"><?php echo e($product->name); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($product->sku); ?></td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">$<?php echo e(number_format($product->discount_price ?? $product->price, 2)); ?></td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($product->stock); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-5 py-3 dark:border-gray-800">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Add Selected</button>
                </div>
            </form>
        <?php else: ?>
            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No available products to add.</div>
        <?php endif; ?>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.product-cb').forEach(cb => cb.checked = this.checked);
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\product-sections\index.blade.php ENDPATH**/ ?>