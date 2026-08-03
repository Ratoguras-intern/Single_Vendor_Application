@extends('layouts.frontend')

@php
    $seoTitle = $category->seo_title ?: $category->name . ' - ' . config('app.name');
    $seoDesc = $category->seo_description ?: $category->description;
@endphp

@section('title', $seoTitle)

@push('styles')
<meta name="description" content="{{ $seoDesc }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDesc }}">
@if($category->banner_url)
<meta property="og:image" content="{{ $category->banner_url }}">
@endif
@endpush

@section('content')
    @livewire('product-listing', ['categorySlug' => $category->slug])
@endsection
