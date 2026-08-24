@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'canonical' => $page->canonical_url,
    'image' => $page->og_image_url,
])

@section('content')
@include('frontend.partials.page-hero', [
    'title' => $page->title,
    'description' => $page->short_description,
    'eyebrow' => 'Stories & Insights',
    'backgroundImage' => $page->featured_image_url,
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">

        {{-- Filters --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-10">
            <form action="{{ url('/blog') }}" method="GET" role="search" class="relative w-full max-w-sm">
                <label for="blog-search" class="sr-only">Search articles</label>
                <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <input id="blog-search" type="search" name="q" value="{{ $searchQuery }}" placeholder="Search articles..."
                    class="input !pl-10 !py-2.5">
                @if ($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory }}">
                @endif
            </form>

            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2" role="navigation" aria-label="Blog categories">
                    <a href="{{ url('/blog') }}"
                        class="rounded-full px-4 py-2 text-xs sm:text-sm font-medium transition-colors border {{ !$activeCategory
                            ? 'bg-primary-500 text-white border-primary-500'
                            : 'border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-600 dark:text-secondary-300 hover:border-primary-300 hover:text-primary-600 dark:hover:text-primary-400' }}">
                        All
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ url('/blog') }}?category={{ $category->slug }}"
                            class="rounded-full px-4 py-2 text-xs sm:text-sm font-medium transition-colors border {{ $activeCategory === $category->slug
                                ? 'bg-primary-500 text-white border-primary-500'
                                : 'border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-600 dark:text-secondary-300 hover:border-primary-300 hover:text-primary-600 dark:hover:text-primary-400' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Featured post --}}
        @if ($featuredPost && !$activeCategory && trim((string) $searchQuery) === '')
            <article class="mb-12">
                <a href="{{ route('frontend.blog.post', $featuredPost) }}" class="group grid lg:grid-cols-2 rounded-2xl overflow-hidden bg-white dark:bg-secondary-900 shadow-card border border-secondary-200 dark:border-secondary-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/50">
                    <div class="relative aspect-[16/9] lg:aspect-auto lg:min-h-[320px] overflow-hidden bg-secondary-100 dark:bg-secondary-800">
                        @if ($featuredPost->featured_image_url)
                            <img src="{{ $featuredPost->featured_image_url }}" alt="{{ $featuredPost->title }}"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="h-12 w-12 text-secondary-300 dark:text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Zm10.5-11.25h.008v.008h-.008V9.75Z"/></svg>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 badge bg-primary-500 text-white text-[10px] font-bold uppercase tracking-wider">Featured</span>
                    </div>
                    <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                        <div class="flex items-center gap-3 text-xs text-secondary-500 dark:text-secondary-400 mb-3">
                            @if ($featuredPost->category)
                                <span class="font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">{{ $featuredPost->category->name }}</span>
                            @endif
                            <span>{{ $featuredPost->published_at?->format('M j, Y') }}</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white leading-snug mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $featuredPost->title }}</h2>
                        @if ($featuredPost->excerpt)
                            <p class="text-sm sm:text-base text-secondary-500 dark:text-secondary-400 leading-relaxed line-clamp-3 mb-5">{{ $featuredPost->excerpt }}</p>
                        @endif
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-secondary-900 dark:text-white group-hover:gap-3 transition-all">
                            Read Article
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </span>
                    </div>
                </a>
            </article>
        @endif

        {{-- Posts grid --}}
        @if ($posts->isNotEmpty())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach ($posts as $post)
                    <article class="card-hover !p-0 overflow-hidden h-full flex flex-col">
                        <a href="{{ route('frontend.blog.post', $post) }}" class="group flex flex-col h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500/50 rounded-card">
                            <div class="aspect-[16/9] overflow-hidden bg-secondary-100 dark:bg-secondary-800 relative">
                                @if ($post->featured_image_url)
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="h-10 w-10 text-secondary-300 dark:text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Zm10.5-11.25h.008v.008h-.008V9.75Z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center gap-2.5 text-[11px] text-secondary-400 dark:text-secondary-500 mb-2">
                                    @if ($post->category)
                                        <span class="font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">{{ $post->category->name }}</span>
                                        <span aria-hidden="true">·</span>
                                    @endif
                                    <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                                </div>
                                <h3 class="font-semibold text-secondary-900 dark:text-white leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">{{ $post->title }}</h3>
                                @if ($post->excerpt)
                                    <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed line-clamp-2">{{ $post->excerpt }}</p>
                                @endif
                                <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-secondary-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 group-hover:gap-2.5 transition-all mt-auto pt-4">
                                    Read Article
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                @include('frontend.partials.pagination', ['paginator' => $posts])
            </div>
        @else
            <div class="max-w-lg mx-auto">
                @include('frontend.partials.empty-state', [
                    'title' => $searchQuery || $activeCategory ? 'No articles found' : 'No Articles Yet',
                    'description' => $searchQuery || $activeCategory
                        ? 'Try a different search term or browse all categories.'
                        : 'We are working on our first stories. Check back soon for news, guides and inspiration.',
                    'icon' => 'newspaper',
                    'actionUrl' => $searchQuery || $activeCategory ? '/blog' : null,
                    'actionLabel' => $searchQuery || $activeCategory ? 'Clear filters' : null,
                ])
            </div>
        @endif
    </div>
</section>
@endsection
