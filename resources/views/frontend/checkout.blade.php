@extends('layouts.frontend')

@section('title', 'Checkout - NBK Vertex')

@section('content')
<div x-data="checkout()" x-init="init()" class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold" style="color: var(--bloom-foreground);"><span data-i18n="Checkout" x-text="$store.i18n.t('Checkout')">Checkout</span></h1>
        <p class="mt-2" style="color: var(--bloom-muted-foreground);"><span data-i18n="Complete your order" x-text="$store.i18n.t('Complete your order')">Complete your order</span></p>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
        <ul class="text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('frontend.checkout.store') }}">
        @csrf

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border bg-white shadow p-6" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <h2 class="text-lg font-semibold mb-6" style="color: var(--bloom-foreground);"><span data-i18n="Shipping Information" x-text="$store.i18n.t('Shipping Information')">Shipping Information</span></h2>

                    <div class="space-y-6">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="First Name" x-text="$store.i18n.t('First Name')">First Name</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->name ?? '') }}" placeholder="John" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Last Name" x-text="$store.i18n.t('Last Name')">Last Name</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Doe" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Email" x-text="$store.i18n.t('Email')">Email</span></label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" placeholder="john@example.com" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Phone" x-text="$store.i18n.t('Phone')">Phone</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 890" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Address" x-text="$store.i18n.t('Address')">Address</span></label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="123 Main St" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                        </div>

                        <div class="grid sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="City" x-text="$store.i18n.t('City')">City</span></label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="New York" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="State" x-text="$store.i18n.t('State')">State</span></label>
                                <input type="text" name="state" value="{{ old('state') }}" placeholder="NY" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="ZIP" x-text="$store.i18n.t('ZIP')">ZIP</span></label>
                                <input type="text" name="zip" value="{{ old('zip') }}" placeholder="10001" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white shadow p-6" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <h2 class="text-lg font-semibold mb-6" style="color: var(--bloom-foreground);"><span data-i18n="Payment Method" x-text="$store.i18n.t('Payment Method')">Payment Method</span></h2>

                    <div class="space-y-4">
                        <label class="flex items-center gap-3 p-4 rounded-lg border cursor-pointer transition-colors" :class="paymentMethod === 'cod' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300'">
                            <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} required class="w-4 h-4 text-emerald-600" />
                            <div>
                                <p class="font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Cash on Delivery" x-text="$store.i18n.t('Cash on Delivery')">Cash on Delivery</span></p>
                                <p class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Pay when your order arrives" x-text="$store.i18n.t('Pay when your order arrives')">Pay when your order arrives</span></p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 rounded-lg border cursor-not-allowed opacity-50" style="border-color: var(--bloom-border);">
                            <input type="radio" name="payment_method" value="stripe" disabled class="w-4 h-4" />
                            <div>
                                <p class="font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Credit / Debit Card" x-text="$store.i18n.t('Credit / Debit Card')">Credit / Debit Card</span></p>
                                <p class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Coming soon" x-text="$store.i18n.t('Coming soon')">Coming soon</span></p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 rounded-lg border cursor-not-allowed opacity-50" style="border-color: var(--bloom-border);">
                            <input type="radio" name="payment_method" value="paypal" disabled class="w-4 h-4" />
                            <div>
                                <p class="font-medium" style="color: var(--bloom-foreground);"><span data-i18n="PayPal" x-text="$store.i18n.t('PayPal')">PayPal</span></p>
                                <p class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Coming soon" x-text="$store.i18n.t('Coming soon')">Coming soon</span></p>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" :disabled="cartItems.length === 0" class="w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: var(--bloom-primary);">
                    <span data-i18n="Place Order — Cash on Delivery" x-text="$store.i18n.t('Place Order — Cash on Delivery')">Place Order — Cash on Delivery</span>
                </button>
            </div>

            <div class="lg:col-span-1">
                <div class="rounded-xl border bg-white shadow sticky top-4" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold" style="color: var(--bloom-foreground);"><span data-i18n="Order Summary" x-text="$store.i18n.t('Order Summary')">Order Summary</span></h2>
                    </div>
                    <div class="px-6 pb-6 space-y-4">
                        <template x-for="(item, index) in cartItems" :key="item.id">
                            <div class="flex items-center gap-3">
                                <img :src="item.image" :alt="item.name" class="w-12 h-12 rounded-lg object-cover bg-gray-100" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                    <p class="text-xs" style="color: var(--bloom-muted-foreground);"><span data-i18n="Qty:" x-text="$store.i18n.t('Qty:')">Qty: </span><span x-text="item.quantity"></span></p>
                                </div>
                                <p class="text-sm font-medium"><span x-text="$store.currency.format(item.price * item.quantity)"></span></p>
                            </div>
                        </template>

                        <div class="h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--bloom-muted-foreground);"><span data-i18n="Subtotal" x-text="$store.i18n.t('Subtotal')">Subtotal</span></span>
                                <span x-text="$store.currency.format(cartSubtotal())"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--bloom-muted-foreground);"><span data-i18n="Shipping" x-text="$store.i18n.t('Shipping')">Shipping</span></span>
                                <span x-text="cartShipping() === 0 ? 'Free' : $store.currency.format(cartShipping())"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--bloom-muted-foreground);"><span data-i18n="Tax" x-text="$store.i18n.t('Tax')">Tax</span></span>
                                <span x-text="$store.currency.format(cartTax())"></span>
                            </div>
                            <div class="h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold"><span data-i18n="Total" x-text="$store.i18n.t('Total')">Total</span></span>
                                <span class="text-lg font-bold" style="color: var(--bloom-primary);"><span x-text="$store.currency.format(cartTotal())"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function checkout() {
    return {
        cartItems: [],
        paymentMethod: '{{ old("payment_method", "cod") }}',
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
@endsection
