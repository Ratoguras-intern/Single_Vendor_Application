@props([
    'items' => [],          // [['label' => 'Customer Care', 'url' => null], ['label' => 'Shipping Info']]
    'variant' => 'light',   // light (on dark hero) | dark (on page background)
])

<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="flex flex-wrap items-center gap-1.5 text-xs sm:text-sm {{ $variant === 'light'
        ? 'text-secondary-300 dark:text-secondary-400'
        : 'text-secondary-500 dark:text-secondary-400' }}">
        <li class="flex items-center gap-1.5">
            <a href="{{ route('frontend.home') }}" class="transition-colors hover:underline underline-offset-2
                {{ $variant === 'light' ? 'hover:text-white' : 'hover:text-primary-600 dark:hover:text-primary-400' }}">
                Home
            </a>
        </li>
        @foreach ($items as $item)
            <li class="flex items-center gap-1.5" aria-hidden="true">
                <svg class="h-3.5 w-3.5 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                @if (!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="transition-colors hover:underline underline-offset-2
                        {{ $variant === 'light' ? 'hover:text-white' : 'hover:text-primary-600 dark:hover:text-primary-400' }}">{{ $item['label'] }}</a>
                @else
                    <span class="{{ $variant === 'light'
                        ? 'font-medium text-white'
                        : 'font-medium text-secondary-800 dark:text-secondary-200' }}"
                        aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
