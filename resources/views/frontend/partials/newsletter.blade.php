<section class="py-16 lg:py-24">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl border p-12 text-center" style="border-color: var(--bloom-border); background: linear-gradient(to right, rgba(232, 155, 45, 0.1), rgba(253, 246, 227, 0.1));">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Still have questions?" x-text="$store.i18n.t('Still have questions?')">Still have questions?</span></h2>
            <p class="text-lg mb-8 max-w-2xl mx-auto" style="color: var(--bloom-muted-foreground);"><span data-i18n="Can't find what you're looking for? Our customer support team is here to help." x-text="$store.i18n.t(&quot;Can't find what you're looking for? Our customer support team is here to help.&quot;)">Can't find what you're looking for? Our customer support team is here to help.</span></p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:+15551234567" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90" style="background-color: var(--bloom-primary);">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                    <span data-i18n="Call Us Now" x-text="$store.i18n.t('Call Us Now')">Call Us Now</span>
                </a>
                <a href="{{ route('frontend.contact') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 border border-gray-300 bg-white shadow-sm hover:bg-gray-50">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    <span data-i18n="Live Chat" x-text="$store.i18n.t('Live Chat')">Live Chat</span>
                </a>
            </div>
        </div>
    </div>
</section>
