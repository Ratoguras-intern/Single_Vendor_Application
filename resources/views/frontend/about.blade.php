@extends('layouts.frontend')

@section('title', 'About Us - NBK Vertex')

@section('content')
<div style="background-color: var(--bloom-background);">
    {{-- Hero --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-orange-50 to-yellow-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <span class="mb-6 inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold shadow" style="background-color: var(--bloom-primary); color: var(--bloom-primary-foreground); border-color: transparent;"><span data-i18n="About Us" x-text="$store.i18n.t('About Us')">About Us</span></span>
                <h1 class="text-4xl lg:text-6xl font-bold mb-6" style="color: var(--bloom-foreground);">We're <span class="bg-gradient-to-r from-indigo-500 to-blue-600 bg-clip-text text-transparent">NBK Vertex</span></h1>
                <p class="text-lg max-w-2xl mx-auto" style="color: var(--bloom-muted-foreground);"><span data-i18n="Discover unique products that inspire your lifestyle. Quality craftsmanship meets modern design." x-text="$store.i18n.t('Discover unique products that inspire your lifestyle. Quality craftsmanship meets modern design.')">Discover unique products that inspire your lifestyle. Quality craftsmanship meets modern design.</span></p>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div class="space-y-8">
                <div class="rounded-xl border bg-white shadow p-8" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <h2 class="text-2xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Our Story" x-text="$store.i18n.t('Our Story')">Our Story</span></h2>
                    <p class="leading-relaxed" style="color: var(--bloom-muted-foreground);"><span data-i18n="Founded with a passion for quality e-commerce, NBK Vertex has grown from a small project into a trusted platform for online businesses worldwide. We believe that every store should have access to powerful, modern tools." x-text="$store.i18n.t('Founded with a passion for quality e-commerce, NBK Vertex has grown from a small project into a trusted platform for online businesses worldwide. We believe that every store should have access to powerful, modern tools.')">Founded with a passion for quality e-commerce, NBK Vertex has grown from a small project into a trusted platform for online businesses worldwide. We believe that every store should have access to powerful, modern tools.</span></p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="rounded-xl border bg-white shadow p-6 text-center" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="text-3xl font-bold mb-2" style="color: var(--bloom-primary);">500+</div>
                        <div class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Happy Customers" x-text="$store.i18n.t('Happy Customers')">Happy Customers</span></div>
                    </div>
                    <div class="rounded-xl border bg-white shadow p-6 text-center" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="text-3xl font-bold mb-2" style="color: var(--bloom-primary);">50+</div>
                        <div class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Premium Brands" x-text="$store.i18n.t('Premium Brands')">Premium Brands</span></div>
                    </div>
                    <div class="rounded-xl border bg-white shadow p-6 text-center" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="text-3xl font-bold mb-2" style="color: var(--bloom-primary);">24/7</div>
                        <div class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Customer Support" x-text="$store.i18n.t('Customer Support')">Customer Support</span></div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white shadow p-8" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                    <h2 class="text-2xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Our Mission" x-text="$store.i18n.t('Our Mission')">Our Mission</span></h2>
                    <p class="leading-relaxed" style="color: var(--bloom-muted-foreground);"><span data-i18n="To provide premium quality footwear that combines cutting-edge design with exceptional comfort, making every step a confident one. We're committed to sustainable practices and delivering outstanding customer experiences." x-text="$store.i18n.t('To provide premium quality footwear that combines cutting-edge design with exceptional comfort, making every step a confident one. We\'re committed to sustainable practices and delivering outstanding customer experiences.')">To provide premium quality footwear that combines cutting-edge design with exceptional comfort, making every step a confident one. We're committed to sustainable practices and delivering outstanding customer experiences.</span></p>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.partials.newsletter')
</div>
@endsection
