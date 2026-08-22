<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Hero Slides</h3>
        <button type="button" @click="addSlide()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Slide
        </button>
    </div>

    <template x-for="(slide, index) in slides" :key="index">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4"
            @media-picker:select.window="if ($event.detail.name === 'hero-slide-' + index) { slide.image = $event.detail.media[0].url; slide.remove_image = false; }">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="'Slide ' + (index + 1)"></span>
                <button type="button" @click="removeSlide(index)" class="text-red-500 hover:text-red-600 text-xs font-medium">Remove</button>
            </div>
            <input type="hidden" :name="'config[slides][' + index + '][badge]'" :value="slide.badge">
            <input type="hidden" :name="'config[slides][' + index + '][badge_color]'" :value="slide.badge_color">
            <input type="hidden" :name="'config[slides][' + index + '][heading]'" :value="slide.heading">
            <input type="hidden" :name="'config[slides][' + index + '][description]'" :value="slide.description">
            <input type="hidden" :name="'config[slides][' + index + '][image]'" :value="slide.image">
            <input type="hidden" :name="'config[slides][' + index + '][image_path]'" :value="slide.image_path">
            <input type="hidden" :name="'config[slides][' + index + '][brightness]'" :value="slide.brightness">
            <input type="hidden" :name="'config[slides][' + index + '][overlay_opacity]'" :value="slide.overlay_opacity">
            <input type="hidden" :name="'config[slides][' + index + '][overlay_color]'" :value="slide.overlay_color">
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
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Slide Image</label>
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <button type="button"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:border-brand-500 hover:text-brand-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-brand-500 dark:hover:text-brand-400"
                                @click="$dispatch('media-picker:open', { name: 'hero-slide-' + index, multiple: false, folder: 'banners' })">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Zm10.5-11.25h.008v.008h-.008V9.75Z"/></svg>
                                Choose Image
                            </button>
                            <p class="mt-1 text-xs text-gray-400">Pick from the media library or paste an external image URL below.</p>
                            <div class="mt-2">
                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Or Image URL (optional)</label>
                                <input type="url" x-model="slide.image" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="https://example.com/image.jpg">
                            </div>
                            <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <input type="checkbox" x-model="slide.remove_image" :name="'config[slides][' + index + '][remove_image]'" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                Remove current image
                            </label>
                        </div>
                            <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <input type="checkbox" x-model="slide.remove_image" :name="'config[slides][' + index + '][remove_image]'" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                Remove current image
                            </label>
                        </div>
                        <div class="shrink-0 w-36">
                            <div class="w-36 h-20 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                                <template x-if="slidePreview(slide)">
                                    <img :src="slidePreview(slide)" alt="Preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!slidePreview(slide)">
                                    <span class="text-xs text-gray-400">No image</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Image Lighting</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Brightness <span class="font-medium" x-text="slide.brightness + '%'"></span></label>
                            <input type="range" min="0" max="200" step="1" x-model.number="slide.brightness" class="w-full accent-brand-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Dark Overlay <span class="font-medium" x-text="slide.overlay_opacity + '%'"></span></label>
                            <input type="range" min="0" max="100" step="1" x-model.number="slide.overlay_opacity" class="w-full accent-brand-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Overlay Color</label>
                            <input type="color" x-model="slide.overlay_color" class="h-9 w-full rounded-lg border border-gray-300 bg-white p-1 dark:border-gray-700 dark:bg-gray-900">
                        </div>
                    </div>
                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" x-model="slide.overlay_enabled" :name="'config[slides][' + index + '][overlay_enabled]'" value="1" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                        Enable dark overlay (helps text readability)
                    </label>
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

    @if(empty($section->config['slides']))
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">No slides configured. Click "Add Slide" to create one.</p>
    @endif
</div>
