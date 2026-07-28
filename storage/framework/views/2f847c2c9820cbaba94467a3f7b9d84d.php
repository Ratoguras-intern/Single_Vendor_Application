<?php $__env->startSection('title', 'Order #' . $order->order_number . ' - NBK Vertex'); ?>

<?php $__env->startSection('content'); ?>
<section x-data class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="<?php echo e(route('customer.orders.index')); ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mb-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to Orders
                </a>
                <h1 class="page-heading">Order <?php echo e($order->order_number); ?></h1>
            </div>
            <span class="badge <?php echo e($order->status === 'completed' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-300')); ?>">
                <?php echo e(ucfirst($order->status)); ?>

            </span>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Order Items</h2>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-4 p-4 rounded-card bg-secondary-50 dark:bg-secondary-950">
                                <?php if($item->product && $item->product->primaryImage()): ?>
                                    <img src="<?php echo e($item->product->primaryImage()->image); ?>" alt="<?php echo e($item->product->name); ?>" class="w-16 h-16 rounded-card object-cover" />
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-card bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-secondary-900 dark:text-white"><?php echo e($item->product->name ?? 'Product #' . $item->product_id); ?></p>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Qty: <?php echo e($item->quantity); ?> &bull; <span x-text="$store.currency.format(<?php echo e($item->price); ?>)"></span> each</p>
                                </div>
                                <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format(<?php echo e($item->price * $item->quantity); ?>)"></span></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Subtotal</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><span x-text="$store.currency.format(<?php echo e($order->subtotal); ?>)"></span></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Shipping</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><?php echo e($order->shipping > 0 ? '' : 'Free'); ?><?php if($order->shipping > 0): ?><span x-text="$store.currency.format(<?php echo e($order->shipping); ?>)"></span><?php endif; ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Tax</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><span x-text="$store.currency.format(<?php echo e($order->tax); ?>)"></span></span>
                        </div>
                        <?php if($order->discount > 0): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-secondary-500 dark:text-secondary-400">Discount</span>
                                <span class="font-medium text-green-600 dark:text-green-400">-<span x-text="$store.currency.format(<?php echo e($order->discount); ?>)"></span></span>
                            </div>
                        <?php endif; ?>
                        <div class="divider"></div>
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-secondary-900 dark:text-white">Total</span>
                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format(<?php echo e($order->total_amount); ?>)"></span></span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Date</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><?php echo e($order->created_at->format('M d, Y h:i A')); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Payment</span>
                            <span class="badge <?php echo e($order->payment_status === 'paid' ? 'badge-success' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-300'); ?>">
                                <?php echo e(ucfirst($order->payment_status)); ?>

                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Method</span>
                            <span class="font-medium text-secondary-900 dark:text-white uppercase"><?php echo e($order->payment_method); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary-500 dark:text-secondary-400">Phone</span>
                            <span class="font-medium text-secondary-900 dark:text-white"><?php echo e($order->phone); ?></span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Shipping Address</h2>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 whitespace-pre-line"><?php echo e($order->shipping_address); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\customer\orders\show.blade.php ENDPATH**/ ?>