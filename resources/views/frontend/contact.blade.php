@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'canonical' => $page->canonical_url,
    'image' => $page->og_image_url,
])

@section('content')
@php
    $contactCards = collect([
        ['key' => 'email', 'label' => 'Email Support', 'value' => $companyContact['email'], 'note' => $companyContact['response_time'] ?: 'We reply within one business day',
            'color' => 'primary', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>'],
        ['key' => 'phone', 'label' => 'Call Us', 'value' => $companyContact['phone'], 'note' => 'Speak with our support team',
            'color' => 'emerald', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>'],
        ['key' => 'hours', 'label' => 'Business Hours', 'value' => $companyContact['hours'], 'note' => 'Support availability',
            'color' => 'amber', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
        ['key' => 'address', 'label' => 'Visit Us', 'value' => $companyContact['address'], 'note' => 'Our head office location',
            'color' => 'violet', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>'],
    ])->filter(fn ($card) => filled($card['value']));
@endphp

@include('frontend.partials.page-hero', [
    'title' => $page->title,
    'description' => $page->short_description,
    'eyebrow' => $page->subtitle ?: 'Get in Touch',
    'backgroundImage' => $page->featured_image_url ?? asset('images/pages/contact-support.jpg'),
])

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        @if ($contactCards->isNotEmpty())
            <div class="grid sm:grid-cols-2 lg:grid-cols-{{ min($contactCards->count(), 4) }} gap-4 sm:gap-5 mb-12 sm:mb-16">
                @foreach ($contactCards as $card)
                    <div class="card-hover group text-center">
                        <div class="w-12 h-12 mx-auto rounded-xl bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-950/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 text-{{ $card['color'] }}-500 dark:text-{{ $card['color'] }}-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">{!! $card['icon'] !!}</svg>
                        </div>
                        <h3 class="font-semibold text-secondary-900 dark:text-white text-sm mb-1">{{ $card['label'] }}</h3>
                        <p class="text-sm font-medium text-primary-600 dark:text-primary-400 break-words">{{ $card['value'] }}</p>
                        <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">{{ $card['note'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid lg:grid-cols-5 gap-8 lg:gap-12 max-w-6xl mx-auto">
            <div class="lg:col-span-3">
                <div class="card">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-secondary-900 dark:text-white">Send us a message</h2>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>
                    <form x-data="contactForm()" x-on:submit.prevent="submit" class="space-y-4">
                        <div x-show="submitted" x-transition class="rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400 flex items-center gap-2" role="status">
                            <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Message sent successfully! We'll get back to you soon.
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-name" class="label">Full Name <span class="text-red-500">*</span></label>
                                <input id="contact-name" x-model="name" type="text" name="name" placeholder="John Doe" required autocomplete="name" maxlength="255" class="input" />
                                <template x-if="errors.name"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.name[0]"></p></template>
                            </div>
                            <div>
                                <label for="contact-email" class="label">Email Address <span class="text-red-500">*</span></label>
                                <input id="contact-email" x-model="email" type="email" name="email" placeholder="john@example.com" required autocomplete="email" maxlength="255" class="input" />
                                <template x-if="errors.email"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.email[0]"></p></template>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-phone" class="label">Phone <span class="text-secondary-400 font-normal">(optional)</span></label>
                                <input id="contact-phone" x-model="phone" type="tel" name="phone" placeholder="+1 (555) 000-0000" autocomplete="tel" maxlength="30" class="input" />
                                <template x-if="errors.phone"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.phone[0]"></p></template>
                            </div>
                            <div>
                                <label for="contact-subject" class="label">Subject <span class="text-red-500">*</span></label>
                                <input id="contact-subject" x-model="subject" type="text" name="subject" placeholder="How can we help you?" required maxlength="255" class="input" />
                                <template x-if="errors.subject"><p class="mt-1.5 text-sm text-red-500 dark:text-red-400" x-text="errors.subject[0]"></p></template>
                            </div>
                        </div>
                        <div>
                            <label for="contact-message" class="label">Message <span class="text-red-500">*</span></label>
                            <textarea id="contact-message" x-model="message" name="message" placeholder="Tell us more about your question or concern..." rows="6" required maxlength="5000" class="input resize-y"></textarea>
                            <div class="mt-1 flex items-center justify-between">
                                <template x-if="errors.message"><p class="text-sm text-red-500 dark:text-red-400" x-text="errors.message[0]"></p></template>
                                <span class="ml-auto text-xs text-secondary-400" x-text="message.length + '/5000'"></span>
                            </div>
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
                <div class="card !p-0 overflow-hidden">
                    <img src="{{ asset('images/pages/contact-support.jpg') }}" alt="Our friendly support team" loading="lazy" class="w-full h-44 object-cover">
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-1">{{ \App\Models\Setting::get('contact.support_card_title', 'Real humans, real help') }}</h3>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">{{ \App\Models\Setting::get('contact.support_card_text', "No bots, no runaround — your message goes straight to the people who can fix it.") }}</p>
                    </div>
                </div>

                <div class="card">
                    <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-5">Order Support</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-lg bg-primary-50 dark:bg-primary-950/30 shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm text-secondary-900 dark:text-white">Secure &amp; Private</h4>
                                <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-0.5">Your information is always protected.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-lg bg-primary-50 dark:bg-primary-950/30 shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm text-secondary-900 dark:text-white">Track your order</h4>
                                <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-0.5">Order status and tracking are available in <a href="{{ auth()->check() ? route('customer.orders.index') : route('login') }}" class="text-primary-600 dark:text-primary-400 font-medium hover:underline underline-offset-2">your account</a>.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-lg bg-primary-50 dark:bg-primary-950/30 shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm text-secondary-900 dark:text-white">Quick answers</h4>
                                <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-0.5">Many questions are answered in the <a href="/help-center" class="text-primary-600 dark:text-primary-400 font-medium hover:underline underline-offset-2">Help Center</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($companyContact['phone'])
                    <div class="card bg-gradient-to-br from-primary-50 to-primary-100/50 dark:from-primary-950/20 dark:to-primary-950/10 border-primary-100 dark:border-primary-900/30">
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-lg bg-primary-500 text-white shrink-0">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-secondary-900 dark:text-white text-sm">Need urgent help?</h3>
                                <p class="text-xs text-secondary-600 dark:text-secondary-400 mt-1">For order emergencies, call us directly at <strong class="text-primary-600 dark:text-primary-400">{{ $companyContact['phone'] }}</strong> during business hours.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if ($popularFaqs->isNotEmpty())
    <section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-secondary-900">
        <div class="section">
            <div class="text-center mb-10">
                <span class="section-eyebrow">FAQ</span>
                <h2 class="section-heading">Common Questions</h2>
                <p class="section-subheading mt-2 max-w-2xl mx-auto">Quick answers to questions customers ask most.</p>
            </div>
            <div class="max-w-4xl mx-auto">
                @include('frontend.partials.faq-accordion', ['faqs' => $popularFaqs])
                <div class="text-center mt-8">
                    <a href="/help-center" class="inline-flex items-center gap-2 text-sm font-semibold text-secondary-900 dark:text-white border-b border-secondary-300 dark:border-secondary-600 pb-0.5 hover:border-primary-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        View all FAQs
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('contactForm', () => ({
        name: '',
        email: '',
        phone: '',
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
                    body: JSON.stringify({ name: this.name, email: this.email, phone: this.phone, subject: this.subject, message: this.message })
                });
                const data = await res.json();
                this.submitting = false;
                if (res.ok && data.success) {
                    this.submitted = true;
                    setTimeout(() => { this.submitted = false; this.name = ''; this.email = ''; this.phone = ''; this.subject = ''; this.message = ''; }, 3000);
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
