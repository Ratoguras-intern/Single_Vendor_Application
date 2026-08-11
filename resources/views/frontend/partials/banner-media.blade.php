@props(['banner', 'overlayBackground' => null, 'imageClass' => ''])

@php
    $fallback = asset('frontend-assets/images/no-image.jpg');
@endphp

@if($banner->image_url)
    <img
        src="{{ $banner->image_url }}"
        alt="{{ $banner->title }}"
        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 hidden md:block {{ $imageClass }}"
        style="{{ $banner->image_css }}"
        loading="lazy"
        onerror="this.onerror=null;this.src='{{ $fallback }}';"
    >
@endif
@if($banner->mobile_image_url)
    <img
        src="{{ $banner->mobile_image_url }}"
        alt="{{ $banner->title }}"
        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 md:hidden {{ $imageClass }}"
        style="{{ $banner->image_css }}"
        loading="lazy"
        onerror="this.onerror=null;this.src='{{ $fallback }}';"
    >
@elseif($banner->image_url)
    <img
        src="{{ $banner->image_url }}"
        alt="{{ $banner->title }}"
        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 md:hidden {{ $imageClass }}"
        style="{{ $banner->image_css }}"
        loading="lazy"
        onerror="this.onerror=null;this.src='{{ $fallback }}';"
    >
@endif
@if(! $banner->image_url && ! $banner->mobile_image_url && $banner->background_style)
    <div class="absolute inset-0" style="{{ $banner->background_style }}"></div>
@endif
@if($overlayBackground)
    <div class="absolute inset-0" style="background: {{ $overlayBackground }};"></div>
@endif
