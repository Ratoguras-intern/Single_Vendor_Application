<div x-data="{ 'loaded': true }" x-init="$nextTick(() => { loaded = false })"
    x-show="loaded"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[99999] flex items-center justify-center bg-white dark:bg-gray-900">
    <div class="h-10 w-10 animate-spin rounded-full border-4 border-brand-500 border-t-transparent"></div>
</div>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\common\preloader.blade.php ENDPATH**/ ?>