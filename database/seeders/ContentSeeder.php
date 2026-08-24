<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Job;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PressRelease;
use App\Models\Setting;
use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPageTemplates();
        $this->seedSidebarItems();
        $this->seedPageImages();
        $this->seedSettings();
        $this->seedFaqCategories();
        $this->seedFaqs();
        $this->seedJobs();
        $this->seedPostCategories();
        $this->seedPosts();
        $this->seedPressReleases();
        $this->seedShippingMethods();

        Cache::forget('footer_pages_customer-care');
        Cache::forget('footer_pages_company');
        Cache::forget('footer_pages_legal');
    }

    protected function seedPageTemplates(): void
    {
        $templates = [
            'contact-us' => 'contact',
            'help-center' => 'help-center',
            'shipping-info' => 'shipping-info',
            'returns-exchanges' => 'returns',
            'about-us' => 'about',
            'careers' => 'careers',
            'blog' => 'blog',
            'press' => 'press',
        ];

        foreach ($templates as $slug => $template) {
            Page::where('slug', $slug)->update(['template' => $template]);
        }

        $subtitles = [
            'contact-us' => "Questions, feedback or support — we'd love to hear from you.",
            'help-center' => 'Find quick answers to common questions.',
            'shipping-info' => 'Delivery options, costs and coverage at a glance.',
            'returns-exchanges' => 'Easy, transparent returns within your window.',
            'about-us' => 'The story behind our store.',
            'careers' => 'Join a team building the future of online retail.',
            'blog' => 'News, buying guides and inspiration from our team.',
            'press' => 'Company news and media resources.',
        ];

        foreach ($subtitles as $slug => $subtitle) {
            Page::where('slug', $slug)->update(['subtitle' => $subtitle]);
        }
    }

    protected function seedSidebarItems(): void
    {
        $menu = \App\Models\NavigationMenu::where('slug', 'admin-sidebar')->first();

        if (! $menu) {
            return;
        }

        $items = [
            ['name' => 'FAQ Management', 'url' => 'admin.faqs.index', 'icon_key' => 'info', 'sort_order' => 9],
            ['name' => 'Blog Posts', 'url' => 'admin.posts.index', 'icon_key' => 'featured', 'sort_order' => 10],
            ['name' => 'Post Categories', 'url' => 'admin.post-categories.index', 'icon_key' => 'category', 'sort_order' => 11],
            ['name' => 'Careers', 'url' => 'admin.jobs.index', 'icon_key' => 'customer', 'sort_order' => 12],
            ['name' => 'Press Releases', 'url' => 'admin.press-releases.index', 'icon_key' => 'mail', 'sort_order' => 13],
            ['name' => 'Shipping Methods', 'url' => 'admin.shipping-methods.index', 'icon_key' => 'cart', 'sort_order' => 14],
            ['name' => 'Contact Inbox', 'url' => 'admin.contact-messages.index', 'icon_key' => 'mail', 'sort_order' => 15],
            ['name' => 'Content Settings', 'url' => 'admin.content-settings.edit', 'icon_key' => 'branding', 'sort_order' => 16],
            ['name' => 'Branding & Logo', 'url' => 'admin.branding.edit', 'icon_key' => 'branding', 'sort_order' => 17, 'permission' => 'super_admin'],
        ];

        foreach ($items as $item) {
            \App\Models\NavigationItem::firstOrCreate(
                ['menu_id' => $menu->id, 'parent_id' => 104, 'name' => $item['name']],
                $item + ['target' => '_self', 'is_enabled' => true]
            );
        }

        Cache::flush();
    }

    protected function seedPageImages(): void
    {
        Page::where('slug', 'about-us')->update([
            'featured_image' => 'banners/hlXqR494c6n1E0ETzPUxL0yzdPqKXk3VjIJuS7zW.png',
            'og_image' => 'banners/products/L5vTaMZwPpxTIQOtX12199YIVNRDBVm7slUUxaO7.jpg',
        ]);
    }

    protected function seedSettings(): void
    {
        Setting::set('contact.business_hours', 'Monday - Friday: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed');
        Setting::set('contact.response_time', 'We respond to all messages within 24 hours.');
        Setting::set('returns.window_days', 30);
        Setting::set('returns.process_steps', json_encode([
            ['title' => 'Start Your Return', 'description' => 'Request a return from your orders page.'],
            ['title' => 'Pack Your Items', 'description' => 'Include all original packaging and accessories.'],
            ['title' => 'Ship It Back', 'description' => 'Drop off at any partner courier location.'],
            ['title' => 'We Inspect', 'description' => 'Quality check within 2 business days of arrival.'],
            ['title' => 'Refund Issued', 'description' => 'Money back to your original payment method.'],
        ]));
        Setting::set('shipping.process_steps', json_encode([
            ['title' => 'Order Confirmed', 'description' => 'You receive an email confirmation instantly.'],
            ['title' => 'Packed With Care', 'description' => 'Dispatched from our warehouse in 1-2 days.'],
            ['title' => 'In Transit', 'description' => 'Track every step with live updates.'],
            ['title' => 'Delivered', 'description' => 'Signed for and delivered to your door.'],
        ]));
        Setting::set('shipping.areas', "United States (all states)\nCanada\nUnited Kingdom\nEuropean Union\nAustralia & New Zealand\nMiddle East");
        Setting::set('shipping.important_info', "Customs duties and import taxes are the responsibility of the recipient.\nPO Box deliveries are limited to standard shipping.\nDelivery estimates exclude weekends and public holidays.\nFree shipping threshold applies before taxes.");
        Setting::set('shipping.free_threshold', 75);
        Setting::set('press.contact_name', 'Communications Team');
        Setting::set('press.contact_email', 'press@example.com');
        Setting::set('press.contact_phone', '+1 (555) 987-6543');
        Setting::set('about.founded_year', 2018);
    }

    protected function seedFaqCategories(): void
    {
        $categories = [
            ['name' => 'Orders & Payment', 'slug' => 'orders-payment', 'sort_order' => 1],
            ['name' => 'Shipping & Delivery', 'slug' => 'shipping-delivery', 'sort_order' => 2],
            ['name' => 'Returns & Refunds', 'slug' => 'returns-refunds', 'sort_order' => 3],
            ['name' => 'Account & Security', 'slug' => 'account-security', 'sort_order' => 4],
        ];

        foreach ($categories as $data) {
            FaqCategory::firstOrCreate(['name' => $data['name']], $data + ['is_active' => true]);
        }
    }

    protected function seedFaqs(): void
    {
        if (Faq::exists()) {
            return;
        }

        $byName = FaqCategory::pluck('id', 'name');

        $faqs = [
            [$byName['Orders & Payment'], 'How can I place an order?', 'Browse the shop, add items to your cart and proceed to checkout. You will receive an order confirmation email once payment is complete.', 1, true],
            [$byName['Orders & Payment'], 'Which payment methods do you accept?', 'We accept major credit and debit cards, PayPal, Stripe and Cash on Delivery in eligible regions.', 2, true],
            [$byName['Orders & Payment'], 'Can I change or cancel my order?', 'Orders can be modified or cancelled within one hour of placement. Contact support as soon as possible.', 3, false],
            [$byName['Shipping & Delivery'], 'How long does shipping take?', 'Standard delivery takes 3-7 business days; express delivery arrives in 1-3 business days after dispatch.', 1, true],
            [$byName['Shipping & Delivery'], 'Do you ship internationally?', 'Yes — we ship to most countries worldwide. See the Shipping Info page for covered regions.', 2, false],
            [$byName['Shipping & Delivery'], 'How do I track my order?', 'A tracking link is emailed to you as soon as your parcel leaves our warehouse.', 3, false],
            [$byName['Returns & Refunds'], 'What is your return policy?', 'Unused items in original condition can be returned within 30 days of delivery. See the Returns page for details.', 1, true],
            [$byName['Returns & Refunds'], 'When will I receive my refund?', 'Refunds are issued to the original payment method within 5-7 business days after inspection.', 2, false],
            [$byName['Account & Security'], 'How do I reset my password?', 'Use the "Forgot password" link on the login page and follow the email instructions.', 1, false],
            [$byName['Account & Security'], 'Is my payment information secure?', 'All payments are processed over encrypted connections by certified providers. We never store card details.', 2, true],
        ];

        foreach ($faqs as [$categoryId, $question, $answer, $order, $published]) {
            Faq::create([
                'faq_category_id' => $categoryId,
                'question' => $question,
                'answer' => $answer,
                'sort_order' => $order,
                'is_published' => $published,
            ]);
        }
    }

    protected function seedJobs(): void
    {
        Job::firstOrCreate(['slug' => 'senior-frontend-engineer'], [
            'title' => 'Senior Frontend Engineer',
            'department' => 'Engineering',
            'location' => 'Remote',
            'employment_type' => 'Full-time',
            'experience_level' => 'Senior',
            'description' => "We are looking for a senior frontend engineer to craft fast, accessible shopping experiences used by thousands of customers every day. You will own features end-to-end, from design collaboration to production monitoring.",
            'responsibilities' => "- Build and maintain customer-facing features with modern JavaScript frameworks\n- Improve performance, accessibility and SEO across the storefront\n- Collaborate with designers on polished UI implementations\n- Mentor junior engineers through code review",
            'requirements' => "- 5+ years of frontend experience in production environments\n- Deep knowledge of HTML, CSS, JavaScript/TypeScript\n- Experience with Laravel Blade or similar templating\n- Strong eye for detail and UX",
            'benefits' => "- Fully remote, flexible hours\n- Annual learning budget\n- Health insurance\n- Paid time off",
            'application_instructions' => 'Send your resume and GitHub profile with a short note about a project you are proud of.',
            'application_email' => 'careers@example.com',
            'status' => 'published',
            'published_at' => now()->subDays(5),
        ]);

        Job::firstOrCreate(['slug' => 'customer-support-specialist'], [
            'title' => 'Customer Support Specialist',
            'department' => 'Customer Care',
            'location' => 'Kathmandu, Nepal',
            'employment_type' => 'Full-time',
            'experience_level' => 'Entry level',
            'description' => "Be the friendly voice of the brand. You will help customers with orders, returns and product questions across chat and email, turning problems into loyalty.",
            'responsibilities' => "- Resolve customer inquiries via chat and email\n- Own escalations until fully resolved\n- Identify recurring issues and suggest improvements",
            'requirements' => "- Excellent written English\n- Empathy and patience under pressure\n- E-commerce experience is a plus",
            'benefits' => "- On-site role with hybrid flexibility\n- Meal allowance\n- Career growth path",
            'application_instructions' => null,
            'application_email' => 'careers@example.com',
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);
    }

    protected function seedPostCategories(): void
    {
        foreach (['Buying Guides', 'Company News', 'Lifestyle'] as $name) {
            PostCategory::firstOrCreate(['name' => $name]);
        }
    }

    protected function seedPosts(): void
    {
        if (Post::exists()) {
            return;
        }

        $cat = PostCategory::pluck('id', 'name');

        Post::create([
            'post_category_id' => $cat['Company News'],
            'title' => 'Welcome to Our New Storefront',
            'slug' => 'welcome-to-our-new-storefront',
            'excerpt' => 'A faster, friendlier shopping experience has arrived. Here is what changed and why it matters.',
            'content' => '<h2>A fresh start</h2><p>We rebuilt our storefront from the ground up to be faster, clearer and easier to shop. Pages now load noticeably quicker on mobile networks.</p><h2>What changed</h2><ul><li>Simplified navigation with smarter search</li><li>Clearer pricing and delivery information</li><li>Streamlined checkout with fewer steps</li></ul>',
            'featured_image' => 'banners/hoR59KUvH2ciAQplg0nrvPioOQciSdvQc6K9f9tq.jpg',
            'author_name' => 'Team NBK Vertex',
            'is_featured' => true,
            'status' => 'published',
            'published_at' => now()->subDays(10),
        ]);

        Post::create([
            'post_category_id' => $cat['Buying Guides'],
            'title' => 'How to Choose the Right Size Every Time',
            'slug' => 'how-to-choose-the-right-size-every-time',
            'excerpt' => 'Sizing charts vary between brands. Our simple measuring method removes the guesswork.',
            'content' => '<h2>Measure first</h2><p>Grab a soft tape measure and note three key numbers before comparing them to our size chart.</p><h2>When in doubt</h2><p>If you fall between sizes, we generally recommend sizing up — returns are easy if needed.</p>',
            'featured_image' => 'banners/hlXqR494c6n1E0ETzPUxL0yzdPqKXk3VjIJuS7zW.png',
            'author_name' => 'Team NBK Vertex',
            'status' => 'published',
            'published_at' => now()->subDays(6),
        ]);

        Post::create([
            'post_category_id' => $cat['Lifestyle'],
            'title' => '5 Small Upgrades That Make Everyday Life Better',
            'slug' => 'five-small-upgrades-that-make-everyday-life-better',
            'excerpt' => 'Little changes with outsized impact — five affordable picks our team swears by.',
            'content' => '<h2>Start small</h2><p>You do not need a big budget to improve daily routines. These five picks each cost less than dinner out.</p>',
            'featured_image' => 'banners/mobile/fLryyM6R9o0QsrMrovsQmrXXaRo8qh49tqCHsh8C.webp',
            'author_name' => 'Team NBK Vertex',
            'status' => 'published',
            'published_at' => now()->subDays(3),
        ]);
    }

    protected function seedPressReleases(): void
    {
        PressRelease::firstOrCreate(['slug' => 'storefront-relaunch'], [
            'title' => 'NBK Vertex Launches Redesigned Storefront',
            'summary' => 'The completely redesigned storefront delivers faster browsing and a simplified checkout experience.',
            'content' => "<p>We are excited to announce the launch of our fully redesigned storefront, focused on speed and simplicity.</p><h2>Faster by design</h2><p>Benchmarks show significantly reduced page load times across devices.</p>",
            'released_at' => now()->subDays(14),
            'status' => 'published',
        ]);

        PressRelease::firstOrCreate(['slug' => 'expanded-shipping-partnership'], [
            'title' => 'Expanded Shipping Partnership Brings Faster Deliveries',
            'summary' => 'New logistics partnership cuts average delivery times for international customers.',
            'content' => "<p>Our new logistics partners enable quicker international delivery at no extra cost to customers.</p>",
            'released_at' => now()->subDays(45),
            'status' => 'published',
        ]);

        PressRelease::firstOrCreate(['slug' => 'sustainability-commitment'], [
            'title' => 'Our Sustainability Commitment for the Coming Year',
            'summary' => 'Reduced packaging waste and carbon-neutral shipping options arrive this year.',
            'content' => "<p>We are committing to recyclable packaging across all shipments and carbon-neutral delivery options.</p>",
            'released_at' => now()->subDays(80),
            'status' => 'draft',
        ]);
    }

    protected function seedShippingMethods(): void
    {
        $methods = [
            ['name' => 'Standard Shipping', 'description' => 'Reliable doorstep delivery with full tracking.', 'price' => 4.99, 'delivery_estimate' => '3-7 business days', 'availability' => 'Domestic & international', 'sort_order' => 1],
            ['name' => 'Express Shipping', 'description' => 'Priority handling for urgent orders.', 'price' => 14.99, 'delivery_estimate' => '1-3 business days', 'availability' => 'Domestic only', 'sort_order' => 2],
            ['name' => 'Cash on Delivery', 'description' => 'Pay in cash when your order arrives.', 'price' => 2.99, 'delivery_estimate' => '3-8 business days', 'availability' => 'Selected cities', 'sort_order' => 3],
        ];

        foreach ($methods as $method) {
            ShippingMethod::firstOrCreate(['name' => $method['name']], $method + ['is_active' => true]);
        }
    }
}
