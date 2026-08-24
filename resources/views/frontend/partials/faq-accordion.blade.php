@props([
    'faqs' => [],            // collection of Faq models
    'openFirst' => false,
])

<div x-data="{ open: @js($openFirst ? 0 : null) }" class="space-y-3">
    @foreach ($faqs as $faq)
        <div class="border border-secondary-200 dark:border-secondary-700 rounded-xl overflow-hidden bg-white dark:bg-secondary-900 transition-colors
            {{ $loop->first && $openFirst ? 'border-primary-200 dark:border-primary-800/50' : '' }}">
            <h3 class="m-0">
                <button
                    type="button"
                    x-on:click="open === {{ $loop->index }} ? open = null : open = {{ $loop->index }}"
                    x-bind:aria-expanded="(open === {{ $loop->index }}).toString()"
                    aria-controls="faq-panel-{{ $faq->id }}"
                    class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-secondary-50 dark:hover:bg-white/[0.02] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40">
                    <span class="font-semibold text-sm sm:text-base text-secondary-900 dark:text-white">{{ $faq->question }}</span>
                    <svg class="h-5 w-5 shrink-0 text-secondary-400 transition-transform duration-200"
                        :class="open === {{ $loop->index }} && 'rotate-180 text-primary-500'"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
            </h3>
            <div id="faq-panel-{{ $faq->id }}" x-show="open === {{ $loop->index }}" x-collapse x-cloak>
                <div class="px-5 pb-5 text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">{{ $faq->answer }}</div>
            </div>
        </div>
    @endforeach
</div>
