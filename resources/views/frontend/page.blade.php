@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'canonical' => $page->canonical_url,
    'image' => $page->og_image_url,
])

@section('content')
@php
    preg_match_all('/<h2[^>]*id="([^"]+)"[^>]*>(.*?)<\/h2>/is', (string) $content, $headingMatches);
    $toc = collect($headingMatches[1] ?? [])->map(fn ($id, $i) => [
        'id' => $id,
        'label' => trim(strip_tags($headingMatches[2][$i] ?? '')),
    ])->filter(fn ($item) => $item['label'] !== '')->values();

    $heroImage = $page->featured_image_url ?? match ($page->slug) {
        'privacy-policy' => asset('images/pages/privacy-lock.jpg'),
        'cookie-policy' => asset('images/pages/cookie-policy.jpg'),
        'accessibility' => asset('images/pages/accessibility.jpg'),
        'terms-and-conditions', 'terms-of-service' => asset('images/pages/terms-desk.jpg'),
        default => null,
    };

    $bannerPath = match ($page->slug) {
        'privacy-policy' => 'images/pages/banner-privacy.jpg',
        'terms-and-conditions', 'terms-of-service' => 'images/pages/banner-terms.jpg',
        'cookie-policy' => 'images/pages/banner-cookie.jpg',
        'accessibility' => 'images/pages/banner-accessibility.jpg',
        default => null,
    };
    $bannerImage = ($bannerPath && file_exists(public_path($bannerPath))) ? asset($bannerPath) : null;
@endphp

@include('frontend.partials.page-hero', [
    'title' => $page->title,
    'description' => $page->short_description,
    'eyebrow' => str_replace('-', ' ', ucfirst($page->footer_section ?? '')),
    'backgroundImage' => $heroImage,
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="@if($toc->isNotEmpty()) lg:grid lg:grid-cols-[260px_minmax(0,1fr)] xl:grid-cols-[280px_minmax(0,1fr)] @endif gap-8 lg:gap-10 max-w-6xl mx-auto">
            @if ($toc->isNotEmpty())
                <aside class="lg:sticky lg:top-24 lg:self-start mb-8 lg:mb-0" aria-label="Table of contents">
                    <nav class="card !p-5">
                        <h2 class="text-xs font-semibold uppercase tracking-wider text-secondary-400 dark:text-secondary-500 mb-3">On this page</h2>
                        <ol class="space-y-1 border-l border-secondary-200 dark:border-secondary-700">
                            @foreach ($toc as $item)
                                <li>
                                    <a href="#{{ $item['id'] }}"
                                        class="block py-1.5 pl-4 text-sm text-secondary-500 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-500 border-l -ml-px transition-colors leading-snug">
                                        {{ \Illuminate\Support\Str::limit($item['label'], 42) }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </aside>
            @endif

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-3 text-xs text-secondary-400 dark:text-secondary-500 mb-4">
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <span>Last updated {{ optional($page->updated_at)->format('F j, Y') }}</span>
                </div>

                @if($content)
                    <article class="card p-6 sm:p-8 lg:p-10">
                        <div class="page-content text-secondary-600 dark:text-secondary-400 leading-relaxed">
                            {!! $content !!}
                        </div>
                    </article>
                @else
                    @include('frontend.partials.empty-state', [
                        'title' => 'Content coming soon',
                        'description' => 'This page is being prepared. Please check back shortly.',
                    ])
                @endif
            </div>
        </div>
    </div>
</section>

@if ($bannerImage)
    <section class="pb-14 sm:pb-16 lg:pb-20" aria-hidden="true">
        <div class="section max-w-6xl mx-auto">
            <div class="relative overflow-hidden rounded-2xl shadow-card group">
                <img src="{{ $bannerImage }}" alt="" loading="lazy" class="w-full h-52 sm:h-64 lg:h-72 object-cover group-hover:scale-[1.02] transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-secondary-950/60 via-transparent to-transparent"></div>
            </div>
        </div>
    </section>
@endif

@include('frontend.partials.cta-help')

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
