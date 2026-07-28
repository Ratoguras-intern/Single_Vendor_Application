<?php
    $section = $sections->get('trust-bar');
    $trustItems = $section?->config['items'] ?? [];
?>

<?php if(!empty($trustItems)): ?>
<section class="py-8 border-b border-secondary-100 dark:border-secondary-700/50 bg-white dark:bg-secondary-900">
    <div class="section">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $trustItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center">
                        <?php if($item['icon'] === 'truck'): ?>
                            <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0H21a.75.75 0 0 0 .75-.75V11.25a3 3 0 0 0-3-3h-1.5l-1.72-4.575A1.5 1.5 0 0 0 13.09 2.25H10.91a1.5 1.5 0 0 0-1.43 1.025L7.75 7.5H6a3 3 0 0 0-3 3v5.25c0 .621.504 1.125 1.125 1.125h12.75"/></svg>
                        <?php elseif($item['icon'] === 'shield'): ?>
                            <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                        <?php elseif($item['icon'] === 'refresh'): ?>
                            <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/></svg>
                        <?php else: ?>
                            <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-secondary-900 dark:text-white"><?php echo e($item['title']); ?></p>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400"><?php echo e($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/frontend/home/trust-bar.blade.php ENDPATH**/ ?>