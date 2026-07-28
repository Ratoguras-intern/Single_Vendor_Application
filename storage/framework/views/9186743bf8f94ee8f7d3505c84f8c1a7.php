<?php
    $notifications = auth()->user()->notifications()->latest()->take(10)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
?>

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <?php if($unreadCount > 0): ?>
        <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-medium text-white"><?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?></span>
        <?php endif; ?>
    </button>

    <div x-show="open" @click.away="open = false" x-transition
        class="absolute right-0 mt-2 w-80 rounded-lg border border-gray-200 bg-white p-4 shadow-lg dark:border-gray-800 dark:bg-gray-900 z-50">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Notifications</h4>
            <?php if($unreadCount > 0): ?>
            <form method="POST" action="<?php echo e(route('notifications.markRead')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-xs text-brand-500 hover:underline">Mark all read</button>
            </form>
            <?php endif; ?>
        </div>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-start gap-3 p-2 rounded-lg <?php echo e($notification->read_at ? 'bg-white' : 'bg-blue-50 dark:bg-white/[0.03]'); ?> hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-50 text-green-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-700 dark:text-gray-300 <?php echo e($notification->read_at ? '' : 'font-medium'); ?>"><?php echo e($notification->data['message'] ?? 'New notification'); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-4">
                <p class="text-sm text-gray-400">No notifications yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/header/notification-dropdown.blade.php ENDPATH**/ ?>