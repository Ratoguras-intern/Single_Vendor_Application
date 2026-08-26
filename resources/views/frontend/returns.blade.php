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
    'eyebrow' => $page->subtitle ?: 'Hassle-free returns',
    'backgroundImage' => $page->featured_image_url ?? asset('images/pages/returns-boxes.jpg'),
])

@if ($processSteps->isNotEmpty())
    <section class="py-12 sm:py-16 bg-white dark:bg-secondary-900" aria-label="Return process">
        <div class="section">
            <div class="text-center mb-10">
                <span class="section-eyebrow">The process</span>
                <h2 class="section-heading !text-2xl sm:!text-3xl">How Returns Work</h2>
                @if (filled($windowDays))
                    <p class="section-subheading mt-2">Returns accepted within {{ $windowDays }} days of delivery.</p>
                @endif
            </div>
            @include('frontend.partials.process-steps', ['steps' => $processSteps, 'columns' => 5])
        </div>
    </section>
@endif

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section max-w-5xl mx-auto space-y-10">

        {{-- Return window highlight --}}
        <div class="card !p-0 overflow-hidden flex flex-col sm:flex-row items-stretch bg-gradient-to-r from-primary-50/80 to-white dark:from-primary-950/20 dark:to-secondary-900 border-primary-100 dark:border-primary-900/30">
            <div class="flex-1 flex flex-col sm:flex-row items-center sm:justify-between gap-6 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-primary-500 text-white flex flex-col items-center justify-center shrink-0 shadow-card" aria-hidden="true">
                        <span class="text-xl font-extrabold leading-none">{{ filled($windowDays) ? $windowDays : '—' }}</span>
                        <span class="text-[9px] font-semibold uppercase tracking-wider mt-0.5">days</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-secondary-900 dark:text-white">Return Window</h2>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-0.5">
                            @if (filled($windowDays))
                                Items can be returned within {{ $windowDays }} days of delivery.
                            @else
                                Return period information is being updated.
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ $startReturnUrl }}" class="btn-primary w-full sm:w-auto shrink-0">
                    Start a Return
                </a>
            </div>
            <div class="relative sm:w-60 shrink-0 min-h-[150px]">
                <img src="{{ asset('images/pages/packaging-open.jpg') }}" alt="Order being packed for an easy return" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-primary-50 dark:from-secondary-900 via-transparent to-transparent sm:bg-gradient-to-l"></div>
            </div>
        </div>

        @if ($content && trim(strip_tags($content)) !== '')
            <article class="card p-6 sm:p-8 lg:p-10">
                <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
            </article>
        @endif
    </div>
</section>

@include('frontend.partials.cta-help')

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
