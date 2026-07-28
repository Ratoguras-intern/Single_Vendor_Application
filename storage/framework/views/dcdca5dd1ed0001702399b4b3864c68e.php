<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Orders', 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <?php
        $allStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        $allPaymentStatuses = ['pending', 'paid', 'failed', 'cod'];
        $hasFilters = request()->filled('status') || request()->filled('payment_status') || request()->filled('month');
    ?>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Orders</h2>
        <?php if($hasFilters): ?>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                Clear Filters
            </a>
        <?php endif; ?>
    </div>

    
    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="<?php echo e(route('admin.orders.index')); ?>" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[150px]">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="payment_status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment</label>
                <select name="payment_status" id="payment_status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">All</option>
                    <?php $__currentLoopData = $allPaymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ps); ?>" <?php echo e(request('payment_status') === $ps ? 'selected' : ''); ?>><?php echo e(ucfirst($ps)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="min-w-[160px]">
                <label for="month" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                <input type="month" name="month" id="month" value="<?php echo e(request('month')); ?>"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Filter
                </button>
                <?php if($hasFilters): ?>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]" x-data="orderActions(<?php echo e($order->id); ?>, '<?php echo e($order->status); ?>', '<?php echo e($order->payment_status); ?>')">
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">#<?php echo e($order->id); ?></td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-white"><?php echo e($order->user->name ?? 'Guest'); ?></td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                    </a>

                                    <div class="relative" x-data="{ openStatus: false }">
                                        <button @click="openStatus = !openStatus" @keydown.escape.window="openStatus = false" :class="statusColor" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium transition hover:opacity-80" title="Update Status">
                                            {{ statusLabel }}
                                            <svg class="h-3 w-3 shrink-0 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="openStatus" @click.away="openStatus = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 z-50 mt-1 w-40 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-900" style="display: none;">
                                            <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button @click="updateField('status', '<?php echo e($s); ?>')" :class="currentStatus === '<?php echo e($s); ?>' ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/[0.06]'" class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-xs">
                                                    <svg x-show="currentStatus === '<?php echo e($s); ?>'" class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                                    <span x-show="currentStatus !== '<?php echo e($s); ?>'" class="h-3 w-3 shrink-0"></span>
                                                    <?php echo e(ucfirst($s)); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <div class="relative" x-data="{ openPayment: false }">
                                        <button @click="openPayment = !openPayment" @keydown.escape.window="openPayment = false" :class="paymentColor" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium transition hover:opacity-80" title="Update Payment">
                                            {{ paymentLabel }}
                                            <svg class="h-3 w-3 shrink-0 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="openPayment" @click.away="openPayment = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 z-50 mt-1 w-40 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-900" style="display: none;">
                                            <?php $__currentLoopData = $allPaymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button @click="updateField('payment_status', '<?php echo e($ps); ?>')" :class="currentPayment === '<?php echo e($ps); ?>' ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/[0.06]'" class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-xs">
                                                    <svg x-show="currentPayment === '<?php echo e($ps); ?>'" class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                                    <span x-show="currentPayment !== '<?php echo e($ps); ?>'" class="h-3 w-3 shrink-0"></span>
                                                    <?php echo e(ucfirst($ps)); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M8 2V5M16 2V5M3 10H21M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No orders found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($hasFilters ? 'Try adjusting your filters.' : 'Orders will appear here once customers start purchasing.'); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            <?php echo e($orders->links()); ?>

        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function orderActions(orderId, status, paymentStatus) {
                return {
                    currentStatus: status,
                    currentPayment: paymentStatus,

                    get statusLabel() {
                        return this.currentStatus.charAt(0).toUpperCase() + this.currentStatus.slice(1);
                    },

                    get paymentLabel() {
                        return this.currentPayment.charAt(0).toUpperCase() + this.currentPayment.slice(1);
                    },

                    get statusColor() {
                        const colors = {
                            pending: 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                            processing: 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                            shipped: 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                            completed: 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                            cancelled: 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                        };
                        return colors[this.currentStatus] || colors.pending;
                    },

                    get paymentColor() {
                        const colors = {
                            pending: 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                            paid: 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                            failed: 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                            cod: 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                        };
                        return colors[this.currentPayment] || colors.pending;
                    },

                    async updateField(field, value) {
                        try {
                            const response = await fetch(`/admin/orders/${orderId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ field, value }),
                            });

                            const data = await response.json();

                            if (response.ok) {
                                if (field === 'status') {
                                    this.currentStatus = value;
                                } else {
                                    this.currentPayment = value;
                                }
                            }
                        } catch (e) {
                            console.error('Update failed:', e);
                        }
                    },
                };
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\orders\index.blade.php ENDPATH**/ ?>