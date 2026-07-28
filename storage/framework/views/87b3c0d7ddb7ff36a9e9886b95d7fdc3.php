<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'sidebar' => false,
    'href' => '/',
    'subtitle' => '',
    'showText' => true,
    'compact' => false,
    'textClass' => '',
]));

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

foreach (array_filter(([
    'sidebar' => false,
    'href' => '/',
    'subtitle' => '',
    'showText' => true,
    'compact' => false,
    'textClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $appName = config('app.name', 'Your Brand');
    $words = explode(' ', $appName);
    $initials = strtoupper(collect($words)->map(fn($w) => $w[0] ?? '')->take(2)->join(''));
?>

<a href="<?php echo e($href); ?>"
    class="flex items-center gap-2.5 group select-none <?php echo e($compact ? '' : ''); ?>">

    <div class="relative flex <?php echo e($compact ? 'h-9 w-9' : 'h-10 w-10'); ?> shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-700 shadow-lg shadow-secondary-900/20 transition-all duration-300 group-hover:scale-105">
        <div class="absolute inset-0 rounded-xl ring-1 ring-white/10"></div>
        <span class="relative flex items-center <?php echo e($compact ? 'text-sm' : 'text-base'); ?> font-black tracking-tight">
            <span class="text-white"><?php echo e($initials); ?></span>
        </span>
    </div>

    <?php if($showText): ?>
        <?php if($sidebar): ?>
            <div x-show="$store.sidebar?.isExpanded || $store.sidebar?.isHovered || $store.sidebar?.isMobileOpen"
                x-transition.opacity class="leading-tight">
        <?php else: ?>
            <div class="leading-tight">
        <?php endif; ?>

            <div class="flex items-center gap-1.5">
                <span class="<?php echo e($compact ? 'text-lg' : 'text-xl'); ?> font-extrabold tracking-tight text-secondary-900 dark:text-white <?php echo e($textClass); ?>">
                    <?php echo e($appName); ?>

                </span>
            </div>

            <?php if($subtitle): ?>
                <p class="mt-px text-[10px] uppercase tracking-[0.3em] font-medium text-secondary-400 dark:text-secondary-500">
                    <?php echo e($subtitle); ?>

                </p>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</a>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/components/brand-logo.blade.php ENDPATH**/ ?>