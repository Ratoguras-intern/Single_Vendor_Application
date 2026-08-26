@props([
    'value' => null,
    'options' => [10, 25, 50, 100, 'all'],
])

@php
    $currentValue = $value ?? request('per_page', '25');
@endphp

<div {{ $attributes->merge(['class' => 'min-w-0 w-full sm:w-auto sm:min-w-[130px]']) }}>
    <label for="per_page" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Show</label>
    <select
        name="per_page"
        id="per_page"
        onchange="var f=this.form; if(f) liveFilter(f)"
        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
    >
        @foreach($options as $option)
            <option value="{{ $option }}" {{ $currentValue == $option ? 'selected' : '' }}>
                {{ $option === 'all' ? 'All' : $option . ' / page' }}
            </option>
        @endforeach
    </select>
</div>
