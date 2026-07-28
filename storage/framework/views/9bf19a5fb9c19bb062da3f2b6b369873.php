<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

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

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-400 dark:border-primary-500 text-start text-base font-medium text-primary-700 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 focus:outline-none focus:text-primary-800 dark:focus:text-primary-300 focus:bg-primary-100 dark:focus:bg-primary-900/30 focus:border-primary-700 dark:focus:border-primary-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-secondary-600 dark:text-secondary-400 hover:text-secondary-800 dark:hover:text-white hover:bg-secondary-50 dark:hover:bg-white/5 hover:border-secondary-300 dark:hover:border-secondary-600 focus:outline-none focus:text-secondary-800 dark:focus:text-white focus:bg-secondary-50 dark:focus:bg-white/5 focus:border-secondary-300 dark:focus:border-secondary-600 transition duration-150 ease-in-out';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\components\responsive-nav-link.blade.php ENDPATH**/ ?>