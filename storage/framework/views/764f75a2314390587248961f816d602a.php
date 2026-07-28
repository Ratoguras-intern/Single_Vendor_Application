<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Customers', 'url' => route('admin.customers.index')],
        ['label' => $customer->name, 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Customer: <?php echo e($customer->name); ?></h2>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.customers.orders', $customer)); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                View Orders
            </a>
            <a href="<?php echo e(route('admin.customers.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                Back to Customers
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white"><?php echo e($stats['total_orders']); ?></p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Orders</p>
            <p class="mt-1 text-2xl font-bold text-green-600"><?php echo e($stats['completed_orders']); ?></p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">$<?php echo e(number_format($stats['total_spent'], 2)); ?></p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Order</p>
            <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white"><?php echo e($stats['last_order'] ? $stats['last_order']->created_at->format('M d, Y') : 'N/A'); ?></p>
        </div>
    </div>

    
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Customer Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                <p class="font-medium text-gray-800 dark:text-white"><?php echo e($customer->name); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                <p class="font-medium text-gray-800 dark:text-white"><?php echo e($customer->email); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                <p class="font-medium text-gray-800 dark:text-white"><?php echo e($customer->phone ?? 'N/A'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                <p class="font-medium text-gray-800 dark:text-white"><?php echo e($customer->created_at->format('M d, Y')); ?></p>
            </div>
        </div>
    </div>

    
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Purchase History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Items</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Payment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    #<?php echo e($order->order_number); ?>

                                </a>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($order->items->count()); ?></td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white">$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    <?php echo e($order->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400')); ?>">
                                    <?php echo e(ucfirst($order->status)); ?>

                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    <?php echo e($order->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400'); ?>">
                                    <?php echo e(ucfirst($order->payment_status)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            <?php echo e($orders->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\customers\show.blade.php ENDPATH**/ ?>