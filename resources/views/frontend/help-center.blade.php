@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'canonical' => $page->canonical_url,
    'image' => $page->og_image_url,
])

@section('content')
<section class="relative overflow-hidden bg-secondary-950">
    <div class="absolute inset-0" aria-hidden="true">
        <img src="{{ $page->featured_image_url ?? asset('images/pages/help-shopping.jpg') }}" alt="" class="h-full w-full object-cover scale-105" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-b from-secondary-950/90 via-secondary-950/75 to-secondary-950/90"></div>
    </div>
    <div class="relative section py-14 sm:py-16 lg:py-20 text-center">
        <div class="max-w-2xl mx-auto">
            <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-400 dark:text-primary-300 mb-3">{{ $page->subtitle ?: 'How can we help?' }}</span>
            <h1 class="text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold text-white tracking-tight leading-tight mb-4">{{ $page->title }}</h1>
            @if($page->short_description)
                <p class="text-base sm:text-lg text-secondary-300 dark:text-secondary-400 leading-relaxed mb-8">{{ $page->short_description }}</p>
            @endif

            <form action="{{ url('/help-center') }}" method="GET" role="search" class="relative max-w-xl mx-auto">
                <label for="hc-search" class="sr-only">Search help articles</label>
                <svg class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <input id="hc-search" type="search" name="q" value="{{ $searchQuery }}"
                    placeholder="Search for answers, orders, shipping..."
                    class="w-full rounded-xl border border-secondary-600/50 bg-white/[0.07] backdrop-blur pl-11 pr-28 py-3.5 text-sm text-white placeholder:text-secondary-400 focus:border-primary-400 focus:bg-white/10 focus:ring-2 focus:ring-primary-500/30 focus:outline-none transition-colors">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-primary-500 px-4 py-2 text-xs font-semibold text-white hover:bg-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 transition-colors">
                    Search
                </button>
            </form>
        </div>
    </div>
</section>

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950 min-h-[40vh]">
    <div class="section">
        @if ($searchQuery !== '')
            {{-- Search results --}}
            <div class="max-w-4xl mx-auto">
                <h2 class="text-lg font-bold text-secondary-900 dark:text-white mb-1">
                    {{ $searchResults->count() }} result{{ $searchResults->count() === 1 ? '' : 's' }} for
                    &ldquo;<span class="text-primary-600 dark:text-primary-400">{{ $searchQuery }}</span>&rdquo;
                </h2>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-6">
                    <a href="{{ url('/help-center') }}" class="inline-flex items-center gap-1 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                        Clear search
                    </a>
                </p>

                @if ($searchResults->isNotEmpty())
                    @include('frontend.partials.faq-accordion', ['faqs' => $searchResults])
                @else
                    @include('frontend.partials.empty-state', [
                        'title' => 'No answers found',
                        'description' => "We couldn't find anything matching your search. Try different keywords or contact our support team directly.",
                        'icon' => 'search',
                        'actionUrl' => '/contact-us',
                        'actionLabel' => 'Contact Support',
                    ])
                @endif
            </div>
        @elseif ($categories->isNotEmpty())
            <div class="max-w-5xl mx-auto lg:grid lg:grid-cols-[minmax(0,1fr)_300px] lg:gap-10 items-start">
                <div class="max-w-4xl">
                @if ($categories->count() > 1)
                    <div class="mb-10">
                        <h2 class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary-400 dark:text-secondary-500 mb-3">Popular topics</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories as $category)
                                <a href="#faq-category-{{ $category->slug }}"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 px-4 py-2 text-sm font-medium text-secondary-600 dark:text-secondary-300 hover:border-primary-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50/60 dark:hover:bg-primary-950/20 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40">
                                    {{ $category->name }}
                                    <span class="text-xs text-secondary-400">{{ $category->faqs->count() }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="space-y-12 sm:space-y-14">
                @foreach ($categories as $category)
                    <div id="faq-category-{{ $category->slug }}">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-9 h-9 rounded-lg bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center shrink-0">
                                <svg class="h-[18px] w-[18px] text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
                            </span>
                            <h2 class="text-lg font-bold text-secondary-900 dark:text-white">{{ $category->name }}</h2>
                            <span class="badge-secondary">{{ $category->faqs->count() }}</span>
                        </div>
                        @include('frontend.partials.faq-accordion', ['faqs' => $category->faqs])
                    </div>
                @endforeach
                </div>

                <aside class="mt-10 lg:mt-0 space-y-5 lg:sticky lg:top-24">
                    <div class="card !p-0 overflow-hidden">
                        <img src="{{ asset('images/pages/help-laptop.jpg') }}" alt="Shopping online with help always one click away" loading="lazy" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-sm text-secondary-900 dark:text-white">{{ \App\Models\Setting::get('help.aside_title', "Can't find your answer?") }}</h3>
                            <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-1 mb-3">{{ \App\Models\Setting::get('help.aside_text', 'Our team replies within one business day.') }}</p>
                            <a href="/contact-us" class="btn-primary w-full !py-2 text-xs justify-center">Contact Support</a>
                        </div>
                    </div>
                </aside>
                </div>
            </div>

            <div class="mt-14">
                @include('frontend.partials.cta-help')
            </div>
        @else
            <div class="max-w-4xl mx-auto">
                @include('frontend.partials.empty-state', [
                    'title' => 'Help articles coming soon',
                    'description' => 'Our support team is preparing helpful answers for you.',
                    'icon' => 'help',
                    'actionUrl' => '/contact-us',
                    'actionLabel' => 'Contact Support',
                ])
            </div>
        @endif
    </div>
</section>
@endsection
