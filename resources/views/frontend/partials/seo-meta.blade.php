@props([
    'title',
    'description' => null,
    'canonical' => null,
    'image' => null,
])

@push('meta')
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif

    @if ($canonical)
        <link rel="canonical" href="{{ $canonical }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ site_name() }}">
    <meta property="og:title" content="{{ $title }}">
    @if ($description)
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    @if ($image)
        <meta property="og:image" content="{{ $image }}">
    @endif
    <meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $title }}">
    @if ($description)
        <meta name="twitter:description" content="{{ $description }}">
    @endif
    @if ($image)
        <meta name="twitter:image" content="{{ $image }}">
    @endif
@endpush
