@props([
    'title' => 'Still need help?',
    'description' => 'Our support team is ready to assist you with any question.',
])

@php
    $helpUrl = \App\Models\Page::where('slug', 'help-center')->published()->exists() ? '/help-center' : null;
    $contactUrl = \App\Models\Page::where('slug', 'contact-us')->published()->exists() ? '/contact-us' : null;
@endphp

@if ($helpUrl || $contactUrl)
    <section class="section pb-14 sm:pb-16 lg:pb-20">
        <div class="rounded-2xl bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950 px-6 py-10 sm:px-12 sm:py-12 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.07]" aria-hidden="true">
                <div class="absolute -top-20 right-10 w-72 h-72 rounded-full bg-primary-400 blur-3xl"></div>
                <div class="absolute -bottom-24 left-10 w-64 h-64 rounded-full bg-primary-500 blur-3xl"></div>
            </div>
            <div class="relative max-w-xl mx-auto">
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-2">{{ $title }}</h2>
                @if($description)
                    <p class="text-sm sm:text-base text-secondary-300 dark:text-secondary-400 leading-relaxed mb-6">{{ $description }}</p>
                @endif
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    @if ($helpUrl)
                        <a href="{{ $helpUrl }}" class="btn-primary w-full sm:w-auto">
                            Browse Help Center
                        </a>
                    @endif
                    @if ($contactUrl)
                        <a href="{{ $contactUrl }}" class="inline-flex items-center justify-center gap-2 rounded-btn px-5 py-2.5 text-sm font-semibold border-2 border-white/25 text-white hover:bg-white/10 transition-colors w-full sm:w-auto">
                            Contact Support
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
