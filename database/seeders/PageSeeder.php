<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Customer Care
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'footer_section' => 'customer-care',
                'short_description' => 'Have a question or need assistance? We are here to help you with anything related to your orders, account, or our products.',
                'content' => $this->contactUsContent(),
                'seo_title' => 'Contact Us - NBK Vertex',
                'seo_description' => 'Get in touch with NBK Vertex support team. We are available 24/7 to help with orders, returns, and general inquiries.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 1,
            ],
            [
                'title' => 'Help Center',
                'slug' => 'help-center',
                'footer_section' => 'customer-care',
                'short_description' => 'Find answers to frequently asked questions about orders, payments, shipping, returns, and your account.',
                'content' => $this->helpCenterContent(),
                'seo_title' => 'Help Center - NBK Vertex',
                'seo_description' => 'Browse our help center for answers to common questions about shopping, orders, payments, and account management.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 2,
            ],
            [
                'title' => 'Shipping Info',
                'slug' => 'shipping-info',
                'footer_section' => 'customer-care',
                'short_description' => 'Learn about our shipping options, delivery times, costs, and how to track your order.',
                'content' => $this->shippingInfoContent(),
                'seo_title' => 'Shipping Information - NBK Vertex',
                'seo_description' => 'Complete shipping guide including delivery times, costs, tracking information, and international shipping policies.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 3,
            ],
            [
                'title' => 'Returns & Exchanges',
                'slug' => 'returns-exchanges',
                'footer_section' => 'customer-care',
                'short_description' => 'Not satisfied with your purchase? Learn about our hassle-free return and exchange policies.',
                'content' => $this->returnsContent(),
                'seo_title' => 'Returns & Exchanges - NBK Vertex',
                'seo_description' => 'Easy returns and exchanges within 30 days. Learn about our return policy, process, and refund timelines.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 4,
            ],

            // Company
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'footer_section' => 'company',
                'short_description' => 'Discover the story behind NBK Vertex, our mission, values, and commitment to delivering quality products.',
                'content' => $this->aboutUsContent(),
                'seo_title' => 'About Us - NBK Vertex',
                'seo_description' => 'Learn about NBK Vertex, our mission to provide quality products, and our commitment to customer satisfaction.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 1,
            ],
            [
                'title' => 'Careers',
                'slug' => 'careers',
                'footer_section' => 'company',
                'short_description' => 'Join our growing team! Explore open positions and learn about our company culture and benefits.',
                'content' => $this->careersContent(),
                'seo_title' => 'Careers - NBK Vertex',
                'seo_description' => 'Join the NBK Vertex team. Explore career opportunities, company culture, and employee benefits.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 2,
            ],
            [
                'title' => 'Blog',
                'slug' => 'blog',
                'footer_section' => 'company',
                'short_description' => 'Stay updated with the latest news, trends, and insights from the world of eCommerce and retail.',
                'content' => $this->blogContent(),
                'seo_title' => 'Blog - NBK Vertex',
                'seo_description' => 'Read the latest articles, news, and insights from NBK Vertex on eCommerce, products, and industry trends.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 3,
            ],
            [
                'title' => 'Press',
                'slug' => 'press',
                'footer_section' => 'company',
                'short_description' => 'Read our latest press releases, company announcements, and media coverage.',
                'content' => $this->pressContent(),
                'seo_title' => 'Press - NBK Vertex',
                'seo_description' => 'NBK Vertex press releases, media kit, and company announcements. For media inquiries, contact our PR team.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 4,
            ],

            // Legal
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'footer_section' => 'legal',
                'short_description' => 'Learn how we collect, use, and protect your personal information.',
                'content' => $this->privacyPolicyContent(),
                'seo_title' => 'Privacy Policy - NBK Vertex',
                'seo_description' => 'NBK Vertex privacy policy. Understand how we handle your data, cookies, and personal information.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 1,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'footer_section' => 'legal',
                'short_description' => 'Read the terms and conditions governing your use of our website and services.',
                'content' => $this->termsContent(),
                'seo_title' => 'Terms & Conditions - NBK Vertex',
                'seo_description' => 'Terms and conditions for using the NBK Vertex website and services. Please read carefully before placing an order.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 2,
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'footer_section' => 'legal',
                'short_description' => 'Understand how we use cookies to improve your browsing experience.',
                'content' => $this->cookiePolicyContent(),
                'seo_title' => 'Cookie Policy - NBK Vertex',
                'seo_description' => 'NBK Vertex cookie policy. Learn about the cookies we use and how to manage your cookie preferences.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 3,
            ],
            [
                'title' => 'Accessibility',
                'slug' => 'accessibility',
                'footer_section' => 'legal',
                'short_description' => 'Our commitment to making our website accessible to everyone.',
                'content' => $this->accessibilityContent(),
                'seo_title' => 'Accessibility Statement - NBK Vertex',
                'seo_description' => 'NBK Vertex accessibility statement. Our commitment to making our website usable by all people.',
                'status' => 'published',
                'show_in_footer' => true,
                'footer_order' => 4,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }

    private function contactUsContent(): string
    {
        return '
<div class="not-prose">
<div class="grid md:grid-cols-2 gap-10">
<div>
<h2>Get in Touch</h2>
<p>We are here to help with orders, product questions, account issues, and general enquiries. Choose the channel that works best for you.</p>

<h3>Customer Support</h3>
<ul>
<li><strong>Email:</strong> support@nbkvertex.com</li>
<li><strong>Phone:</strong> +1 (555) 123-4567</li>
<li><strong>Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM (EST)</li>
<li><strong>Saturday:</strong> 10:00 AM - 4:00 PM (EST)</li>
<li><strong>Sunday:</strong> Closed</li>
<li><strong>Response Time:</strong> Within 1 business day</li>
</ul>

<h3>Other Enquiries</h3>
<ul>
<li><strong>Privacy & Data:</strong> privacy@nbkvertex.com</li>
<li><strong>Business Inquiries:</strong> business@nbkvertex.com</li>
<li><strong>Accessibility:</strong> accessibility@nbkvertex.com</li>
</ul>

<h3>Visit Us</h3>
<p>123 Fashion Street<br>Style City, SC 12345<br>United States</p>
</div>

<div>
<h2>How Can We Help?</h2>
<p>When contacting us, including your order number (if applicable) helps us resolve your enquiry faster.</p>

<h3>Common Topics</h3>
<div class="space-y-3">
<div class="p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Order Issues</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Track, modify, or report a problem with your order</div>
</div>
<div class="p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Shipping Questions</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Delivery times, costs, and international shipping</div>
</div>
<div class="p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Returns & Refunds</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">How to start a return and refund timelines</div>
</div>
<div class="p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Product Questions</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Specifications, sizing, and availability</div>
</div>
<div class="p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Account Support</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Login, password reset, and profile updates</div>
</div>
</div>

<p class="mt-4 text-sm text-secondary-500 dark:text-secondary-400">Looking for quick answers? Visit our <a href="/help-center" class="text-primary-600 dark:text-primary-400 hover:underline">Help Center</a> for FAQs.</p>
</div>
</div>
</div>';
    }

    private function helpCenterContent(): string
    {
        return '
<div class="not-prose mb-8">
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
<a href="#orders" class="flex items-center gap-3 p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all group">
<div class="w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center shrink-0 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/40 transition-colors">
<svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Orders & Tracking</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">4 articles</div>
</div>
</a>
<a href="#payments" class="flex items-center gap-3 p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all group">
<div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center shrink-0 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40 transition-colors">
<svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Payments</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">3 articles</div>
</div>
</a>
<a href="#shipping" class="flex items-center gap-3 p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all group">
<div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center shrink-0 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 transition-colors">
<svg class="h-5 w-5 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Shipping & Delivery</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">2 articles</div>
</div>
</a>
<a href="#returns" class="flex items-center gap-3 p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all group">
<div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center shrink-0 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/40 transition-colors">
<svg class="h-5 w-5 text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Returns & Refunds</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">3 articles</div>
</div>
</a>
<a href="#account" class="flex items-center gap-3 p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all group">
<div class="w-10 h-10 rounded-lg bg-violet-50 dark:bg-violet-950/30 flex items-center justify-center shrink-0 group-hover:bg-violet-100 dark:group-hover:bg-violet-900/40 transition-colors">
<svg class="h-5 w-5 text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Account & Security</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">3 articles</div>
</div>
</a>
<a href="#products" class="flex items-center gap-3 p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all group">
<div class="w-10 h-10 rounded-lg bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center shrink-0 group-hover:bg-rose-100 dark:group-hover:bg-rose-900/40 transition-colors">
<svg class="h-5 w-5 text-rose-500 dark:text-rose-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Product Information</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">3 articles</div>
</div>
</a>
</div>
</div>

<h2 id="orders">Orders & Tracking</h2>

<h3>How do I place an order?</h3>
<p>Browse our shop, add items to your cart, and proceed to checkout. You can checkout as a guest or create an account for a faster experience.</p>

<h3>How do I track my order?</h3>
<p>After your order ships, you will receive an email with a tracking number. You can enter this number on the carrier website or view tracking information in your account dashboard.</p>

<h3>Can I cancel my order?</h3>
<p>You can cancel your order within 2 hours of placing it. After that, the order may have already entered processing. Contact support for assistance.</p>

<h3>What if my order is delayed?</h3>
<p>Most orders ship within 1-2 business days. If your order is significantly delayed, please contact our support team with your order number.</p>

<h2 id="payments">Payments</h2>

<h3>What payment methods are accepted?</h3>
<p>We accept Visa, Mastercard, PayPal, Stripe, and Cash on Delivery (COD). All online payments are processed securely through encrypted connections.</p>

<h3>Is my payment information secure?</h3>
<p>Yes. We use industry-standard SSL encryption to protect your payment details. We never store your full credit card number on our servers.</p>

<h3>Do you offer installment payments?</h3>
<p>We are working on introducing installment payment options. Currently, you can use PayPal Pay Later for qualifying purchases.</p>

<h2 id="shipping">Shipping & Delivery</h2>

<h3>How long does shipping take?</h3>
<p>Standard shipping: 3-5 business days. Express shipping: 1-2 business days. International shipping: 7-14 business days depending on destination.</p>

<h3>How much does shipping cost?</h3>
<p>Standard shipping is free on orders over $50. Express shipping starts at $9.99. International rates are calculated at checkout based on destination and weight.</p>

<h2 id="returns">Returns & Refunds</h2>

<h3>What is your return policy?</h3>
<p>We accept returns within 30 days of delivery. Items must be unused, in original packaging, with tags attached. Some items like personalized products and intimate apparel are non-returnable.</p>

<h3>How do I initiate a return?</h3>
<p>Log into your account, go to "My Orders," select the order, and click "Return Item." Follow the prompts to select items and reason for return.</p>

<h3>When will I receive my refund?</h3>
<p>Refunds are processed within 5-7 business days after we receive and inspect the returned item. The refund will be credited to your original payment method.</p>

<h2 id="account">Account & Security</h2>

<h3>How do I create an account?</h3>
<p>Click "Sign Up" in the top navigation. You can register with your email address or use social login with Google or Facebook.</p>

<h3>I forgot my password. What do I do?</h3>
<p>Click "Forgot Password" on the login page. Enter your email address, and we will send you a link to reset your password.</p>

<h3>How do I update my account information?</h3>
<p>Log into your account and go to "Profile Settings." From there, you can update your name, email, phone number, and addresses.</p>

<h2 id="products">Product Information</h2>

<h3>Are product colors accurate?</h3>
<p>We make every effort to display colors as accurately as possible. However, colors may appear slightly different on your screen due to monitor settings.</p>

<h3>Do you offer product warranties?</h3>
<p>Most products come with a standard 90-day manufacturer warranty. Premium items may include extended warranties. Check the product page for specific warranty information.</p>

<h3>How do I find my size?</h3>
<p>Each product page includes a size guide. Measure yourself and compare with our size chart for the best fit. If you are between sizes, we recommend sizing up.</p>';
    }

    private function shippingInfoContent(): string
    {
        return '
<div class="not-prose mb-8">
<div class="grid sm:grid-cols-3 gap-4">
<div class="p-5 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-950/30 flex items-center justify-center mb-3">
<svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
</div>
<div class="font-bold text-secondary-900 dark:text-white mb-1">Standard Shipping</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mb-2">3-5 business days</div>
<div class="text-sm font-semibold text-primary-600 dark:text-primary-400">Free over $50</div>
<div class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Otherwise $5.99</div>
</div>
<div class="p-5 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-primary-200 dark:border-primary-800/40 relative">
<div class="absolute top-3 right-3"><span class="text-[10px] font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-950/30 px-2 py-0.5 rounded-full">Popular</span></div>
<div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-950/30 flex items-center justify-center mb-3">
<svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
</div>
<div class="font-bold text-secondary-900 dark:text-white mb-1">Express Shipping</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mb-2">1-2 business days</div>
<div class="text-sm font-semibold text-primary-600 dark:text-primary-400">$9.99 flat rate</div>
<div class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Real-time tracking</div>
</div>
<div class="p-5 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-950/30 flex items-center justify-center mb-3">
<svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
</div>
<div class="font-bold text-secondary-900 dark:text-white mb-1">Overnight Shipping</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mb-2">Next business day</div>
<div class="text-sm font-semibold text-primary-600 dark:text-primary-400">$19.99 flat rate</div>
<div class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Order before 2 PM EST</div>
</div>
</div>
</div>

<h2>Order Processing</h2>
<p>All orders are processed within 1-2 business days. Orders placed on weekends or holidays will be processed on the next business day. You will receive a confirmation email once your order has been shipped.</p>

<h2>Estimated Delivery Times</h2>
<table>
<thead><tr><th>Region</th><th>Standard</th><th>Express</th></tr></thead>
<tbody>
<tr><td>Continental US</td><td>3-5 days</td><td>1-2 days</td></tr>
<tr><td>Alaska & Hawaii</td><td>5-7 days</td><td>2-3 days</td></tr>
<tr><td>Canada</td><td>5-10 days</td><td>3-5 days</td></tr>
<tr><td>Europe</td><td>7-14 days</td><td>5-7 days</td></tr>
<tr><td>Asia Pacific</td><td>10-14 days</td><td>5-10 days</td></tr>
</tbody>
</table>

<h2>International Shipping</h2>
<p>We ship to over 50 countries worldwide. International shipping rates are calculated at checkout based on destination, weight, and dimensions. Please note:</p>
<ul>
<li>International orders may be subject to customs duties and taxes, which are the responsibility of the recipient</li>
<li>Delivery times for international orders may vary due to customs processing</li>
<li>We are not responsible for packages held by customs in the destination country</li>
</ul>

<h2>Tracking Your Order</h2>
<p>Once your order ships, you will receive an email with a tracking number. You can also track your order by:</p>
<ul>
<li>Logging into your account and visiting "My Orders"</li>
<li>Entering your tracking number on the carrier website</li>
<li>Contacting our support team with your order number</li>
</ul>

<h2>Shipping Delays & Exceptions</h2>
<p>While we strive to deliver all orders on time, delays may occur due to:</p>
<ul>
<li>Severe weather conditions</li>
<li>Natural disasters</li>
<li>Carrier disruptions</li>
<li>Holiday peak seasons</li>
<li>Incorrect shipping addresses</li>
</ul>
<p>If your order is significantly delayed, please contact our support team for assistance.</p>

<h2>Lost or Damaged Packages</h2>
<p>If your package appears to be lost or arrives damaged, please contact us within 48 hours of the expected delivery date. We will investigate and arrange a replacement or refund as appropriate.</p>';
    }

    private function returnsContent(): string
    {
        return '
<div class="not-prose mb-8">
<div class="grid sm:grid-cols-3 gap-4 mb-6">
<div class="p-5 rounded-xl bg-primary-50 dark:bg-primary-950/20 border border-primary-100 dark:border-primary-900/30 text-center">
<div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center mx-auto mb-3">
<span class="text-lg font-bold text-primary-600 dark:text-primary-400">30</span>
</div>
<div class="font-semibold text-secondary-900 dark:text-white text-sm">Day Return Window</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">From delivery date</div>
</div>
<div class="p-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-center">
<div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mx-auto mb-3">
<svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
</div>
<div class="font-semibold text-secondary-900 dark:text-white text-sm">Eligibility Check</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">Unused, tags attached</div>
</div>
<div class="p-5 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 text-center">
<div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center mx-auto mb-3">
<span class="text-sm font-bold text-blue-600 dark:text-blue-400">5-7d</span>
</div>
<div class="font-semibold text-secondary-900 dark:text-white text-sm">Refund Processing</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">After inspection</div>
</div>
</div>

<h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-4">How It Works</h3>
<div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-0 mb-8">
<div class="flex items-center gap-3 flex-1">
<div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-sm font-bold shrink-0">1</div>
<div><div class="font-semibold text-sm text-secondary-900 dark:text-white">Submit Request</div><div class="text-xs text-secondary-500 dark:text-secondary-400">From your account</div></div>
</div>
<svg class="h-5 w-5 text-secondary-300 dark:text-secondary-600 shrink-0 hidden sm:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
<div class="flex items-center gap-3 flex-1">
<div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-sm font-bold shrink-0">2</div>
<div><div class="font-semibold text-sm text-secondary-900 dark:text-white">Print Label</div><div class="text-xs text-secondary-500 dark:text-secondary-400">Prepaid included</div></div>
</div>
<svg class="h-5 w-5 text-secondary-300 dark:text-secondary-600 shrink-0 hidden sm:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
<div class="flex items-center gap-3 flex-1">
<div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-sm font-bold shrink-0">3</div>
<div><div class="font-semibold text-sm text-secondary-900 dark:text-white">Ship Item</div><div class="text-xs text-secondary-500 dark:text-secondary-400">Drop off package</div></div>
</div>
<svg class="h-5 w-5 text-secondary-300 dark:text-secondary-600 shrink-0 hidden sm:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
<div class="flex items-center gap-3 flex-1">
<div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-sm font-bold shrink-0">4</div>
<div><div class="font-semibold text-sm text-secondary-900 dark:text-white">Get Refund</div><div class="text-xs text-secondary-500 dark:text-secondary-400">5-7 business days</div></div>
</div>
</div>
</div>

<h2>Return Policy Overview</h2>
<p>We want you to love your purchase. If you are not completely satisfied, we accept returns within 30 days of delivery for a full refund or exchange.</p>

<h2>Return Period</h2>
<ul>
<li><strong>Standard Returns:</strong> 30 days from delivery date</li>
<li><strong>Holiday Returns:</strong> Items purchased between November 15 and December 31 can be returned through January 31</li>
<li><strong>Defective Items:</strong> 90 days from delivery date</li>
</ul>

<h2>Eligible Products</h2>
<p>The following items can be returned:</p>
<ul>
<li>Unused items in original packaging</li>
<li>Items with all original tags attached</li>
<li>Items in original condition (unworn, unwashed, unaltered)</li>
<li>Items in original box or packaging</li>
</ul>

<h2>Non-Returnable Products</h2>
<p>The following items cannot be returned:</p>
<ul>
<li>Personalized or custom-made items</li>
<li>Intimate apparel and swimwear</li>
<li>Gift cards</li>
<li>Final sale items (marked as "Non-Returnable")</li>
<li>Items without original tags or packaging</li>
<li>Items that have been worn, washed, or altered</li>
</ul>

<h2>Exchanges</h2>
<p>We offer free exchanges for a different size or color of the same item. To request an exchange:</p>
<ol>
<li>Initiate a return for the original item</li>
<li>Select "Exchange" and choose the new size or color</li>
<li>We will ship the new item as soon as we receive the original</li>
</ol>

<h2>Refund Processing</h2>
<ul>
<li><strong>Credit/Debit Card:</strong> 5-7 business days after inspection</li>
<li><strong>PayPal:</strong> 3-5 business days after inspection</li>
<li><strong>Store Credit:</strong> Instant upon inspection</li>
</ul>
<p>Refunds are issued to the original payment method. Shipping charges are non-refundable unless the return is due to our error.</p>

<h2>Damaged or Incorrect Items</h2>
<p>If you received a damaged or incorrect item, please contact us within 48 hours of delivery with:</p>
<ul>
<li>Your order number</li>
<li>Photos of the damaged or incorrect item</li>
<li>Description of the issue</li>
</ul>
<p>We will arrange a free return pickup and send a replacement or issue a full refund including shipping costs.</p>';
    }

    private function aboutUsContent(): string
    {
        return '
<div class="not-prose mb-8">
<div class="grid sm:grid-cols-4 gap-4">
<div class="p-5 rounded-xl bg-primary-50 dark:bg-primary-950/20 border border-primary-100 dark:border-primary-900/30 text-center">
<div class="text-2xl font-bold text-primary-600 dark:text-primary-400 mb-1">2020</div>
<div class="text-sm font-medium text-secondary-900 dark:text-white">Founded</div>
</div>
<div class="p-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-center">
<div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">50,000+</div>
<div class="text-sm font-medium text-secondary-900 dark:text-white">Customers</div>
</div>
<div class="p-5 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 text-center">
<div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">50+</div>
<div class="text-sm font-medium text-secondary-900 dark:text-white">Countries Served</div>
</div>
<div class="p-5 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 text-center">
<div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mb-1">10,000+</div>
<div class="text-sm font-medium text-secondary-900 dark:text-white">Products</div>
</div>
</div>
</div>

<h2>Our Story</h2>
<p>Founded in 2020, NBK Vertex began with a straightforward goal: make quality products easier to discover and purchase online. What started as a small curated catalogue has grown into a trusted eCommerce platform serving tens of thousands of customers across more than 50 countries.</p>
<p>We noticed that online shopping often felt fragmented, overwhelming, or impersonal. We set out to build a platform where product information is clear, pricing is honest, and every customer can shop with confidence.</p>

<h2>Our Mission</h2>
<p>To provide a shopping experience built on simplicity, reliability, and trust. We focus on curating quality products, offering transparent information, and delivering the kind of customer service that keeps people coming back.</p>

<h2>Our Values</h2>

<h3>Customer First</h3>
<p>Every decision at NBK Vertex starts with the customer. From how we design our website to how we handle returns, we ask ourselves whether this makes the experience better for the people who shop with us.</p>

<h3>Quality and Transparency</h3>
<p>We carefully select products and suppliers. Every item in our catalogue goes through a review process, and we provide detailed, accurate descriptions so customers know exactly what they are buying.</p>

<h3>Continuous Improvement</h3>
<p>We are never finished improving. Whether it is faster checkout, better product recommendations, or more responsive support, we actively seek feedback and invest in making the platform better.</p>

<h3>Responsible Service</h3>
<p>We take our obligations seriously. That means protecting customer data, honouring our return policies, and operating with integrity in every transaction.</p>

<h2>Why Shop With Us</h2>
<ul>
<li><strong>Curated Selection:</strong> Products are reviewed and selected for quality and value</li>
<li><strong>Clear Information:</strong> Detailed descriptions, specifications, and sizing guides</li>
<li><strong>Honest Pricing:</strong> No hidden fees, and shipping costs are calculated transparently at checkout</li>
<li><strong>Reliable Support:</strong> Our team is available during business hours via email and phone</li>
<li><strong>Easy Returns:</strong> 30-day return policy with straightforward process</li>
<li><strong>Secure Shopping:</strong> Industry-standard encryption and trusted payment partners</li>
</ul>

<h2>Our Team</h2>
<p>NBK Vertex is built by a small, dedicated team of eCommerce professionals, designers, and customer support specialists. We work remotely across multiple time zones to ensure coverage during extended business hours.</p>

<h2>Get in Touch</h2>
<p>Have a question about who we are or what we do? Reach us at <strong>hello@nbkvertex.com</strong> or visit our <a href="/contact-us">Contact Us</a> page for support options.</p>';
    }

    private function careersContent(): string
    {
        return '
<div class="not-prose mb-8">
<div class="grid sm:grid-cols-2 gap-4">
<div class="flex items-start gap-3 p-4 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-950/30 flex items-center justify-center shrink-0">
<svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Career Growth</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Advance your professional journey</div>
</div>
</div>
<div class="flex items-start gap-3 p-4 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-950/30 flex items-center justify-center shrink-0">
<svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Flexible Work</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Remote and hybrid options</div>
</div>
</div>
<div class="flex items-start gap-3 p-4 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-950/30 flex items-center justify-center shrink-0">
<svg class="h-5 w-5 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Collaborative Team</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">Work with talented people</div>
</div>
</div>
<div class="flex items-start gap-3 p-4 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700">
<div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-950/30 flex items-center justify-center shrink-0">
<svg class="h-5 w-5 text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
</div>
<div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Learning Opportunities</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400">$2,000/year development budget</div>
</div>
</div>
</div>
</div>

<h2>Join Our Team</h2>
<p>At NBK Vertex, we are always looking for talented, passionate individuals to join our growing team. We offer a collaborative work environment where creativity thrives and every voice matters.</p>

<h2>Company Culture</h2>
<p>We believe that great products come from great teams. Our culture is built on:</p>
<ul>
<li><strong>Innovation:</strong> We encourage creative thinking and bold ideas</li>
<li><strong>Collaboration:</strong> We work together across teams to achieve our goals</li>
<li><strong>Growth:</strong> We invest in our people through mentorship and learning opportunities</li>
<li><strong>Balance:</strong> We value work-life balance and flexible working arrangements</li>
<li><strong>Impact:</strong> We celebrate wins and learn from challenges together</li>
</ul>

<h2>Benefits & Perks</h2>
<ul>
<li>Competitive salary and performance bonuses</li>
<li>Comprehensive health, dental, and vision insurance</li>
<li>Flexible remote and hybrid work options</li>
<li>Generous PTO policy (20 days + holidays)</li>
<li>Professional development budget ($2,000/year)</li>
<li>Employee discount on all products (30% off)</li>
<li>Wellness program and gym membership</li>
<li>Team building events and company retreats</li>
</ul>

<h2>Open Positions</h2>

<h3>Senior Full-Stack Developer</h3>
<p><strong>Location:</strong> Remote / New York, NY &nbsp;|&nbsp; <strong>Type:</strong> Full-time</p>
<p>We are looking for an experienced full-stack developer to help build and scale our eCommerce platform. You will work with Laravel, Vue.js, and cloud infrastructure.</p>
<ul>
<li>5+ years of experience with PHP/Laravel</li>
<li>Strong knowledge of MySQL, Redis, and queues</li>
<li>Experience with front-end frameworks (Vue.js, React)</li>
<li>Understanding of cloud services (AWS, GCP)</li>
</ul>

<h3>Product Manager</h3>
<p><strong>Location:</strong> Remote / New York, NY &nbsp;|&nbsp; <strong>Type:</strong> Full-time</p>
<p>Lead product strategy and execution for our core shopping experience.</p>
<ul>
<li>3+ years of product management experience</li>
<li>eCommerce or retail experience preferred</li>
<li>Data-driven decision making</li>
<li>Strong communication and leadership skills</li>
</ul>

<h3>Customer Experience Specialist</h3>
<p><strong>Location:</strong> Remote &nbsp;|&nbsp; <strong>Type:</strong> Full-time</p>
<p>Be the face of NBK Vertex to our customers.</p>
<ul>
<li>1+ years of customer service experience</li>
<li>Excellent written and verbal communication</li>
<li>Problem-solving mindset</li>
</ul>

<h3>Marketing Coordinator</h3>
<p><strong>Location:</strong> New York, NY &nbsp;|&nbsp; <strong>Type:</strong> Full-time</p>
<p>Support our marketing campaigns across email, social media, and paid channels.</p>
<ul>
<li>1-2 years of marketing experience</li>
<li>Knowledge of digital marketing channels</li>
<li>Creative thinking and attention to detail</li>
</ul>

<h2>How to Apply</h2>
<p>Send your resume and a brief cover letter to <strong>careers@nbkvertex.com</strong> with the position title in the subject line. We review applications on a rolling basis and aim to respond within one week.</p>';
    }

    private function blogContent(): string
    {
        return '
<div class="not-prose">
<p class="mb-6">Stay informed with our latest articles, product guides, and industry insights.</p>

<div class="grid gap-6 sm:grid-cols-2">
<div class="rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden hover:shadow-lg transition-shadow group">
<div class="bg-gradient-to-br from-primary-500 to-primary-600 h-40 flex items-center justify-center relative overflow-hidden">
<div class="absolute inset-0 bg-primary-600/20"></div>
<span class="text-white/90 text-4xl font-bold relative z-10 font-display">01</span>
</div>
<div class="p-5">
<span class="badge-primary text-[10px]">Product Guide</span>
<h3 class="text-base font-bold text-secondary-900 dark:text-white mt-2 mb-2">How to Choose the Right Laptop for Your Needs</h3>
<p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">Buying a laptop can be overwhelming. We break down the key specifications and features to consider based on your usage.</p>
<div class="flex items-center justify-between mt-4 pt-3 border-t border-secondary-100 dark:border-secondary-700">
<span class="text-xs text-secondary-400 dark:text-secondary-500">August 12, 2026</span>
<span class="text-xs font-semibold text-primary-600 dark:text-primary-400 group-hover:underline cursor-pointer">Read more</span>
</div>
</div>
</div>

<div class="rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden hover:shadow-lg transition-shadow group">
<div class="bg-gradient-to-br from-blue-500 to-blue-600 h-40 flex items-center justify-center relative overflow-hidden">
<div class="absolute inset-0 bg-blue-600/20"></div>
<span class="text-white/90 text-4xl font-bold relative z-10 font-display">02</span>
</div>
<div class="p-5">
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400">Industry Trends</span>
<h3 class="text-base font-bold text-secondary-900 dark:text-white mt-2 mb-2">The Future of Online Shopping: What to Expect in 2027</h3>
<p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">From AI-powered recommendations to AR try-ons, explore the trends shaping the future of eCommerce.</p>
<div class="flex items-center justify-between mt-4 pt-3 border-t border-secondary-100 dark:border-secondary-700">
<span class="text-xs text-secondary-400 dark:text-secondary-500">August 5, 2026</span>
<span class="text-xs font-semibold text-primary-600 dark:text-primary-400 group-hover:underline cursor-pointer">Read more</span>
</div>
</div>
</div>

<div class="rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden hover:shadow-lg transition-shadow group">
<div class="bg-gradient-to-br from-emerald-500 to-emerald-600 h-40 flex items-center justify-center relative overflow-hidden">
<div class="absolute inset-0 bg-emerald-600/20"></div>
<span class="text-white/90 text-4xl font-bold relative z-10 font-display">03</span>
</div>
<div class="p-5">
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">Sustainability</span>
<h3 class="text-base font-bold text-secondary-900 dark:text-white mt-2 mb-2">Our Commitment to Sustainable Packaging</h3>
<p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">Learn about our journey to 100% recyclable packaging and how we are reducing our environmental footprint.</p>
<div class="flex items-center justify-between mt-4 pt-3 border-t border-secondary-100 dark:border-secondary-700">
<span class="text-xs text-secondary-400 dark:text-secondary-500">July 28, 2026</span>
<span class="text-xs font-semibold text-primary-600 dark:text-primary-400 group-hover:underline cursor-pointer">Read more</span>
</div>
</div>
</div>

<div class="rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden hover:shadow-lg transition-shadow group">
<div class="bg-gradient-to-br from-violet-500 to-violet-600 h-40 flex items-center justify-center relative overflow-hidden">
<div class="absolute inset-0 bg-violet-600/20"></div>
<span class="text-white/90 text-4xl font-bold relative z-10 font-display">04</span>
</div>
<div class="p-5">
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-violet-50 text-violet-700 dark:bg-violet-950/30 dark:text-violet-400">Tips & Tricks</span>
<h3 class="text-base font-bold text-secondary-900 dark:text-white mt-2 mb-2">10 Ways to Get the Most Out of Your Shopping Experience</h3>
<p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">Maximize your savings and satisfaction with these expert shopping tips and tricks.</p>
<div class="flex items-center justify-between mt-4 pt-3 border-t border-secondary-100 dark:border-secondary-700">
<span class="text-xs text-secondary-400 dark:text-secondary-500">July 20, 2026</span>
<span class="text-xs font-semibold text-primary-600 dark:text-primary-400 group-hover:underline cursor-pointer">Read more</span>
</div>
</div>
</div>
</div>

<p class="mt-8 text-sm text-secondary-500 dark:text-secondary-400 italic">This is a demo blog section. A full blog system with individual article pages, categories, and comments will be available in a future update.</p>
</div>';
    }

    private function pressContent(): string
    {
        return '
<div class="not-prose mb-8">
<div class="grid sm:grid-cols-3 gap-4">
<div class="p-5 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700 text-center">
<div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-950/30 flex items-center justify-center mx-auto mb-3">
<svg class="h-5 w-5 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5"/></svg>
</div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Media Kit</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">Logos, brand guidelines, and assets available upon request</div>
</div>
<div class="p-5 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700 text-center">
<div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-950/30 flex items-center justify-center mx-auto mb-3">
<svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
</div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Press Inquiries</div>
<div class="text-xs text-primary-600 dark:text-primary-400 mt-1">media@nbkvertex.com</div>
</div>
<div class="p-5 rounded-xl bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-200 dark:border-secondary-700 text-center">
<div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-950/30 flex items-center justify-center mx-auto mb-3">
<svg class="h-5 w-5 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
</div>
<div class="font-semibold text-sm text-secondary-900 dark:text-white">Interviews</div>
<div class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">Contact us to schedule spokesperson interviews</div>
</div>
</div>
</div>

<h2>Company News</h2>

<h3>NBK Vertex Launches Improved Online Shopping Experience</h3>
<p><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary-100 text-primary-700 dark:bg-primary-950/30 dark:text-primary-400 mb-2">August 10, 2026</span></p>
<p>NBK Vertex has rolled out a refreshed website design featuring faster page loads, improved product search, and a streamlined checkout process. The update reflects ongoing investment in customer experience and platform performance.</p>

<h3>NBK Vertex Introduces New Product Discovery Features</h3>
<p><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary-100 text-primary-700 dark:bg-primary-950/30 dark:text-primary-400 mb-2">July 25, 2026</span></p>
<p>New filtering and comparison tools help customers find products more efficiently. The feature set includes category-specific filters, wish list integration, and improved product detail pages with expanded specifications.</p>

<h3>NBK Vertex Expands Its Customer Support Resources</h3>
<p><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary-100 text-primary-700 dark:bg-primary-950/30 dark:text-primary-400 mb-2">July 8, 2026</span></p>
<p>NBK Vertex has expanded its help center with detailed FAQs covering orders, payments, shipping, and returns. The update also includes an improved contact system with dedicated email channels for different support categories.</p>

<h2>Media Contact</h2>
<ul>
<li><strong>Press Email:</strong> media@nbkvertex.com</li>
<li><strong>Media Kit:</strong> Available upon request</li>
<li><strong>Spokesperson Interviews:</strong> Contact us to schedule</li>
</ul>
<p class="text-sm text-secondary-500 dark:text-secondary-400 mt-4">We aim to respond to media enquiries within 1 business day. Please include your publication name, deadline, and specific questions in your initial outreach.</p>';
    }

    private function privacyPolicyContent(): string
    {
        return '
<div class="not-prose mb-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-secondary-800 px-3 py-1.5 rounded-full">
<svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
Last updated: August 18, 2026
</span>
</div>

<h2>1. Information We Collect</h2>
<p>We collect information you provide directly to us, including:</p>
<ul>
<li>Name, email address, and phone number when you create an account</li>
<li>Shipping and billing addresses when you place an order</li>
<li>Payment information (processed securely through our payment partners)</li>
<li>Communication you send to us (emails, support tickets, reviews)</li>
</ul>

<p>We also automatically collect certain information when you visit our website:</p>
<ul>
<li>IP address and browser type</li>
<li>Pages visited and time spent on each page</li>
<li>Referring website or source</li>
<li>Device information and operating system</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use the information we collect to:</p>
<ul>
<li>Process and fulfill your orders</li>
<li>Send order confirmations and shipping notifications</li>
<li>Provide customer support</li>
<li>Send marketing communications (with your consent)</li>
<li>Improve our website and services</li>
<li>Detect and prevent fraud</li>
<li>Comply with legal obligations</li>
</ul>

<h2>3. Information Sharing</h2>
<p>We do not sell your personal information. We may share your information with:</p>
<ul>
<li>Shipping carriers to deliver your orders</li>
<li>Payment processors to handle transactions</li>
<li>Service providers who assist with website operations</li>
<li>Law enforcement when required by law</li>
</ul>

<h2>4. Cookies</h2>
<p>We use cookies and similar technologies to enhance your browsing experience. See our Cookie Policy for detailed information about the cookies we use and how to manage your preferences.</p>

<h2>5. Data Security</h2>
<p>We implement industry-standard security measures to protect your personal information. However, no method of electronic transmission is 100% secure, and we cannot guarantee absolute security.</p>

<h2>6. Your Rights</h2>
<p>You have the right to:</p>
<ul>
<li>Access the personal information we hold about you</li>
<li>Request correction of inaccurate information</li>
<li>Request deletion of your personal information</li>
<li>Opt out of marketing communications</li>
<li>Request a copy of your data in a portable format</li>
</ul>

<h2>7. Contact Us</h2>
<p>If you have questions about this privacy policy, please contact us at <strong>privacy@nbkvertex.com</strong>.</p>';
    }

    private function termsContent(): string
    {
        return '
<div class="not-prose mb-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-secondary-800 px-3 py-1.5 rounded-full">
<svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
Last updated: August 18, 2026
</span>
</div>

<h2>1. Acceptance of Terms</h2>
<p>By accessing and using the NBK Vertex website, you accept and agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our website.</p>

<h2>2. Account Registration</h2>
<p>To access certain features, you may need to create an account. You are responsible for:</p>
<ul>
<li>Maintaining the confidentiality of your account credentials</li>
<li>All activities that occur under your account</li>
<li>Notifying us immediately of any unauthorized use</li>
</ul>

<h2>3. Products and Pricing</h2>
<ul>
<li>All product descriptions and images are as accurate as possible</li>
<li>Prices are subject to change without notice</li>
<li>We reserve the right to limit order quantities</li>
<li>Product availability is not guaranteed</li>
</ul>

<h2>4. Orders and Payment</h2>
<ul>
<li>By placing an order, you are making an offer to purchase</li>
<li>We reserve the right to accept or decline any order</li>
<li>Payment must be received before order processing</li>
<li>All payments are processed securely through our payment partners</li>
</ul>

<h2>5. Shipping and Delivery</h2>
<ul>
<li>Delivery times are estimates and not guaranteed</li>
<li>Risk of loss passes to you upon delivery to the carrier</li>
<li>We are not responsible for delays caused by carriers</li>
</ul>

<h2>6. Returns and Refunds</h2>
<p>Returns are accepted within 30 days of delivery for unused items in original packaging. See our Returns & Exchanges page for complete details.</p>

<h2>7. Intellectual Property</h2>
<p>All content on this website, including text, images, logos, and designs, is the property of NBK Vertex and is protected by copyright and trademark laws. You may not reproduce, distribute, or create derivative works without our written consent.</p>

<h2>8. Limitation of Liability</h2>
<p>NBK Vertex shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of our website or products.</p>

<h2>9. Governing Law</h2>
<p>These terms are governed by the laws of the State of South Carolina, United States.</p>

<h2>10. Changes to Terms</h2>
<p>We reserve the right to update these terms at any time. Continued use of the website after changes constitutes acceptance of the new terms.</p>

<h2>11. Contact</h2>
<p>For questions about these terms, contact us at <strong>legal@nbkvertex.com</strong>.</p>';
    }

    private function cookiePolicyContent(): string
    {
        return '
<div class="not-prose mb-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-secondary-800 px-3 py-1.5 rounded-full">
<svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
Last updated: August 18, 2026
</span>
</div>

<h2>What Are Cookies</h2>
<p>Cookies are small text files stored on your device when you visit a website. They help us provide you with a better experience by remembering your preferences and understanding how you use our site.</p>

<h2>Types of Cookies We Use</h2>

<h3>Essential Cookies</h3>
<p>These cookies are necessary for the website to function. They enable core features like shopping cart, checkout, and account authentication.</p>
<ul>
<li><strong>session_id:</strong> Maintains your session while browsing</li>
<li><strong>csrf_token:</strong> Protects against cross-site request forgery</li>
<li><strong>cart_data:</strong> Stores your shopping cart contents</li>
</ul>

<h3>Analytics Cookies</h3>
<p>These cookies help us understand how visitors interact with our website by collecting anonymous data.</p>
<ul>
<li><strong>_ga:</strong> Google Analytics - distinguishes unique users</li>
<li><strong>_gid:</strong> Google Analytics - distinguishes unique users</li>
</ul>

<h3>Marketing Cookies</h3>
<p>These cookies are used to deliver relevant advertisements and track campaign performance.</p>
<ul>
<li><strong>_fbp:</strong> Facebook Pixel - tracks conversions from Facebook ads</li>
</ul>

<h3>Preference Cookies</h3>
<p>These cookies remember your choices such as language, currency, and theme preferences.</p>
<ul>
<li><strong>locale:</strong> Stores your language preference</li>
<li><strong>currency:</strong> Stores your currency preference</li>
<li><strong>theme:</strong> Stores your dark/light mode preference</li>
</ul>

<h2>Managing Cookies</h2>
<p>You can control and manage cookies through your browser settings. Note that disabling certain cookies may affect website functionality.</p>

<h3>Browser Settings</h3>
<ul>
<li><strong>Chrome:</strong> Settings > Privacy and Security > Cookies</li>
<li><strong>Firefox:</strong> Settings > Privacy & Security > Cookies</li>
<li><strong>Safari:</strong> Preferences > Privacy > Cookies</li>
<li><strong>Edge:</strong> Settings > Privacy > Cookies</li>
</ul>

<h2>Third-Party Cookies</h2>
<p>Some cookies are placed by third-party services that appear on our pages. We do not control these third-party cookies. Please refer to the respective privacy policies of these services.</p>

<h2>Updates to This Policy</h2>
<p>We may update this cookie policy from time to time. Changes will be posted on this page with an updated revision date.</p>

<h2>Contact</h2>
<p>For questions about our cookie policy, contact us at <strong>privacy@nbkvertex.com</strong>.</p>';
    }

    private function accessibilityContent(): string
    {
        return '
<div class="not-prose mb-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-secondary-800 px-3 py-1.5 rounded-full">
<svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
Last updated: August 18, 2026
</span>
</div>

<h2>Our Commitment</h2>
<p>NBK Vertex is committed to ensuring digital accessibility for all users, including people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.</p>

<h2>Standards</h2>
<p>We aim to conform to the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA. These guidelines explain how to make web content more accessible for people with disabilities and more user-friendly for everyone.</p>

<h2>Measures Taken</h2>
<ul>
<li>Semantic HTML structure throughout the website</li>
<li>Keyboard navigation support for all interactive elements</li>
<li>ARIA labels and roles for screen reader compatibility</li>
<li>Sufficient color contrast ratios (minimum 4.5:1)</li>
<li>Alt text for all meaningful images</li>
<li>Responsive design that works across all device sizes</li>
<li>Form labels and error messages that are accessible</li>
<li>Skip navigation links for screen reader users</li>
</ul>

<h2>Known Limitations</h2>
<p>While we strive for full accessibility, some areas of the website may not yet be fully accessible:</p>
<ul>
<li>Some older product images may lack complete alt text</li>
<li>Third-party payment widgets may have limited accessibility features</li>
<li>Some complex data tables may need additional markup for screen readers</li>
</ul>
<p>We are actively working to address these limitations.</p>

<h2>Feedback</h2>
<p>We welcome your feedback on the accessibility of our website. If you encounter any accessibility barriers or have suggestions for improvement, please contact us:</p>
<ul>
<li><strong>Email:</strong> accessibility@nbkvertex.com</li>
<li><strong>Phone:</strong> +1 (555) 123-4567</li>
</ul>
<p>We aim to respond to accessibility feedback within 2 business days.</p>

<h2>Third-Party Accessibility</h2>
<p>We require our third-party vendors to provide accessible content. However, we do not have full control over third-party content. If you experience accessibility issues with third-party tools, please let us know.</p>

<h2>Enforcement</h2>
<p>If you are not satisfied with our response, you may escalate your concern by contacting us at <strong>accessibility@nbkvertex.com</strong> with the subject line "Accessibility Concern" and we will escalate to senior management.</p>';
    }
}
