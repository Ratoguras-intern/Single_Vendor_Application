@props([
    'name',
    'label' => null,
    'value' => null,
    'preview' => null,
    'multiple' => false,
    'max' => null,
    'folder' => null,
    'help' => null,
    'removeName' => null,
])

@php
    // Prefill from an existing entity image. `value` is a media table id;
    // `preview` is always a renderable URL (legacy files may have no id).
    $initial = [];
    if ($preview) {
        $initial[] = ['id' => $value ? (int) $value : null, 'url' => $preview];
    }
@endphp

<div x-data="mediaField(@js([
        'name' => $name,
        'multiple' => (bool) $multiple,
        'max' => $max ? (int) $max : null,
        'folder' => $folder,
        'removeName' => $removeName,
        'initial' => $initial,
    ]))"
    x-on:media-picker:select.window="onSelect($event)">

    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif

    {{-- Hidden inputs carrying the selection --}}
    <template x-if="!multiple">
        <input type="hidden" :name="name" :value="items.find(i => i.id)?.id || ''">
    </template>
    <template x-if="multiple">
        <template x-for="item in items" :key="'hid-' + (item.id ?? item.url)">
            <input type="hidden" :name="name + '[]'" :value="item.id">
        </template>
    </template>
    @if($removeName)
        <input type="hidden" name="{{ $removeName }}" :value="removed ? '1' : ''">
    @endif

    {{-- Empty state: Add Image --}}
    <button type="button" x-show="!hasImage" x-on:click="openPicker()"
        class="flex w-full flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50 px-4 py-8 text-gray-500 hover:border-brand-400 hover:text-brand-600 dark:text-gray-400 dark:hover:border-brand-500 dark:hover:text-brand-400 transition-colors">
        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Zm10.5-11.25h.008v.008h-.008V9.75Z"/></svg>
        <span class="text-sm font-medium">{{ $addText ?? 'Add Image' }}</span>
        <span class="text-xs text-gray-400">Choose from the media library or upload from your computer</span>
    </button>

    {{-- Filled state: preview + actions --}}
    <div x-show="hasImage" x-cloak>
        @if($multiple)
            <div class="flex flex-wrap gap-3">
                <template x-for="item in items" :key="'prev-' + (item.id ?? item.url)">
                    <div class="relative group">
                        <div class="h-20 w-20 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <img :src="item.url" alt="" class="h-full w-full object-cover">
                        </div>
                        <button type="button" x-on:click="removeItem(item)"
                            class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow-sm opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity"
                            aria-label="Remove image">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" x-show="!max || items.length < max" x-on:click="openPicker()"
                    class="flex h-20 w-20 flex-col items-center justify-center gap-1 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-400 hover:border-brand-400 hover:text-brand-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span class="text-[10px] font-medium">Add</span>
                </button>
            </div>
            <div class="mt-2 flex items-center gap-3">
                <button type="button" x-on:click="removeAll()" class="text-xs font-medium text-red-500 hover:text-red-600">Remove all</button>
                <span class="text-xs text-gray-400" x-text="(items.length || '0') + ((max) ? ' / ' + max : '')"></span>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-3">
                <div class="mx-auto flex max-h-44 min-h-28 items-center justify-center overflow-hidden rounded-lg bg-white dark:bg-gray-900 p-2"
                    :class="{{ json_encode(($square ?? false) ? 'aspect-square w-40' : 'w-full') }}">
                    <img :src="items.find(i => i.url)?.url" alt="" class="max-h-full max-w-full object-contain">
                </div>
                <div class="mt-3 flex items-center justify-center gap-4">
                    <button type="button" x-on:click="openPicker()"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:border-brand-400 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Change Image
                    </button>
                    <button type="button" x-on:click="removeAll()"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-600">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        Remove
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if($help)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
