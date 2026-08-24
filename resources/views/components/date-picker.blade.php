@php
    $mode = $mode ?? 'single';
    $name = $name ?? (($mode ?? 'single') === 'range' ? 'date_range' : 'dates');
    $min = $min ?? null;
    $max = $max ?? null;
    $help = $help ?? null;

    $rawValues = collect(is_array($value ?? null) ? ($value ?? []) : [$value ?? null])
        ->flatMap(fn ($v) => preg_split('/\s*,\s*/', (string) $v, -1, PREG_SPLIT_NO_EMPTY))
        ->filter()
        ->unique()
        ->sort()
        ->values();
@endphp

<script>
    function dateCalendar(config) {
        const pad = (n) => String(n).padStart(2, '0');
        const keyOf = (d) => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        const parts = (k) => k.split('-').map(Number);
        const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        return {
            name: config.name,
            mode: config.mode,
            startName: config.startName || (config.name + '_start'),
            endName: config.endName || (config.name + '_end'),
            min: config.min || null,
            max: config.max || null,
            todayKey: keyOf(new Date()),
            selected: new Set(config.initial),
            pendingStart: null,
            dragMoved: false,
            pressedCell: null,
            preview: [],
            dragging: false,
            anchor: null,
            expanded: false,
            open: false,
            viewYear: null,
            viewMonth: null,
            weekdays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            months: MONTHS,

            init() {
                const seed = this.sortedKeys[0];
                const base = seed ? parts(seed) : parts(this.todayKey);
                this.viewYear = base[0];
                this.viewMonth = base[1] - 1;
            },

            get sortedKeys() {
                return Array.from(this.selected).sort();
            },

            get cells() {
                if (this.viewYear === null) return [];
                const firstDow = new Date(this.viewYear, this.viewMonth, 1).getDay();
                const start = new Date(this.viewYear, this.viewMonth, 1 - firstDow);
                return Array.from({ length: 42 }, (_, i) => {
                    const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
                    return { key: keyOf(d), day: d.getDate(), inMonth: d.getMonth() === this.viewMonth };
                });
            },

            get monthLabel() {
                return this.months[this.viewMonth] + ' ' + this.viewYear;
            },

            get triggerLabel() {
                if (!this.sortedKeys.length) {
                    if (this.mode === 'single') return 'Select a date';
                    if (this.mode === 'range') return 'Select date range';
                    return 'Select dates';
                }
                const first = this.pretty(this.sortedKeys[0]);
                const last = this.pretty(this.sortedKeys[this.sortedKeys.length - 1]);
                if (this.mode === 'single') return first;
                if (this.sortedKeys.length === 1) return first;
                return first + ' — ' + last + (this.mode === 'multiple' ? ' (' + this.sortedKeys.length + ' dates)' : '');
            },

            prevMonth() {
                if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; } else { this.viewMonth--; }
            },

            nextMonth() {
                if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; } else { this.viewMonth++; }
            },

            isSelected(key) {
                return this.selected.has(key);
            },

            isPreviewed(key) {
                return this.preview.includes(key) && !this.selected.has(key);
            },

            isDisabled(key) {
                return Boolean((this.min && key < this.min) || (this.max && key > this.max));
            },

            pretty(key) {
                const [y, m, d] = parts(key);
                return MONTHS[m - 1] + ' ' + d + ', ' + y;
            },

            range(a, b) {
                const out = [];
                const [y1, m1, d1] = parts(a);
                const [y2, m2, d2] = parts(b);
                let cur = new Date(y1, m1 - 1, d1);
                const end = new Date(y2, m2 - 1, d2);
                const step = end >= cur ? 1 : -1;
                while (step === 1 ? cur <= end : cur >= end) {
                    out.push(keyOf(cur));
                    cur = new Date(cur.getFullYear(), cur.getMonth(), cur.getDate() + step);
                }
                return out;
            },

            commitRange(endKey) {
                this.range(this.pendingStart, endKey).forEach((k) => this.selected.add(k));
                this.selected = new Set(Array.from(this.selected).sort());
                this.pendingStart = null;
                this.preview = [];
            },

            clickCell(cell) {
                if (this.isDisabled(cell.key)) return;

                if (this.mode === 'single') {
                    this.preview = [];
                    this.selected = new Set([cell.key]);
                    this.open = false;
                } else if (this.mode === 'multiple') {
                    this.preview = [];
                    if (this.selected.has(cell.key)) {
                        this.selected.delete(cell.key);
                    } else {
                        this.selected.add(cell.key);
                    }
                } else if (this.mode === 'range') {
                    if (!this.pendingStart || cell.key === this.pendingStart) {
                        this.selected = new Set([cell.key]);
                        this.pendingStart = cell.key;
                        this.preview = [];
                    } else {
                        this.commitRange(cell.key);
                    }
                }
            },

            dragStart(event, cell) {
                if ((this.mode !== 'multiple' && this.mode !== 'range') || this.isDisabled(cell.key) || event.button !== 0) return;
                event.preventDefault();
                this.pressedCell = cell.key;
                this.dragging = true;
                this.dragMoved = false;
                this.anchor = cell.key;
                this.preview = (this.mode === 'range' && this.pendingStart && this.pendingStart !== cell.key)
                    ? this.range(this.pendingStart, cell.key)
                    : [cell.key];
            },

            dragOver(cell) {
                if (this.isDisabled(cell.key)) return;
                if (this.mode === 'range') {
                    if (this.dragging) this.dragMoved = true;
                    const base = this.pendingStart || this.anchor;
                    if (base && cell.key !== base) {
                        this.preview = this.range(base, cell.key);
                    }
                    return;
                }
                if (!this.dragging || cell.key === this.anchor) return;
                this.dragMoved = true;
                this.preview = this.range(this.anchor, cell.key);
            },

            cellUp(cell) {
                if (!this.pressedCell) return;
                const hadPending = Boolean(this.pendingStart);
                this.pressedCell = null;
                this.dragging = false;
                if (this.mode !== 'range' || !hadPending) this.preview = [];
            },

            endDrag() {
                if (!this.pressedCell) return;
                const hadPending = Boolean(this.pendingStart);
                this.pressedCell = null;
                this.dragging = false;
                this.anchor = null;
                if (this.mode !== 'range' || !hadPending) this.preview = [];
            },

            removeKey(key) {
                this.selected.delete(key);
            },

            clearAll() {
                this.selected.clear();
                this.pendingStart = null;
                this.expanded = false;
            },

            close() {
                this.open = false;
                if (this.mode === 'range' && !this.sortedKeys.length) {
                    this.pendingStart = null;
                    this.preview = [];
                }
            },
        };
    }
</script>

<div x-data="dateCalendar({
        name: @js($name),
        mode: @js($mode),
        min: @js($min),
        max: @js($max),
        initial: @js($rawValues->all()),
    })"
    x-init="init()"
    @mouseup.window="endDrag()"
    @keydown.escape.window="close()"
    @scroll.window.passive="if (dragging) { dragging = false; preview = []; anchor = null; }"
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
    class="w-full">

    @if ($mode === 'single')
        <input type="hidden" name="{{ $name }}" :value="sortedKeys[0] || ''">
    @elseif ($mode === 'range')
        <input type="hidden" name="{{ $startName }}" :value="sortedKeys[0] || ''">
        <input type="hidden" name="{{ $endName }}" :value="sortedKeys.length ? sortedKeys[sortedKeys.length - 1] : ''">
    @else
        <template x-for="k in sortedKeys" :key="k">
            <input type="hidden" name="{{ $name }}[]" :value="k">
        </template>
    @endif

    @if ($label)
        <span class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
    @endif

    <button type="button"
        @click="open = true"
        :aria-expanded="String(open)"
        aria-haspopup="dialog"
        class="flex w-full items-center justify-between gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-left text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        :class="sortedKeys.length ? 'text-gray-800 dark:text-white' : 'text-gray-400 italic dark:text-gray-500'">
        <span class="truncate" x-text="triggerLabel"></span>
        <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
    </button>

    @if ($help)
        <p class="mt-1 text-xs text-gray-400">{{ $help }}</p>
    @endif

    @if (isset($errors) && $errors->any())
        @error($name)
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    @endif

    <div x-cloak x-show="open"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $label ?? ($mode === 'multiple' ? 'Select dates' : 'Select a date') }}"
        class="fixed inset-0 z-[100000]">

        <div class="absolute inset-0 bg-secondary-950/60 backdrop-blur-sm"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="close()"></div>

        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="open"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="pointer-events-auto flex max-h-[92vh] w-full max-w-md flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900">

                <div class="flex shrink-0 items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $label ?? ($mode === 'multiple' ? 'Select Dates' : 'Select a Date') }}</h2>
                        <p class="mt-0.5 text-xs text-gray-400">
                            {{ $mode === 'range' ? 'Click start and end — or drag across days' : ($mode === 'multiple' ? 'Tap dates or click and drag to pick a range' : 'Pick one date') }}
                        </p>
                    </div>
                    <button type="button" @click="close()" aria-label="Close date picker"
                        class="rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/10 dark:hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="select-none overflow-y-auto p-4 sm:p-5">
                    <div class="mb-3 flex items-center justify-between">
                        <button type="button" @click="prevMonth()" aria-label="Previous month"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-500 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                        </button>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white" aria-live="polite" x-text="monthLabel"></span>
                        <button type="button" @click="nextMonth()" aria-label="Next month"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-500 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    </div>

                    <div class="mb-1 grid grid-cols-7">
                        <template x-for="wd in weekdays" :key="wd">
                            <span class="py-1 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400" x-text="wd"></span>
                        </template>
                    </div>

                    <div class="grid grid-cols-7 gap-y-0.5">
                        <template x-for="cell in cells" :key="cell.key">
                            <button type="button"
                                x-text="cell.day"
                                :aria-label="pretty(cell.key)"
                                :aria-pressed="mode === 'multiple' ? String(isSelected(cell.key)) : null"
                                :aria-disabled="isDisabled(cell.key) ? 'true' : null"
                                :tabindex="cell.inMonth ? 0 : -1"
                                @click="clickCell(cell)"
                                @mousedown="dragStart($event, cell)"
                                @mouseenter="dragOver(cell)"
                                @mouseup="cellUp(cell)"
                                class="relative mx-auto flex h-9 w-9 items-center justify-center rounded-lg text-sm transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-brand-500"
                                :class="[
                                    isDisabled(cell.key)
                                        ? 'cursor-not-allowed text-gray-300 dark:text-gray-600'
                                        : 'cursor-pointer',
                                    isSelected(cell.key)
                                        ? 'bg-brand-500 font-bold text-white hover:bg-brand-600'
                                        : isPreviewed(cell.key)
                                            ? 'bg-brand-100 text-brand-700 ring-2 ring-brand-400/60 dark:bg-brand-500/20 dark:text-brand-300'
                                            : cell.inMonth
                                                ? (cell.key === todayKey
                                                    ? 'text-brand-600 ring-1 ring-brand-400 hover:bg-brand-50 dark:text-brand-400 dark:hover:bg-gray-800'
                                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800')
                                                : 'text-gray-300 hover:bg-gray-50 dark:text-gray-600 dark:hover:bg-gray-800/60'
                                ]">
                                <span x-show="cell.key === todayKey" class="absolute bottom-1 h-1 w-1 rounded-full bg-brand-500" aria-hidden="true"></span>
                                <span x-show="isSelected(cell.key)" class="absolute bottom-0.5 text-white" aria-hidden="true">
                                    <svg class="h-1.5 w-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                                </span>
                                <span x-show="cell.key === todayKey" class="sr-only">(Today)</span>
                            </button>
                        </template>
                    </div>

                    @if ($mode === 'range')
                        <p class="mt-4 border-t border-gray-100 pt-3 text-center text-sm text-gray-500 dark:border-gray-700/60 dark:text-gray-400" aria-live="polite">
                            <template x-if="sortedKeys.length >= 2">
                                <span>Range: <strong class="font-semibold text-gray-900 dark:text-white" x-text="pretty(sortedKeys[0]) + ' — ' + pretty(sortedKeys[sortedKeys.length - 1])"></strong>
                                    (<span x-text="sortedKeys.length"></span>&nbsp;days)</span>
                            </template>
                            <template x-if="sortedKeys.length === 1">
                                <span class="italic">Now pick the end date</span>
                            </template>
                            <template x-if="!sortedKeys.length && !pendingStart">
                                <span class="italic">No range selected yet</span>
                            </template>
                            <template x-if="!sortedKeys.length && pendingStart">
                                <span class="italic">Start picked — choose the end date</span>
                            </template>
                        </p>
                    @elseif ($mode === 'multiple')
                        <div class="mt-4 border-t border-gray-100 pt-3 dark:border-gray-700/60">
                            <div class="flex items-center justify-between gap-3" aria-live="polite">
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <strong class="font-semibold text-gray-900 dark:text-white" x-text="sortedKeys.length"></strong>&nbsp;<span x-text="sortedKeys.length === 1 ? 'date selected' : 'dates selected'"></span>
                                </p>
                                <button type="button" x-show="sortedKeys.length" @click="clearAll()"
                                    class="shrink-0 text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Clear All</button>
                            </div>
                            <div x-show="sortedKeys.length" class="mt-2 flex flex-wrap items-center gap-1.5">
                                <template x-for="k in (expanded ? sortedKeys : sortedKeys.slice(0, 6))" :key="'chip-' + k">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-500/10 py-0.5 pl-2.5 pr-1 text-xs font-medium text-brand-700 dark:bg-brand-400/10 dark:text-brand-300">
                                        <span x-text="pretty(k)"></span>
                                        <button type="button" @click="removeKey(k)" :aria-label="'Remove ' + pretty(k)"
                                            class="rounded-full p-0.5 text-brand-500 hover:bg-brand-500/20 hover:text-brand-700 dark:hover:text-brand-200">
                                            <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                </template>
                                <button type="button" x-show="sortedKeys.length > 6" @click="expanded = !expanded"
                                    class="text-xs font-medium text-brand-500 underline underline-offset-2 hover:text-brand-600 dark:text-brand-400">
                                    <span x-text="expanded ? 'Show less' : '+ ' + (sortedKeys.length - 6) + ' more'"></span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if ($mode === 'single')
                        <p class="mt-4 border-t border-gray-100 pt-3 text-center text-sm text-gray-500 dark:border-gray-700/60 dark:text-gray-400" aria-live="polite">
                            <template x-if="sortedKeys.length">
                                <span>Selected: <strong class="font-semibold text-gray-900 dark:text-white" x-text="pretty(sortedKeys[0])"></strong></span>
                            </template>
                            <template x-if="!sortedKeys.length">
                                <span class="italic">No date selected yet</span>
                            </template>
                        </p>
                    @endif

                </div>

                <div class="flex shrink-0 items-center justify-end gap-2 border-t border-gray-200 px-5 py-3.5 dark:border-gray-700">
                    @if (in_array($mode, ['single', 'range']))
                        <button type="button" x-show="sortedKeys.length" @click="clearAll()"
                            class="mr-auto text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">{{ $mode === 'range' ? 'Clear Range' : 'Clear Date' }}</button>
                    @endif
                    <button type="button" @click="close()"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
