@extends('layouts.frontend')

@section('title', 'Home - NBK Vertex')

@php
    $sortedSections = $sections->sortBy('sort_order');
@endphp

@section('content')
@foreach($sortedSections as $section)
    @php
        $slug = $section->slug;
    @endphp

    @if($slug === 'hero-carousel')
        @include('frontend.home.hero-carousel')
    @elseif($slug === 'trust-bar')
        @include('frontend.home.trust-bar')
        @include('frontend.partials.banner-promotional')
    @elseif($slug === 'shop-by-category')
        @include('frontend.home.shop-by-category')
    @elseif($slug === 'featured-products')
        @include('frontend.home.featured-products')
        @include('frontend.partials.banner-featured-section')
    @elseif($slug === 'new-arrivals')
        @include('frontend.home.new-arrivals')
    @elseif($slug === 'trending-products')
        @include('frontend.home.trending-products')
    @elseif($slug === 'flash-sale')
        @include('frontend.home.flash-sale')
    @elseif($slug === 'best-sellers')
        @include('frontend.home.best-sellers')
    @elseif($slug === 'recommended-products')
        @include('frontend.home.recommended-products')
    @elseif($slug === 'popular-products')
        @include('frontend.home.popular-products')
        @include('frontend.partials.banner-middle')
    @elseif($slug === 'top-brands')
        @include('frontend.home.top-brands')
    @elseif($slug === 'why-choose-us')
        @include('frontend.home.why-choose-us')
    @elseif($slug === 'testimonials')
        @include('frontend.home.testimonials')
    @elseif($slug === 'newsletter-cta')
        @include('frontend.home.newsletter-cta')
    @elseif($slug === 'instagram-gallery')
        @include('frontend.home.instagram-gallery')
    @endif
@endforeach

@include('frontend.partials.banner-bottom')
@endsection
