@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . config('app.name', 'NBK Vertex'))

@if($seoDescription)
    @push('meta')
        <meta name="description" content="{{ $seoDescription }}">
    @endpush
@endif

@section('content')
@php
    $pageIcon = match($page->footer_section) {
        'customer-care' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>',
        'company' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
        'legal' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>',
        default => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>',
    };
@endphp

@include('frontend.partials.page-hero', [
    'title' => $page->title,
    'description' => $page->short_description,
    'eyebrow' => str_replace('-', ' ', ucfirst($page->footer_section ?? '')),
    'backgroundImage' => $page->featured_image_url,
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="max-w-4xl mx-auto">
            @if($page->content)
                <div class="card p-6 sm:p-8 lg:p-10">
                    <div class="page-content text-secondary-600 dark:text-secondary-400 leading-relaxed">
                        {!! $page->content !!}
                    </div>
                </div>
            @else
                <div class="card text-center py-16">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-secondary-100 dark:bg-secondary-800 flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-secondary-400 dark:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </div>
                    <p class="text-secondary-500 dark:text-secondary-400 text-sm">This page has no content yet.</p>
                </div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
    .page-content h2 {
        font-size: 1.375rem;
        font-weight: 700;
        color: theme('colors.secondary.900');
        margin-top: 2.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid theme('colors.secondary.200');
        line-height: 1.3;
    }
    .dark .page-content h2 { color: theme('colors.white'); border-bottom-color: theme('colors.secondary.700'); }
    .page-content h2:first-child { margin-top: 0; }

    .page-content h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: theme('colors.secondary.900');
        margin-top: 1.75rem;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .dark .page-content h3 { color: theme('colors.white'); }

    .page-content p {
        margin-bottom: 1rem;
        line-height: 1.8;
        font-size: 0.9375rem;
    }

    .page-content ul,
    .page-content ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .page-content ul { list-style-type: disc; }
    .page-content ol { list-style-type: decimal; }

    .page-content li {
        margin-bottom: 0.4rem;
        line-height: 1.7;
        font-size: 0.9375rem;
    }

    .page-content strong {
        font-weight: 600;
        color: theme('colors.secondary.900');
    }
    .dark .page-content strong { color: theme('colors.white'); }

    .page-content a {
        color: theme('colors.primary.500');
        text-decoration: underline;
        text-underline-offset: 2px;
        font-weight: 500;
    }
    .page-content a:hover { color: theme('colors.primary.600'); }

    .page-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid theme('colors.secondary.200');
    }
    .dark .page-content table { border-color: theme('colors.secondary.700'); }

    .page-content table th,
    .page-content table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid theme('colors.secondary.200');
    }
    .dark .page-content table th,
    .dark .page-content table td {
        border-bottom-color: theme('colors.secondary.700');
    }

    .page-content table th {
        font-weight: 600;
        color: theme('colors.secondary.900');
        background: theme('colors.secondary.50');
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .dark .page-content table th {
        color: theme('colors.white');
        background: theme('colors.secondary.800');
    }

    .page-content table tr:last-child td { border-bottom: 0; }

    .page-content table tr:hover td {
        background: theme('colors.secondary.50');
    }
    .dark .page-content table tr:hover td {
        background: rgba(255,255,255,0.02);
    }

    .page-content blockquote {
        border-left: 3px solid theme('colors.primary.500');
        padding: 1rem 1.25rem;
        margin: 1.5rem 0;
        background: theme('colors.primary.50');
        border-radius: 0 0.5rem 0.5rem 0;
        font-style: italic;
        color: theme('colors.secondary.600');
    }
    .dark .page-content blockquote {
        background: rgba(232, 155, 45, 0.05);
        color: theme('colors.secondary.400');
    }

    .page-content hr {
        border: 0;
        border-top: 1px solid theme('colors.secondary.200');
        margin: 2rem 0;
    }
    .dark .page-content hr { border-top-color: theme('colors.secondary.700'); }

    .page-content code {
        font-size: 0.875em;
        background: theme('colors.secondary.100');
        padding: 0.15rem 0.4rem;
        border-radius: 0.25rem;
        font-family: ui-monospace, monospace;
    }
    .dark .page-content code { background: rgba(255,255,255,0.1); }

    .page-content pre {
        background: theme('colors.secondary.900');
        color: theme('colors.secondary.100');
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .page-content pre code {
        background: none;
        padding: 0;
        color: inherit;
    }

    .page-content em {
        color: theme('colors.secondary.500');
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection
