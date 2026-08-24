<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Company Info</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Company Name</label>
            <input type="text" name="config[company_name]" value="{{ old('config.company_name', $section->config['company_name'] ?? site_name()) }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
            <input type="text" name="config[phone]" value="{{ old('config.phone', $section->config['phone'] ?? '+1 (555) 123-4567') }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" name="config[email]" value="{{ old('config.email', $section->config['email'] ?? 'hello@nbkvertex.com') }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
            <input type="text" name="config[address]" value="{{ old('config.address', $section->config['address'] ?? '123 Fashion Street, Style City, SC 12345') }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>
        <div class="md:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Company Description</label>
            <textarea name="config[company_description]" rows="2"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('config.company_description', $section->config['company_description'] ?? 'A powerful e-commerce management platform.') }}</textarea>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Copyright Text</label>
            <input type="text" name="config[copyright_text]" value="{{ old('config.copyright_text', $section->config['copyright_text'] ?? 'All Rights Reserved.') }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>
    </div>
</div>

<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Footer Columns</h3>
        <button type="button" @click="addFooterColumn()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Column
        </button>
    </div>

    <template x-for="(column, colIndex) in footerColumns" :key="colIndex">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <input type="text" x-model="column.heading" placeholder="Column Heading"
                    class="text-sm font-semibold text-gray-800 dark:text-white bg-transparent border-b border-gray-300 dark:border-gray-600 focus:border-brand-500 focus:outline-none px-1 py-0.5 w-48">
                <div class="flex items-center gap-2">
                    <button type="button" @click="addFooterLink(colIndex)" class="text-xs text-brand-500 hover:text-brand-600 font-medium">+ Add Link</button>
                    <button type="button" @click="removeFooterColumn(colIndex)" class="text-red-500 hover:text-red-600 text-xs font-medium">Remove</button>
                </div>
            </div>
            <input type="hidden" :name="'config[footer_columns][' + colIndex + '][heading]'" :value="column.heading">
            <template x-for="(link, linkIndex) in column.links" :key="linkIndex">
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" x-model="column.links[linkIndex]" :name="'config[footer_columns][' + colIndex + '][links][' + linkIndex + ']'" placeholder="Link text"
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <button type="button" @click="removeFooterLink(colIndex, linkIndex)" class="text-red-400 hover:text-red-500 text-xs">&times;</button>
                </div>
            </template>
        </div>
    </template>

    @if(empty($section->config['footer_columns']))
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">No footer columns configured. Click "Add Column" to create one.</p>
    @endif
</div>

<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Social Links</h3>
        <button type="button" @click="addSocialLink()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Link
        </button>
    </div>

    <template x-for="(social, index) in socialLinks" :key="index">
        <div class="flex items-center gap-3 mb-3">
            <select x-model="social.platform" :name="'config[social_links][' + index + '][platform]'"
                class="w-40 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                <option value="Facebook">Facebook</option>
                <option value="Twitter">Twitter</option>
                <option value="Instagram">Instagram</option>
                <option value="GitHub">GitHub</option>
                <option value="LinkedIn">LinkedIn</option>
                <option value="YouTube">YouTube</option>
            </select>
            <input type="text" x-model="social.url" :name="'config[social_links][' + index + '][url]'" placeholder="URL"
                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            <button type="button" @click="removeSocialLink(index)" class="text-red-500 hover:text-red-600 text-xs font-medium">Remove</button>
        </div>
    </template>
</div>
