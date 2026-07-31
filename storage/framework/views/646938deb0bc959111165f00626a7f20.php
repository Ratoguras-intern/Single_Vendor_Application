<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Hero Slides</h3>
        <button type="button" @click="addSlide()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Slide
        </button>
    </div>

    <template x-for="(slide, index) in slides" :key="index">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="'Slide ' + (index + 1)"></span>
                <button type="button" @click="removeSlide(index)" class="text-red-500 hover:text-red-600 text-xs font-medium">Remove</button>
            </div>
            <input type="hidden" :name="'config[slides][' + index + '][badge]'" :value="slide.badge">
            <input type="hidden" :name="'config[slides][' + index + '][badge_color]'" :value="slide.badge_color">
            <input type="hidden" :name="'config[slides][' + index + '][heading]'" :value="slide.heading">
            <input type="hidden" :name="'config[slides][' + index + '][description]'" :value="slide.description">
            <input type="hidden" :name="'config[slides][' + index + '][image]'" :value="slide.image">
            <input type="hidden" :name="'config[slides][' + index + '][cta_primary]'" :value="slide.cta_primary">
            <input type="hidden" :name="'config[slides][' + index + '][cta_secondary]'" :value="slide.cta_secondary">
            <input type="hidden" :name="'config[slides][' + index + '][link_primary]'" :value="slide.link_primary">
            <input type="hidden" :name="'config[slides][' + index + '][link_secondary]'" :value="slide.link_secondary">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Badge Text</label>
                    <input type="text" x-model="slide.badge" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Badge Color</label>
                    <select x-model="slide.badge_color" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="bg-green-500">Green</option>
                        <option value="bg-red-500">Red</option>
                        <option value="bg-primary-500">Primary (Gold)</option>
                        <option value="bg-blue-500">Blue</option>
                        <option value="bg-purple-500">Purple</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Heading</label>
                    <input type="text" x-model="slide.heading" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea x-model="slide.description" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Image URL</label>
                    <input type="url" x-model="slide.image" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary CTA Text</label>
                    <input type="text" x-model="slide.cta_primary" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary CTA Link</label>
                    <input type="text" x-model="slide.link_primary" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Secondary CTA Text</label>
                    <input type="text" x-model="slide.cta_secondary" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Secondary CTA Link</label>
                    <input type="text" x-model="slide.link_secondary" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
        </div>
    </template>

    <?php if(empty($section->config['slides'])): ?>
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">No slides configured. Click "Add Slide" to create one.</p>
    <?php endif; ?>
</div>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views/admin/homepage-sections/_hero-carousel.blade.php ENDPATH**/ ?>