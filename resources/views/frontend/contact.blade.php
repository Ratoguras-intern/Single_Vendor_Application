@extends('layouts.frontend')

@section('title', 'Contact Us - NBK Vertex')

@section('content')
@include('frontend.partials.page-hero', [
    'title' => 'Contact Us',
    'description' => 'Have a question or need assistance? We\'re here to help with orders, products, and your account.',
    'eyebrow' => 'Get in Touch',
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-12 sm:mb-16">
            <div class="card-hover group text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                </div>
                <h3 class="font-semibold text-secondary-900 dark:text-white text-sm mb-1">Email Support</h3>
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">hello@nbkvertex.com</p>
                <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">We reply within 2 hours</p>
            </div>
            <div class="card-hover group text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                </div>
                <h3 class="font-semibold text-secondary-900 dark:text-white text-sm mb-1">Call Us</h3>
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">+1 (555) 123-4567</p>
                <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Mon-Fri 8am to 5pm</p>
            </div>
            <div class="card-hover group text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <h3 class="font-semibold text-secondary-900 dark:text-white text-sm mb-1">Business Hours</h3>
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Mon - Fri: 9am - 6pm</p>
                <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Sat: 10am - 4pm · Sun: Closed</p>
            </div>
            <div class="card-hover group text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-violet-50 dark:bg-violet-950/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                </div>
                <h3 class="font-semibold text-secondary-900 dark:text-white text-sm mb-1">Visit Us</h3>
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">123 Fashion Street</p>
                <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Style City, SC 12345</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">
            <div class="lg:col-span-3">
                <div class="card">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-secondary-900 dark:text-white" data-i18n="Send us a message" x-text="$store.i18n.t('Send us a message')">Send us a message</h2>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1" data-i18n="Fill out the form below and we'll get back to you as soon as possible." x-text="$store.i18n.t('Fill out the form below and we\\'ll get back to you as soon as possible.')">Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>
                    <form x-data="contactForm()" x-on:submit.prevent="submit" class="space-y-4">
                        @if(session('success'))
                            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div x-show="submitted" x-transition class="rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400 flex items-center gap-2">
                            <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Message sent successfully! We'll get back to you soon.
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="label" data-i18n="Your Name" x-text="$store.i18n.t('Your Name')">Your Name</label>
                                <input x-model="name" type="text" name="name" placeholder="John Doe" required class="input" />
                                <template x-if="errors.name"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.name[0]"></p></template>
                            </div>
                            <div>
                                <label class="label" data-i18n="Your Email" x-text="$store.i18n.t('Your Email')">Your Email</label>
                                <input x-model="email" type="email" name="email" placeholder="john@example.com" required class="input" />
                                <template x-if="errors.email"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.email[0]"></p></template>
                            </div>
                        </div>
                        <div>
                            <label class="label" data-i18n="Subject" x-text="$store.i18n.t('Subject')">Subject</label>
                            <input x-model="subject" type="text" name="subject" placeholder="How can we help you?" required class="input" />
                            <template x-if="errors.subject"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.subject[0]"></p></template>
                        </div>
                        <div>
                            <label class="label" data-i18n="Your Message" x-text="$store.i18n.t('Your Message')">Your Message</label>
                            <textarea x-model="message" name="message" placeholder="Tell us more about your question or concern..." rows="5" required class="input resize-none"></textarea>
                            <template x-if="errors.message"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.message[0]"></p></template>
                        </div>
                        <button type="submit" :disabled="submitting || submitted" class="btn-primary w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="submitting">
                                <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span> Sending...</span>
                            </template>
                            <template x-if="submitted">
                                <span class="flex items-center gap-2"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Sent!</span>
                            </template>
                            <template x-if="!submitting && !submitted">
                                <span class="flex items-center gap-2"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg> Send Message</span>
                            </template>
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-5">
                <div class="card">
                    <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-5" data-i18n="Why Contact Us?" x-text="$store.i18n.t('Why Contact Us?')">Why Contact Us?</h3>
                    <div class="space-y-4">
                        @php
                        $features = [
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"/>', 'title' => '24/7 Support', 'desc' => 'Get help whenever you need it, day or night'],
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>', 'title' => 'Quick Response', 'desc' => 'We typically reply within 2 hours'],
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>', 'title' => 'Secure & Private', 'desc' => 'Your information is always protected'],
                        ];
                        @endphp
                        @foreach($features as $feature)
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-lg bg-primary-50 dark:bg-primary-950/30 shrink-0 mt-0.5">
                                    <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">{!! $feature['icon'] !!}</svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm text-secondary-900 dark:text-white">{{ $feature['title'] }}</h4>
                                    <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-0.5">{{ $feature['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card bg-gradient-to-br from-primary-50 to-primary-100/50 dark:from-primary-950/20 dark:to-primary-950/10 border-primary-100 dark:border-primary-900/30">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-primary-500 text-white shrink-0">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-secondary-900 dark:text-white text-sm">Need urgent help?</h3>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400 mt-1">For order emergencies, call us directly at <strong class="text-primary-600 dark:text-primary-400">+1 (555) 123-4567</strong> during business hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-secondary-900">
    <div class="section">
        <div class="text-center mb-10">
            <span class="section-eyebrow">FAQ</span>
            <h2 class="section-heading" data-i18n="Frequently Asked Questions" x-text="$store.i18n.t('Frequently Asked Questions')">Frequently Asked Questions</h2>
            <p class="section-subheading mt-2 max-w-2xl mx-auto" data-i18n="Find quick answers to common questions about our products and services." x-text="$store.i18n.t('Find quick answers to common questions about our products and services.')">Find quick answers to common questions about our products and services.</p>
        </div>

        <div class="max-w-4xl mx-auto" x-data="{ activeFaq: null }">
            @php
            $faqs = [
                ['q' => 'What are your shipping policies?', 'a' => 'We offer free shipping on orders over $50. Standard shipping takes 3-5 business days. Express and overnight options are also available at checkout.'],
                ['q' => 'How can I track my order?', 'a' => 'Once your order ships, you\'ll receive a tracking number via email. You can also track your order from your account dashboard under "My Orders."'],
                ['q' => 'What is your return policy?', 'a' => 'We accept returns within 30 days of purchase. Items must be in original condition with tags attached. Start a return from your account dashboard.'],
                ['q' => 'Do you offer international shipping?', 'a' => 'Yes, we ship to over 50 countries worldwide. International shipping rates and delivery times vary by destination and are calculated at checkout.'],
            ];
            @endphp
            @foreach($faqs as $i => $faq)
                <div class="border border-secondary-200 dark:border-secondary-700 rounded-xl mb-3 overflow-hidden transition-colors {{ $loop->first ? 'border-primary-200 dark:border-primary-800/40' : '' }}">
                    <button
                        @click="activeFaq === {{ $i }} ? activeFaq = null : activeFaq = {{ $i }}"
                        class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-secondary-50 dark:hover:bg-white/[0.02] transition-colors"
                        :class="activeFaq === {{ $i }} && 'bg-primary-50/50 dark:bg-primary-950/10'"
                    >
                        <span class="font-semibold text-sm text-secondary-900 dark:text-white">{{ $faq['q'] }}</span>
                        <svg class="h-5 w-5 shrink-0 text-secondary-400 transition-transform duration-200" :class="activeFaq === {{ $i }} && 'rotate-180 text-primary-500'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="activeFaq === {{ $i }}" x-collapse x-cloak>
                        <div class="px-5 pb-4 text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('contactForm', () => ({
        name: '',
        email: '',
        subject: '',
        message: '',
        submitting: false,
        submitted: false,
        errors: {},
        async submit() {
            this.submitting = true;
            this.errors = {};
            try {
                const res = await fetch('{{ route('frontend.contact.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ name: this.name, email: this.email, subject: this.subject, message: this.message })
                });
                const data = await res.json();
                this.submitting = false;
                if (res.ok && data.success) {
                    this.submitted = true;
                    setTimeout(() => { this.submitted = false; this.name = ''; this.email = ''; this.subject = ''; this.message = ''; }, 3000);
                } else {
                    this.errors = data.errors || {};
                }
            } catch (e) {
                this.submitting = false;
                this.errors = { message: ['Something went wrong. Please try again.'] };
            }
        }
    }));
});
</script>
@endsection
