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
    'eyebrow' => 'Newsroom',
    'backgroundImage' => $page->featured_image_url,
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section max-w-5xl mx-auto">

        @if ($releases->isNotEmpty())
            <ul class="space-y-4">
                @foreach ($releases as $release)
                    <li>
                        <a href="{{ route('frontend.press.show', $release) }}"
                            class="card card-hover group flex flex-col sm:flex-row sm:items-center gap-5 !p-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/50">
                            <div class="w-full sm:w-28 shrink-0 aspect-[16/10] sm:aspect-square rounded-xl overflow-hidden bg-secondary-100 dark:bg-secondary-800 relative">
                                @if ($release->featured_image_url)
                                    <img src="{{ $release->featured_image_url }}" alt="{{ $release->title }}" loading="lazy"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="h-7 w-7 text-secondary-300 dark:text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <time datetime="{{ $release->released_at?->toDateString() }}" class="text-[11px] font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">{{ $release->released_at?->format('M j, Y') ?? $release->created_at->format('M j, Y') }}</time>
                                <h2 class="mt-1 font-semibold text-secondary-900 dark:text-white leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">{{ $release->title }}</h2>
                                @if ($release->summary)
                                    <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed line-clamp-2">{{ $release->summary }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center gap-1.5 shrink-0 self-start sm:self-auto text-xs font-semibold text-secondary-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-all group-hover:gap-2.5">
                                Read More
                                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-10">
                @include('frontend.partials.pagination', ['paginator' => $releases])
            </div>
        @else
            @include('frontend.partials.empty-state', [
                'title' => 'No Press Releases',
                'description' => 'There are no press releases at this time. Media inquiries are always welcome.',
                'icon' => 'megaphone',
            ])
        @endif

        {{-- Media contact --}}
        @if ($pressContact && ($pressContact['email'] || $pressContact['phone'] || $pressContact['name']))
            <div class="card !p-6 sm:!p-8 mt-10 bg-gradient-to-r from-secondary-900 to-secondary-800 dark:from-secondary-950 dark:to-secondary-900 border-transparent text-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                    <div>
                        <h2 class="font-bold text-lg">Media Inquiries</h2>
                        <p class="text-sm text-secondary-300 dark:text-secondary-400 mt-1 max-w-md leading-relaxed">
                            Journalists and media professionals can reach our communications team directly.
                        </p>
                    </div>
                    <div class="space-y-1.5 text-sm shrink-0">
                        @if ($pressContact['email'])
                            <a href="mailto:{{ $pressContact['email'] }}" class="flex items-center gap-2 font-medium hover:text-primary-300 transition-colors">
                                <svg class="h-4 w-4 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                {{ $pressContact['email'] }}
                            </a>
                        @endif
                        @if ($pressContact['phone'])
                            <p class="flex items-center gap-2 text-secondary-300 dark:text-secondary-400">
                                <svg class="h-4 w-4 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                {{ $pressContact['phone'] }}
                            </p>
                        @endif
                        @if ($pressContact['name'])
                            <p class="text-xs text-secondary-400">Attn: {{ $pressContact['name'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($content && trim(strip_tags($content)) !== '')
            <article class="card p-6 sm:p-8 lg:p-10 mt-8">
                <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
            </article>
        @endif
    </div>
</section>

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
