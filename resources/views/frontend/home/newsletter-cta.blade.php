@php
    $section = ($sections ?? collect())->get('newsletter-cta');
    $bgImage = $section?->config['bg_image'] ?? 'https://images.unsplash.com/photo-1607082349566-187342175e2f?w=1920&q=80';
    $buttonText = $section?->config['button_text'] ?? 'Subscribe';
@endphp

<section class="relative home-section overflow-hidden">
    <img src="{{ $bgImage }}" alt="Newsletter" class="absolute inset-0 w-full h-full object-cover" loading="lazy" />
    <div class="absolute inset-0 bg-secondary-900/80 backdrop-blur-sm"></div>

    <div class="section relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $section?->title ?? 'Stay in the Loop' }}</h2>
            <p class="mt-4 text-lg text-secondary-300">{{ $section?->subtitle ?? 'Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.' }}</p>

            <form class="mt-6 flex max-w-md mx-auto gap-3" x-data="{ email: '', submitted: false }" x-on:submit.prevent="submitted = true; email = ''">
                <input type="email" x-model="email" placeholder="Enter your email address" required x-show="!submitted"
                    class="flex-1 rounded-input border-white/20 bg-white/10 text-white placeholder:text-white/50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 backdrop-blur-sm transition-colors" />
                <button type="submit" x-show="!submitted" class="btn-primary btn-lg whitespace-nowrap">
                    {{ $buttonText }}
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </button>
                <p x-show="submitted" x-cloak class="flex-1 flex items-center justify-center text-green-400 text-sm font-medium">Thanks for subscribing!</p>
            </form>

            <p class="mt-4 text-xs text-secondary-400">No spam, unsubscribe anytime.</p>
        </div>
    </div>
</section>
