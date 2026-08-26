<div
    x-data
    x-show="$store.promptModal.show"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[99998] overflow-y-auto"
    style="display: none;"
    x-effect="if ($store.promptModal.show) { $nextTick(() => $refs.promptInput?.focus()); }"
    @keydown.escape.window="$store.promptModal.cancel()"
    @keydown.enter.window.prevent="if ($store.promptModal.show) $store.promptModal.confirm()"
    role="dialog"
    aria-modal="true"
>
    <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="$store.promptModal.cancel()"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            x-show="$store.promptModal.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-xl bg-white dark:bg-secondary-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            @click.stop
        >
            <div class="bg-white dark:bg-secondary-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-500/10 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white" x-text="$store.promptModal.title"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-show="$store.promptModal.message" x-text="$store.promptModal.message"></p>
                            <input
                                type="text"
                                x-model="$store.promptModal.value"
                                x-ref="promptInput"
                                class="mt-3 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                @keydown.enter.prevent="$store.promptModal.confirm()"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-secondary-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                <button
                    type="button"
                    @click="$store.promptModal.confirm()"
                    class="inline-flex w-full justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 sm:ml-3 sm:w-auto transition-colors"
                >
                    OK
                </button>
                <button
                    type="button"
                    @click="$store.promptModal.cancel()"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-secondary-700 sm:mt-0 sm:w-auto transition-colors"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
