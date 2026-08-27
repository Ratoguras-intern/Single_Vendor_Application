<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $imagePool = [
        'mens-clothing' => [
            'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1611312449408-fcece27cdbb7?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1617137968427-85924c800a22?q=80&w=800&auto=format&fit=crop',
        ],
        'womens-clothing' => [
            'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1623479322729-28b25c16b011?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?q=80&w=800&auto=format&fit=crop',
        ],
        'kids-clothing' => [
            'https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1522771930-78848d9293e8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1522771930-78848d9293e8?q=80&w=800&auto=format&fit=crop',
        ],
        'shoes' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1560769629-975ec94e6a86?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1579338559194-a162d19bf842?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1533867617858-e7b97e060509?q=80&w=800&auto=format&fit=crop',
        ],
        'bags' => [
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1554342872-034a06541bad?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1594223274512-ad4805609b7c?q=80&w=800&auto=format&fit=crop',
        ],
        'watches' => [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1547996160-81dfa63595aa?q=80&w=800&auto=format&fit=crop',
        ],
        'jewelry' => [
            'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?q=80&w=800&auto=format&fit=crop',
        ],
        'accessories' => [
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1556306535-0f09a537f0a3?q=80&w=800&auto=format&fit=crop',
        ],
        'laptops' => [
            'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?q=80&w=800&auto=format&fit=crop',
        ],
        'desktop-computers' => [
            'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1547082299-de196ea013d6?q=80&w=800&auto=format&fit=crop',
        ],
        'gaming-pcs' => [
            'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1603302576837-37561b2e2302?q=80&w=800&auto=format&fit=crop',
        ],
        'monitors' => [
            'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1586210579191-33b45e38fa2c?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=800&auto=format&fit=crop',
        ],
        'smartphones' => [
            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1556656793-08538906a9f8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1580910051074-3eb694886505?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1605236453806-6ff36851218e?q=80&w=800&auto=format&fit=crop',
        ],
        'tablets' => [
            'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1561154464-82e9adf32764?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9?q=80&w=800&auto=format&fit=crop',
        ],
        'computer-accessories' => [
            'https://images.unsplash.com/photo-1587829741301-dc798b83add3?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1527814050087-3793815479db?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?q=80&w=800&auto=format&fit=crop',
        ],
        'components' => [
            'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555617981-dac3880eac6e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1553406830-ef2513450d76?q=80&w=800&auto=format&fit=crop',
        ],
        'furniture' => [
            'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1567016432779-094069958ea5?q=80&w=800&auto=format&fit=crop',
        ],
        'kitchen' => [
            'https://images.unsplash.com/photo-1556911220-bff31c812dba?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1584345604476-8ec5e12e42dd?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?q=80&w=800&auto=format&fit=crop',
        ],
        'home-decor' => [
            'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?q=80&w=800&auto=format&fit=crop',
        ],
        'lighting' => [
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?q=80&w=800&auto=format&fit=crop',
        ],
        'storage' => [
            'https://images.unsplash.com/photo-1585543805890-6051f7829f98?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1594620302200-9a762244a156?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1594575111057-47b35c5f98f7?q=80&w=800&auto=format&fit=crop',
        ],
        'cleaning' => [
            'https://images.unsplash.com/photo-1585421514738-01798e348b17?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1563453392212-326f5e854473?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1628177142898-93e36e4e3a50?q=80&w=800&auto=format&fit=crop',
        ],
        'fitness-equipment' => [
            'https://images.unsplash.com/photo-1517963879433-6ad2b056d712?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1558611848-73f7eb4001a1?q=80&w=800&auto=format&fit=crop',
        ],
        'cycling' => [
            'https://images.unsplash.com/photo-1485965120184-e220f721d03e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1571068316344-75bc76f77890?q=80&w=800&auto=format&fit=crop',
        ],
        'running' => [
            'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1594737625785-a6cbdabd333c?q=80&w=800&auto=format&fit=crop',
        ],
        'camping' => [
            'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1537565266759-34bbc16be345?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?q=80&w=800&auto=format&fit=crop',
        ],
        'outdoor-equipment' => [
            'https://images.unsplash.com/photo-1522163182402-834f871fd851?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?q=80&w=800&auto=format&fit=crop',
        ],
    ];

    private array $fallbackImages = [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?q=80&w=800&auto=format&fit=crop',
    ];

    private array $prefixes = ['Pro', 'Elite', 'Nova', 'Urban', 'Aero', 'Pulse', 'Volt', 'Zenith', 'Core', 'Luxe'];

    private array $suffixes = ['One', 'Plus', 'Max', 'Lite', 'Prime', 'X', 'Ultra', 'Slim', 'Gen 2', 'Studio'];

    private int $counter = 0;

    public function run(): void
    {
        // Drop all existing products and their images
        ProductImage::query()->delete();
        Product::query()->delete();

        $brands = Brand::all();
        if ($brands->isEmpty()) {
            return;
        }

        $categories = Category::whereNotNull('parent_id')->orderBy('id')->get();
        if ($categories->isEmpty()) {
            $categories = Category::whereNull('parent_id')->orderBy('id')->get();
        }

        foreach ($categories as $category) {
            $this->seedCategory($category, $brands);
        }
    }

    private function seedCategory(Category $category, $brands): void
    {
        $perCategory = 4;
        $images = $this->imagePool[$category->slug] ?? $this->fallbackImages;

        for ($i = 0; $i < $perCategory; $i++) {
            $this->counter++;
            $brand = $brands->random();
            $name = $this->generateName($category->name);
            $slug = Str::slug($name);

            if (Product::where('slug', $slug)->exists()) {
                $slug = $slug.'-'.$this->counter;
            }

            $price = round((rand(29, 490) + $this->counter) / 5) * 5;
            $discountPrice = rand(0, 1) ? round($price * (0.7 + rand(0, 20) / 100) / 5) * 5 : null;

            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $this->description($category->name),
                'price' => $price,
                'discount_price' => $discountPrice,
                'stock' => $this->counter === 9 ? 0 : (($this->counter * 7) % 75 + 5),
                'sku' => strtoupper(substr(Str::slug($category->slug), 0, 4)).'-'.str_pad((string) $this->counter, 4, '0', STR_PAD_LEFT),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'status' => 'active',
                'is_new_arrival' => $this->counter % 3 === 0,
                'is_flash_sale' => $discountPrice !== null,
                'is_best_seller' => $this->counter % 5 === 0,
                'is_featured' => $this->counter % 5 === 1,
                'is_trending' => $this->counter % 5 === 2,
                'is_recommended' => $this->counter % 5 === 3,
                'is_popular' => $this->counter % 5 === 4,
                'is_limited_edition' => $this->counter % 10 === 7,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $images[$i % count($images)],
                'is_primary' => true,
            ]);
        }
    }

    private function generateName(string $categoryName): string
    {
        $prefix = $this->prefixes[array_rand($this->prefixes)];
        $suffix = $this->suffixes[array_rand($this->suffixes)];

        return $prefix.' '.$categoryName.' '.$suffix;
    }

    private function description(string $categoryName): string
    {
        return 'Premium-quality '.$categoryName.' product engineered for everyday performance, comfort and durability.';
    }
}
