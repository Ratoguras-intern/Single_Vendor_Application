@props(['name' => null])

@php
    $markup = $name ? (config("categories.icons.{$name}") ?? '') : '';
@endphp

@if($markup)
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        {{ $attributes->merge(['aria-hidden' => 'true']) }}
    >{!! $markup !!}</svg>
@else
    {!! $slot !!}
@endif
