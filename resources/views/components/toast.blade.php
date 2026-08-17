@props(['position' => 'top-right', 'duration' => 3000, 'topOffset' => null])

@php
    $sessionToasts = [];
    foreach (['success', 'error', 'warning', 'info'] as $type) {
        if (session($type)) {
            $sessionToasts[] = ['type' => $type, 'message' => session($type)];
        }
    }

    $top = $topOffset ?? '1.25rem';
    $positionStyles = match($position) {
        'top-right' => "top: {$top}; right: 1.25rem;",
        'top-left' => "top: {$top}; left: 1.25rem;",
        'bottom-right' => 'bottom: 1.5rem; right: 1.5rem;',
        'bottom-left' => 'bottom: 1.5rem; left: 1.5rem;',
        default => "top: {$top}; right: 1.25rem;",
    };
@endphp

<div
    x-data="{
        items: [],
        add(detail) {
            const id = Date.now() + Math.random();
            const duration = detail.duration || {{ $duration }};
            this.items.push({ id, message: detail.message || '', title: detail.title || '', type: detail.type || 'success', show: true, progress: 100, duration });
            const start = Date.now();
            const tick = () => {
                const item = this.items.find(i => i.id === id);
                if (!item || !item.show) return;
                item.progress = Math.max(0, 100 - ((Date.now() - start) / duration) * 100);
                if (item.progress > 0) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
            setTimeout(() => this.dismiss(id), duration);
        },
        dismiss(id) {
            const t = this.items.find(i => i.id === id);
            if (t) { t.show = false; t.progress = 0; }
            setTimeout(() => { this.items = this.items.filter(i => i.id !== id); }, 350);
        },
        init() {
            @foreach ($sessionToasts as $toast)
                this.add({ message: @js($toast['message']), type: @js($toast['type']) });
            @endforeach
        }
    }"
    x-on:toast.window="add($event.detail)"
    role="status"
    aria-live="polite"
    {{ $attributes->merge(['class' => 'fixed z-[99999] flex flex-col gap-2.5 pointer-events-none w-full max-w-sm']) }}
    style="{{ $positionStyles }}"
>
    <template x-for="t in items" :key="t.id">
        <div
            x-show="t.show"
            x-transition:enter="transition ease-out duration-350"
            x-transition:enter-start="opacity-0 translate-x-5 scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-250"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-5 scale-95"
            class="pointer-events-auto relative overflow-hidden rounded-xl border shadow-xl backdrop-blur-md"
            :class="{
                'border-emerald-200/60 bg-white dark:border-emerald-800/40 dark:bg-secondary-900/95': t.type === 'success',
                'border-red-200/60 bg-white dark:border-red-800/40 dark:bg-secondary-900/95': t.type === 'error',
                'border-amber-200/60 bg-white dark:border-amber-800/40 dark:bg-secondary-900/95': t.type === 'warning',
                'border-blue-200/60 bg-white dark:border-blue-800/40 dark:bg-secondary-900/95': t.type === 'info'
            }"
        >
            <div class="flex items-start gap-3 p-4">
                {{-- Icon --}}
                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                    :class="{
                        'bg-emerald-50 dark:bg-emerald-500/15': t.type === 'success',
                        'bg-red-50 dark:bg-red-500/15': t.type === 'error',
                        'bg-amber-50 dark:bg-amber-500/15': t.type === 'warning',
                        'bg-blue-50 dark:bg-blue-500/15': t.type === 'info'
                    }">
                    <template x-if="t.type === 'success'">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="t.type === 'error'">
                        <svg class="h-4 w-4 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                    </template>
                    <template x-if="t.type === 'warning'">
                        <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                    </template>
                    <template x-if="t.type === 'info'">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    </template>
                </div>

                {{-- Text --}}
                <div class="flex-1 min-w-0">
                    <template x-if="t.title">
                        <p class="text-sm font-semibold text-secondary-900 dark:text-white" x-text="t.title"></p>
                    </template>
                    <p class="text-sm leading-snug"
                        :class="{
                            'text-secondary-600 dark:text-secondary-300': !t.title,
                            'text-secondary-500 dark:text-secondary-400 mt-0.5': t.title
                        }" x-text="t.message"></p>
                </div>

                {{-- Close --}}
                <button x-on:click="dismiss(t.id)" class="shrink-0 rounded-md p-1 text-secondary-400 hover:text-secondary-600 hover:bg-secondary-100 dark:hover:text-secondary-300 dark:hover:bg-white/5 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Progress bar --}}
            <div class="h-0.5 w-full bg-secondary-100 dark:bg-secondary-800">
                <div class="h-full rounded-full transition-none"
                    :class="{
                        'bg-emerald-500': t.type === 'success',
                        'bg-red-500': t.type === 'error',
                        'bg-amber-500': t.type === 'warning',
                        'bg-blue-500': t.type === 'info'
                    }"
                    :style="'width:' + t.progress + '%'"></div>
            </div>
        </div>
    </template>
</div>
