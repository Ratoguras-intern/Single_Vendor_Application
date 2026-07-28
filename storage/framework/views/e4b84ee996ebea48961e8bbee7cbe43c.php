<?php $__env->startSection('title', 'Checkout - NBK Vertex'); ?>

<?php $__env->startSection('content'); ?>
<section x-data="checkout()" x-init="init()" class="py-8 sm:py-12">
    <div class="section">
        <div class="mb-8">
            <h1 class="page-heading" data-i18n="Checkout" x-text="$store.i18n.t('Checkout')">Checkout</h1>
            <p class="section-subheading mt-1" data-i18n="Complete your order" x-text="$store.i18n.t('Complete your order')">Complete your order</p>
        </div>

        <?php if($errors->any()): ?>
        <div class="mb-6 p-4 rounded-card bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800">
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('frontend.checkout.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="card">
                        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-6" data-i18n="Shipping Information" x-text="$store.i18n.t('Shipping Information')">Shipping Information</h2>

                        <div class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label"><span data-i18n="First Name" x-text="$store.i18n.t('First Name')">First Name</span></label>
                                    <input type="text" name="first_name" value="<?php echo e(old('first_name', Auth::user()->name ?? '')); ?>" placeholder="John" required class="input" />
                                </div>
                                <div>
                                    <label class="label"><span data-i18n="Last Name" x-text="$store.i18n.t('Last Name')">Last Name</span></label>
                                    <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" placeholder="Doe" required class="input" />
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label"><span data-i18n="Email" x-text="$store.i18n.t('Email')">Email</span></label>
                                    <input type="email" name="email" value="<?php echo e(old('email', Auth::user()->email ?? '')); ?>" placeholder="john@example.com" required class="input" />
                                </div>
                                <div>
                                    <label class="label"><span data-i18n="Phone" x-text="$store.i18n.t('Phone')">Phone</span></label>
                                    <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="+1 234 567 890" required class="input" />
                                </div>
                            </div>

                            <div>
                                <label class="label"><span data-i18n="Address" x-text="$store.i18n.t('Address')">Address</span></label>
                                <input type="text" name="address" value="<?php echo e(old('address')); ?>" placeholder="123 Main St" required class="input" />
                            </div>

                            <div class="grid sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label"><span data-i18n="City" x-text="$store.i18n.t('City')">City</span></label>
                                    <input type="text" name="city" value="<?php echo e(old('city')); ?>" placeholder="New York" required class="input" />
                                </div>
                                <div>
                                    <label class="label"><span data-i18n="State" x-text="$store.i18n.t('State')">State</span></label>
                                    <input type="text" name="state" value="<?php echo e(old('state')); ?>" placeholder="NY" required class="input" />
                                </div>
                                <div>
                                    <label class="label"><span data-i18n="ZIP" x-text="$store.i18n.t('ZIP')">ZIP</span></label>
                                    <input type="text" name="zip" value="<?php echo e(old('zip')); ?>" placeholder="10001" required class="input" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-6" data-i18n="Payment Method" x-text="$store.i18n.t('Payment Method')">Payment Method</h2>

                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 rounded-card border-2 cursor-pointer transition-colors" :class="paymentMethod === 'cod' ? 'border-green-500 bg-green-50 dark:bg-green-950/30' : 'border-secondary-200 dark:border-secondary-700 hover:border-secondary-300 dark:hover:border-secondary-600'">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" <?php echo e(old('payment_method', 'cod') === 'cod' ? 'checked' : ''); ?> required class="w-4 h-4 text-green-600 dark:text-green-400" />
                                <div>
                                    <p class="font-medium text-secondary-900 dark:text-white"><span data-i18n="Cash on Delivery" x-text="$store.i18n.t('Cash on Delivery')">Cash on Delivery</span></p>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400"><span data-i18n="Pay when your order arrives" x-text="$store.i18n.t('Pay when your order arrives')">Pay when your order arrives</span></p>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 rounded-card border border-secondary-200 dark:border-secondary-700 cursor-not-allowed opacity-50">
                                <input type="radio" name="payment_method" value="stripe" disabled class="w-4 h-4" />
                                <div>
                                    <p class="font-medium text-secondary-900 dark:text-white"><span data-i18n="Credit / Debit Card" x-text="$store.i18n.t('Credit / Debit Card')">Credit / Debit Card</span></p>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400"><span data-i18n="Coming soon" x-text="$store.i18n.t('Coming soon')">Coming soon</span></p>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 rounded-card border border-secondary-200 dark:border-secondary-700 cursor-not-allowed opacity-50">
                                <input type="radio" name="payment_method" value="paypal" disabled class="w-4 h-4" />
                                <div>
                                    <p class="font-medium text-secondary-900 dark:text-white"><span data-i18n="PayPal" x-text="$store.i18n.t('PayPal')">PayPal</span></p>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400"><span data-i18n="Coming soon" x-text="$store.i18n.t('Coming soon')">Coming soon</span></p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" :disabled="cartItems.length === 0" class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed">
                        <span data-i18n="Place Order — Cash on Delivery" x-text="$store.i18n.t('Place Order — Cash on Delivery')">Place Order — Cash on Delivery</span>
                    </button>
                </div>

                <div class="lg:col-span-1">
                    <div class="card sticky top-24">
                        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white mb-6" data-i18n="Order Summary" x-text="$store.i18n.t('Order Summary')">Order Summary</h2>

                        <div class="space-y-4">
                            <template x-for="(item, index) in cartItems" :key="item.id">
                                <div class="flex items-center gap-3">
                                    <img :src="item.image" :alt="item.name" class="w-12 h-12 rounded-card object-cover bg-secondary-100 dark:bg-white/5" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-secondary-900 dark:text-white truncate" x-text="item.name"></p>
                                        <p class="text-xs text-secondary-500 dark:text-secondary-400"><span data-i18n="Qty:" x-text="$store.i18n.t('Qty:')">Qty: </span><span x-text="item.quantity"></span></p>
                                    </div>
                                    <p class="text-sm font-medium text-secondary-900 dark:text-white"><span x-text="$store.currency.format(item.price * item.quantity)"></span></p>
                                </div>
                            </template>

                            <div class="divider"></div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-secondary-500 dark:text-secondary-400"><span data-i18n="Subtotal" x-text="$store.i18n.t('Subtotal')">Subtotal</span></span>
                                    <span class="text-secondary-900 dark:text-white" x-text="$store.currency.format(cartSubtotal())"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-secondary-500 dark:text-secondary-400"><span data-i18n="Shipping" x-text="$store.i18n.t('Shipping')">Shipping</span></span>
                                    <span class="text-secondary-900 dark:text-white" x-text="cartShipping() === 0 ? 'Free' : $store.currency.format(cartShipping())"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-secondary-500 dark:text-secondary-400"><span data-i18n="Tax" x-text="$store.i18n.t('Tax')">Tax</span></span>
                                    <span class="text-secondary-900 dark:text-white" x-text="$store.currency.format(cartTax())"></span>
                                </div>
                                <div class="divider"></div>
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-secondary-900 dark:text-white"><span data-i18n="Total" x-text="$store.i18n.t('Total')">Total</span></span>
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400"><span x-text="$store.currency.format(cartTotal())"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function checkout() {
    return {
        cartItems: [],
        paymentMethod: '<?php echo e(old("payment_method", "cod")); ?>',
        async init() {
            const data = await apiFetch(window.apiRoutes.cart);
            if (data) this.cartItems = data.items;
        },
        cartSubtotal() {
            return this.cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },
        cartShipping() {
            return this.cartSubtotal() > 50 ? 0 : 9.99;
        },
        cartTax() {
            return this.cartSubtotal() * 0.08;
        },
        cartTotal() {
            return this.cartSubtotal() + this.cartShipping() + this.cartTax();
        }
    };
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\checkout.blade.php ENDPATH**/ ?>