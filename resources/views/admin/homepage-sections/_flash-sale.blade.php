<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Flash Sale Settings</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Ends At</label>
            <input type="datetime-local" name="config[ends_at]" value="{{ old('config.ends_at', isset($section->config['ends_at']) ? date('Y-m-d\TH:i', strtotime($section->config['ends_at'])) : '') }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Countdown timer on the frontend uses this date.</p>
        </div>
    </div>
</div>
