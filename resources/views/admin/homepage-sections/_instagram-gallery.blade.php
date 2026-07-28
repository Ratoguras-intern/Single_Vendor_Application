<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Instagram Gallery Images</h3>
        <button type="button" @click="addGalleryImage()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Image
        </button>
    </div>

    <template x-for="(img, index) in galleryImages" :key="index">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="'Image ' + (index + 1)"></span>
                <button type="button" @click="removeGalleryImage(index)" class="text-red-500 hover:text-red-600 dark:hover:text-red-400 text-xs font-medium">Remove</button>
            </div>
            <input type="hidden" :name="'config[images][' + index + '][url]'" :value="img.url">
            <input type="hidden" :name="'config[images][' + index + '][span]'" :value="img.span">
            <input type="hidden" :name="'config[images][' + index + '][alt]'" :value="img.alt">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Image URL</label>
                    <input type="url" x-model="img.url" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Alt Text</label>
                    <input type="text" x-model="img.alt" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Grid Span</label>
                    <select x-model="img.span" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="col-span-1 row-span-1">1x1 (Normal)</option>
                        <option value="col-span-1 row-span-2">1x2 (Tall)</option>
                        <option value="col-span-2 row-span-1">2x1 (Wide)</option>
                        <option value="col-span-2 row-span-2">2x2 (Large)</option>
                    </select>
                </div>
            </div>
        </div>
    </template>

    @if(empty($section->config['images']))
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">No gallery images configured. Click "Add Image" to create one.</p>
    @endif
</div>
