<footer class="border-t transition-colors duration-300" style="background-color: var(--bloom-background); border-color: var(--bloom-border);">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-12 border-b" style="border-color: var(--bloom-border);">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-2xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Stay in the loop" x-text="$store.i18n.t('Stay in the loop')">{{ __('Stay in the loop') }}</span></h3>
                <p class="mb-6" style="color: var(--bloom-muted-foreground);"><span data-i18n="Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration." x-text="$store.i18n.t('Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.')">{{ __('Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.') }}</span></p>
                <form class="flex max-w-md mx-auto gap-2" x-data="{ email: '' }" x-on:submit.prevent="email = ''">
                    <input type="email" x-model="email" placeholder="{{ __('Enter your email') }}" required class="flex-1 flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-9 px-4 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        <span class="sr-only">Subscribe</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8">
                <div class="lg:col-span-2">
                    <div class="mb-5">
                        <x-brand-logo subtitle="Commerce Suite" />
                    </div>

                    <p class="mb-6 max-w-sm text-sm leading-relaxed" style="color: var(--bloom-muted-foreground);">A powerful e-commerce management platform. Streamline operations, boost sales, and scale your business with confidence.</p>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm" style="color: var(--bloom-muted-foreground);">
                            <svg class="h-4 w-4 shrink-0" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <span>123 Fashion Street, Style City, SC 12345</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm" style="color: var(--bloom-muted-foreground);">
                            <svg class="h-4 w-4 shrink-0" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm" style="color: var(--bloom-muted-foreground);">
                            <svg class="h-4 w-4 shrink-0" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            <span>hello@nbkvertex.com</span>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <a href="#" aria-label="Facebook" class="h-10 w-10 rounded-lg inline-flex items-center justify-center transition-all duration-300 hover:scale-105" style="background-color: var(--bloom-muted);">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="#" aria-label="Twitter" class="h-10 w-10 rounded-lg inline-flex items-center justify-center transition-all duration-300 hover:scale-105" style="background-color: var(--bloom-muted);">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="h-10 w-10 rounded-lg inline-flex items-center justify-center transition-all duration-300 hover:scale-105" style="background-color: var(--bloom-muted);">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                        <a href="#" aria-label="GitHub" class="h-10 w-10 rounded-lg inline-flex items-center justify-center transition-all duration-300 hover:scale-105" style="background-color: var(--bloom-muted);">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/><path d="M9 18c-4.51 2-5-2-7-2"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold mb-4 uppercase tracking-wider" style="color: var(--bloom-foreground);"><span data-i18n="Shop" x-text="$store.i18n.t('Shop')">{{ __('Shop') }}</span></h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('frontend.shop') }}" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="All Products" x-text="$store.i18n.t('All Products')">{{ __('All Products') }}</span></a></li>
                        <li><a href="{{ route('frontend.shop') }}" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="New Arrivals" x-text="$store.i18n.t('New Arrivals')">{{ __('New Arrivals') }}</span></a></li>
                        <li><a href="{{ route('frontend.shop') }}" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Sale" x-text="$store.i18n.t('Sale')">{{ __('Sale') }}</span></a></li>
                        <li><a href="{{ route('frontend.shop') }}" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Featured" x-text="$store.i18n.t('Featured')">{{ __('Featured') }}</span></a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-semibold mb-4 uppercase tracking-wider" style="color: var(--bloom-foreground);"><span data-i18n="Customer Care" x-text="$store.i18n.t('Customer Care')">{{ __('Customer Care') }}</span></h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('frontend.contact') }}" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Contact Us" x-text="$store.i18n.t('Contact Us')">{{ __('Contact Us') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Help Center" x-text="$store.i18n.t('Help Center')">{{ __('Help Center') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Shipping Info" x-text="$store.i18n.t('Shipping Info')">{{ __('Shipping Info') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Returns & Exchanges" x-text="$store.i18n.t('Returns & Exchanges')">{{ __('Returns & Exchanges') }}</span></a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-semibold mb-4 uppercase tracking-wider" style="color: var(--bloom-foreground);"><span data-i18n="Company" x-text="$store.i18n.t('Company')">{{ __('Company') }}</span></h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('frontend.about') }}" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="About Us" x-text="$store.i18n.t('About Us')">{{ __('About Us') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Careers" x-text="$store.i18n.t('Careers')">{{ __('Careers') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Blog" x-text="$store.i18n.t('Blog')">{{ __('Blog') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Press" x-text="$store.i18n.t('Press')">{{ __('Press') }}</span></a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-semibold mb-4 uppercase tracking-wider" style="color: var(--bloom-foreground);"><span data-i18n="Legal" x-text="$store.i18n.t('Legal')">{{ __('Legal') }}</span></h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Privacy Policy" x-text="$store.i18n.t('Privacy Policy')">{{ __('Privacy Policy') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Terms & Conditions" x-text="$store.i18n.t('Terms & Conditions')">{{ __('Terms & Conditions') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Cookie Policy" x-text="$store.i18n.t('Cookie Policy')">{{ __('Cookie Policy') }}</span></a></li>
                        <li><a href="#" class="text-sm hover:text-gray-900 dark:hover:text-white transition-colors inline-block" style="color: var(--bloom-muted-foreground);"><span data-i18n="Accessibility" x-text="$store.i18n.t('Accessibility')">{{ __('Accessibility') }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="my-8 h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>

        <div class="py-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm" style="color: var(--bloom-muted-foreground);">
                    <span>&copy; {{ date('Y') }} NBK Vertex&trade;. <span data-i18n="All Rights Reserved." x-text="$store.i18n.t('All Rights Reserved.')">{{ __('All Rights Reserved.') }}</span></span>
                </div>
                <p class="text-sm" style="color: var(--bloom-muted-foreground);"><span data-i18n="Powered by" x-text="$store.i18n.t('Powered by')">{{ __('Powered by') }}</span> <span class="font-bold" style="color: var(--bloom-primary);">NBK Vertex</span></p>
            </div>

            <div class="flex items-center gap-6 text-sm">
                <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors" style="color: var(--bloom-muted-foreground);"><span data-i18n="Privacy" x-text="$store.i18n.t('Privacy')">{{ __('Privacy') }}</span></a>
                <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors" style="color: var(--bloom-muted-foreground);"><span data-i18n="Terms" x-text="$store.i18n.t('Terms')">{{ __('Terms') }}</span></a>
                <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors" style="color: var(--bloom-muted-foreground);"><span data-i18n="Cookies" x-text="$store.i18n.t('Cookies')">{{ __('Cookies') }}</span></a>
            </div>
        </div>
    </div>
</footer>
