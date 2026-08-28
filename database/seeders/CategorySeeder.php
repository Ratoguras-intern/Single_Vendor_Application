<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    private array $categories = [
        'Electronics' => [
            'slug' => 'electronics',
            'description' => 'Latest phones, laptops, tablets and smart devices from the world\'s leading brands.',
            'sort_order' => 1,
            'featured' => true,
            'icon' => 'Cpu',
            'image_key' => 'electronics',
            'subcategories' => [
                ['name' => 'Smartphones', 'slug' => 'smartphones', 'description' => 'Flagship and mid-range smartphones from Apple, Samsung, Google and more.', 'sort_order' => 1, 'icon' => 'Smartphone', 'image_key' => 'smartphones'],
                ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Ultrabooks, gaming laptops and workstations for every workflow.', 'sort_order' => 2, 'icon' => 'Laptop', 'image_key' => 'laptops'],
                ['name' => 'Tablets', 'slug' => 'tablets', 'description' => 'iPads and Android tablets for creativity, work and entertainment.', 'sort_order' => 3, 'icon' => 'Tablet', 'image_key' => 'tablets'],
            ],
        ],
        'Computers & Accessories' => [
            'slug' => 'computers-accessories',
            'description' => 'Desktops, monitors and peripherals to power your desk.',
            'sort_order' => 2,
            'featured' => true,
            'icon' => 'Monitor',
            'image_key' => 'computer-accessories',
            'subcategories' => [
                ['name' => 'Desktops & Monitors', 'slug' => 'desktops-monitors', 'description' => 'Desktop PCs, all-in-ones and high-resolution displays.', 'sort_order' => 1, 'icon' => 'Monitor', 'image_key' => 'desktop-computers'],
                ['name' => 'Computer Accessories', 'slug' => 'computer-accessories', 'description' => 'Keyboards, mice, webcams, chargers and hubs.', 'sort_order' => 2, 'icon' => 'Keyboard', 'image_key' => 'computer-accessories'],
            ],
        ],
        'Fashion' => [
            'slug' => 'fashion',
            'description' => 'Seasonal clothing, denim and wardrobe essentials for men and women.',
            'sort_order' => 3,
            'featured' => true,
            'icon' => 'Shirt',
            'image_key' => 'fashion',
            'subcategories' => [
                ['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'description' => 'Jeans, shirts, jackets and outerwear for men.', 'sort_order' => 1, 'icon' => 'Shirt', 'image_key' => 'mens-clothing'],
                ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'description' => 'Dresses, knitwear, denim and wardrobe staples for women.', 'sort_order' => 2, 'icon' => 'Shirt', 'image_key' => 'womens-clothing'],
            ],
        ],
        'Footwear' => [
            'slug' => 'footwear',
            'description' => 'Sneakers, running shoes and everyday footwear for every step.',
            'sort_order' => 4,
            'featured' => false,
            'icon' => 'Footprints',
            'image_key' => 'shoes',
            'subcategories' => [
                ['name' => 'Sneakers', 'slug' => 'sneakers', 'description' => 'Classic silhouettes and performance sneakers from Nike, Adidas and more.', 'sort_order' => 1, 'icon' => 'Footprints', 'image_key' => 'shoes'],
            ],
        ],
        'Watches' => [
            'slug' => 'watches',
            'description' => 'Automatic, quartz and smart timepieces from heritage watchmakers.',
            'sort_order' => 5,
            'featured' => false,
            'icon' => 'Watch',
            'image_key' => 'watches',
            'subcategories' => [
                ['name' => 'Smart & Automatic Watches', 'slug' => 'smart-watches', 'description' => 'Mechanical automatics and connected smartwatches.', 'sort_order' => 1, 'icon' => 'Watch', 'image_key' => 'watches'],
            ],
        ],
        'Bags & Luggage' => [
            'slug' => 'bags-luggage',
            'description' => 'Handbags, backpacks, wallets and travel luggage built to last.',
            'sort_order' => 6,
            'featured' => false,
            'icon' => 'ShoppingBag',
            'image_key' => 'bags',
            'subcategories' => [
                ['name' => 'Bags & Travel', 'slug' => 'bags-travel', 'description' => 'Totes, crossbodies, backpacks and rolling luggage.', 'sort_order' => 1, 'icon' => 'ShoppingBag', 'image_key' => 'bags'],
            ],
        ],
        'Home & Living' => [
            'slug' => 'home-living',
            'description' => 'Furniture, kitchen essentials and decor to make your house a home.',
            'sort_order' => 7,
            'featured' => true,
            'icon' => 'Sofa',
            'image_key' => 'home-living',
            'subcategories' => [
                ['name' => 'Furniture', 'slug' => 'furniture', 'description' => 'Sofas, beds, armchairs and tables for every room.', 'sort_order' => 1, 'icon' => 'Armchair', 'image_key' => 'furniture'],
                ['name' => 'Kitchen & Dining', 'slug' => 'kitchen-dining', 'description' => 'Appliances, cookware and dining must-haves.', 'sort_order' => 2, 'icon' => 'CookingPot', 'image_key' => 'kitchen'],
                ['name' => 'Home Decor', 'slug' => 'home-decor', 'description' => 'Vases, rugs, prints and decorative accents.', 'sort_order' => 3, 'icon' => 'Flower', 'image_key' => 'home-decor'],
            ],
        ],
        'Beauty & Personal Care' => [
            'slug' => 'beauty-personal-care',
            'description' => 'Skincare, cosmetics and everyday personal care from trusted brands.',
            'sort_order' => 8,
            'featured' => false,
            'icon' => 'Sparkles',
            'image_key' => 'beauty-personal-care',
            'subcategories' => [
                ['name' => 'Beauty & Skincare', 'slug' => 'beauty-skincare', 'description' => 'Serums, moisturizers, makeup and sun protection.', 'sort_order' => 1, 'icon' => 'Sparkles', 'image_key' => 'beauty-personal-care'],
            ],
        ],
        'Sports & Fitness' => [
            'slug' => 'sports-fitness',
            'description' => 'Home gym equipment, running gear and everything for an active lifestyle.',
            'sort_order' => 9,
            'featured' => true,
            'icon' => 'Dumbbell',
            'image_key' => 'sports-fitness',
            'subcategories' => [
                ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment', 'description' => 'Dumbbells, benches, cardio machines and training accessories.', 'sort_order' => 1, 'icon' => 'Dumbbell', 'image_key' => 'fitness-equipment'],
                ['name' => 'Running & Outdoor', 'slug' => 'running-outdoor', 'description' => 'Running shoes, GPS watches and endurance gear.', 'sort_order' => 2, 'icon' => 'Mountain', 'image_key' => 'running'],
            ],
        ],
        'Gaming' => [
            'slug' => 'gaming',
            'description' => 'Consoles, controllers and titles for every kind of player.',
            'sort_order' => 10,
            'featured' => false,
            'icon' => 'Gamepad2',
            'image_key' => 'gaming-pcs',
            'subcategories' => [
                ['name' => 'Consoles & Accessories', 'slug' => 'gaming-consoles', 'description' => 'PlayStation, Xbox, Nintendo and gaming peripherals.', 'sort_order' => 1, 'icon' => 'Gamepad2', 'image_key' => 'gaming-pcs'],
            ],
        ],
        'Audio' => [
            'slug' => 'audio',
            'description' => 'Headphones, earbuds, speakers and soundbars for everyday listening.',
            'sort_order' => 11,
            'featured' => false,
            'icon' => 'Headphones',
            'image_key' => 'audio',
            'subcategories' => [
                ['name' => 'Headphones & Speakers', 'slug' => 'headphones-speakers', 'description' => 'Over-ear, in-ear and wireless audio from Sony, Bose, JBL and more.', 'sort_order' => 1, 'icon' => 'Headphones', 'image_key' => 'audio'],
            ],
        ],
        'Toys & Books' => [
            'slug' => 'toys-books',
            'description' => 'Building sets, games and playtime favorites for all ages.',
            'sort_order' => 12,
            'featured' => false,
            'icon' => 'Gift',
            'image_key' => 'toys-books',
            'subcategories' => [
                ['name' => 'Toys & Games', 'slug' => 'toys-games', 'description' => 'LEGO sets, board games and classic toys.', 'sort_order' => 1, 'icon' => 'Gift', 'image_key' => 'toys-books'],
            ],
        ],
        'Pet Supplies' => [
            'slug' => 'pet-supplies',
            'description' => 'Food, treats and accessories for dogs and cats.',
            'sort_order' => 13,
            'featured' => false,
            'icon' => 'Package',
            'image_key' => 'pet-supplies',
            'subcategories' => [
                ['name' => 'Pet Essentials', 'slug' => 'pet-essentials', 'description' => 'Nutrition, toys and care products for your pets.', 'sort_order' => 1, 'icon' => 'Package', 'image_key' => 'pet-supplies'],
            ],
        ],
        'Baby & Toddler' => [
            'slug' => 'baby-toddler',
            'description' => 'Baby gear, diapers and nursery essentials for the little ones.',
            'sort_order' => 14,
            'featured' => false,
            'icon' => 'Heart',
            'image_key' => 'baby-toddler',
            'subcategories' => [
                ['name' => 'Baby Essentials', 'slug' => 'baby-essentials', 'description' => 'Diapers, strollers, feeding and comfort essentials.', 'sort_order' => 1, 'icon' => 'Heart', 'image_key' => 'baby-toddler'],
            ],
        ],
        'Garden & Outdoor' => [
            'slug' => 'garden-outdoor',
            'description' => 'Power tools, garden care and outdoor living gear.',
            'sort_order' => 15,
            'featured' => false,
            'icon' => 'TreePine',
            'image_key' => 'garden-outdoor',
            'subcategories' => [
                ['name' => 'Garden & Patio', 'slug' => 'garden-patio', 'description' => 'Trimmers, drills, hoses and mowers for your outdoor space.', 'sort_order' => 1, 'icon' => 'TreePine', 'image_key' => 'garden-outdoor'],
            ],
        ],
    ];

    public function run(): void
    {
        foreach ($this->categories as $name => $data) {
            $slug = $data['slug'] ?? Str::slug($name);
            $imageKey = $data['image_key'] ?? null;

            $parent = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'featured' => $data['featured'],
                    'status' => 'active',
                    'icon' => $data['icon'] ?? null,
                    'parent_id' => null,
                    'image' => $imageKey ? "categories/{$imageKey}.jpg" : null,
                    'thumbnail_image' => $imageKey ? "categories/{$imageKey}-thumb.webp" : null,
                    'banner_image' => $imageKey ? "categories/{$imageKey}.jpg" : null,
                    'banner_mobile_image' => null,
                    'seo_title' => "Shop {$name} | NBK Vertex",
                    'seo_description' => "Browse our {$name} collection at NBK Vertex.",
                ],
            );

            foreach ($data['subcategories'] as $sub) {
                $subSlug = $sub['slug'] ?? Str::slug($sub['name']);
                $subImageKey = $sub['image_key'] ?? null;

                Category::updateOrCreate(
                    ['slug' => $subSlug],
                    [
                        'name' => $sub['name'],
                        'description' => $sub['description'],
                        'parent_id' => $parent->id,
                        'sort_order' => $sub['sort_order'],
                        'featured' => false,
                        'status' => 'active',
                        'icon' => $sub['icon'] ?? null,
                        'image' => $subImageKey ? "categories/{$subImageKey}.jpg" : null,
                        'thumbnail_image' => $subImageKey ? "categories/{$subImageKey}-thumb.webp" : null,
                        'banner_image' => null,
                        'banner_mobile_image' => null,
                        'seo_title' => $sub['name'],
                        'seo_description' => $sub['description'],
                    ],
                );
            }
        }

        // Remove any categories (top-level or child) that are not part of the
        // current catalog. Safe on fresh databases and keeps re-seeding an
        // existing database in sync. Products referencing removed categories
        // cascade-delete; ProductSeeder re-creates the full catalog anyway.
        $allowedSlugs = [];
        foreach ($this->categories as $name => $data) {
            $allowedSlugs[] = $data['slug'] ?? Str::slug($name);
            foreach ($data['subcategories'] as $sub) {
                $allowedSlugs[] = $sub['slug'] ?? Str::slug($sub['name']);
            }
        }

        Category::whereNotIn('slug', $allowedSlugs)->delete();

        $this->command->info(sprintf(
            'Seeded %d top-level categories with %d subcategories.',
            Category::whereNull('parent_id')->count(),
            Category::whereNotNull('parent_id')->count()
        ));
    }
}