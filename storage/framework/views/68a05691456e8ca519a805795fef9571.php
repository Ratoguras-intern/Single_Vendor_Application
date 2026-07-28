<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active' => false, 'href' => '#']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active' => false, 'href' => '#']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => 'relative inline-flex items-center px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 ' . ($active ? 'text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5')])); ?>>
    <?php echo e($slot); ?>

    <?php if($active): ?>
        <span class="absolute bottom-0.5 left-3 right-3 h-0.5 bg-primary-500 rounded-full"></span>
    <?php endif; ?>
</a>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\components\nav-link.blade.php ENDPATH**/ ?>