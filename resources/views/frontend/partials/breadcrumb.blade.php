@php
    $backUrl = $backUrl ?? route('frontend.home');
    $backLabel = $backLabel ?? 'Back';
@endphp

<nav class="mb-8">
    <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm font-medium transition-colors hover:text-gray-900" style="color: var(--bloom-muted-foreground);">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        <span data-i18n="{{ $backLabel }}" x-text="$store.i18n.t('{{ $backLabel }}')">{{ $backLabel }}</span>
    </a>
</nav>
