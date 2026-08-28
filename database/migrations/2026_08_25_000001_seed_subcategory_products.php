<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $categories = DB::table('categories')
            ->whereNotNull('parent_id')
            ->get(['id', 'name', 'parent_id']);

        $brandIds = DB::table('brands')->pluck('id')->toArray();
        if (empty($brandIds)) return;

        $unsplashImages = [
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
            'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=600&q=80',
            'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=600&q=80',
            'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&q=80',
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&q=80',
            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600&q=80',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80',
            'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=600&q=80',
            'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=600&q=80',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
            'https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?w=600&q=80',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600&q=80',
            'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=600&q=80',
            'https://images.unsplash.com/photo-1622434641406-a158123450f9?w=600&q=80',
            'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&q=80',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80',
            'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=600&q=80',
            'https://images.unsplash.com/photo-1434056886845-dbe89f0b9571?w=600&q=80',
        ];

        $productTemplates = [
            'Men\'s Clothing' => [
                ['name' => 'Classic Oxford Shirt', 'desc' => 'Timeless oxford shirt in premium cotton. Perfect for casual and semi-formal occasions.', 'price' => 45.00],
                ['name' => 'Slim Fit Chinos', 'desc' => 'Modern slim fit chinos with stretch fabric for all-day comfort.', 'price' => 55.00],
                ['name' => 'Denim Jacket', 'desc' => 'Vintage-wash denim jacket with brass button closure.', 'price' => 79.00],
            ],
            'Women\'s Clothing' => [
                ['name' => 'Floral Summer Dress', 'desc' => 'Lightweight floral print dress with adjustable straps.', 'price' => 62.00],
                ['name' => 'High-Waist Palazzo Pants', 'desc' => 'Flowing palazzo pants with high-rise waistband.', 'price' => 48.00],
                ['name' => 'Cashmere Cardigan', 'desc' => 'Soft cashmere blend cardigan with ribbed trim.', 'price' => 89.00],
            ],
            'Kids Clothing' => [
                ['name' => 'Rainbow Graphic Tee', 'desc' => 'Fun rainbow print t-shirt in soft organic cotton.', 'price' => 18.00],
                ['name' => 'Stretch Denim Overalls', 'desc' => 'Adjustable denim overalls with reinforced knees.', 'price' => 35.00],
                ['name' => 'Cozy Fleece Hoodie', 'desc' => 'Warm fleece hoodie with kangaroo pocket.', 'price' => 28.00],
            ],
            'Jewelry' => [
                ['name' => 'Sterling Silver Chain', 'desc' => 'Elegant sterling silver chain necklace, 18 inches.', 'price' => 65.00],
                ['name' => 'Pearl Drop Earrings', 'desc' => 'Classic freshwater pearl earrings with gold hooks.', 'price' => 42.00],
                ['name' => 'Leather Wrap Bracelet', 'desc' => 'Handcrafted leather bracelet with magnetic clasp.', 'price' => 32.00],
            ],
            'Accessories' => [
                ['name' => 'Polarized Aviator Sunglasses', 'desc' => 'UV400 polarized lenses in lightweight metal frame.', 'price' => 38.00],
                ['name' => 'Wool Blend Scarf', 'desc' => 'Soft wool blend scarf in herringbone pattern.', 'price' => 29.00],
                ['name' => 'Leather Belt', 'desc' => 'Full-grain leather belt with brushed nickel buckle.', 'price' => 35.00],
            ],
            'Desktop Computers' => [
                ['name' => 'ProStation Desktop', 'desc' => 'High-performance desktop with latest gen processor.', 'price' => 899.00],
                ['name' => 'Compact Mini PC', 'desc' => 'Space-saving mini PC for everyday computing.', 'price' => 449.00],
                ['name' => 'Workstation X1', 'desc' => 'Professional workstation for creative workflows.', 'price' => 1299.00],
            ],
            'Gaming PCs' => [
                ['name' => 'GameForge Titan', 'desc' => 'Ultimate gaming rig with top-tier GPU.', 'price' => 1899.00],
                ['name' => 'StormRider Pro', 'desc' => 'Mid-range gaming PC with RGB lighting.', 'price' => 1199.00],
                ['name' => 'NanoForce Mini', 'desc' => 'Compact gaming PC that packs a punch.', 'price' => 999.00],
            ],
            'Monitors' => [
                ['name' => 'UltraWide 34"', 'desc' => '34-inch ultrawide curved monitor, 1440p.', 'price' => 449.00],
                ['name' => '4K ProDisplay', 'desc' => '27-inch 4K UHD monitor with HDR support.', 'price' => 399.00],
                ['name' => 'Gaming LED 27"', 'desc' => '165Hz gaming monitor with 1ms response time.', 'price' => 329.00],
            ],
            'Tablets' => [
                ['name' => 'SlateTab 11', 'desc' => '11-inch tablet with stylus support.', 'price' => 349.00],
                ['name' => 'MiniTab 8', 'desc' => 'Compact 8-inch tablet for on-the-go.', 'price' => 199.00],
                ['name' => 'ProTab Studio', 'desc' => 'Professional tablet for digital artists.', 'price' => 599.00],
            ],
            'Components' => [
                ['name' => 'RTX 5070 Graphics Card', 'desc' => 'Next-gen GPU for gaming and creative work.', 'price' => 549.00],
                ['name' => 'DDR5 32GB Kit', 'desc' => 'High-speed DDR5 memory kit, 2x16GB.', 'price' => 129.00],
                ['name' => 'NVMe SSD 2TB', 'desc' => 'Ultra-fast NVMe SSD with 7000MB/s read.', 'price' => 159.00],
            ],
            'Furniture' => [
                ['name' => 'Ergonomic Office Chair', 'desc' => 'Adjustable chair with lumbar support and headrest.', 'price' => 299.00],
                ['name' => 'Standing Desk Pro', 'desc' => 'Electric sit-stand desk with programmable heights.', 'price' => 449.00],
                ['name' => 'Bookshelf 5-Tier', 'desc' => 'Industrial-style bookshelf with solid wood shelves.', 'price' => 179.00],
            ],
            'Kitchen' => [
                ['name' => 'Air Fryer XL', 'desc' => '6-quart digital air fryer with 8 presets.', 'price' => 89.00],
                ['name' => 'Knife Set 12pc', 'desc' => 'Professional knife set with bamboo block.', 'price' => 129.00],
                ['name' => 'Espresso Machine', 'desc' => 'Semi-automatic espresso machine with milk frother.', 'price' => 249.00],
            ],
            'Lighting' => [
                ['name' => 'Smart LED Floor Lamp', 'desc' => 'WiFi-enabled floor lamp with color changing.', 'price' => 79.00],
                ['name' => 'Industrial Pendant Light', 'desc' => 'Rustic pendant light with Edison bulb.', 'price' => 59.00],
                ['name' => 'LED Desk Lamp', 'desc' => 'Dimmable desk lamp with USB charging port.', 'price' => 45.00],
            ],
            'Storage' => [
                ['name' => 'Modular Drawer Unit', 'desc' => 'Stackable drawer organizer in bamboo.', 'price' => 65.00],
                ['name' => 'Under-Bed Storage Box', 'desc' => 'Low-profile storage with rolling wheels.', 'price' => 35.00],
                ['name' => 'Wire Basket Set', 'desc' => 'Set of 3 decorative wire storage baskets.', 'price' => 42.00],
            ],
            'Cleaning' => [
                ['name' => 'Robot Vacuum Pro', 'desc' => 'Smart robot vacuum with mopping function.', 'price' => 299.00],
                ['name' => 'Steam Mop Elite', 'desc' => 'Chemical-free steam mop for all floors.', 'price' => 119.00],
                ['name' => 'Cordless Stick Vacuum', 'desc' => 'Lightweight cordless vacuum with HEPA filter.', 'price' => 199.00],
            ],
            'Cycling' => [
                ['name' => 'Carbon Road Bike', 'desc' => 'Lightweight carbon frame road bike, 22-speed.', 'price' => 1299.00],
                ['name' => 'Urban Commuter Bike', 'desc' => 'Durable city bike with fenders and rack.', 'price' => 449.00],
                ['name' => 'MTB Trail Bike', 'desc' => 'Full suspension mountain bike, 29er wheels.', 'price' => 899.00],
            ],
            'Running' => [
                ['name' => 'Pro Runner Shoes', 'desc' => 'Responsive running shoes with carbon plate.', 'price' => 159.00],
                ['name' => 'GPS Running Watch', 'desc' => 'Multi-sport GPS watch with heart rate.', 'price' => 249.00],
                ['name' => 'Hydration Vest', 'desc' => 'Lightweight running vest with 2L bladder.', 'price' => 79.00],
            ],
            'Camping' => [
                ['name' => '4-Person Tent', 'desc' => 'Waterproof dome tent with easy setup.', 'price' => 189.00],
                ['name' => 'Sleeping Bag -10C', 'desc' => 'Mummy sleeping bag rated to -10°C.', 'price' => 99.00],
                ['name' => 'Portable Camp Stove', 'desc' => 'Compact propane stove with windscreen.', 'price' => 49.00],
            ],
            'Outdoor Equipment' => [
                ['name' => 'Hiking Backpack 65L', 'desc' => 'Large capacity hiking pack with rain cover.', 'price' => 129.00],
                ['name' => 'Trekking Poles Pair', 'desc' => 'Adjustable carbon trekking poles.', 'price' => 59.00],
                ['name' => 'Water Filter System', 'desc' => 'Portable water purifier for camping.', 'price' => 39.00],
            ],
        ];

        $now = now();
        $products = [];

        foreach ($categories as $cat) {
            $existingCount = DB::table('products')->where('category_id', $cat->id)->count();
            if ($existingCount >= 2) continue;

            $templates = $productTemplates[$cat->name] ?? null;
            if (!$templates) {
                $templates = [
                    ['name' => $cat->name . ' Premium Item', 'desc' => 'High-quality ' . strtolower($cat->name) . ' product.', 'price' => 49.00],
                    ['name' => $cat->name . ' Best Seller', 'desc' => 'Top-rated ' . strtolower($cat->name) . ' essential.', 'price' => 65.00],
                    ['name' => $cat->name . ' Classic', 'desc' => 'Classic ' . strtolower($cat->name) . ' piece for everyday use.', 'price' => 38.00],
                ];
            }

            $toAdd = 3 - $existingCount;
            foreach (array_slice($templates, 0, $toAdd) as $t) {
                $brandId = $brandIds[array_rand($brandIds)];
                $price = $t['price'];
                $discount = $price > 40 ? round($price * 0.8, 2) : null;
                $slug = Str::slug($t['name']) . '-' . Str::random(5);
                $img = $unsplashImages[array_rand($unsplashImages)];

                $productId = DB::table('products')->insertGetId([
                    'name' => $t['name'],
                    'slug' => $slug,
                    'description' => $t['desc'],
                    'price' => $price,
                    'discount_price' => $discount,
                    'stock' => rand(5, 50),
                    'sku' => strtoupper(Str::random(3) . '-' . rand(1000, 9999)),
                    'average_rating' => round(mt_rand(300, 500) / 100, 2),
                    'reviews_count' => rand(0, 25),
                    'category_id' => $cat->id,
                    'brand_id' => $brandId,
                    'status' => 1,
                    'is_featured' => (bool) rand(0, 1),
                    'is_new_arrival' => (bool) rand(0, 1),
                    'is_trending' => (bool) rand(0, 1),
                    'is_best_seller' => (bool) rand(0, 1),
                    'is_flash_sale' => false,
                    'is_recommended' => false,
                    'is_popular' => (bool) rand(0, 1),
                    'is_limited_edition' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image' => $img,
                    'is_primary' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Don't delete in down() for safety
    }
};
