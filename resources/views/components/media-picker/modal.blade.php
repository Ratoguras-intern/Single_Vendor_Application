@props([
    'indexUrl' => route('admin.media.index'),
    'storeUrl' => route('admin.media.store'),
])

{{-- ─────────────────────────────────────────────────────────────────────────
    Media Library Modal (global singleton)

    Mount once per page (already mounted in admin/layouts/app.blade.php).
    Any field opens it via the `media-picker:open` window event:

        window.dispatchEvent(new CustomEvent('media-picker:open', {
            detail: {
                name: 'logo_media_id',   // target field identifier
                multiple: false,          // allow multi-select
                max: null,                // max selections (multiple mode)
                folder: 'logos',          // default folder key (config: media.folders)
                preselect: [12],          // media ids to mark selected on open
            }
        }))

    It answers with `media-picker:select` carrying `{ name, media: [...] }`
    where every media item is { id, path, url, thumb_url, name, ... }.
───────────────────────────────────────────────────────────────────────── --}}
<div
    x-data="mediaPicker()"
    x-init="init()"
    x-on:media-picker:open.window="openWith($event.detail)"
    x-on:keydown.escape.window="close()"
    x-cloak
    data-index-url="{{ $indexUrl }}"
    data-store-url="{{ $storeUrl }}"
    data-max-size-kb="{{ (int) config('media.max_size', 2048) }}"
    data-allowed-mimes="{{ json_encode(array_values((array) config('media.allowed_mimes'))) }}"
    data-folders="{{ json_encode(array_map(fn ($key, $cfg) => ['key' => $key, 'label' => $cfg['label']], array_keys((array) config('media.folders', [])), (array) config('media.folders', []))) }}"
    role="dialog"
    aria-modal="true"
    aria-label="Media library"
    class="fixed inset-0 z-[100000]"
    x-show="open"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-secondary-950/60 backdrop-blur-sm"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="close()"
    ></div>

    {{-- Dialog panel --}}
    <div class="absolute inset-0 flex items-center justify-center p-0 sm:p-6 pointer-events-none">
        <div
            class="pointer-events-auto relative w-full sm:max-w-5xl h-full sm:h-[min(85vh,780px)] bg-white dark:bg-secondary-900 sm:rounded-2xl border border-secondary-200 dark:border-secondary-700 shadow-2xl flex flex-col overflow-hidden"
            x-show="open"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        >
            {{-- ─── Header ─── --}}
            <div class="shrink-0 border-b border-secondary-200 dark:border-secondary-700 px-4 sm:px-6 py-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-secondary-900 dark:text-white">Media Library</h2>
                        <p class="text-xs text-secondary-400 mt-0.5">Manage and select images for your website</p>
                    </div>
                    <button type="button" x-on:click="close()" x-ref="closeBtn"
                        class="p-1.5 rounded-lg text-secondary-400 hover:text-secondary-700 hover:bg-secondary-100 dark:hover:text-white dark:hover:bg-white/10 transition-colors"
                        aria-label="Close media library">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- ─── Body: sidebar + main ─── --}}
            <div class="flex-1 min-h-0 flex">
                {{-- Folder sidebar (desktop) --}}
                <aside class="hidden md:flex w-48 lg:w-52 shrink-0 flex-col gap-0.5 overflow-y-auto border-r border-secondary-200 dark:border-secondary-700 p-3">
                    <template x-for="f in sidebarFolders" :key="f.key">
                        <button type="button" x-on:click="setFolder(f.key)"
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-left transition-colors"
                            :class="folder === f.key
                                ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400'
                                : 'text-secondary-600 hover:bg-secondary-100 hover:text-secondary-900 dark:text-secondary-400 dark:hover:bg-white/5 dark:hover:text-white'">
                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>
                            <span x-text="f.label"></span>
                        </button>
                    </template>
                </aside>

                {{-- Main column --}}
                <div class="flex-1 min-w-0 flex flex-col">
                    {{-- Toolbar --}}
                    <div class="shrink-0 px-4 sm:px-6 py-3 border-b border-secondary-200 dark:border-secondary-700">
                        <div class="flex items-center gap-2.5">
                            {{-- Mobile folder selector --}}
                            <select x-model="folder" x-on:change="loadMedia(true)"
                                class="md:hidden shrink-0 rounded-lg border border-secondary-300 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-800 px-2.5 py-2 text-sm text-secondary-800 dark:text-white focus:border-primary-400 focus:outline-none"
                                aria-label="Choose folder">
                                <template x-for="f in sidebarFolders" :key="'m-'+f.key">
                                    <option :value="f.key" x-text="f.label" :selected="folder === f.key"></option>
                                </template>
                            </select>

                            {{-- Search --}}
                            <div class="relative flex-1 min-w-0">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-secondary-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/></svg>
                                <input type="search" x-model="search" x-on:input.debounce.350ms="loadMedia(true)"
                                    placeholder="Search media..."
                                    class="w-full pl-10 pr-4 py-2 rounded-xl border border-secondary-200 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-800 text-sm text-secondary-900 dark:text-white placeholder:text-secondary-400 focus:border-primary-400 focus:ring-2 focus:ring-primary-500/10 transition-all"
                                    aria-label="Search media">
                            </div>

                            {{-- Upload button --}}
                            <button type="button" x-on:click="$refs.fileInput.click()"
                                class="shrink-0 inline-flex items-center gap-2 rounded-lg bg-primary-600 px-3 sm:px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                <span class="hidden sm:inline">Upload Files</span>
                                <input type="file" x-ref="fileInput" class="hidden" multiple
                                    accept="{{ implode(',', (array) config('media.allowed_mimes')) }}"
                                    x-on:change="addFiles($event.target.files); $event.target.value = ''">
                            </button>
                        </div>
                        <p class="mt-1.5 text-[11px] text-secondary-400 hidden sm:block">
                            Current folder: <span class="font-medium text-secondary-500 dark:text-secondary-300" x-text="activeFolderLabel"></span> · drop images anywhere in the grid to upload into it
                        </p>
                    </div>

                    {{-- Grid area (also the drop zone) --}}
                    <div class="flex-1 min-h-0 overflow-y-auto relative p-4 sm:p-6"
                        x-on:dragover.prevent="dragOver = true"
                        x-on:dragleave.prevent="dragOver = false"
                        x-on:drop.prevent="dragOver = false; addFiles($event.dataTransfer.files)">

                        {{-- Drag overlay --}}
                        <div x-show="dragOver" x-cloak
                            class="absolute inset-3 z-10 flex items-center justify-center rounded-2xl border-2 border-dashed border-primary-500 bg-primary-50/90 dark:bg-primary-500/10 pointer-events-none">
                            <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Drop images to upload to <span x-text="activeFolderLabel"></span></p>
                        </div>

                        {{-- Loading skeleton --}}
                        <div x-show="loading" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                            <template x-for="i in 10" :key="'sk'+i">
                                <div class="aspect-square rounded-xl bg-secondary-100 dark:bg-secondary-800 animate-pulse"></div>
                            </template>
                        </div>

                        {{-- Empty state --}}
                        <div x-show="!loading && items.length === 0" class="py-16 text-center">
                            <div class="mx-auto h-14 w-14 rounded-2xl bg-secondary-100 dark:bg-secondary-800 flex items-center justify-center mb-3">
                                <svg class="h-7 w-7 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M18 6h.008v.008H18V6Zm2.25 12H3.75A1.5 1.5 0 0 1 2.25 16.5v-9A1.5 1.5 0 0 1 3.75 6h16.5a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5Z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-secondary-700 dark:text-secondary-300">No images found</p>
                            <p class="text-xs text-secondary-400 mt-1">Upload an image or try a different search term.</p>
                        </div>

                        {{-- Media grid --}}
                        <div x-show="!loading && items.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                            <template x-for="item in items" :key="item.id">
                                <div class="group relative">
                                    <button type="button"
                                        x-on:click="toggle(item)"
                                        class="block w-full aspect-square rounded-xl overflow-hidden border-2 transition-all duration-150 bg-secondary-50 dark:bg-secondary-800"
                                        :class="isSelected(item)
                                            ? 'border-primary-500 ring-2 ring-primary-500/30'
                                            : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-600'">
                                        <img :src="item.thumb_url || item.url || ''" :alt="item.name"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                            x-on:error="$el.style.display = 'none'">
                                    </button>

                                    {{-- Selection badge --}}
                                    <div x-show="isSelected(item)"
                                        class="absolute top-2 left-2 h-5 w-5 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-sm">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </div>

                                    {{-- Delete action --}}
                                    <button type="button"
                                        x-on:click="confirmDelete(item)"
                                        class="absolute top-2 right-2 p-1.5 rounded-lg bg-white/90 dark:bg-secondary-900/90 text-secondary-400 opacity-0 group-hover:opacity-100 focus:opacity-100 hover:text-red-600 transition-all"
                                        :aria-label="'Delete ' + item.name"
                                        title="Delete from library">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>

                                    {{-- Meta --}}
                                    <div class="mt-1.5 px-0.5">
                                        <p class="text-xs font-medium text-secondary-700 dark:text-secondary-300 truncate" x-text="item.name" :title="item.name"></p>
                                        <p class="text-[11px] text-secondary-400" x-text="item.size_human"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Load more --}}
                        <div x-show="!loading && page < lastPage" class="mt-6 text-center">
                            <button type="button" x-on:click="loadMore()" :disabled="loadingMore"
                                class="inline-flex items-center gap-2 rounded-lg border border-secondary-300 dark:border-secondary-600 px-4 py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors disabled:opacity-50">
                                <svg x-show="loadingMore" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                <span x-text="loadingMore ? 'Loading...' : 'Load more'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── Upload queue (floating panel) ─── --}}
            <div x-show="queue.length > 0" x-cloak
                class="absolute bottom-20 right-4 sm:right-6 z-10 w-72 max-w-[calc(100%-2rem)] rounded-xl border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 shadow-xl p-3 space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500 dark:text-secondary-400">
                        Uploading to <span x-text="activeFolderLabel"></span>
                    </p>
                    <span x-show="uploading" class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-secondary-300 border-t-primary-500"></span>
                </div>
                <template x-for="q in queue" :key="q.id">
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 shrink-0 rounded-lg overflow-hidden bg-secondary-100 dark:bg-secondary-700">
                            <img :src="q.preview" alt="" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-secondary-800 dark:text-secondary-200 truncate" x-text="q.name"></p>
                            <div x-show="q.status === 'uploading'" class="mt-1 h-1 w-full rounded-full bg-secondary-100 dark:bg-secondary-700 overflow-hidden">
                                <div class="h-full bg-primary-500 transition-all" :style="'width:' + q.progress + '%'"></div>
                            </div>
                            <p x-show="q.status === 'error'" class="text-[11px] text-red-500 truncate" x-text="q.error"></p>
                            <p x-show="q.status === 'done'" class="text-[11px] text-emerald-600 dark:text-emerald-400">Added</p>
                        </div>
                        <button type="button" x-on:click="removeQueued(q)" :disabled="q.status === 'uploading'"
                            class="p-1 rounded text-secondary-400 hover:text-red-600 disabled:opacity-40" aria-label="Remove file">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- ─── Footer ─── --}}
            <div class="shrink-0 border-t border-secondary-200 dark:border-secondary-700 px-4 sm:px-6 py-3.5 bg-white dark:bg-secondary-900">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 truncate">
                        <template x-if="selected.length > 0">
                            <span>
                                Selected:
                                <span class="font-medium text-secondary-800 dark:text-secondary-200" x-text="selected[0].name"></span>
                                <span x-show="selected.length > 1" class="text-secondary-400">+<span x-text="selected.length - 1"></span> more</span>
                            </span>
                        </template>
                        <template x-if="selected.length === 0">
                            <span>No image selected</span>
                        </template>
                    </p>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <button type="button" x-on:click="close()"
                            class="rounded-lg border border-secondary-300 dark:border-secondary-600 px-4 py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                            Cancel
                        </button>
                        <button type="button" x-on:click="confirmSelection()" :disabled="selected.length === 0"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="multiple ? 'Use Selected Images' : 'Use Selected Image'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mediaPicker() {
    return {
        open: false,
        search: '',
        items: [],
        page: 1,
        lastPage: 1,
        loading: false,
        loadingMore: false,

        // folders
        folders: [],
        folder: 'all',

        // selection contract
        targetName: null,
        multiple: false,
        max: null,
        preselect: [],
        selected: [],

        // uploads
        queue: [],
        uploading: false,
        dragOver: false,

        init() {
            this.indexUrl = this.$el.dataset.indexUrl;
            this.storeUrl = this.$el.dataset.storeUrl;
            this.maxSizeKb = parseInt(this.$el.dataset.maxSizeKb, 10) || 2048;
            this.allowedMimes = JSON.parse(this.$el.dataset.allowedMimes || '[]');
            this.folders = JSON.parse(this.$el.dataset.folders || '[]');
        },

        get sidebarFolders() {
            return [
                { key: 'all', label: 'All Media' },
                { key: 'recent', label: 'Recent' },
                ...this.folders,
                { key: 'other', label: 'Other' },
            ];
        },

        get activeFolderLabel() {
            return this.sidebarFolders.find((f) => f.key === this.folder)?.label || 'All Media';
        },

        openWith(detail) {
            this.targetName = detail.name || null;
            this.multiple = !!detail.multiple;
            this.max = detail.max || null;
            this.preselect = Array.isArray(detail.preselect) ? detail.preselect.filter(Boolean) : [];
            this.folder = detail.folder && this.sidebarFolders.some((f) => f.key === detail.folder)
                ? detail.folder
                : 'all';
            this.selected = [];
            this.queue = [];
            this.search = '';
            this.open = true;
            document.body.classList.add('overflow-hidden');
            this.loadMedia(true);
            this.$nextTick(() => this.$refs.closeBtn?.focus());
        },

        close() {
            if (!this.open) return;
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },

        setFolder(key) {
            if (this.folder === key) return;
            this.folder = key;
            this.loadMedia(true);
        },

        async loadMedia(reset = false) {
            if (reset) {
                this.loading = true;
                this.page = 1;
            }
            try {
                const params = new URLSearchParams({ page: this.page });
                if (this.search) params.set('search', this.search);
                if (this.folder && this.folder !== 'all') params.set('folder', this.folder);
                const res = await fetch(`${this.indexUrl}?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                });
                const json = await res.json();
                this.items = reset ? json.data : [...this.items, ...json.data];
                this.lastPage = json.last_page;

                if (reset && this.preselect.length > 0) {
                    const wanted = new Set(this.preselect);
                    this.items
                        .filter((i) => wanted.has(i.id))
                        .slice(0, this.multiple ? (this.max || Infinity) : 1)
                        .forEach((i) => { if (!this.isSelected(i)) this.selected.push(i); });
                    this.preselect = [];
                }
            } catch {
                this.toast('Could not load media library.', 'error');
            } finally {
                this.loading = false;
                this.loadingMore = false;
            }
        },

        loadMore() {
            if (this.page >= this.lastPage) return;
            this.page++;
            this.loadingMore = true;
            this.loadMedia(false);
        },

        isSelected(item) {
            return this.selected.some((s) => s.id === item.id);
        },

        toggle(item) {
            if (this.isSelected(item)) {
                this.selected = this.selected.filter((s) => s.id !== item.id);
                return;
            }
            if (!this.multiple) {
                this.selected = [item];
                return;
            }
            if (this.max && this.selected.length >= this.max) {
                this.toast(`You can select up to ${this.max} images.`, 'warning');
                return;
            }
            this.selected.push(item);
        },

        confirmSelection() {
            if (this.selected.length === 0) return;
            window.dispatchEvent(new CustomEvent('media-picker:select', {
                detail: { name: this.targetName, media: [...this.selected] },
            }));
            this.close();
        },

        // ── Uploads ────────────────────────────────────────────────
        addFiles(fileList) {
            const files = Array.from(fileList || []);
            let queued = 0;
            files.forEach((file) => {
                const error = this.validateFile(file);
                if (error) {
                    this.toast(`${file.name}: ${error}`, 'error');
                    return;
                }
                this.queue.push({
                    id: Date.now() + Math.random(),
                    file,
                    name: file.name,
                    sizeHuman: this.humanSize(file.size),
                    preview: URL.createObjectURL(file),
                    status: 'ready',
                    progress: 0,
                    error: '',
                });
                queued++;
            });
            if (queued > 0) this.uploadAll();
        },

        validateFile(file) {
            if (!this.allowedMimes.includes(file.type)) {
                return 'unsupported file type.';
            }
            if (file.size > this.maxSizeKb * 1024) {
                return `file exceeds ${this.humanSize(this.maxSizeKb * 1024)} limit.`;
            }
            return null;
        },

        removeQueued(q) {
            URL.revokeObjectURL(q.preview);
            this.queue = this.queue.filter((x) => x.id !== q.id);
        },

        uploadAll() {
            const pending = this.queue.filter((q) => q.status === 'ready' || q.status === 'error');
            if (pending.length === 0) return;

            this.uploading = true;
            let chain = Promise.resolve();
            pending.forEach((q) => {
                chain = chain.then(() => this.uploadOne(q));
            });
            chain.then(() => {
                this.uploading = false;

                const failed = this.queue.filter((q) => q.status === 'error').length;
                const done = this.queue.filter((q) => q.status === 'done');

                if (done.length > 0) {
                    this.queue = this.queue.filter((q) => q.status !== 'done');
                    this.search = '';
                    this.loadMedia(true).then(() => {
                        // Auto-select freshly uploaded images (respect limits).
                        done.forEach((uploaded) => {
                            const item = this.items.find((i) => i.id === uploaded.serverId);
                            if (item) this.toggle(item);
                        });
                    });
                }

                if (failed > 0) {
                    this.toast(`${failed} file(s) failed to upload.`, 'error');
                }
            });
        },

        uploadOne(q) {
            return new Promise((resolve) => {
                q.status = 'uploading';
                q.progress = 0;

                const formData = new FormData();
                formData.append('files[]', q.file);
                if (this.folder && this.folder !== 'all' && this.folder !== 'recent' && this.folder !== 'other') {
                    formData.append('folder', this.folder);
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', this.storeUrl);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content || '');

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        q.progress = Math.round((e.loaded / e.total) * 100);
                    }
                });

                xhr.addEventListener('load', () => {
                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (xhr.status >= 200 && xhr.status < 300 && json.data?.length) {
                            const media = json.data[0];
                            q.status = 'done';
                            q.serverId = media.id;
                            if (!media.duplicate) {
                                this.toast(`"${media.name}" added to ${this.activeFolderLabel}.`, 'success');
                            } else {
                                this.toast(`"${media.name}" already exists in library — reused.`, 'info');
                            }
                        } else {
                            q.status = 'error';
                            q.error = json.errors?.[0]?.error || json.message || 'Upload failed.';
                        }
                    } catch {
                        q.status = 'error';
                        q.error = 'Unexpected server response.';
                    }
                    resolve();
                });

                xhr.addEventListener('error', () => {
                    q.status = 'error';
                    q.error = 'Network error during upload.';
                    resolve();
                });

                xhr.send(formData);
            });
        },

        // ── Deletion ───────────────────────────────────────────────
        confirmDelete(item) {
            this.$store.confirmModal.open({
                title: 'Delete Image',
                message: `"${item.name}" will be permanently removed from the media library. Forms already using it are not affected.`,
                confirmText: 'Delete',
                onConfirm: async () => {
                    try {
                        const res = await fetch(`${this.indexUrl}/${item.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                        });
                        if (!res.ok) throw new Error();
                        this.items = this.items.filter((i) => i.id !== item.id);
                        this.selected = this.selected.filter((s) => s.id !== item.id);
                        this.toast('Image deleted from library.', 'success');
                    } catch {
                        this.toast('Could not delete image.', 'error');
                    }
                },
            });
        },

        // ── Helpers ────────────────────────────────────────────────
        humanSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
            return `${round(bytes)} ${units[i]}`;

            function round(n) { return i === 0 ? n : Math.round(n * 10) / 10; }
        },

        toast(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
        },
    };
}

/**
 * Form field bound to the Media Library. Renders a preview with
 * Add / Change / Remove actions and hidden inputs holding the selected
 * media id(s). No native file input — everything flows through the modal.
 *
 * Config keys: name, multiple, max, folder, removeName,
 *              initial: [{id, url}] (prefill from the server side).
 */
function mediaField(config) {
    return {
        name: config.name,
        multiple: !!config.multiple,
        max: config.max || null,
        folder: config.folder || null,
        removeName: config.removeName || null,
        items: (config.initial || []).filter((i) => i && (i.url || i.id)),
        removed: false,

        get hasImage() {
            return this.items.some((i) => i.url);
        },

        openPicker() {
            window.dispatchEvent(new CustomEvent('media-picker:open', {
                detail: {
                    name: this.name,
                    multiple: this.multiple,
                    max: this.max,
                    folder: this.folder,
                    preselect: this.items.map((i) => i.id).filter(Boolean),
                },
            }));
        },

        onSelect(event) {
            if (event.detail.name !== this.name) return;
            this.items = event.detail.media.map((m) => ({ id: m.id, url: m.thumb_url || m.url, name: m.name }));
            this.removed = false;
        },

        removeItem(item) {
            this.items = this.items.filter((i) => i !== item);
            if (this.items.length === 0) this.removed = true;
        },

        removeAll() {
            this.items = [];
            this.removed = true;
        },
    };
}
</script>
