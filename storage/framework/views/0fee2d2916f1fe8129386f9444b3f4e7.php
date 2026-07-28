<?php $__env->startSection('title', 'Contact Us - NBK Vertex'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-16 lg:py-24 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="text-center max-w-3xl mx-auto">
            <span class="badge-primary mb-4" data-i18n="Get in Touch" x-text="$store.i18n.t('Get in Touch')">Get in Touch</span>
            <h1 class="text-4xl lg:text-5xl font-bold text-secondary-900 dark:text-white mb-4">
                We'd love to <span class="text-primary-500 dark:text-primary-400">hear from you</span>
            </h1>
            <p class="text-lg text-secondary-500 dark:text-secondary-400 max-w-2xl mx-auto" data-i18n="Have a question, suggestion, or just want to say hello? We're here to help and would love to hear from you." x-text="$store.i18n.t('Have a question, suggestion, or just want to say hello? We\\'re here to help and would love to hear from you.')">Have a question, suggestion, or just want to say hello? We're here to help and would love to hear from you.</p>
        </div>
    </div>
</section>

<section class="py-16 lg:py-20">
    <div class="section">
        <div class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-secondary-900 dark:text-white" data-i18n="Send us a message" x-text="$store.i18n.t('Send us a message')">Send us a message</h2>
                        <p class="text-secondary-500 dark:text-secondary-400" data-i18n="Fill out the form below and we'll get back to you as soon as possible." x-text="$store.i18n.t('Fill out the form below and we\\'ll get back to you as soon as possible.')">Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>
                    <form x-data="{ name: '', email: '', subject: '', message: '', submitting: false, submitted: false }" x-on:submit.prevent="submitting = true; setTimeout(() => { submitting = false; submitted = true; setTimeout(() => { submitted = false; name = ''; email = ''; subject = ''; message = ''; }, 3000); }, 1000)" class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="label" data-i18n="Your Name" x-text="$store.i18n.t('Your Name')">Your Name</label>
                                <input x-model="name" type="text" name="name" placeholder="John Doe" required class="input" />
                            </div>
                            <div>
                                <label class="label" data-i18n="Your Email" x-text="$store.i18n.t('Your Email')">Your Email</label>
                                <input x-model="email" type="email" name="email" placeholder="john@example.com" required class="input" />
                            </div>
                        </div>
                        <div>
                            <label class="label" data-i18n="Subject" x-text="$store.i18n.t('Subject')">Subject</label>
                            <input x-model="subject" type="text" name="subject" placeholder="How can we help you?" required class="input" />
                        </div>
                        <div>
                            <label class="label" data-i18n="Your Message" x-text="$store.i18n.t('Your Message')">Your Message</label>
                            <textarea x-model="message" name="message" placeholder="Tell us more about your question or concern..." rows="5" required class="input resize-none"></textarea>
                        </div>
                        <button type="submit" :disabled="submitting || submitted" class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="submitting">
                                <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span> Sending...</span>
                            </template>
                            <template x-if="submitted">
                                <span class="flex items-center gap-2"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Message Sent!</span>
                            </template>
                            <template x-if="!submitting && !submitted">
                                <span class="flex items-center gap-2"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg> Send Message</span>
                            </template>
                        </button>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card">
                    <h2 class="text-xl font-semibold text-secondary-900 dark:text-white mb-4" data-i18n="Contact Information" x-text="$store.i18n.t('Contact Information')">Contact Information</h2>
                    <div class="space-y-5">
                        <?php
                        $contactInfo = [
                            ['icon' => 'mail', 'title' => 'Email Us', 'details' => ['hello@nbkvertex.com', 'support@nbkvertex.com'], 'description' => 'Send us an email anytime'],
                            ['icon' => 'phone', 'title' => 'Call Us', 'details' => ['+1 (555) 123-4567', '+1 (555) 987-6543'], 'description' => 'Mon-Fri from 8am to 5pm'],
                            ['icon' => 'map', 'title' => 'Visit Us', 'details' => ['123 Fashion Street', 'Style City, SC 12345'], 'description' => 'Come say hello at our office'],
                            ['icon' => 'clock', 'title' => 'Working Hours', 'details' => ['Monday - Friday: 9am - 6pm', 'Saturday: 10am - 4pm'], 'description' => 'Sunday: Closed'],
                        ];
                        ?>
                        <?php $__currentLoopData = $contactInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-4">
                                <div class="p-2 rounded-card bg-primary-50 dark:bg-primary-950/30">
                                    <?php if($info['icon'] === 'mail'): ?>
                                        <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                    <?php elseif($info['icon'] === 'phone'): ?>
                                        <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                    <?php elseif($info['icon'] === 'map'): ?>
                                        <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    <?php else: ?>
                                        <svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-secondary-900 dark:text-white mb-1"><?php echo e($info['title']); ?></h3>
                                    <?php $__currentLoopData = $info['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="text-sm text-secondary-500 dark:text-secondary-400"><?php echo e($detail); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1"><?php echo e($info['description']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="card">
                    <h2 class="text-xl font-semibold text-secondary-900 dark:text-white mb-4" data-i18n="Why Contact Us?" x-text="$store.i18n.t('Why Contact Us?')">Why Contact Us?</h2>
                    <div class="space-y-4">
                        <?php
                        $features = [
                            ['icon' => 'headphones', 'title' => '24/7 Support', 'description' => 'Get help whenever you need it'],
                            ['icon' => 'message', 'title' => 'Quick Response', 'description' => 'We reply within 2 hours'],
                            ['icon' => 'shield', 'title' => 'Secure & Private', 'description' => 'Your information is safe with us'],
                        ];
                        ?>
                        <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-3">
                                <div class="p-1.5 rounded-card bg-primary-50 dark:bg-primary-950/30">
                                    <?php if($feature['icon'] === 'headphones'): ?>
                                        <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"/></svg>
                                    <?php elseif($feature['icon'] === 'message'): ?>
                                        <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                                    <?php else: ?>
                                        <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-medium text-sm text-secondary-900 dark:text-white"><?php echo e($feature['title']); ?></h4>
                                    <p class="text-xs text-secondary-500 dark:text-secondary-400"><?php echo e($feature['description']); ?></p>
                                </div>
                            </div>
                            <?php if(!$loop->last): ?>
                                <div class="divider"></div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="text-center mb-12">
            <span class="badge-secondary mb-4">FAQ</span>
            <h2 class="section-heading" data-i18n="Frequently Asked Questions" x-text="$store.i18n.t('Frequently Asked Questions')">Frequently Asked Questions</h2>
            <p class="section-subheading mt-2 max-w-2xl mx-auto" data-i18n="Find quick answers to common questions about our products and services." x-text="$store.i18n.t('Find quick answers to common questions about our products and services.')">Find quick answers to common questions about our products and services.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <?php
            $faqs = [
                ['question' => 'What are your shipping policies?', 'answer' => 'We offer free shipping on orders over $50. Standard shipping takes 3-5 business days.'],
                ['question' => 'How can I track my order?', 'answer' => "Once your order ships, you'll receive a tracking number via email to monitor your package."],
                ['question' => 'What is your return policy?', 'answer' => 'We accept returns within 30 days of purchase. Items must be in original condition.'],
                ['question' => 'Do you offer international shipping?', 'answer' => 'Yes, we ship worldwide. International shipping rates vary by destination.'],
            ];
            ?>
            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card-hover">
                    <h3 class="font-semibold text-secondary-900 dark:text-white mb-2"><?php echo e($faq['question']); ?></h3>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400"><?php echo e($faq['answer']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\frontend\contact.blade.php ENDPATH**/ ?>