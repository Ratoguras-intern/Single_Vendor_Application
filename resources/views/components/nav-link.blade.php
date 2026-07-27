@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'relative inline-flex items-center px-3 py-2 rounded-btn text-sm font-medium transition-colors duration-150 ' . ($active ? 'text-primary-600 bg-primary-50 dark:text-primary-400 dark:bg-primary-500/10' : 'text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 dark:text-secondary-400 dark:hover:text-white dark:hover:bg-white/5')]) }}>
    {{ $slot }}
    @if($active)
        <span class="absolute bottom-0.5 left-3 right-3 h-0.5 bg-primary-500 rounded-full"></span>
    @endif
</a>
