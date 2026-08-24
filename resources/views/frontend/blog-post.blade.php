@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'image' => $post->featured_image_url,
    'type' => 'article',
])

@section('content')
<article>
    <header class="relative overflow-hidden bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950">
        <div class="absolute inset-0 opacity-[0.07]" aria-hidden="true">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-400 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 rounded-full bg-primary-500 blur-3xl"></div>
        </div>
        <div class="relative section max-w-4xl py-10 sm:py-14">

            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-secondary-300 dark:text-secondary-400 mb-4 mt-6">
                @if ($post->category)
                    <span class="badge bg-primary-500/15 text-primary-300 border border-primary-500/30 font-semibold">{{ $post->category->name }}</span>
                @endif
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                <span aria-hidden="true">·</span>
                <span>{{ $post->reading_minutes }} min read</span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                {{ $post->title }}
            </h1>

            @if ($post->excerpt)
                <p class="mt-4 text-base sm:text-lg text-secondary-300 dark:text-secondary-400 leading-relaxed max-w-3xl">{{ $post->excerpt }}</p>
            @endif
        </div>
    </header>

    <div class="bg-secondary-50 dark:bg-secondary-950 py-12 sm:py-16">
        <div class="section max-w-4xl mx-auto">
            @if ($post->featured_image_url)
                <figure class="mb-10 rounded-2xl overflow-hidden shadow-card border border-secondary-200 dark:border-secondary-800">
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full aspect-[16/9] object-cover">
                </figure>
            @endif

            <article class="card !p-6 sm:!p-10">
                <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
            </article>

            {{-- Share --}}
            <div class="card !p-5 sm:!px-8 mt-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <a href="/blog" class="inline-flex items-center gap-2 text-sm font-semibold text-secondary-600 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg class="h-4 w-4 rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        Back to Blog
                    </a>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-secondary-400 uppercase tracking-wider mr-1">Share</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook"
                            class="w-9 h-9 rounded-full border border-secondary-200 dark:border-secondary-700 flex items-center justify-center text-secondary-500 hover:bg-primary-500 hover:border-primary-500 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on X"
                            class="w-9 h-9 rounded-full border border-secondary-200 dark:border-secondary-700 flex items-center justify-center text-secondary-500 hover:bg-primary-500 hover:border-primary-500 hover:text-white transition-colors">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn"
                            class="w-9 h-9 rounded-full border border-secondary-200 dark:border-secondary-700 flex items-center justify-center text-secondary-500 hover:bg-primary-500 hover:border-primary-500 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 1 1 0-4.124 2.062 2.062 0 0 1 0 4.124zM7.119 20.452H3.554V9h3.565v11.452z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Related posts --}}
            @if ($related->isNotEmpty())
                <section class="mt-14" aria-label="Related articles">
                    <h2 class="text-xl font-bold text-secondary-900 dark:text-white mb-6">Related Articles</h2>
                    <div class="grid sm:grid-cols-3 gap-5">
                        @foreach ($related as $related)
                            <a href="{{ route('frontend.blog.post', $related) }}" class="group card-hover !p-0 overflow-hidden h-full flex flex-col focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500/50 rounded-card">
                                <div class="aspect-[16/9] overflow-hidden bg-secondary-100 dark:bg-secondary-800">
                                    @if ($related->featured_image_url)
                                        <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" loading="lazy"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="h-8 w-8 text-secondary-300 dark:text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <time datetime="{{ $related->published_at?->toDateString() }}" class="text-[11px] text-secondary-400 dark:text-secondary-500">{{ $related->published_at?->format('M j, Y') }}</time>
                                    <h3 class="mt-1 text-sm font-semibold text-secondary-900 dark:text-white leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">{{ $related->title }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</article>

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
