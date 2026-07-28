<?php $__env->startSection('title', 'Order Confirmed - NBK Vertex'); ?>

<?php $__env->startSection('content'); ?>
<section x-data class="py-16 sm:py-24">
    <div class="section max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <div class="w-20 h-20 mx-auto rounded-full bg-green-100 dark:bg-green-950/30 flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-secondary-900 dark:text-white mb-3">Order Confirmed!</h1>
        <p class="text-lg text-secondary-500 dark:text-secondary-400 mb-8">Thank you for your order. You'll pay when it arrives.</p>

        <div class="card text-left mb-8">
            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Order Number</p>
                    <p class="font-mono font-bold text-lg text-secondary-900 dark:text-white"><?php echo e($order->order_number); ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Payment Method</p>
                    <p class="font-medium text-secondary-900 dark:text-white"><?php echo e(strtoupper($order->payment_method)); ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Status</p>
                    <span class="badge <?php echo e($order->status === 'completed' ? 'badge-success' : 'bg-yellow-100 dark:bg-yellow-950/30 text-yellow-700 dark:text-yellow-400'); ?>">
                        <?php echo e(ucfirst($order->status)); ?>

                    </span>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Total</p>
                    <p class="font-bold text-lg text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format(<?php echo e($order->total_amount); ?>)"></span></p>
                </div>
            </div>

            <div class="divider my-6"></div>

            <div>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-1">Shipping To</p>
                <p class="whitespace-pre-line text-sm text-secondary-900 dark:text-white"><?php echo e($order->shipping_address); ?></p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-4">
            <a href="<?php echo e(route('frontend.shop')); ?>" class="btn-primary">
                Continue Shopping
            </a>
            <a href="<?php echo e(route('customer.orders.index')); ?>" class="btn-outline">
                View My Orders
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\confirmation.blade.php ENDPATH**/ ?>