@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-400 dark:border-primary-500 text-start text-base font-medium text-primary-700 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 focus:outline-none focus:text-primary-800 dark:focus:text-primary-300 focus:bg-primary-100 dark:focus:bg-primary-900/30 focus:border-primary-700 dark:focus:border-primary-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-secondary-600 dark:text-secondary-400 hover:text-secondary-800 dark:hover:text-white hover:bg-secondary-50 dark:hover:bg-white/5 hover:border-secondary-300 dark:hover:border-secondary-600 focus:outline-none focus:text-secondary-800 dark:focus:text-white focus:bg-secondary-50 dark:focus:bg-white/5 focus:border-secondary-300 dark:focus:border-secondary-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
