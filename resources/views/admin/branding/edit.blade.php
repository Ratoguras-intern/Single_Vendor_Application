@extends('admin.layouts.app')

@section('content')
<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Branding Settings</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your brand name, logo, and favicon displayed across the site</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
            <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-400">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
            <div class="flex items-center gap-2 text-sm text-red-700 dark:text-red-400">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                @foreach($errors->all() as $error){{ $error }}@endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Previews --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Current Logo --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Current Logo</h3>

                @if($logoUrl && $logoMeta)
                    <div class="flex flex-col sm:flex-row gap-6">
                        {{-- Preview --}}
                        <div class="shrink-0">
                            <div class="w-full sm:w-48 h-32 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center p-4 overflow-hidden">
                                <img src="{{ $logoUrl }}" alt="Current logo" class="max-h-full max-w-full object-contain">
                            </div>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <dl class="space-y-2 text-sm">
                                <div class="flex gap-2">
                                    <dt class="text-gray-500 dark:text-gray-400 shrink-0">File:</dt>
                                    <dd class="text-gray-800 dark:text-white truncate">{{ $logoMeta['filename'] }}</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="text-gray-500 dark:text-gray-400 shrink-0">Type:</dt>
                                    <dd class="text-gray-800 dark:text-white">{{ strtoupper(pathinfo($logoMeta['filename'], PATHINFO_EXTENSION)) }}</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="text-gray-500 dark:text-gray-400 shrink-0">Size:</dt>
                                    <dd class="text-gray-800 dark:text-white">{{ round($logoMeta['size'] / 1024, 1) }} KB</dd>
                                </div>
                                @if($logoMeta['updated_at'])
                                <div class="flex gap-2">
                                    <dt class="text-gray-500 dark:text-gray-400 shrink-0">Updated:</dt>
                                    <dd class="text-gray-800 dark:text-white">{{ $logoMeta['updated_at']->diffForHumans() }}</dd>
                                </div>
                                @endif
                            </dl>
                            {{-- Remove button --}}
                            <form method="POST" action="{{ route('admin.branding.destroy') }}" class="mt-4" x-data
                                @submit.prevent="$store.confirmModal.open({
                                    title: 'Remove Logo',
                                    message: 'Are you sure you want to remove the current logo? The site will revert to the default text-based brand.',
                                    confirmText: 'Remove Logo',
                                    confirmClass: 'bg-red-600 hover:bg-red-700',
                                    form: $el
                                })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    Remove Logo
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Empty state --}}
                    <div class="flex flex-col items-center justify-center py-10 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-700 flex items-center justify-center mb-3">
                            @php
                                $appName = site_name();
                                $words = explode(' ', $appName);
                                $initials = strtoupper(collect($words)->map(fn($w) => $w[0] ?? '')->take(2)->join(''));
                            @endphp
                            <span class="text-lg font-black text-white">{{ $initials }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No logo uploaded yet</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Upload a logo to replace the default initials</p>
                    </div>
                @endif
            </div>

            {{-- Current Favicon --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Current Favicon</h3>

                @if($faviconUrl)
                    <div class="flex items-center gap-6">
                        <div class="shrink-0 flex items-end gap-3">
                            <div class="h-16 w-16 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center p-2 overflow-hidden">
                                <img src="{{ $faviconUrl }}" alt="Current favicon" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="hidden sm:flex flex-col gap-2">
                                <span class="inline-flex items-center gap-1 rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-1 text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                    <span class="h-3 w-3 rounded-sm bg-white ring-1 ring-gray-300 dark:ring-gray-600 inline-flex items-center justify-center overflow-hidden">
                                        <img src="{{ $faviconUrl }}" alt="" class="h-full w-full object-contain">
                                    </span>
                                    Tab
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-md bg-gray-900 px-2 py-1 text-[10px] font-medium text-gray-300">
                                    <span class="h-3 w-3 rounded-sm bg-white inline-flex items-center justify-center overflow-hidden">
                                        <img src="{{ $faviconUrl }}" alt="" class="h-full w-full object-contain">
                                    </span>
                                    Bookmark
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Shown in browser tabs, bookmarks, and history.</p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Remove it from the Favicon panel on the right if needed.</p>
                        </div>
                    </div>
                @elseif($logoUrl)
                    <div class="flex items-center gap-4 py-2">
                        <span class="h-8 w-8 rounded-md ring-1 ring-gray-300 dark:ring-gray-600 inline-flex items-center justify-center overflow-hidden bg-white shrink-0">
                            <img src="{{ $logoUrl }}" alt="" class="h-full w-full object-contain">
                        </span>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No dedicated favicon — the logo is used as a fallback.</p>
                    </div>
                @else
                    <div class="py-6 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No favicon set</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">The built-in default icon is used</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Controls --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Brand Name --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Brand Name</h3>

                <form method="POST" action="{{ route('admin.branding.update') }}">
                    @csrf
                    @method('PUT')

                    <label for="site_name" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">Display Name</label>
                    <input type="text" id="site_name" name="site_name"
                        value="{{ old('site_name', \site_name()) }}"
                        maxlength="50"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2.5 text-sm text-gray-800 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 transition-colors"
                        placeholder="{{ config('app.name', 'NBK Vertex') }}">
                    @error('site_name')
                        <p class="mt-2 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Shown next to the logo and in page titles. Leave empty to use the default app name.</p>

                    <button type="submit"
                        class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                        Save Name
                    </button>
                </form>
            </div>

            {{-- Logo --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ $logoUrl ? 'Replace Logo' : 'Upload Logo' }}</h3>

                <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data"
                    x-data
                    @media-picker:select.window="if ($event.detail.name === 'logo_media_id' && $event.detail.media.length) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Logo selected — click Save Logo to apply.', type: 'success' } })); }">
                    @csrf
                    @method('PUT')

                    <x-media-picker.picker name="logo_media_id" :preview="$logoUrl" folder="logos"
                        remove-name="remove_logo"
                        help="Choose from the media library or upload a new file." />
                    @error('logo_media_id')
                        <p class="mt-2 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                        {{ $logoUrl ? 'Save Logo' : 'Set Logo' }}
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <ul class="space-y-1 text-xs text-gray-500 dark:text-gray-400">
                        <li>&bull; Max width: 200px</li>
                        <li>&bull; Transparent PNG or SVG</li>
                        <li>&bull; Max file size: 2MB</li>
                    </ul>
                </div>
            </div>

            {{-- Favicon --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ $faviconUrl ? 'Replace Favicon' : 'Upload Favicon' }}</h3>

                <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data"
                    x-data
                    @media-picker:select.window="if ($event.detail.name === 'favicon_media_id' && $event.detail.media.length) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Favicon selected — click Save Favicon to apply.', type: 'success' } })); }">
                    @csrf
                    @method('PUT')

                    <x-media-picker.picker name="favicon_media_id" :preview="$faviconUrl" folder="logos"
                        remove-name="remove_favicon"
                        help="Square image, 32x32 or larger." />
                    @error('favicon_media_id')
                        <p class="mt-2 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                        {{ $faviconUrl ? 'Save Favicon' : 'Set Favicon' }}
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <ul class="space-y-1 text-xs text-gray-500 dark:text-gray-400">
                        <li>&bull; Square, 32x32px minimum</li>
                        <li>&bull; PNG with transparency</li>
                        <li>&bull; Falls back to the logo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
