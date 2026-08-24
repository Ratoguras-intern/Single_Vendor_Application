@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'image' => $release->featured_image_url,
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

            <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-400 dark:text-primary-300 mt-6 mb-3">
                Press Release
                @if ($release->released_at)
                    <span class="text-secondary-400 font-normal normal-case tracking-normal">· {{ $release->released_at->format('F j, Y') }}</span>
                @endif
            </span>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">{{ $release->title }}</h1>

            @if ($release->summary)
                <p class="mt-4 text-base sm:text-lg text-secondary-300 dark:text-secondary-400 leading-relaxed max-w-3xl">{{ $release->summary }}</p>
            @endif
        </div>
    </header>

    <div class="bg-secondary-50 dark:bg-secondary-950 py-12 sm:py-16">
        <div class="section max-w-4xl mx-auto">
            @if ($release->featured_image_url)
                <figure class="mb-10 rounded-2xl overflow-hidden shadow-card border border-secondary-200 dark:border-secondary-800">
                    <img src="{{ $release->featured_image_url }}" alt="{{ $release->title }}" class="w-full aspect-[16/9] object-cover">
                </figure>
            @endif

            <article class="card !p-6 sm:!p-10">
                <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
            </article>

            <div class="card !p-5 sm:!px-8 mt-8 flex flex-wrap items-center justify-between gap-4">
                <a href="/press" class="inline-flex items-center gap-2 text-sm font-semibold text-secondary-600 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <svg class="h-4 w-4 rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    Back to Press
                </a>
                <a href="/contact-us" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline underline-offset-2">Media contact</a>
            </div>

            @if ($recent->isNotEmpty())
                <section class="mt-14" aria-label="More press releases">
                    <h2 class="text-xl font-bold text-secondary-900 dark:text-white mb-6">More Releases</h2>
                    <ul class="space-y-3">
                        @foreach ($recent as $item)
                            <li>
                                <a href="{{ route('frontend.press.show', $item) }}" class="group card-hover !p-4 sm:!p-5 flex items-start gap-4 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/50">
                                    <time datetime="{{ $item->released_at?->toDateString() }}" class="shrink-0 text-[11px] font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400 pt-1 w-20">{{ $item->released_at?->format('M j, Y') }}</time>
                                    <span class="font-medium text-sm text-secondary-800 dark:text-secondary-200 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors leading-snug">{{ $item->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</article>

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
