<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'brands' => collect(),
    'priceRange' => null,
    'ajax' => false,
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
    'brands' => collect(),
    'priceRange' => null,
    'ajax' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $activeBrands = request()->input('brand', []);
    $minPrice = request('min_price', $priceRange?->min_price ? floor($priceRange->min_price) : 0);
    $maxPrice = request('max_price', $priceRange?->max_price ? ceil($priceRange->max_price) : 10000);
?>

<div
    x-data="{
        mobileOpen: false,
        minPrice: <?php echo e($minPrice); ?>,
        maxPrice: <?php echo e($maxPrice); ?>,
        selectedBrands: <?php echo \Illuminate\Support\Js::from($activeBrands)->toHtml() ?>,
        inStock: <?php echo e(request()->boolean('in_stock') ? 'true' : 'false'); ?>,
        onSale: <?php echo e(request()->boolean('on_sale') ? 'true' : 'false'); ?>,
        featured: <?php echo e(request()->boolean('featured') ? 'true' : 'false'); ?>,
        newArrivals: <?php echo e(request()->boolean('new_arrivals') ? 'true' : 'false'); ?>,
        applyFilters() {
            const url = new URL(window.location);
            const params = new URLSearchParams(url.search);

            params.delete('brand');
            this.selectedBrands.forEach(b => params.append('brand', b));

            params.delete('min_price');
            params.delete('max_price');
            if (this.minPrice > <?php echo e($minPrice); ?>) params.set('min_price', this.minPrice);
            if (this.maxPrice < <?php echo e($maxPrice); ?>) params.set('max_price', this.maxPrice);

            ['in_stock', 'on_sale', 'featured', 'new_arrivals'].forEach(key => {
                params.delete(key);
                if (this[key]) params.set(key, '1');
            });

            params.delete('page');
            const targetUrl = url.pathname + '?' + params.toString();

            <?php if($ajax): ?>
            window.dispatchEvent(new CustomEvent('shop:apply', { detail: { url: targetUrl } }));
            <?php else: ?>
            window.location = targetUrl;
            <?php endif; ?>
        },
        clearAll() {
            const url = new URL(window.location);
            const targetUrl = url.pathname;

            <?php if($ajax): ?>
            window.dispatchEvent(new CustomEvent('shop:clear', { detail: { url: targetUrl } }));
            <?php else: ?>
            window.location = targetUrl;
            <?php endif; ?>
        },
        hasActiveFilters() {
            return this.selectedBrands.length > 0 || this.inStock || this.onSale || this.featured || this.newArrivals
                || this.minPrice > <?php echo e($minPrice); ?> || this.maxPrice < <?php echo e($maxPrice); ?>;
        },
        toggleBrand(slug) {
            const idx = this.selectedBrands.indexOf(slug);
            if (idx === -1) this.selectedBrands.push(slug);
            else this.selectedBrands.splice(idx, 1);
        },
    }"
    x-on:toggle-filter.window="mobileOpen = !mobileOpen"
    x-on:keydown.escape.window="mobileOpen = false"
>

    
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="mobileOpen = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
        aria-hidden="true"
    ></div>

    
    <aside
        x-show="mobileOpen || true"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full lg:translate-x-0"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full lg:translate-x-0"
        :class="mobileOpen ? 'fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] shadow-2xl bg-white dark:bg-secondary-900 overflow-y-auto p-6 lg:relative lg:w-full lg:shadow-none lg:z-auto lg:bg-transparent lg:dark:bg-transparent lg:p-0' : 'hidden lg:block lg:w-full'"
        role="navigation"
        aria-label="Product filters"
    >
        
        <div class="flex items-center justify-between mb-6 lg:hidden">
            <h2 class="text-lg font-bold text-secondary-900 dark:text-white">Filters</h2>
            <button type="button" x-on:click="mobileOpen = false" class="p-2 -mr-2 text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-300" aria-label="Close filters">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-6">

            
            <div x-show="hasActiveFilters()" class="flex items-center justify-between">
                <span class="text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Active Filters</span>
                <button type="button" x-on:click="clearAll()" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Clear All</button>
            </div>

            
            <?php if($brands->isNotEmpty()): ?>
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-secondary-900 dark:text-white">Brands</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto scrollbar-hide">
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    value="<?php echo e($brand->slug); ?>"
                                    x-on:change="toggleBrand('<?php echo e($brand->slug); ?>')"
                                    :checked="selectedBrands.includes('<?php echo e($brand->slug); ?>')"
                                    class="h-4 w-4 rounded border-secondary-300 dark:border-secondary-600 text-primary-500 focus:ring-primary-500/20"
                                >
                                <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors flex-1"><?php echo e($brand->name); ?></span>
                                <span class="text-xs text-secondary-400 dark:text-secondary-500"><?php echo e($brand->products_count); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-secondary-900 dark:text-white">Price Range</h3>
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <label for="min-price" class="sr-only">Minimum price</label>
                        <input
                            type="number"
                            id="min-price"
                            x-model.number="minPrice"
                            min="<?php echo e($minPrice); ?>"
                            max="<?php echo e($maxPrice); ?>"
                            placeholder="Min"
                            class="input text-sm py-2"
                        >
                    </div>
                    <span class="text-secondary-400 dark:text-secondary-500">—</span>
                    <div class="flex-1">
                        <label for="max-price" class="sr-only">Maximum price</label>
                        <input
                            type="number"
                            id="max-price"
                            x-model.number="maxPrice"
                            min="<?php echo e($minPrice); ?>"
                            max="<?php echo e($maxPrice); ?>"
                            placeholder="Max"
                            class="input text-sm py-2"
                        >
                    </div>
                </div>
            </div>

            
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-secondary-900 dark:text-white">Availability</h3>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">In Stock Only</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="inStock = !inStock"
                        :aria-checked="inStock"
                        :class="inStock ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="inStock ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">On Sale</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="onSale = !onSale"
                        :aria-checked="onSale"
                        :class="onSale ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="onSale ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">Featured</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="featured = !featured"
                        :aria-checked="featured"
                        :class="featured ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="featured ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
                <label class="flex items-center justify-between cursor-pointer group">
                    <span class="text-sm text-secondary-700 dark:text-secondary-300 group-hover:text-secondary-900 dark:group-hover:text-white transition-colors">New Arrivals</span>
                    <button
                        type="button"
                        role="switch"
                        x-on:click="newArrivals = !newArrivals"
                        :aria-checked="newArrivals"
                        :class="newArrivals ? 'bg-primary-500' : 'bg-secondary-300 dark:bg-secondary-600'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 dark:focus:ring-offset-secondary-900"
                    >
                        <span
                            :class="newArrivals ? 'translate-x-5' : 'translate-x-1'"
                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-1"
                        ></span>
                    </button>
                </label>
            </div>

            
            <div class="flex gap-3 pt-2">
                <button type="button" x-on:click="applyFilters()" class="btn-primary flex-1">
                    Apply Filters
                </button>
                <button type="button" x-on:click="clearAll()" class="btn-outline">
                    Clear
                </button>
            </div>
        </div>
    </aside>
</div>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/frontend/partials/category-filters.blade.php ENDPATH**/ ?>