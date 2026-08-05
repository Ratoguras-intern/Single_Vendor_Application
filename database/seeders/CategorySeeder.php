<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private array $categories = [
        'Fashion' => [
            'description' => 'Trendy clothing, footwear, and accessories for every style.',
            'sort_order' => 1,
            'featured' => true,
            'icon' => 'Shirt',
            'subcategories' => [
                ['name' => "Men's Clothing", 'description' => 'Shirts, trousers, jackets and more for men.', 'sort_order' => 1, 'icon' => 'Shirt'],
                ['name' => "Women's Clothing", 'description' => 'Dresses, tops, skirts and more for women.', 'sort_order' => 2, 'icon' => 'Shirt'],
                ['name' => 'Kids Clothing', 'description' => 'Comfortable and fun clothing for children.', 'sort_order' => 3, 'icon' => 'Shirt'],
                ['name' => 'Shoes', 'description' => 'Sneakers, boots, sandals and formal shoes.', 'sort_order' => 4, 'icon' => 'Footprints'],
                ['name' => 'Bags', 'description' => 'Handbags, backpacks, and travel bags.', 'sort_order' => 5, 'icon' => 'ShoppingBag'],
                ['name' => 'Watches', 'description' => 'Wristwatches and smartwatches.', 'sort_order' => 6, 'icon' => 'Watch'],
                ['name' => 'Jewelry', 'description' => 'Necklaces, rings, bracelets and earrings.', 'sort_order' => 7, 'icon' => 'Gem'],
                ['name' => 'Accessories', 'description' => 'Belts, scarves, sunglasses and more.', 'sort_order' => 8, 'icon' => 'Tag'],
            ],
        ],
        'Electronics' => [
            'description' => 'Latest gadgets, computers, and smart devices.',
            'sort_order' => 2,
            'featured' => true,
            'icon' => 'Cpu',
            'subcategories' => [
                ['name' => 'Laptops', 'description' => 'Notebooks and ultrabooks for work and play.', 'sort_order' => 1, 'icon' => 'Laptop'],
                ['name' => 'Desktop Computers', 'description' => 'Desktops and all-in-one PCs.', 'sort_order' => 2, 'icon' => 'Monitor'],
                ['name' => 'Gaming PCs', 'description' => 'High-performance gaming rigs.', 'sort_order' => 3, 'icon' => 'Gamepad2'],
                ['name' => 'Monitors', 'description' => 'Displays for gaming, work and entertainment.', 'sort_order' => 4, 'icon' => 'Monitor'],
                ['name' => 'Smartphones', 'description' => 'Android and iOS smartphones.', 'sort_order' => 5, 'icon' => 'Smartphone'],
                ['name' => 'Tablets', 'description' => 'iPads and Android tablets.', 'sort_order' => 6, 'icon' => 'Tablet'],
                ['name' => 'Computer Accessories', 'description' => 'Keyboards, mice, and peripherals.', 'sort_order' => 7, 'icon' => 'Keyboard'],
                ['name' => 'Components', 'description' => 'CPUs, GPUs, RAM and motherboards.', 'sort_order' => 8, 'icon' => 'Cpu'],
            ],
        ],
        'Home & Living' => [
            'description' => 'Everything you need to make your house a home.',
            'sort_order' => 3,
            'featured' => true,
            'icon' => 'Sofa',
            'subcategories' => [
                ['name' => 'Furniture', 'description' => 'Sofas, tables, chairs and bedroom sets.', 'sort_order' => 1, 'icon' => 'Armchair'],
                ['name' => 'Kitchen', 'description' => 'Cookware, appliances and utensils.', 'sort_order' => 2, 'icon' => 'CookingPot'],
                ['name' => 'Home Decor', 'description' => 'Vases, cushions, wall art and more.', 'sort_order' => 3, 'icon' => 'Flower'],
                ['name' => 'Lighting', 'description' => 'Lamps, chandeliers and LED fixtures.', 'sort_order' => 4, 'icon' => 'Lamp'],
                ['name' => 'Storage', 'description' => 'Shelves, cabinets and organizers.', 'sort_order' => 5, 'icon' => 'Boxes'],
                ['name' => 'Cleaning', 'description' => 'Vacuums, mops and cleaning supplies.', 'sort_order' => 6, 'icon' => 'Sparkles'],
            ],
        ],
        'Sports & Fitness' => [
            'description' => 'Gear and equipment for your active lifestyle.',
            'sort_order' => 4,
            'featured' => true,
            'icon' => 'Dumbbell',
            'subcategories' => [
                ['name' => 'Fitness Equipment', 'description' => 'Weights, machines and resistance bands.', 'sort_order' => 1, 'icon' => 'Dumbbell'],
                ['name' => 'Cycling', 'description' => 'Bikes, helmets and cycling gear.', 'sort_order' => 2, 'icon' => 'Bike'],
                ['name' => 'Running', 'description' => 'Running shoes, apparel and trackers.', 'sort_order' => 3, 'icon' => 'Footprints'],
                ['name' => 'Camping', 'description' => 'Tents, sleeping bags and camp gear.', 'sort_order' => 4, 'icon' => 'Tent'],
                ['name' => 'Outdoor Equipment', 'description' => 'Hiking, climbing and adventure gear.', 'sort_order' => 5, 'icon' => 'Mountain'],
            ],
        ],
    ];

    public function run(): void
    {
        foreach ($this->categories as $name => $data) {
            $parent = Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'featured' => $data['featured'],
                    'status' => 'active',
                    'icon' => $data['icon'] ?? null,
                    'seo_title' => "Shop {$name} | NBK Vertex",
                    'seo_description' => "Browse our {$name} collection at NBK Vertex.",
                ],
            );

            foreach ($data['subcategories'] as $sub) {
                Category::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($sub['name'])],
                    [
                        'name' => $sub['name'],
                        'description' => $sub['description'],
                        'parent_id' => $parent->id,
                        'sort_order' => $sub['sort_order'],
                        'featured' => false,
                        'status' => 'active',
                        'icon' => $sub['icon'] ?? null,
                    ],
                );
            }
        }
    }
}
