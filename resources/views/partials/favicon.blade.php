@php
    $faviconPath = \App\Models\Setting::get('site_favicon') ?? \App\Models\Setting::get('site_logo');
    $hasFavicon = $faviconPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($faviconPath);
@endphp

@if($hasFavicon)
    <link rel="icon" type="image/png" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($faviconPath) }}">
    <link rel="apple-touch-icon" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($faviconPath) }}">
@else
    <link rel="icon" href="{{ asset('favicon.ico') }}">
@endif
