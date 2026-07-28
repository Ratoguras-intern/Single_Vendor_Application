<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Testimonials</h3>
        <button type="button" @click="addTestimonial()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Testimonial
        </button>
    </div>

    <template x-for="(testimonial, index) in testimonials" :key="index">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="'Testimonial ' + (index + 1)"></span>
                <button type="button" @click="removeTestimonial(index)" class="text-red-500 hover:text-red-600 text-xs font-medium">Remove</button>
            </div>
            <input type="hidden" :name="'config[testimonials][' + index + '][name]'" :value="testimonial.name">
            <input type="hidden" :name="'config[testimonials][' + index + '][avatar]'" :value="testimonial.avatar">
            <input type="hidden" :name="'config[testimonials][' + index + '][rating]'" :value="testimonial.rating">
            <input type="hidden" :name="'config[testimonials][' + index + '][review]'" :value="testimonial.review">
            <input type="hidden" :name="'config[testimonials][' + index + '][role]'" :value="testimonial.role">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" x-model="testimonial.name" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Role / Title</label>
                    <input type="text" x-model="testimonial.role" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Avatar URL</label>
                    <input type="url" x-model="testimonial.avatar" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Rating (1-5)</label>
                    <input type="number" x-model.number="testimonial.rating" min="1" max="5" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Review</label>
                    <textarea x-model="testimonial.review" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                </div>
            </div>
        </div>
    </template>

    @if(empty($section->config['testimonials']))
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">No testimonials configured. Click "Add Testimonial" to create one.</p>
    @endif
</div>
