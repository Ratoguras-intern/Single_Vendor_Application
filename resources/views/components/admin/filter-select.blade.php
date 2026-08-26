@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'allLabel' => 'All',
    'minWidth' => 'min-w-[150px]',
])

@php
    $currentValue = $value ?? request($name);
@endphp

<div {{ $attributes->merge(['class' => "min-w-0 w-full sm:w-auto sm:{$minWidth}"]) }}>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        onchange="var f=this.form; if(f) liveFilter(f)"
        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
    >
        <option value="">{{ $allLabel }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ $currentValue == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
