@extends('layouts.frontend')

@section('title', 'About Us - NBK Vertex')

@section('content')
@include('frontend.partials.page-hero', [
    'title' => 'About NBK Vertex',
    'description' => 'Discover the story behind our mission to make quality products accessible to everyone.',
    'eyebrow' => 'Our Story',
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center mb-16">
            <div>
                <span class="section-eyebrow">Who We Are</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-secondary-900 dark:text-white mb-4">Built for Quality, Driven by People</h2>
                <div class="space-y-4 text-secondary-600 dark:text-secondary-400 leading-relaxed">
                    <p>Founded in 2020, NBK Vertex started with a simple idea: make quality products accessible to everyone, everywhere. What began as a small online store has grown into a trusted eCommerce platform serving thousands of customers worldwide.</p>
                    <p>Our founders, frustrated by the gap between quality and affordability in online shopping, set out to create a platform that prioritizes value without compromising on quality. Today, we curate products from trusted manufacturers and artisans around the globe.</p>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-primary-100 to-primary-200/50 dark:from-primary-950/30 dark:to-primary-900/20 flex items-center justify-center overflow-hidden">
                    <div class="text-center p-8">
                        <div class="text-6xl sm:text-7xl font-extrabold text-primary-500 dark:text-primary-400 font-display">5+</div>
                        <div class="text-sm font-semibold text-secondary-600 dark:text-secondary-400 mt-2 uppercase tracking-wider">Years of Excellence</div>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-primary-500/10 rounded-2xl -z-10"></div>
                <div class="absolute -top-4 -left-4 w-16 h-16 bg-primary-500/10 rounded-xl -z-10"></div>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-16">
            @php
            $stats = [
                ['value' => '50,000+', 'label' => 'Happy Customers', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'],
                ['value' => '10,000+', 'label' => 'Products Available', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>'],
                ['value' => '50+', 'label' => 'Countries Served', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>'],
                ['value' => '4.8/5', 'label' => 'Customer Rating', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>'],
            ];
            @endphp
            @foreach($stats as $stat)
                <div class="card text-center group hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-200">
                    <div class="w-11 h-11 mx-auto rounded-xl bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">{!! $stat['icon'] !!}</svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-primary-500 dark:text-primary-400 mb-1">{{ $stat['value'] }}</div>
                    <div class="text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 gap-5 mb-16">
            <div class="card border-l-4 border-l-primary-500">
                <div class="flex items-start gap-4">
                    <div class="p-2.5 rounded-xl bg-primary-50 dark:bg-primary-950/30 shrink-0">
                        <svg class="h-6 w-6 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-secondary-900 dark:text-white mb-2">Our Mission</h3>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">To provide a seamless shopping experience that combines quality products, competitive pricing, and exceptional customer service. We believe everyone deserves access to products that enhance their daily lives.</p>
                    </div>
                </div>
            </div>
            <div class="card border-l-4 border-l-emerald-500">
                <div class="flex items-start gap-4">
                    <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 shrink-0">
                        <svg class="h-6 w-6 text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-secondary-900 dark:text-white mb-2">Our Vision</h3>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">To become the world's most trusted eCommerce platform, known for quality, transparency, and customer-first approach. We envision a future where online shopping is effortless, reliable, and delightful.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-16">
            <div class="text-center mb-10">
                <span class="section-eyebrow">Why Choose Us</span>
                <h2 class="section-heading">Built Different</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                @php
                $values = [
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>', 'title' => 'Quality Commitment', 'desc' => 'Every product is vetted and inspected before it reaches you.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>', 'title' => 'Secure Shopping', 'desc' => 'Industry-standard encryption protects every transaction.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>', 'title' => 'Fast Delivery', 'desc' => 'Free shipping on orders over $50 with express options.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>', 'title' => '24/7 Support', 'desc' => 'Our team is always ready to help with any question.'],
                ];
                @endphp
                @foreach($values as $value)
                    <div class="card-hover group">
                        <div class="w-11 h-11 rounded-xl bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center mb-4 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/40 transition-colors">
                            <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">{!! $value['icon'] !!}</svg>
                        </div>
                        <h3 class="font-semibold text-secondary-900 dark:text-white text-sm mb-1.5">{{ $value['title'] }}</h3>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card bg-gradient-to-r from-secondary-900 to-secondary-800 dark:from-secondary-800 dark:to-secondary-700 text-white text-center py-10 sm:py-12 px-6">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3">Ready to Experience the Difference?</h2>
            <p class="text-secondary-300 dark:text-secondary-400 mb-6 max-w-xl mx-auto">Join thousands of satisfied customers who trust NBK Vertex for quality products and exceptional service.</p>
            <a href="{{ route('frontend.shop') }}" class="btn-primary">
                Start Shopping
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection
