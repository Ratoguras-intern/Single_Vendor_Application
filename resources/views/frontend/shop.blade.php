@extends('layouts.frontend')

@php
    $seoTitle = 'Shop - ' . config('app.name');
    $seoDesc = 'Browse our complete collection of premium products.';
@endphp

@section('title', $seoTitle)

@push('styles')
<meta name="description" content="{{ $seoDesc }}">
@endpush

@section('content')
    @livewire('product-listing')
@endsection
