<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Sale Banner Slider Settings</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Transition Speed (ms)</label>
            <input type="number" name="config[transition_speed]" min="2000" max="30000" step="500"
                value="{{ old('config.transition_speed', $section->getConfig('transition_speed', 5000)) }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">How long each sale banner stays visible before auto-advancing.</p>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Autoplay</label>
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="hidden" name="config[autoplay]" value="0">
                <input type="checkbox" name="config[autoplay]" value="1" {{ $section->getConfig('autoplay', true) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                Auto-advance slides
            </label>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Pause on Hover</label>
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="hidden" name="config[pause_on_hover]" value="0">
                <input type="checkbox" name="config[pause_on_hover]" value="1" {{ $section->getConfig('pause_on_hover', true) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                Pause autoplay while hovering
            </label>
        </div>
    </div>
    <div class="mt-4 rounded-lg border border-gray-200 dark:border-gray-800 p-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Manage the banners shown in this slider from
            <a href="{{ route('admin.sale-banners.index') }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">Sale Banners</a>.
            Only active banners with the <strong>sale</strong> position are displayed, in their sort order.
        </p>
    </div>
</div>
