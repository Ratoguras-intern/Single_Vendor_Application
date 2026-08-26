@props([
    'action' => '',
    'method' => 'GET',
    'hasFilters' => false,
])

<div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="{{ $method }}" class="flex flex-wrap items-end gap-4">
        {{ $slot }}
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Filter
            </button>
            @if($hasFilters)
                <a href="{{ strtok(url()->current(), '?') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>
