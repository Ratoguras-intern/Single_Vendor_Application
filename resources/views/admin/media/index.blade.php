@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Media Library', 'url' => null],
    ];

    $folderOptions = collect($folders)->map(fn ($cfg) => $cfg['label'])->put('recent', 'Recent')->put('other', 'Other')->all();
    $currentFolder = request('folder');
@endphp

@section('content')
    <div id="media-library"
        x-data="mediaLibrary({
            indexUrl: '{{ route('admin.media.index') }}',
            storeUrl: '{{ route('admin.media.store') }}',
            importUrl: '{{ route('admin.media.import-url') }}',
            maxSizeKb: {{ (int) config('media.max_size', 2048) }},
            allowedMimes: @json(array_values((array) config('media.allowed_mimes'))),
        })">

        {{-- ==================== A. HEADER ==================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-5">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Media Library</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ number_format($total) }} file{{ $total === 1 ? '' : 's' }} · SVG, PNG, JPG, WebP, AVIF, GIF
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="toggleUrlPanel()"
                    class="btn-secondary justify-center" :class="urlPanelOpen && 'border-brand-500 text-brand-600'">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m9.86-2.813a4.5 4.5 0 0 0-1.242-7.244l-4.5-4.5a4.5 4.5 0 0 0-6.364 6.364L4.34 8.374"/></svg>
                    Import by URL
                </button>
                <button type="button" @click="$refs.fileInput.click()" class="btn-primary justify-center">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                    Upload Files
                </button>
                <input type="file" x-ref="fileInput" class="hidden" multiple
                    accept="{{ implode(',', (array) config('media.allowed_mimes')) }}"
                    @change="queueFiles($event.target.files); $event.target.value = ''">
            </div>
        </div>

        {{-- ==================== B. URL IMPORT PANEL ==================== --}}
        <div x-show="urlPanelOpen" x-cloak x-transition
            class="mb-4 rounded-lg border border-brand-200 bg-brand-50/60 p-4 dark:border-brand-500/30 dark:bg-brand-500/5">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                <div class="relative flex-1 min-w-0">
                    <input type="url" x-model="urlInput" @keydown.enter.prevent="importFromUrl()"
                        placeholder="https://example.com/image.jpg"
                        :disabled="importing"
                        class="input w-full" aria-label="Image URL">
                </div>
                <button type="button" @click="importFromUrl()" :disabled="importing || !urlInput.trim()"
                    class="btn-primary justify-center disabled:opacity-50">
                    <svg x-show="!importing" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    <svg x-show="importing" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="importing ? 'Importing...' : 'Import'"></span>
                </button>
            </div>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Downloads the image from an external URL into your library. Supports JPG, PNG, WebP, AVIF, GIF and SVG.
            </p>
        </div>

        {{-- ==================== C. FILTER BAR ==================== --}}
        <x-admin.filter-bar action="{{ route('admin.media.index') }}" :hasFilters="request()->hasAny(['search','folder','per_page'])">
            <x-admin.filter-search name="search" label="Search" placeholder="Search by file name..." />
            <x-admin.filter-select name="folder" label="Folder" :options="$folderOptions" :value="$currentFolder" allLabel="All Media" minWidth="min-w-[160px]" />
            <x-admin.filter-per-page value="{{ $perPage }}" :options="[12, 24, 48, 96]" />
        </x-admin.filter-bar>

        {{-- ==================== D. DROP ZONE / GRID ==================== --}}
        <div class="relative rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop.prevent="dragOver = false; queueFiles($event.dataTransfer.files)">

            {{-- Drag overlay --}}
            <div x-show="dragOver" x-cloak
                class="absolute inset-0 z-10 m-2 flex items-center justify-center rounded-xl border-2 border-dashed border-brand-500 bg-brand-50/90 dark:bg-brand-500/10 pointer-events-none">
                <p class="text-sm font-medium text-brand-600 dark:text-brand-300">Drop images to upload</p>
            </div>

            <div x-show="uploading" x-cloak
                class="flex items-center justify-center gap-3 border-b border-gray-100 bg-brand-50/50 px-4 py-3 text-sm font-medium text-brand-600 dark:border-gray-800 dark:bg-brand-500/5 dark:text-brand-300">
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Uploading <span x-text="uploadProgressText"></span>...
            </div>

            <div class="p-4 sm:p-5" x-show="!uploading">
                @if ($media->count())
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                        @each('admin.media.partials.grid-item', $media, 'item')
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center px-4 py-16 text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-white/5">
                            <svg class="h-8 w-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M18 6h.008v.008H18V6Zm2.25 12H3.75A1.5 1.5 0 0 1 2.25 16.5v-9A1.5 1.5 0 0 1 3.75 6h16.5a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5Z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">No images found</p>
                        <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                            @if (request()->hasAny(['search', 'folder']))
                                No media matches your current search or folder filter.
                            @else
                                Your media library is empty. Upload an image or paste an image URL to get started.
                            @endif
                        </p>
                        @if (!request()->hasAny(['search', 'folder']))
                            <button type="button" @click="$refs.fileInput.click()"
                                class="btn-secondary mt-4 justify-center">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                Upload Files
                            </button>
                        @endif
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-800">
                {{ $media->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/turbo-script">
    {
        document.addEventListener('alpine:init', () => {
            Alpine.data('mediaLibrary', (config) => ({
                config: config,
                urlPanelOpen: false,
                urlInput: '',
                importing: false,
                dragOver: false,
                uploading: false,
                uploadedCount: 0,
                uploadTotal: 0,

                toggleUrlPanel() {
                    this.urlPanelOpen = !this.urlPanelOpen;
                },

                async importFromUrl() {
                    const url = this.urlInput.trim();
                    if (!url || this.importing) return;
                    this.importing = true;
                    try {
                        const res = await fetch(this.config.importUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            body: JSON.stringify({ url }),
                        });
                        const json = await res.json();
                        if (!res.ok) {
                            this.toast(json.message || 'Failed to import image.', 'error');
                            return;
                        }
                        this.urlInput = '';
                        this.urlPanelOpen = false;
                        this.toast(json.message || 'Image imported.', 'success');
                        this.reload();
                    } catch {
                        this.toast('Network error while importing image.', 'error');
                    } finally {
                        this.importing = false;
                    }
                },

                queueFiles(fileList) {
                    const files = Array.from(fileList || []);
                    if (files.length === 0) return;
                    const valid = files.filter((f) => {
                        const err = this.validateFile(f);
                        if (err) this.toast(`${f.name}: ${err}`, 'error');
                        return !err;
                    });
                    if (valid.length === 0) return;
                    this.uploadAll(valid);
                },

                validateFile(file) {
                    if (!this.config.allowedMimes.includes(file.type)) {
                        return 'unsupported file type.';
                    }
                    if (file.size > this.config.maxSizeKb * 1024) {
                        return `file exceeds ${this.humanSize(this.config.maxSizeKb * 1024)} limit.`;
                    }
                    return null;
                },

                async uploadAll(files) {
                    this.uploading = true;
                    this.uploadedCount = 0;
                    this.uploadTotal = files.length;
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    for (const file of files) {
                        const formData = new FormData();
                        formData.append('files[]', file);
                        try {
                            const res = await fetch(this.config.storeUrl, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                },
                                body: formData,
                            });
                            const json = await res.json();
                            if (!res.ok) {
                                const msg = json.errors?.[0]?.error || json.message || 'Upload failed.';
                                this.toast(`${file.name}: ${msg}`, 'error');
                            } else {
                                this.uploadedCount++;
                                const m = json.data?.[0];
                                if (m?.duplicate) {
                                    this.toast(`"${m.name}" already in library — reused.`, 'info');
                                }
                            }
                        } catch {
                            this.toast(`${file.name}: network error during upload.`, 'error');
                        }
                    }
                    this.uploading = false;
                    if (this.uploadedCount > 0) {
                        this.toast(`${this.uploadedCount} file(s) added to media library.`, 'success');
                        this.reload();
                    }
                },

                copyUrl(url) {
                    const doCopy = async () => {
                        try {
                            await navigator.clipboard.writeText(url);
                            this.toast('Image URL copied.', 'success');
                        } catch {
                            this.toast(url, 'info');
                        }
                    };
                    doCopy();
                },

                askDelete(item) {
                    this.$store.confirmModal.open({
                        title: 'Delete Image',
                        message: `"${item.name}" will be permanently removed from the media library. Forms already using it are not affected.`,
                        confirmText: 'Delete',
                        onConfirm: async () => {
                            try {
                                const res = await fetch(`${this.config.indexUrl}/${item.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                    },
                                });
                                if (!res.ok) throw new Error();
                                this.toast('Image deleted from library.', 'success');
                                this.reload();
                            } catch {
                                this.toast('Could not delete image.', 'error');
                            }
                        },
                    });
                },

                get uploadProgressText() {
                    return this.uploadedCount + ' of ' + this.uploadTotal;
                },

                reload() {
                    if (window.Turbo) {
                        window.Turbo.visit(window.location.href, { action: 'replace' });
                    } else {
                        window.location.reload();
                    }
                },

                humanSize(bytes) {
                    const units = ['B', 'KB', 'MB', 'GB'];
                    let i = 0;
                    while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
                    const n = i === 0 ? bytes : Math.round(bytes * 10) / 10;
                    return n + ' ' + units[i];
                },

                toast(message, type = 'success') {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
                },
            }));
        });
    }
    </script>
@endpush
