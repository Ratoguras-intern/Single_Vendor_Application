@extends('layouts.frontend')

@section('title', 'Contact Us - NBK Vertex')

@section('content')
<div style="background-color: var(--bloom-background);">
    {{-- Hero --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-orange-50 to-yellow-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <span class="mb-6 inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold shadow" style="background-color: var(--bloom-primary); color: var(--bloom-primary-foreground); border-color: transparent;"><span data-i18n="Get in Touch" x-text="$store.i18n.t('Get in Touch')">Get in Touch</span></span>
                <h1 class="text-4xl lg:text-6xl font-bold mb-6" style="color: var(--bloom-foreground);">We'd love to <span class="block lg:inline lg:ml-4" style="color: var(--bloom-primary);">hear from you</span></h1>
                <p class="text-lg max-w-2xl mx-auto" style="color: var(--bloom-muted-foreground);"><span data-i18n="Have a question, suggestion, or just want to say hello? We're here to help and would love to hear from you." x-text="$store.i18n.t('Have a question, suggestion, or just want to say hello? We\\'re here to help and would love to hear from you.')">Have a question, suggestion, or just want to say hello? We're here to help and would love to hear from you.</span></p>
            </div>
        </div>
    </section>

    {{-- Contact Form + Info --}}
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2">
                    <div class="rounded-xl border bg-white shadow" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold" style="color: var(--bloom-foreground);"><span data-i18n="Send us a message" x-text="$store.i18n.t('Send us a message')">Send us a message</span></h2>
                            <p style="color: var(--bloom-muted-foreground);"><span data-i18n="Fill out the form below and we'll get back to you as soon as possible." x-text="$store.i18n.t('Fill out the form below and we\\'ll get back to you as soon as possible.')">Fill out the form below and we'll get back to you as soon as possible.</span></p>
                        </div>
                        <div class="px-6 pb-6">
                            <form x-data="{ name: '', email: '', subject: '', message: '', submitting: false, submitted: false }" x-on:submit.prevent="submitting = true; setTimeout(() => { submitting = false; submitted = true; setTimeout(() => { submitted = false; name = ''; email = ''; subject = ''; message = ''; }, 3000); }, 1000)" class="space-y-6">
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label for="name" class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Your Name" x-text="$store.i18n.t('Your Name')">Your Name</span></label>
                                        <input x-model="name" type="text" id="name" name="name" placeholder="John Doe" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="email" class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Your Email" x-text="$store.i18n.t('Your Email')">Your Email</span></label>
                                        <input x-model="email" type="email" id="email" name="email" placeholder="john@example.com" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="subject" class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Subject" x-text="$store.i18n.t('Subject')">Subject</span></label>
                                    <input x-model="subject" type="text" id="subject" name="subject" placeholder="How can we help you?" required class="flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 md:text-sm" style="border-color: var(--bloom-input);" />
                                </div>

                                <div class="space-y-2">
                                    <label for="message" class="text-sm font-medium" style="color: var(--bloom-foreground);"><span data-i18n="Your Message" x-text="$store.i18n.t('Your Message')">Your Message</span></label>
                                    <textarea x-model="message" id="message" name="message" placeholder="Tell us more about your question or concern..." rows="6" required class="flex w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-400 md:text-sm resize-none" style="border-color: var(--bloom-input);"></textarea>
                                </div>

                                <button type="submit" :disabled="submitting || submitted" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors h-10 px-8 text-black shadow hover:opacity-90 disabled:pointer-events-none disabled:opacity-50" style="background-color: var(--bloom-primary);">
                                    <template x-if="submitting">
                                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span> <span data-i18n="Sending..." x-text="$store.i18n.t('Sending...')">Sending...</span></span>
                                    </template>
                                    <template x-if="submitted">
                                        <span class="flex items-center gap-2"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> <span data-i18n="Message Sent!" x-text="$store.i18n.t('Message Sent!')">Message Sent!</span></span>
                                    </template>
                                    <template x-if="!submitting && !submitted">
                                        <span class="flex items-center gap-2"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg> <span data-i18n="Send Message" x-text="$store.i18n.t('Send Message')">Send Message</span></span>
                                    </template>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    {{-- Contact Information --}}
                    <div class="rounded-xl border bg-white shadow" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold" style="color: var(--bloom-foreground);"><span data-i18n="Contact Information" x-text="$store.i18n.t('Contact Information')">Contact Information</span></h2>
                        </div>
                        <div class="px-6 pb-6 space-y-6">
                            @php
                            $contactInfo = [
                                ['icon' => 'mail', 'title' => 'Email Us', 'details' => ['hello@nbkvertex.com', 'support@nbkvertex.com'], 'description' => 'Send us an email anytime'],
                                ['icon' => 'phone', 'title' => 'Call Us', 'details' => ['+1 (555) 123-4567', '+1 (555) 987-6543'], 'description' => 'Mon-Fri from 8am to 5pm'],
                                ['icon' => 'map', 'title' => 'Visit Us', 'details' => ['123 Fashion Street', 'Style City, SC 12345'], 'description' => 'Come say hello at our office'],
                                ['icon' => 'clock', 'title' => 'Working Hours', 'details' => ['Monday - Friday: 9am - 6pm', 'Saturday: 10am - 4pm'], 'description' => 'Sunday: Closed'],
                            ];
                            @endphp
                            @foreach($contactInfo as $info)
                                <div class="flex items-start gap-4">
                                    <div class="p-2 rounded-lg" style="background-color: rgba(232, 155, 45, 0.1);">
                                        @if($info['icon'] === 'mail')
                                            <svg class="h-5 w-5" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                        @elseif($info['icon'] === 'phone')
                                            <svg class="h-5 w-5" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                        @elseif($info['icon'] === 'map')
                                            <svg class="h-5 w-5" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                        @else
                                            <svg class="h-5 w-5" style="color: var(--bloom-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold mb-1" style="color: var(--bloom-foreground);">{{ $info['title'] }}</h3>
                                        @foreach($info['details'] as $detail)
                                            <p class="text-sm" style="color: var(--bloom-muted-foreground);">{{ $detail }}</p>
                                        @endforeach
                                        <p class="text-xs mt-1" style="color: var(--bloom-muted-foreground);">{{ $info['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Why Contact Us --}}
                    <div class="rounded-xl border bg-white shadow" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold" style="color: var(--bloom-foreground);"><span data-i18n="Why Contact Us?" x-text="$store.i18n.t('Why Contact Us?')">Why Contact Us?</span></h2>
                        </div>
                        <div class="px-6 pb-6 space-y-4">
                            @php
                            $features = [
                                ['icon' => 'headphones', 'title' => '24/7 Support', 'description' => 'Get help whenever you need it'],
                                ['icon' => 'message', 'title' => 'Quick Response', 'description' => 'We reply within 2 hours'],
                                ['icon' => 'shield', 'title' => 'Secure & Private', 'description' => 'Your information is safe with us'],
                            ];
                            @endphp
                            @foreach($features as $index => $feature)
                                <div>
                                    <div class="flex items-start gap-3">
                                        <div class="p-1 rounded" style="background-color: rgba(253, 246, 227, 0.1);">
                                            @if($feature['icon'] === 'headphones')
                                                <svg class="h-4 w-4" style="color: var(--bloom-accent-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"/></svg>
                                            @elseif($feature['icon'] === 'message')
                                                <svg class="h-4 w-4" style="color: var(--bloom-accent-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                                            @else
                                                <svg class="h-4 w-4" style="color: var(--bloom-accent-foreground);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm" style="color: var(--bloom-foreground);">{{ $feature['title'] }}</h4>
                                            <p class="text-xs" style="color: var(--bloom-muted-foreground);">{{ $feature['description'] }}</p>
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="mt-4 h-[1px] w-full shrink-0" style="background-color: var(--bloom-border);"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-16 lg:py-24" style="background-color: var(--bloom-muted);">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="mb-6 inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold" style="color: var(--bloom-foreground); border-color: var(--bloom-border);">FAQ</span>
                <h2 class="text-3xl lg:text-4xl font-bold mb-4" style="color: var(--bloom-foreground);"><span data-i18n="Frequently Asked Questions" x-text="$store.i18n.t('Frequently Asked Questions')">Frequently Asked Questions</span></h2>
                <p class="text-lg max-w-2xl mx-auto" style="color: var(--bloom-muted-foreground);"><span data-i18n="Find quick answers to common questions about our products and services." x-text="$store.i18n.t('Find quick answers to common questions about our products and services.')">Find quick answers to common questions about our products and services.</span></p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @php
                $faqs = [
                    ['question' => 'What are your shipping policies?', 'answer' => 'We offer free shipping on orders over $50. Standard shipping takes 3-5 business days.'],
                    ['question' => 'How can I track my order?', 'answer' => "Once your order ships, you'll receive a tracking number via email to monitor your package."],
                    ['question' => 'What is your return policy?', 'answer' => 'We accept returns within 30 days of purchase. Items must be in original condition.'],
                    ['question' => 'Do you offer international shipping?', 'answer' => 'Yes, we ship worldwide. International shipping rates vary by destination.'],
                ];
                @endphp
                @foreach($faqs as $faq)
                    <div class="rounded-xl border bg-white shadow hover:shadow-md transition-shadow" style="border-color: var(--bloom-border); background-color: var(--bloom-card);">
                        <div class="p-6">
                            <h3 class="font-semibold mb-3" style="color: var(--bloom-foreground);">{{ $faq['question'] }}</h3>
                            <p class="text-sm" style="color: var(--bloom-muted-foreground);">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    @include('frontend.partials.newsletter')
</div>
@endsection
