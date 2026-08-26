@props([
    'mode' => 'single',
    'label' => 'Date',
    'startName' => 'date_from',
    'endName' => 'date_to',
    'name' => null,
    'value' => null,
])

@if($mode === 'range')
    <div {{ $attributes->merge(['class' => 'min-w-0 w-full sm:min-w-[270px] sm:w-[270px]']) }}>
        <x-date-picker
            mode="range"
            :label="$label"
            :start-name="$startName"
            :end-name="$endName"
            :value="trim(request($startName) . ',' . request($endName), ',')"
        />
    </div>
@else
    <div {{ $attributes->merge(['class' => 'min-w-0 w-full sm:w-auto sm:min-w-[160px]']) }}>
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
        <input
            type="month"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value ?? request($name) }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        />
    </div>
@endif
