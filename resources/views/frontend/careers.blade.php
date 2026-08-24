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
    'eyebrow' => $page->subtitle ?: 'Build the future with us',
    'backgroundImage' => $page->featured_image_url ?? asset('images/pages/careers-office.jpg'),
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section max-w-5xl mx-auto">
        @if ($jobs->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white mb-1.5">Open Positions</h2>
                <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ $jobs->count() }} role{{ $jobs->count() === 1 ? '' : 's' }} currently hiring.</p>
            </div>

            <ul class="space-y-4">
                @foreach ($jobs as $job)
                    <li>
                        <a href="{{ route('frontend.careers.show', $job) }}"
                            class="card card-hover group flex flex-col sm:flex-row sm:items-center gap-4 !p-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/50">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-secondary-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $job->title }}</h3>
                                <p class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-secondary-500 dark:text-secondary-400">
                                    @if ($job->department)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                            {{ $job->department }}
                                        </span>
                                    @endif
                                    @if ($job->employment_type)
                                        <span>{{ $job->employment_type }}</span>
                                    @endif
                                    @if ($job->location)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 shrink-0 self-start sm:self-auto rounded-lg border border-secondary-200 dark:border-secondary-700 px-4 py-2 text-xs font-semibold text-secondary-600 dark:text-secondary-300 group-hover:border-primary-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                View Position
                                <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            @include('frontend.partials.empty-state', [
                'title' => 'No Open Positions',
                'description' => "There are currently no open positions. Please check back later - we'd love to hear from you when a role matches your skills.",
                'icon' => 'briefcase',
                'actionUrl' => '/contact-us',
                'actionLabel' => 'Get in Touch',
            ])
        @endif

        @if ($content && trim(strip_tags($content)) !== '')
            <article class="card p-6 sm:p-8 lg:p-10 mt-10">
                <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
            </article>
        @endif
    </div>
</section>

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
