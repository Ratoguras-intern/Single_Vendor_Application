<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Banners', 'url' => null],
    ];
    $currentStatus = request('status', 'all');
    $currentPosition = request('position', '');
    $currentSearch = request('search', '');
?>

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Banners</h2>
        <a href="<?php echo e(route('admin.banners.create')); ?>"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Banner
        </a>
    </div>

    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="<?php echo e(route('admin.banners.index')); ?>" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px] flex-1">
                <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" value="<?php echo e($currentSearch); ?>" placeholder="Title, subtitle, description..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="min-w-[150px]">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="all" <?php echo e($currentStatus === 'all' ? 'selected' : ''); ?>>All</option>
                    <option value="active" <?php echo e($currentStatus === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="expired" <?php echo e($currentStatus === 'expired' ? 'selected' : ''); ?>>Expired</option>
                    <option value="scheduled" <?php echo e($currentStatus === 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                    <option value="inactive" <?php echo e($currentStatus === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="position" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                <select name="position" id="position" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All Positions</option>
                    <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pos); ?>" <?php echo e($currentPosition === $pos ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('-', ' ', $pos))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Filter</button>
                <a href="<?php echo e(route('admin.banners.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">Reset</a>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full" id="banner-table">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400 w-10">#</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400 w-8">Sort</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Image</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Position</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Schedule</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 sortable-container" id="sortable-container">
                    <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] sortable-row" data-id="<?php echo e($banner->id); ?>">
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($banner->id); ?></td>
                            <td class="px-5 py-4">
                                <span class="cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 drag-handle inline-block">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 6h2v2H8V6zm6 0h2v2h-2V6zM8 11h2v2H8v-2zm6 0h2v2h-2v-2zm-6 5h2v2H8v-2zm6 0h2v2h-2v-2z"/></svg>
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <?php if($banner->image): ?>
                                    <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>" class="h-12 w-24 rounded-lg object-cover">
                                <?php else: ?>
                                    <div class="flex h-12 w-24 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">N/A</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white"><?php echo e($banner->title ?? '—'); ?></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    <?php echo e(match($banner->position) {
                                        'hero' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                        'promotional' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                                        'middle' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                        'featured-section' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                        'bottom' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                                        'sidebar' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
                                    }); ?>">
                                    <?php echo e(ucwords(str_replace('-', ' ', $banner->position))); ?>

                                </span>
                                <?php if($banner->target_pages): ?>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        <?php $__currentLoopData = array_diff($banner->target_pages ?? [], [$banner->position]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400"><?php echo e(ucfirst($page)); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="toggleBanner(<?php echo e($banner->id); ?>)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                        <?php echo e($banner->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'); ?>">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                            <?php echo e($banner->is_enabled ? 'translate-x-6' : 'translate-x-1'); ?>"></span>
                                    </button>
                                    <span class="text-xs font-medium
                                        <?php echo e($banner->status === 'active' ? 'text-green-600 dark:text-green-400' : ''); ?>

                                        <?php echo e($banner->status === 'expired' ? 'text-red-600 dark:text-red-400' : ''); ?>

                                        <?php echo e($banner->status === 'scheduled' ? 'text-amber-600 dark:text-amber-400' : ''); ?>

                                        <?php echo e($banner->status === 'inactive' ? 'text-gray-500 dark:text-gray-400' : ''); ?>">
                                        <?php echo e(ucfirst($banner->status)); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">
                                <?php if($banner->starts_at || $banner->ends_at): ?>
                                    <div class="flex flex-col gap-0.5">
                                        <span>Start: <?php echo e($banner->starts_at ? $banner->starts_at->format('M d, Y H:i') : 'Immediate'); ?></span>
                                        <span>End: <?php echo e($banner->ends_at ? $banner->ends_at->format('M d, Y H:i') : 'Never'); ?></span>
                                    </div>
                                <?php else: ?>
                                    Always
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <form action="<?php echo e(route('admin.banners.duplicate', $banner)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" title="Duplicate" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        </button>
                                    </form>
                                    <a href="<?php echo e(route('admin.banners.show', $banner)); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                    </a>
                                    <a href="<?php echo e(route('admin.banners.edit', $banner)); ?>" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <form action="<?php echo e(route('admin.banners.destroy', $banner)); ?>" method="POST" onsubmit="return confirm('Delete this banner?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-500 hover:text-red-600 dark:hover:text-red-400">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M21 3H3C1.89543 3 1 3.89543 1 5V19C1 20.1046 1.89543 21 3 21H21C22.1046 21 23 20.1046 23 19V5C23 3.89543 22.1046 3 21 3Z"/><path d="M1 12H23"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No banners found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Add a banner for your homepage.</p>
                                    <a href="<?php echo e(route('admin.banners.create')); ?>" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
                                        Add Banner
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            <?php echo e($banners->links()); ?>

        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function toggleBanner(id) {
            fetch(`/admin/banners/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => location.reload());
        }

        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('sortable-container');
            if (container && container.children.length > 1) {
                new Sortable(container, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function() {
                        const items = [];
                        document.querySelectorAll('.sortable-row').forEach((row, index) => {
                            items.push({ id: row.dataset.id, sort_order: index });
                        });
                        fetch('<?php echo e(route("admin.banners.reorder")); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ items })
                        }).then(r => r.json()).then(d => {
                            if (d.message) console.log(d.message);
                        });
                    }
                });
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\banners\index.blade.php ENDPATH**/ ?>