<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'slug' => 'hero-carousel',
                'title' => null,
                'subtitle' => null,
                'is_enabled' => true,
                'sort_order' => 0,
                'max_products' => 0,
                'layout' => 'carousel',
                'config' => [
                    'slides' => [
                        [
                            'badge' => 'NEW',
                            'badge_color' => 'bg-green-500',
                            'heading' => 'Step Into Style',
                            'description' => 'Discover our latest collection of premium products — comfort, design, and quality in every piece.',
                            'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80',
                            'cta_primary' => 'Shop Now',
                            'cta_secondary' => 'Learn More',
                            'link_primary' => '/shop',
                            'link_secondary' => '/about',
                        ],
                        [
                            'badge' => 'SALE',
                            'badge_color' => 'bg-red-500',
                            'heading' => 'Summer Collection',
                            'description' => 'Up to 50% off on selected items. Refresh your wardrobe with the latest trends.',
                            'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1920&q=80',
                            'cta_primary' => 'Shop the Sale',
                            'cta_secondary' => 'View All',
                            'link_primary' => '/shop',
                            'link_secondary' => '/shop',
                        ],
                        [
                            'badge' => 'LIMITED',
                            'badge_color' => 'bg-primary-500',
                            'heading' => 'Premium Essentials',
                            'description' => 'Curated selection of must-have pieces. Quality that speaks for itself.',
                            'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&q=80',
                            'cta_primary' => 'Explore Now',
                            'cta_secondary' => 'Our Story',
                            'link_primary' => '/shop',
                            'link_secondary' => '/about',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'trust-bar',
                'title' => null,
                'subtitle' => null,
                'is_enabled' => true,
                'sort_order' => 1,
                'max_products' => 0,
                'layout' => 'grid',
                'config' => [
                    'items' => [
                        ['icon' => 'truck', 'title' => 'Free Shipping', 'description' => 'On orders over $50'],
                        ['icon' => 'shield', 'title' => 'Secure Payments', 'description' => '100% secure checkout'],
                        ['icon' => 'refresh', 'title' => 'Easy Returns', 'description' => '30-day return policy'],
                        ['icon' => 'support', 'title' => '24/7 Support', 'description' => 'Dedicated support team'],
                    ],
                ],
            ],
            [
                'slug' => 'shop-by-category',
                'title' => 'Shop by Category',
                'subtitle' => 'Browse our curated collections',
                'is_enabled' => true,
                'sort_order' => 2,
                'max_products' => 0,
                'layout' => 'grid',
                'config' => null,
            ],
            [
                'slug' => 'featured-products',
                'title' => 'Featured Products',
                'subtitle' => 'Handpicked just for you',
                'is_enabled' => true,
                'sort_order' => 3,
                'max_products' => 8,
                'layout' => 'grid',
                'config' => null,
            ],
            [
                'slug' => 'new-arrivals',
                'title' => 'New Arrivals',
                'subtitle' => 'Fresh drops, just landed',
                'is_enabled' => true,
                'sort_order' => 4,
                'max_products' => 10,
                'layout' => 'carousel',
                'config' => null,
            ],
            [
                'slug' => 'trending-products',
                'title' => 'Trending Now',
                'subtitle' => "What everyone's talking about",
                'is_enabled' => true,
                'sort_order' => 5,
                'max_products' => 8,
                'layout' => 'grid',
                'config' => null,
            ],
            [
                'slug' => 'flash-sale',
                'title' => "Don't Miss Out",
                'subtitle' => 'Limited time offers on selected products.',
                'is_enabled' => true,
                'sort_order' => 6,
                'max_products' => 8,
                'layout' => 'grid',
                'config' => [
                    'ends_at' => now()->addDays(3)->endOfDay()->toIso8601String(),
                ],
            ],
            [
                'slug' => 'best-sellers',
                'title' => 'Best Sellers',
                'subtitle' => 'Most popular picks from our customers',
                'is_enabled' => true,
                'sort_order' => 7,
                'max_products' => 8,
                'layout' => 'grid',
                'config' => null,
            ],
            [
                'slug' => 'recommended-products',
                'title' => 'Recommended For You',
                'subtitle' => 'Picked based on popular taste',
                'is_enabled' => true,
                'sort_order' => 8,
                'max_products' => 8,
                'layout' => 'grid',
                'config' => null,
            ],
            [
                'slug' => 'popular-products',
                'title' => 'Popular Products',
                'subtitle' => 'Trending across the store',
                'is_enabled' => true,
                'sort_order' => 9,
                'max_products' => 8,
                'layout' => 'grid',
                'config' => null,
            ],
            [
                'slug' => 'top-brands',
                'title' => 'Top Brands',
                'subtitle' => 'Trusted by our customers worldwide',
                'is_enabled' => true,
                'sort_order' => 10,
                'max_products' => 0,
                'layout' => 'carousel',
                'config' => null,
            ],
            [
                'slug' => 'why-choose-us',
                'title' => 'Why Choose Us',
                'subtitle' => "We're committed to delivering the best experience",
                'is_enabled' => true,
                'sort_order' => 11,
                'max_products' => 0,
                'layout' => 'grid',
                'config' => [
                    'features' => [
                        ['icon' => 'quality', 'title' => 'Premium Quality', 'description' => 'Every product is crafted with the finest materials and attention to detail.'],
                        ['icon' => 'delivery', 'title' => 'Fast Delivery', 'description' => 'Free express shipping on orders over $50. Track your order in real-time.'],
                        ['icon' => 'checkout', 'title' => 'Secure Checkout', 'description' => 'Your payments are protected with bank-level encryption and security.'],
                        ['icon' => 'returns', 'title' => 'Easy Returns', 'description' => 'Not satisfied? Return within 30 days for a full refund, no questions asked.'],
                        ['icon' => 'support', 'title' => '24/7 Support', 'description' => 'Our dedicated team is always here to help via chat, email, or phone.'],
                    ],
                ],
            ],
            [
                'slug' => 'testimonials',
                'title' => 'What Our Customers Say',
                'subtitle' => 'Real reviews from real people',
                'is_enabled' => true,
                'sort_order' => 12,
                'max_products' => 0,
                'layout' => 'grid',
                'config' => [
                    'testimonials' => [
                        [
                            'name' => 'Sarah Johnson',
                            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&q=80',
                            'rating' => 5,
                            'review' => 'Absolutely love the quality of the products. The attention to detail is remarkable. This has become my go-to store for everything.',
                            'role' => 'Fashion Enthusiast',
                        ],
                        [
                            'name' => 'Michael Chen',
                            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&q=80',
                            'rating' => 5,
                            'review' => 'Fast shipping and the products exceeded my expectations. The customer service team was incredibly helpful when I had questions.',
                            'role' => 'Tech Professional',
                        ],
                        [
                            'name' => 'Emily Williams',
                            'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&q=80',
                            'rating' => 5,
                            'review' => 'The premium quality really shows. I have recommended this store to all my friends and family. Truly exceptional shopping experience.',
                            'role' => 'Interior Designer',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'newsletter-cta',
                'title' => 'Stay in the Loop',
                'subtitle' => 'Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.',
                'is_enabled' => true,
                'sort_order' => 13,
                'max_products' => 0,
                'layout' => 'grid',
                'config' => [
                    'bg_image' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?w=1920&q=80',
                    'button_text' => 'Subscribe',
                ],
            ],
            [
                'slug' => 'instagram-gallery',
                'title' => 'Follow Us on Instagram',
                'subtitle' => '@nbkvertex',
                'is_enabled' => true,
                'sort_order' => 14,
                'max_products' => 0,
                'layout' => 'grid',
                'config' => [
                    'images' => [
                        ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Watch'],
                        ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80', 'span' => 'col-span-1 row-span-2', 'alt' => 'Headphones'],
                        ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Sneakers'],
                        ['url' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Camera'],
                        ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&q=80', 'span' => 'col-span-1 row-span-2', 'alt' => 'Fashion'],
                        ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80', 'span' => 'col-span-1 row-span-1', 'alt' => 'Sunglasses'],
                    ],
                ],
            ],
        ];

        foreach ($sections as $data) {
            HomepageSection::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
