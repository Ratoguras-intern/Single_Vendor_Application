@props(['item'])

@php
    $thumb = $item->fileExists() ? $item->thumbUrl() : null;
    $url = $item->fileExists() ? $item->url() : null;
    $dims = ($item->width || $item->height)
        ? trim(($item->width ?: '').' × '.($item->height ?: ''), ' ×')
        : null;
@endphp

<div class="group relative">
    <div class="relative aspect-square w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03]">
        <img src="{{ $thumb ?: $url ?: '' }}" alt="{{ $item->name ?: 'Media' }}"
            class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-105"
            loading="lazy"
            @error="$el.style.display='none'">

        @if ($item->mime_type === 'image/svg+xml' && !$thumb)
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 8-9 12-9-12 9 4 9-4Z"/></svg>
            </div>
        @endif

        <div class="absolute inset-0 flex items-center justify-center gap-1.5 bg-gray-900/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-150 group-hover:opacity-100">
            @if ($url)
                <button type="button"
                    @click.prevent.stop="$parent.copyUrl('{{ $url }}')"
                    title="Copy URL"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-700 transition-colors hover:bg-brand-500 hover:text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </button>
            @endif
            <button type="button"
                @click.prevent.stop="$parent.askDelete(@json(['id' => $item->id, 'name' => $item->original_name]))"
                title="Delete from library"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-700 transition-colors hover:bg-red-500 hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
            </button>
        </div>
    </div>

    <div class="mt-1.5 space-y-0.5 px-0.5">
        <p class="truncate text-xs font-medium text-gray-700 dark:text-gray-300" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
        <p class="text-[11px] text-gray-400 dark:text-gray-500">
            {{ $item->humanSize() }}@if ($dims) <span class="mx-1 text-gray-300 dark:text-gray-600">·</span> {{ $dims }} @endif
        </p>
    </div>
</div>
