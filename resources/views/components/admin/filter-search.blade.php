@props([
    'name' => 'search',
    'label' => 'Search',
    'placeholder' => 'Search...',
    'value' => null,
])

@php
    $currentValue = $value ?? request($name);
@endphp

<div {{ $attributes->merge(['class' => 'flex-1 min-w-0 w-full sm:w-auto sm:min-w-[200px]']) }}>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <input
        type="text"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $currentValue }}"
        placeholder="{{ $placeholder }}"
        oninput="var f=this.form,t; if(f){ clearTimeout(f._lt); f._lt=setTimeout(function(){ liveFilter(f); },400); }"
        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
    />
</div>
