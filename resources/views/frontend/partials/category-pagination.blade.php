@props(['paginator', 'ajax' => false])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-2" role="navigation" aria-label="Pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-300 dark:text-secondary-600 cursor-not-allowed" aria-disabled="true">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" @if($ajax) @click.prevent="$dispatch('shop:apply', { url: '{{ $paginator->previousPageUrl() }}' })" @endif class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white transition-colors" rel="prev" aria-label="Previous page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $pages = [];
            if ($lastPage <= 7) {
                $pages = range(1, $lastPage);
            } else {
                $pages[] = 1;
                if ($currentPage > 3) {
                    $pages[] = '...';
                }
                $start = max(2, $currentPage - 1);
                $end = min($lastPage - 1, $currentPage + 1);
                foreach (range($start, $end) as $p) {
                    $pages[] = $p;
                }
                if ($currentPage < $lastPage - 2) {
                    $pages[] = '...';
                }
                $pages[] = $lastPage;
            }
        @endphp

        @foreach($pages as $page)
            @if($page === '...')
                <span class="inline-flex items-center justify-center w-10 h-10 text-sm text-secondary-400 dark:text-secondary-500">...</span>
            @elseif($page === $currentPage)
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-btn bg-primary-500 text-white font-medium text-sm shadow-sm" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" @if($ajax) @click.prevent="$dispatch('shop:apply', { url: '{{ $paginator->url($page) }}' })" @endif class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white transition-colors text-sm" aria-label="Page {{ $page }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" @if($ajax) @click.prevent="$dispatch('shop:apply', { url: '{{ $paginator->nextPageUrl() }}' })" @endif class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white transition-colors" rel="next" aria-label="Next page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-btn border border-secondary-200 dark:border-secondary-700 text-secondary-300 dark:text-secondary-600 cursor-not-allowed" aria-disabled="true">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </span>
        @endif
    </nav>
@endif
