<section class="py-16 lg:py-20">
    <div class="section">
        <div class="rounded-card p-12 text-center border border-primary-100 dark:border-primary-900 bg-gradient-to-r from-primary-50 to-primary-100/50 dark:from-primary-950/30 dark:to-primary-900/30">
            <h2 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-white mb-3" data-i18n="Still have questions?" x-text="$store.i18n.t('Still have questions?')">Still have questions?</h2>
            <p class="text-lg text-secondary-500 dark:text-secondary-400 mb-8 max-w-2xl mx-auto" data-i18n="Can't find what you're looking for? Our customer support team is here to help." x-text="$store.i18n.t(&quot;Can't find what you're looking for? Our customer support team is here to help.&quot;)">Can't find what you're looking for? Our customer support team is here to help.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:+15551234567" class="btn-primary">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                    <span data-i18n="Call Us Now" x-text="$store.i18n.t('Call Us Now')">Call Us Now</span>
                </a>
                <a href="{{ route('frontend.contact') }}" class="btn-outline">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    <span data-i18n="Live Chat" x-text="$store.i18n.t('Live Chat')">Live Chat</span>
                </a>
            </div>
        </div>
    </div>
</section>
