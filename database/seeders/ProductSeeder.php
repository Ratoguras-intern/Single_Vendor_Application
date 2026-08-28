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
    /**
     * Curated Unsplash image URLs per subcategory. All URLs resolve with a
     * 200 status and are stored exactly like the admin panel stores uploads
     * (product_images.image) via the existing product_image_url() helper.
     */
    private array $imagePool = [
        'smartphones' => [
            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=800&auto=format&fit=crop&q=80',
        ],
        'laptops' => [
            'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1587614382346-4ec70e388b28?w=800&auto=format&fit=crop&q=80',
        ],
        'tablets' => [
            'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9?w=800&auto=format&fit=crop&q=80',
        ],
        'desktops-monitors' => [
            'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1547082299-de196ea013d6?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
        ],
        'computer-accessories' => [
            'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1527814050087-3793815479db?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
        ],
        'mens-clothing' => [
            'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1611312449408-fcece27cdbb7?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1617137968427-85924c800a22?w=800&auto=format&fit=crop&q=80',
        ],
        'womens-clothing' => [
            'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1623479322729-28b25c16b011?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?w=800&auto=format&fit=crop&q=80',
        ],
        'sneakers' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1579338559194-a162d19bf842?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=800&auto=format&fit=crop&q=80',
        ],
        'smart-watches' => [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=800&auto=format&fit=crop&q=80',
        ],
        'bags-travel' => [
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1554342872-034a06541bad?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1594223274512-ad4805609b7c?w=800&auto=format&fit=crop&q=80',
        ],
        'furniture' => [
            'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1567016432779-094069958ea5?w=800&auto=format&fit=crop&q=80',
        ],
        'kitchen-dining' => [
            'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1584345604476-8ec5e12e42dd?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&auto=format&fit=crop&q=80',
        ],
        'home-decor' => [
            'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
        ],
        'beauty-skincare' => [
            'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=800&auto=format&fit=crop&q=80',
        ],
        'fitness-equipment' => [
            'https://images.unsplash.com/photo-1517963879433-6ad2b056d712?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1558611848-73f7eb4001a1?w=800&auto=format&fit=crop&q=80',
        ],
        'running-outdoor' => [
            'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1594737625785-a6cbdabd333c?w=800&auto=format&fit=crop&q=80',
        ],
        'gaming-consoles' => [
            'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
        ],
        'headphones-speakers' => [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
        ],
        'toys-games' => [
            'https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?w=800&auto=format&fit=crop&q=80',
        ],
        'pet-essentials' => [
            'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&auto=format&fit=crop&q=80',
        ],
        'baby-essentials' => [
            'https://images.unsplash.com/photo-1519689680058-324335c77eba?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=800&auto=format&fit=crop&q=80',
        ],
        'garden-patio' => [
            'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?w=800&auto=format&fit=crop&q=80',
        ],
    ];

    private array $fallbackImages = [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&auto=format&fit=crop&q=80',
    ];

    private array $products = [
        'smartphones' => [
            ['name' => 'Apple iPhone 15 Pro 256GB', 'brand' => 'apple', 'price' => 1099, 'discount' => 10, 'desc' => 'Titanium frame, A17 Pro chip, pro camera system and USB-C. Available in Natural Titanium, Blue Titanium, White and Black.'],
            ['name' => 'Apple iPhone 15 128GB', 'brand' => 'apple', 'price' => 799, 'desc' => 'Dynamic Island, A16 Bionic and a 48MP main camera in a durable design.'],
            ['name' => 'Samsung Galaxy S25 512GB', 'brand' => 'samsung', 'price' => 959, 'discount' => 15, 'desc' => 'Galaxy AI, 200MP camera and an all-day battery with 45W fast charging.'],
            ['name' => 'Samsung Galaxy A56 5G 256GB', 'brand' => 'samsung', 'price' => 449, 'desc' => 'Bright 120Hz AMOLED display and a 50MP camera in a slim aluminium body.'],
            ['name' => 'Google Pixel 9 Pro 256GB', 'brand' => 'google', 'price' => 999, 'desc' => 'Google Tensor G4, pro-level triple camera and seven years of OS updates.'],
            ['name' => 'Google Pixel 8a 128GB', 'brand' => 'google', 'price' => 499, 'desc' => 'The smartest Pixel AI features in an affordable, compact design.'],
            ['name' => 'Xiaomi 14 Ultra 512GB', 'brand' => 'xiaomi', 'price' => 899, 'desc' => 'Leica quad-camera system, Snapdragon 8 Gen 3 and a bright LTPO AMOLED.'],
            ['name' => 'Xiaomi Redmi Note 14 Pro 256GB', 'brand' => 'xiaomi', 'price' => 329, 'desc' => '200MP OIS camera, 120Hz Curved AMOLED and 67W HyperCharge.'],
            ['name' => 'OnePlus 13 256GB', 'brand' => 'oneplus', 'price' => 849, 'discount' => 10, 'desc' => 'Snapdragon 8 Elite, Hasselblad cameras, 6000mAh battery and AquaTouch display.'],
            ['name' => 'OnePlus Nord 5 128GB', 'brand' => 'oneplus', 'price' => 429, 'desc' => 'Flagship-grade performance, 50MP camera and clean OxygenOS experience.'],
            ['name' => 'Nothing Phone (3a) 128GB', 'brand' => 'nothing', 'price' => 379, 'desc' => 'Glyph interface, transparent design and a versatile 50MP triple camera.'],
            ['name' => 'Samsung Galaxy Z Flip 7 512GB', 'brand' => 'samsung', 'price' => 1099, 'discount' => 10, 'desc' => 'FlexWindow cover display, flagship cameras and a refined foldable design.'],
        ],
        'laptops' => [
            ['name' => 'MacBook Air 13 (M4) 256GB', 'brand' => 'apple', 'price' => 1099, 'desc' => 'Apple M4 chip, 18-hour battery life and a fanless design in four finishes.'],
            ['name' => 'MacBook Pro 14 (M4 Pro) 512GB', 'brand' => 'apple', 'price' => 1999, 'discount' => 10, 'desc' => 'M4 Pro chip, Liquid Retina XDR display and pro-oriented ports.'],
            ['name' => 'Dell XPS 14 (Ultra 7) 512GB', 'brand' => 'dell', 'price' => 1499, 'desc' => 'Premium aluminum build, 3.2K OLED touch panel and Intel Core Ultra processors.'],
            ['name' => 'Dell Inspiron 16 Plus 1TB', 'brand' => 'dell', 'price' => 849, 'discount' => 10, 'desc' => '16-inch display, Iris Xe graphics and generous storage for everyday productivity.'],
            ['name' => 'HP Spectre x360 14 (Ultra 7)', 'brand' => 'hp', 'price' => 1399, 'discount' => 10, 'desc' => '360° convertible with 2.8K OLED, IMAX-certified audio and all-day endurance.'],
            ['name' => 'HP Pavilion 15 (i7) 512GB', 'brand' => 'hp', 'price' => 749, 'desc' => 'Reliable everyday laptop with a full-HD display and fast NVMe storage.'],
            ['name' => 'Lenovo ThinkPad X1 Carbon Gen 13', 'brand' => 'lenovo', 'price' => 1699, 'desc' => 'Ultralight 1kg business ultrabook with a backlit keyboard and military-grade testing.'],
            ['name' => 'Lenovo IdeaPad Slim 5 (Ryzen 7)', 'brand' => 'lenovo', 'price' => 699, 'desc' => 'Slim and light with a bright display, great speakers and Ryzen performance.'],
            ['name' => 'ASUS Zenbook 14 OLED (Ultra 7)', 'brand' => 'asus', 'price' => 1199, 'discount' => 10, 'desc' => 'Stunning 3K OLED display, precision CNC body and 20-hour battery life.'],
            ['name' => 'ASUS TUF Gaming A16 (RTX 4060)', 'brand' => 'asus', 'price' => 1299, 'desc' => 'Rugged gaming laptop with a 165Hz display, Ryzen 9 and RTX 4060.'],
            ['name' => 'Microsoft Surface Laptop 7 (Snapdragon X)', 'brand' => 'microsoft', 'price' => 1299, 'desc' => 'Copilot+ PC with a PixelSense touchscreen and 22-hour battery life.'],
            ['name' => 'Acer Swift Go 14 (Ultra 5)', 'brand' => 'acer', 'price' => 749, 'desc' => 'Featherweight travel laptop with OLED display and AI-boosted processes.'],
        ],
        'tablets' => [
            ['name' => 'iPad Pro 11 (M4) 256GB', 'brand' => 'apple', 'price' => 999, 'desc' => 'Ultra Retina XDR display, M4 chip and Apple Pencil Pro support.'],
            ['name' => 'iPad Air 13 (M2) 128GB', 'brand' => 'apple', 'price' => 799, 'desc' => 'Big canvas, M2 performance and Apple Pencil Pro compatibility.'],
            ['name' => 'iPad 10th Gen 64GB', 'brand' => 'apple', 'price' => 349, 'desc' => 'All-screen design, A14 Bionic and USB-C in four playful colors.'],
            ['name' => 'Samsung Galaxy Tab S10+ 256GB', 'brand' => 'samsung', 'price' => 999, 'discount' => 10, 'desc' => 'Dynamic AMOLED 2X display, Galaxy AI and S Pen included.'],
            ['name' => 'Samsung Galaxy Tab A9+ 64GB', 'brand' => 'samsung', 'price' => 219, 'desc' => 'Entry-level entertainment tablet with Dolby Atmos quad speakers.'],
            ['name' => 'Google Pixel Tablet 128GB', 'brand' => 'google', 'price' => 499, 'desc' => 'Hub mode with charging speaker dock for hands-free Google Assistant.'],
            ['name' => 'Xiaomi Pad 6 128GB', 'brand' => 'xiaomi', 'price' => 399, 'discount' => 10, 'desc' => '144Hz 2.8K display, Snapdragon 870 and 33W fast charging.'],
            ['name' => 'Lenovo Tab P12 128GB', 'brand' => 'lenovo', 'price' => 349, 'desc' => '12.7-inch 3K display and quad JBL speakers for immersive media.'],
            ['name' => 'Amazon Fire Max 11 64GB', 'brand' => 'amazon', 'price' => 249, 'desc' => 'Fastest Fire tablet yet with 2.4K display and optional stylus.'],
            ['name' => 'Samsung Galaxy Tab S9 FE 128GB', 'brand' => 'samsung', 'price' => 449, 'desc' => 'Fan edition tablet with S Pen, IP68 water resistance and 90Hz display.'],
        ],
        'desktops-monitors' => [
            ['name' => 'Apple Mac Mini (M4) 256GB', 'brand' => 'apple', 'price' => 599, 'desc' => 'Supercharged desktop in a 5-inch frame with three Thunderbolt 4 ports.'],
            ['name' => 'Apple Studio Display 27" Standard Glass', 'brand' => 'apple', 'price' => 1599, 'desc' => '5K Retina display with a six-speaker sound system and studio-quality mic.'],
            ['name' => 'Dell XPS Desktop (Core Ultra 9)', 'brand' => 'dell', 'price' => 1299, 'desc' => 'Compact professional tower with GeForce RTX graphics and 64GB RAM.'],
            ['name' => 'Dell UltraSharp 32" 4K USB-C Hub', 'brand' => 'dell', 'price' => 999, 'desc' => 'IPS Black 4K panel, dual USB-C and 98% DCI-P3 color coverage.'],
            ['name' => 'HP OMEN 45L Gaming Desktop (RTX 4070)', 'brand' => 'hp', 'price' => 2199, 'desc' => 'CryoChamber cooling, RTX 4070 and 32GB DDR5 for 1440p gaming.'],
            ['name' => 'HP 27" FHD IPS Monitor', 'brand' => 'hp', 'price' => 179, 'desc' => 'Crisp full-HD IPS panel with slim bezels and tilt-adjustable stand.'],
            ['name' => 'Lenovo ThinkCentre Neo 50s (i5)', 'brand' => 'lenovo', 'price' => 749, 'desc' => 'Business desktop with Intel vPro and easy tool-less access.'],
            ['name' => 'Samsung Odyssey OLED G8 32" 4K', 'brand' => 'samsung', 'price' => 999, 'discount' => 10, 'desc' => '240Hz OLED gaming monitor with a 0.03ms response and AtomOS.'],
            ['name' => 'ASUS ProArt Display PA279CRV 27"', 'brand' => 'asus', 'price' => 399, 'desc' => 'Calman Verified 4K panel with USB-C PD for creative professionals.'],
            ['name' => 'LG UltraWide 34" 1440p', 'brand' => 'lg', 'price' => 449, 'desc' => 'Curved IPS ultrawide with 99% sRGB and USB-C docking.'],
            ['name' => 'Dell 27" 4K UHD Monitor', 'brand' => 'dell', 'price' => 579, 'desc' => 'Four-sided ultrathin bezels, USB-C hub and 4K clarity for any desk.'],
            ['name' => 'Samsung Smart Monitor M8 32"', 'brand' => 'samsung', 'price' => 599, 'desc' => '4K smart monitor with built-in apps, webcam and remote PC access.'],
        ],
        'computer-accessories' => [
            ['name' => 'Logitech MX Master 3S Wireless Mouse', 'brand' => 'logitech', 'price' => 99, 'desc' => '8K DPI optical sensor, quiet clicks and MagSpeed scroll wheel.'],
            ['name' => 'Logitech MX Keys S Wireless Keyboard', 'brand' => 'logitech', 'price' => 109, 'desc' => 'Low-profile backlit keys with smart backlighting and USB-C charging.'],
            ['name' => 'Razer DeathAdder V3 Wired Gaming Mouse', 'brand' => 'razer', 'price' => 69, 'desc' => '59g lightweight ergonomic gaming mouse with 30K optical sensor.'],
            ['name' => 'Razer BlackWidow V4 75% Hot-Swap Keyboard', 'brand' => 'razer', 'price' => 179, 'discount' => 10, 'desc' => 'Hot-swappable switches, aluminum top plate and ROG-like acoustics.'],
            ['name' => 'Corsair K70 RGB PRO (Cherry MX Red)', 'brand' => 'corsair', 'price' => 159, 'desc' => 'Rapid Trigger tech, aluminum frame and per-key RGB on AXON.'],
            ['name' => 'Anker 65W GaN USB-C Charger', 'brand' => 'anker', 'price' => 49, 'desc' => 'GaN II technology in a tiny footprint — fast charge two devices at once.'],
            ['name' => 'Anker USB-C Hub 7-in-1 4K', 'brand' => 'anker', 'price' => 79, 'desc' => 'HDMI, SD card and three USB-A ports for a clean laptop setup.'],
            ['name' => 'Logitech C920s Pro HD Webcam', 'brand' => 'logitech', 'price' => 79, 'desc' => 'Full HD 1080p video with dual mics and a privacy shutter.'],
            ['name' => 'Corsair MM300 Extended Mouse Pad', 'brand' => 'corsair', 'price' => 39, 'desc' => 'Anti-fray stitched cloth surface with a non-slip rubber base.'],
            ['name' => 'Anker 2TB Portable SSD', 'brand' => 'anker', 'price' => 149, 'desc' => 'Up to 1050MB/s reads, USB-C and rugged pocket-sized design.'],
            ['name' => 'Razer Gigantus V2 Large Mouse Pad', 'brand' => 'razer', 'price' => 24, 'desc' => 'Micro-etched texture for speed and control in every flick.'],
            ['name' => 'Logitech Brio 500 Streaming Webcam', 'brand' => 'logitech', 'price' => 99, 'desc' => '1080p with HDR, Show Mode tilt and auto light correction.'],
        ],
        'mens-clothing' => [
            ['name' => 'Levi\'s 501 Original Fit Jeans', 'brand' => 'levis', 'price' => 89, 'discount' => 20, 'desc' => 'The iconic straight leg, button fly and premium non-stretch denim.'],
            ['name' => 'Levi\'s Trucker Jacket', 'brand' => 'levis', 'price' => 128, 'desc' => 'Vintage-wash denim jacket with corduroy collar and button closure.'],
            ['name' => 'Zara Slim Fit Wool Suit', 'brand' => 'zara', 'price' => 349, 'desc' => 'Tailored two-piece suit in a breathable wool blend for sharp occasions.'],
            ['name' => 'Zara Oversized Graphic Tee', 'brand' => 'zara', 'price' => 39, 'desc' => 'Relaxed fit cotton tee with a soft garment wash.'],
            ['name' => 'H&M Relaxed Fit Chinos', 'brand' => 'hm', 'price' => 49, 'desc' => 'Stretch-cotton chinos with roomy fit and tonal detailing.'],
            ['name' => 'H&M Premium Cotton Oxford Shirt', 'brand' => 'hm', 'price' => 39, 'desc' => 'Button-down collar oxford in long-staple cotton.'],
            ['name' => 'Nike Dri-FIT Training Tee', 'brand' => 'nike', 'price' => 35, 'desc' => 'Sweat-wicking fabric with a relaxed fit for everyday training.'],
            ['name' => 'Nike Club Fleece Hoodie', 'brand' => 'nike', 'price' => 75, 'desc' => 'Brushed-back fleece with kangaroo pocket and ribbed hems.'],
            ['name' => 'Adidas Essentials 3-Stripes Sweatshirt', 'brand' => 'adidas', 'price' => 55, 'desc' => 'Cotton-blend crew with the signature 3-Stripes down the sleeves.'],
            ['name' => 'Tommy Hilfiger Classic Polo Shirt', 'brand' => 'tommy-hilfiger', 'price' => 89, 'discount' => 20, 'desc' => 'Two-button polo with embroidered flag detail in stretch pique.'],
            ['name' => 'Tommy Hilfiger Oxford Shirt', 'brand' => 'tommy-hilfiger', 'price' => 99, 'desc' => 'Classic-fit long-sleeve oxford in crisp cotton with button collar.'],
            ['name' => 'Zara Wool Trench Coat', 'brand' => 'zara', 'price' => 189, 'desc' => 'Double-breasted trench with belt and storm flap in wool blend.'],
        ],
        'womens-clothing' => [
            ['name' => 'Zara Floral Midi Dress', 'brand' => 'zara', 'price' => 79, 'desc' => 'Flowing chiffon midi with all-over floral print and tie waist.'],
            ['name' => 'Zara Pleated Midi Skirt', 'brand' => 'zara', 'price' => 59, 'desc' => 'Fluid pleats that catch the light and move with you.'],
            ['name' => 'H&M Ribbed Knit Top', 'brand' => 'hm', 'price' => 29, 'desc' => 'Body-hugging ribbed knit with a scoop neck in eco-cotton.'],
            ['name' => 'H&M Wide-Leg Trousers', 'brand' => 'hm', 'price' => 54, 'desc' => 'High-waisted wide-leg trousers with pressing crease and drape.'],
            ['name' => 'Levi\'s High-Waisted Straight Jeans', 'brand' => 'levis', 'price' => 98, 'discount' => 15, 'desc' => 'Classic straight fit with a high rise in rigid denim.'],
            ['name' => 'Levi\'s Women\'s Bomber Jacket', 'brand' => 'levis', 'price' => 118, 'desc' => 'Satin bomber with ribbed trims and a subtle embroidered patch.'],
            ['name' => 'Nike One Leggings', 'brand' => 'nike', 'price' => 65, 'desc' => 'Buttery-infused, sweat-wicking leggings with hidden pockets.'],
            ['name' => 'Nike Sportswear Club Crew', 'brand' => 'nike', 'price' => 65, 'desc' => 'Oversized fleece crew with dropped shoulders and vintage look.'],
            ['name' => 'Zara Tailored Blazer', 'brand' => 'zara', 'price' => 159, 'desc' => 'Structured single-button blazer with peak lapels and back vent.'],
            ['name' => 'H&M Cashmere Sweater', 'brand' => 'hm', 'price' => 79, 'desc' => 'Soft pure-cashmere crewneck in a relaxed silhouette.'],
            ['name' => 'Lululemon Align High-Rise Leggings 25"', 'brand' => 'lululemon', 'price' => 98, 'desc' => 'Buttery Nulu fabric for yoga, lounge and everything in between.'],
            ['name' => 'Tommy Hilfiger Denim Button-Up', 'brand' => 'tommy-hilfiger', 'price' => 89, 'desc' => 'Long-sleeve denim shirt with branded shell buttons.'],
        ],
        'sneakers' => [
            ['name' => 'Nike Air Force 1 \'07', 'brand' => 'nike', 'price' => 115, 'discount' => 15, 'desc' => 'The all-time classic in crisp premium leather with Air cushioning.'],
            ['name' => 'Nike Air Max 270', 'brand' => 'nike', 'price' => 160, 'desc' => 'Tuned Air heel unit and seamless upper for all-day comfort.'],
            ['name' => 'Adidas Ultraboost 5', 'brand' => 'adidas', 'price' => 190, 'discount' => 20, 'desc' => 'BOOST midsole with a sculpted foot-hugging knit upper.'],
            ['name' => 'Adidas Samba OG', 'brand' => 'adidas', 'price' => 100, 'desc' => 'Heritage indoor silhouette with suede overlays and gum outsole.'],
            ['name' => 'Puma RS-X Reinvention', 'brand' => 'puma', 'price' => 120, 'desc' => 'Chunky retro-runner with bold colors and muted cushioning.'],
            ['name' => 'New Balance 550', 'brand' => 'new-balance', 'price' => 130, 'desc' => 'Court-inspired basketball trainer with premium leather panels.'],
            ['name' => 'New Balance Fresh Foam 1080 v13', 'brand' => 'new-balance', 'price' => 160, 'desc' => 'Plush Fresh Foam cushioning for long daily miles.'],
            ['name' => 'Skechers GOwalk Arch Fit', 'brand' => 'skechers', 'price' => 110, 'desc' => 'Podiatrist-certified arch support with a flexible mesh knit.'],
            ['name' => 'Nike Pegasus 41', 'brand' => 'nike', 'price' => 140, 'desc' => 'Responsive Air Zoom unit and ReactX foam for everyday running.'],
            ['name' => 'Adidas NMD_R1', 'brand' => 'adidas', 'price' => 130, 'desc' => 'Street-ready runner with an iconic plug midsole and knit upper.'],
        ],
        'smart-watches' => [
            ['name' => 'Seiko Prospex Diver 200M Automatic', 'brand' => 'seiko', 'price' => 650, 'desc' => 'Automatic ISO-certified diver with sapphire crystal and 200m WR.'],
            ['name' => 'Seiko 5 Sports Automatic', 'brand' => 'seiko', 'price' => 325, 'discount' => 15, 'desc' => 'Mod-style automatic with exhibition caseback and day-date window.'],
            ['name' => 'Citizen Eco-Drive Chandler', 'brand' => 'citizen', 'price' => 195, 'desc' => 'Light-powered forever watch with 44mm case and canvas strap.'],
            ['name' => 'Citizen Promaster Diver', 'brand' => 'citizen', 'price' => 425, 'desc' => 'Eco-Drive diver with ISO rating, luminous markers and 200m WR.'],
            ['name' => 'Casio G-Shock GA2100 Series', 'brand' => 'casio', 'price' => 99, 'discount' => 20, 'desc' => 'The octagonal \'CasiOak\' with carbon-core guard and 200m WR.'],
            ['name' => 'Casio Classic A158W Digital', 'brand' => 'casio', 'price' => 29, 'desc' => 'Retro chrome digital watch with backlight and stopwatch.'],
            ['name' => 'Fossil Chronograph Stainless Steel', 'brand' => 'fossil', 'price' => 189, 'discount' => 20, 'desc' => 'Three-eye chronograph on a polished stainless bracelet.'],
            ['name' => 'Fossil Grant Automatic', 'brand' => 'fossil', 'price' => 259, 'desc' => 'Self-winding automatic with skeleton dial and exhibition back.'],
            ['name' => 'Citizen Corso Chronograph', 'brand' => 'citizen', 'price' => 275, 'desc' => 'Eco-Drive chronograph with fine silver-tone details.'],
            ['name' => 'Seiko Presage Cocktail Time', 'brand' => 'seiko', 'price' => 750, 'desc' => 'Tokyo cocktail-inspired dial with automatic movement and box sapphire.'],
        ],
        'bags-travel' => [
            ['name' => 'Samsonite Omni PC 20" Carry-On Spinner', 'brand' => 'samsonite', 'price' => 159, 'discount' => 25, 'desc' => 'Micro-diamond texture resists scratches; light and spinner-maneuverable.'],
            ['name' => 'Samsonite Stackd Laptop Backpack 15.6"', 'brand' => 'samsonite', 'price' => 89, 'desc' => 'Padded 15.6" laptop compartment and a smart-sleeve in a everyday pack.'],
            ['name' => 'Coach Tabby Shoulder Bag 26', 'brand' => 'coach', 'price' => 450, 'desc' => 'Signature C Jacquard with smooth leather trim and a chain strap.'],
            ['name' => 'Coach Saddle Crossbody', 'brand' => 'coach', 'price' => 350, 'desc' => 'Compact saddle silhouette with turnlock closure and strap.'],
            ['name' => 'Coach Pillow Tabby 18', 'brand' => 'coach', 'price' => 495, 'desc' => 'Quilted pillow leather with brass hardware and convertible strap.'],
            ['name' => 'Samsonite CenterStage Laptop Backpack', 'brand' => 'samsonite', 'price' => 149, 'desc' => 'Commuter backpack with quick access and a trolley pass-through.'],
            ['name' => 'Samsonite Tote Aire 15.6"', 'brand' => 'samsonite', 'price' => 129, 'desc' => 'Durable nylon tote with padded laptop sleeve and zip entry.'],
            ['name' => 'Coach Zip Top Tote', 'brand' => 'coach', 'price' => 398, 'desc' => 'Polished pebble leather tote sized for work and weekends.'],
            ['name' => 'Samsonite Spark HS 28" Spinner', 'brand' => 'samsonite', 'price' => 249, 'discount' => 15, 'desc' => 'Extra-light HS polyester with an 8-wheel braking system.'],
            ['name' => 'Coach Belt Bag', 'brand' => 'coach', 'price' => 195, 'desc' => 'Hands-free leather belt bag with zip pocket and strap.'],
        ],
        'furniture' => [
            ['name' => 'IKEA KIVIK 3-Seat Sofa', 'brand' => 'ikea', 'price' => 599, 'desc' => 'Deep generous seating with loose, washable cushion covers.'],
            ['name' => 'IKEA BILLY Bookcase 80x202cm', 'brand' => 'ikea', 'price' => 79, 'desc' => 'The classic adjustable-shelf bookcase for any room.'],
            ['name' => 'IKEA POÄNG Armchair', 'brand' => 'ikea', 'price' => 129, 'desc' => 'Rocking comfort with laminated birch frame and cushion.'],
            ['name' => 'IKEA MALM 6-Drawer Dresser', 'brand' => 'ikea', 'price' => 249, 'desc' => 'Clean lines, deep drawers and a high-gloss lacquer finish.'],
            ['name' => 'IKEA HEMNES Nightstand', 'brand' => 'ikea', 'price' => 119, 'desc' => 'Solid pine nightstand with a soft-closing drawer and open shelf.'],
            ['name' => 'West Elm Mid-Century Desk', 'brand' => 'west-elm', 'price' => 549, 'discount' => 20, 'desc' => 'Solid acacia desk with tapered legs and dovetail drawer.'],
            ['name' => 'West Elm Platform Bed Frame', 'brand' => 'west-elm', 'price' => 999, 'desc' => 'Low-slung solid wood frame with an upholstered headboard option.'],
            ['name' => 'West Elm Boucle Armchair', 'brand' => 'west-elm', 'price' => 799, 'desc' => 'Cloud-like boucle fabric over a kiln-dried hardwood frame.'],
            ['name' => 'IKEA JÄTTEBO Chaise Lounge', 'brand' => 'ikea', 'price' => 649, 'desc' => 'Corner lounge with chaise for weekend-style relaxing.'],
            ['name' => 'West Elm Velvet Entry Bench', 'brand' => 'west-elm', 'price' => 399, 'desc' => 'Deep velvet cushion on a solid wood base with brass legs.'],
            ['name' => 'IKEA LACK Side Table', 'brand' => 'ikea', 'price' => 49, 'desc' => 'Lightweight side table in a glossy finish — stackable in pairs.'],
            ['name' => 'IKEA GLADOM Tray Table', 'brand' => 'ikea', 'price' => 15, 'desc' => 'Two-level tray table that doubles as laptop or snack stand.'],
        ],
        'kitchen-dining' => [
            ['name' => 'KitchenAid Artisan Stand Mixer 5Qt', 'brand' => 'kitchenaid', 'price' => 449, 'discount' => 20, 'desc' => 'Legendary tilt-head mixer with 10 speeds and 29 accessories available.'],
            ['name' => 'Ninja Foodi 6-in-1 Air Fryer 5.5Qt', 'brand' => 'ninja', 'price' => 179, 'discount' => 20, 'desc' => 'Crisp, roast, broil, bake, reheat and dehydrate without oil.'],
            ['name' => 'Ninja Blender Pro Auto-iQ 1000W', 'brand' => 'ninja', 'price' => 99, 'desc' => 'Pitcher and single-serve cups with one-touch programs.'],
            ['name' => 'Philips 2200 Series Espresso Machine', 'brand' => 'philips', 'price' => 279, 'desc' => 'One-touch cappuccino and latte with a LatteGo milk system.'],
            ['name' => 'Philips Electric Kettle 1.7L', 'brand' => 'philips', 'price' => 55, 'desc' => 'Rapid-boil 2400W kettle with a keep-warm option.'],
            ['name' => 'Tefal Ingenio 10-Pc Cookware Set', 'brand' => 'tefal', 'price' => 199, 'desc' => 'Detachable handles, stackable pans and an interchangeable lid.'],
            ['name' => 'Tefal Titanium Non-Stick Frying Pan 28cm', 'brand' => 'tefal', 'price' => 49, 'desc' => 'Titanium reinforced coating with Thermo-Signal for perfect heat.'],
            ['name' => 'OXO Good Grips Salad Spinner', 'brand' => 'oxo', 'price' => 24, 'desc' => 'One-handed pump action and a brake button for instant stop.'],
            ['name' => 'OXO Slim POP Container Set', 'brand' => 'oxo', 'price' => 35, 'desc' => 'Airtight square containers that stack neatly in any cabinet.'],
            ['name' => 'KitchenAid 4-Slice Toaster', 'brand' => 'kitchenaid', 'price' => 99, 'desc' => 'Dual independent controls with extra-wide self-centering slots.'],
            ['name' => 'Ninja Specialty Coffee Maker', 'brand' => 'ninja', 'price' => 149, 'desc' => 'Craft hot and iced coffee, from classic to cold brew to cappuccino.'],
            ['name' => 'Philips Airfryer XXL 7.3L', 'brand' => 'philips', 'price' => 299, 'desc' => 'Rapid Air technology for up to 90% less fat without oil.'],
        ],
        'home-decor' => [
            ['name' => 'West Elm Ceramic Table Vase', 'brand' => 'west-elm', 'price' => 49, 'desc' => 'Hand-glazed stoneware vase in an organic sculptural silhouette.'],
            ['name' => 'West Elm Woven Jute Rug 5x8', 'brand' => 'west-elm', 'price' => 199, 'discount' => 20, 'desc' => 'Hand-woven natural jute with a soft cotton border.'],
            ['name' => 'West Elm Abstract Gallery Print', 'brand' => 'west-elm', 'price' => 99, 'desc' => 'Framed art print on cotton paper with a matte finish.'],
            ['name' => 'West Elm Boucle Throw Pillow', 'brand' => 'west-elm', 'price' => 39, 'desc' => 'Cozy boucle texture with an easy feather-down insert.'],
            ['name' => 'IKEA RIBBA Picture Frame Set 13x18cm', 'brand' => 'ikea', 'price' => 15, 'desc' => 'Classic white frames with glass and sturdy back panel.'],
            ['name' => 'IKEA PUDDA Velvet Cushion 50x50cm', 'brand' => 'ikea', 'price' => 19, 'desc' => 'Soft velvet cushion in a rich saturated shade.'],
            ['name' => 'IKEA SMAKSPRIDD Throw Blanket', 'brand' => 'ikea', 'price' => 29, 'desc' => 'Warm recycled-polyester throw for sofas and beds.'],
            ['name' => 'West Elm Brass Table Planter', 'brand' => 'west-elm', 'price' => 45, 'desc' => 'Antique-brass planter that works indoors and out.'],
            ['name' => 'IKEA SANELA Blackout Curtains', 'brand' => 'ikea', 'price' => 35, 'desc' => 'Thermal blackout curtains in a woven dark grey.'],
            ['name' => 'West Elm Hand-Knotted Wool Rug 6x9', 'brand' => 'west-elm', 'price' => 349, 'desc' => 'Hand-knotted wool with a timeless antique-inspired pattern.'],
        ],
        'beauty-skincare' => [
            ['name' => 'L\'Oréal Paris Revitalift Filler Serum', 'brand' => 'loreal-paris', 'price' => 34, 'desc' => 'Hyaluronic acid + vitamin C to visibly plump fine lines.'],
            ['name' => 'L\'Oréal Paris Voluminous Lash Paradise Mascara', 'brand' => 'loreal-paris', 'price' => 12, 'desc' => 'Intense black, feathery volume and an hourglass brush.'],
            ['name' => 'Neutrogena Hydro Boost Water Gel 50ml', 'brand' => 'neutrogena', 'price' => 27, 'discount' => 20, 'desc' => 'Squares of hyaluronic acid lock in continuous moisture.'],
            ['name' => 'Neutrogena Ultra Sheer Dry-Touch SPF50', 'brand' => 'neutrogena', 'price' => 14, 'desc' => 'Lightweight, fast-absorbing sunscreen with broad-spectrum SPF50.'],
            ['name' => 'Olay Regenerist Micro-Sculpting Cream', 'brand' => 'olay', 'price' => 26, 'desc' => 'Amino-peptide complex hydrates and firms for 24 hours.'],
            ['name' => 'Estée Lauder Advanced Night Repair Serum 50ml', 'brand' => 'estee-lauder', 'price' => 158, 'desc' => 'The iconic overnight repair serum—world\'s #1 counter serum.'],
            ['name' => 'Estée Lauder Double Wear Foundation', 'brand' => 'estee-lauder', 'price' => 76, 'desc' => '24-hour wear, full coverage with a natural matte finish.'],
            ['name' => 'L\'Oréal Paris Elseve Dream Lengths Shampoo', 'brand' => 'loreal-paris', 'price' => 9, 'desc' => 'Keratin-infused care for visibly longer, stronger hair.'],
            ['name' => 'Neutrogena Hand Cream Norwegian Formula', 'brand' => 'neutrogena', 'price' => 8, 'desc' => 'Ultra-concentrated glycerin relief for very dry hands.'],
            ['name' => 'Olay Complete Daily Moisturizer SPF30', 'brand' => 'olay', 'price' => 22, 'desc' => 'Daily facial lotion with vitamins and broad-spectrum SPF30.'],
            ['name' => 'L\'Oréal Paris Elvive Total Repair 5 Balm', 'brand' => 'loreal-paris', 'price' => 8, 'desc' => 'Ceri-amide + protein for visibly repaired damaged ends.'],
            ['name' => 'Neutrogena Oil-Free Eye Makeup Remover', 'brand' => 'neutrogena', 'price' => 10, 'desc' => 'Dual-phase remover that lifts waterproof makeup gently.'],
        ],
        'fitness-equipment' => [
            ['name' => 'Bowflex SelectTech 552 Adjustable Dumbbells', 'brand' => 'bowflex', 'price' => 399, 'discount' => 15, 'desc' => 'Replaces 15 sets of weights (5-52.5lb) with one dial.'],
            ['name' => 'Bowflex 3.1 Adjustable Bench', 'brand' => 'bowflex', 'price' => 229, 'desc' => 'Six positions from flat to incline with an easy slide adjust.'],
            ['name' => 'NordicTrack Commercial 1750 Treadmill', 'brand' => 'nordictrack', 'price' => 1299, 'discount' => 25, 'desc' => '-3% to 12% incline, -3% decline and 22" HD touchscreen.'],
            ['name' => 'NordicTrack Commercial Studio Cycle', 'brand' => 'nordictrack', 'price' => 599, 'desc' => 'Heavy steel frame, 32 levels of digital resistance and 24" screen.'],
            ['name' => 'TRX HOME2 Suspension Trainer', 'brand' => 'trx', 'price' => 249, 'desc' => 'World-class bodyweight training straps with free workout app.'],
            ['name' => 'Under Armour Undeniable 5.0 Gym Bag', 'brand' => 'under-armour', 'price' => 55, 'desc' => 'Water-resistant base, ventilated pocket and wet-dry interior.'],
            ['name' => 'Under Armour Training Fitness Mat', 'brand' => 'under-armour', 'price' => 45, 'desc' => 'Extra-thick 7mm cushioned surface with slip-proof grip.'],
            ['name' => 'Reebok Cast Iron Kettlebell 12kg', 'brand' => 'reebok', 'price' => 59, 'desc' => 'One-piece cast iron with wide flat base for swings and presses.'],
            ['name' => 'Reebok Resistance Bands Set', 'brand' => 'reebok', 'price' => 19, 'desc' => 'Five color-coded bands from light to heavy with carry bag.'],
            ['name' => 'Bowflex Kettlebell 8kg', 'brand' => 'bowflex', 'price' => 89, 'desc' => 'Compact kettlebell with comfortable wide handle.'],
            ['name' => 'NordicTrack RW900 Rower', 'brand' => 'nordictrack', 'price' => 799, 'desc' => '22" rotating touchscreen, 26 digital resistance levels.'],
            ['name' => 'Under Armour High-Density Foam Roller', 'brand' => 'under-armour', 'price' => 25, 'desc' => 'Heavy-duty EVA foam for deep-tissue recovery.'],
        ],
        'running-outdoor' => [
            ['name' => 'Garmin Forerunner 265', 'brand' => 'garmin', 'price' => 449, 'discount' => 10, 'desc' => 'AMOLED display, daily suggested workouts and 15-day battery.'],
            ['name' => 'Garmin Forerunner 55 GPS Watch', 'brand' => 'garmin', 'price' => 199, 'desc' => 'Lightweight GPS runner with race predictors and training status.'],
            ['name' => 'ASICS Gel-Kayano 31 Running Shoes', 'brand' => 'asics', 'price' => 165, 'desc' => 'Stable, cushioned daily trainer with FF BLAST PLUS foam.'],
            ['name' => 'ASICS GLIDERIDE Max 2', 'brand' => 'asics', 'price' => 160, 'desc' => 'GuideSole geometry for efficient, rolling transitions.'],
            ['name' => 'Garmin HRM-Pro Plus Heart Rate Strap', 'brand' => 'garmin', 'price' => 129, 'desc' => 'Chest strap with running dynamics and on-wrist broadcast.'],
            ['name' => 'Under Armour HOVR Sonic 6', 'brand' => 'under-armour', 'price' => 130, 'desc' => 'Soft UA HOVR cushioning with a flexible mesh upper.'],
            ['name' => 'Garmin Instinct 2 Solar', 'brand' => 'garmin', 'price' => 399, 'desc' => 'Rugged GPS with unlimited solar battery in trail mode.'],
            ['name' => 'ASICS Gel-Cumulus 26', 'brand' => 'asics', 'price' => 140, 'desc' => 'Versatile everyday road shoe with plush FF BLAST cushioning.'],
            ['name' => 'Under Armour Pro Shirt Long Sleeve', 'brand' => 'under-armour', 'price' => 45, 'desc' => 'Compression-layer warmth that wicks sweat fast.'],
            ['name' => 'Garmin Edge 540 Cycling Computer', 'brand' => 'garmin', 'price' => 349, 'desc' => 'Solar-charged bike computer with ClimbPro and planning tools.'],
        ],
        'gaming-consoles' => [
            ['name' => 'Sony PlayStation 5 Slim (Disc)', 'brand' => 'sony', 'price' => 499, 'desc' => 'Ultra-fast SSD, ray tracing and a 1TB drive in a slimmer chassis.'],
            ['name' => 'Sony DualSense Wireless Controller', 'brand' => 'sony', 'price' => 74, 'desc' => 'Haptic feedback and adaptive triggers for immersive play.'],
            ['name' => 'Microsoft Xbox Series X 1TB', 'brand' => 'microsoft', 'price' => 499, 'desc' => 'The most powerful Xbox ever, with 120FPS-capable 4K gaming.'],
            ['name' => 'Microsoft Xbox Wireless Controller (Carbon)', 'brand' => 'microsoft', 'price' => 59, 'desc' => 'Refined ergonomics, textured grip and wireless pairing.'],
            ['name' => 'Nintendo Switch OLED Model', 'brand' => 'nintendo', 'price' => 349, 'discount' => 10, 'desc' => 'Vivid 7" OLED screen with a wide adjustable stand.'],
            ['name' => 'Nintendo Switch Pro Controller', 'brand' => 'nintendo', 'price' => 69, 'desc' => 'HD rumble, motion controls and 40-hour battery life.'],
            ['name' => 'The Legend of Zelda: Tears of the Kingdom', 'brand' => 'nintendo', 'price' => 69, 'desc' => 'Epic open-world adventure across the skies of Hyrule.'],
            ['name' => 'God of War Ragnarök', 'brand' => 'sony', 'price' => 69, 'desc' => 'Kratos and Atreus embark on a sweeping Norse odyssey.'],
            ['name' => 'Razer Kraken V3 X Wired Headset', 'brand' => 'razer', 'price' => 49, 'desc' => '7.1 surround sound with a lightweight, cooling-gel headband.'],
            ['name' => 'Logitech G502 HERO Gaming Mouse', 'brand' => 'logitech', 'price' => 79, 'discount' => 30, 'desc' => '25K HERO sensor, adjustable weight and 11 programmable buttons.'],
        ],
        'headphones-speakers' => [
            ['name' => 'Sony WH-1000XM5 Over-Ear Headphones', 'brand' => 'sony', 'price' => 399, 'discount' => 15, 'desc' => 'Industry-leading noise cancelling with 30-hour battery.'],
            ['name' => 'Sony WF-1000XM5 True Wireless Earbuds', 'brand' => 'sony', 'price' => 299, 'desc' => 'Premium noise cancelling with a new dynamic driver.'],
            ['name' => 'Bose QuietComfort Ultra Headphones', 'brand' => 'bose', 'price' => 429, 'discount' => 10, 'desc' => 'Immersive audio with world-class noise cancelling.'],
            ['name' => 'Bose SoundLink Flex Bluetooth Speaker', 'brand' => 'bose', 'price' => 149, 'desc' => 'Portable, waterproof speaker with PositionIQ tuning.'],
            ['name' => 'JBL Flip 6', 'brand' => 'jbl', 'price' => 129, 'desc' => 'Big, bold sound and 12 hours of play in an IP67 body.'],
            ['name' => 'JBL Tune 770NC ANC Headphones', 'brand' => 'jbl', 'price' => 99, 'desc' => 'Adaptive noise cancelling with JBL Pure Bass sound.'],
            ['name' => 'Beats Studio Pro', 'brand' => 'beats', 'price' => 349, 'desc' => 'Personalized spatial audio with up to 40-hour battery.'],
            ['name' => 'Beats Fit Pro Earbuds', 'brand' => 'beats', 'price' => 199, 'desc' => 'Secure-fit ear tips with active noise cancelling.'],
            ['name' => 'Anker Soundcore Space One', 'brand' => 'anker', 'price' => 99, 'desc' => '40-hour ANC headphones with adjustable comfort levels.'],
            ['name' => 'Sony HT-A3000 Dolby Atmos Soundbar', 'brand' => 'sony', 'price' => 599, 'desc' => '3.1-channel virtual Dolby Atmos with wireless sub ready.'],
        ],
        'toys-games' => [
            ['name' => 'LEGO Star Wars Millennium Falcon', 'brand' => 'lego', 'price' => 169, 'desc' => 'Build the fastest hunk of junk in the galaxy, 1,351 pieces.'],
            ['name' => 'LEGO Icons Botanical Wildflower Bouquet', 'brand' => 'lego', 'price' => 59, 'desc' => 'A blooming forever-bouquet in 16 lifelike flower species.'],
            ['name' => 'LEGO Technic Porsche 911 GT3 RS', 'brand' => 'lego', 'price' => 169, 'desc' => 'Working gearbox, suspension and a detailed flat-six engine.'],
            ['name' => 'Hasbro Monopoly Classic Game', 'brand' => 'hasbro', 'price' => 24, 'desc' => 'Buy, trade and bankrupt your friends in the family classic.'],
            ['name' => 'Hasbro Clue Classic Mystery', 'brand' => 'hasbro', 'price' => 19, 'desc' => 'Solve who, where and with what in the whodunit favorite.'],
            ['name' => 'Hasbro Play-Doh Modeling Compound 36 Pack', 'brand' => 'hasbro', 'price' => 14, 'desc' => 'A rainbow of classic non-toxic modeling compound.'],
            ['name' => 'Mattel Hot Wheels 20-Car Pack', 'brand' => 'mattel', 'price' => 24, 'desc' => 'Twenty die-cast cars across your favorite themes.'],
            ['name' => 'Mattel Barbie Dreamhouse', 'brand' => 'mattel', 'price' => 189, 'desc' => 'Three floors, a pool and a working elevator of imagination.'],
            ['name' => 'Mattel UNO Classic Card Game', 'brand' => 'mattel', 'price' => 9, 'desc' => 'Match colors and numbers, and flip the table with action cards.'],
            ['name' => 'LEGO Icons Titanic', 'brand' => 'lego', 'price' => 679, 'desc' => 'A stunning 9,090-piece tribute to the legendary ocean liner.'],
        ],
        'pet-essentials' => [
            ['name' => 'Pedigree Adult Complete Nutrition 15lb', 'brand' => 'pedigree', 'price' => 34, 'desc' => 'Wholesome grains, protein and omega-6 for adult dogs.'],
            ['name' => 'Pedigree Dentastix Large Dog Treats', 'brand' => 'pedigree', 'price' => 15, 'desc' => 'Twice-daily chews that reduce tartar by up to 80%.'],
            ['name' => 'Whiskas Dry Cat Food Kitten 4lb', 'brand' => 'whiskas', 'price' => 19, 'desc' => 'Complete nutrition for kittens with essential DHA.'],
            ['name' => 'Whiskas Temptations Tasty Chicken Cat Treats', 'brand' => 'whiskas', 'price' => 6, 'desc' => 'Irresistibly crunchy outside, soft inside. 100% complete.'],
            ['name' => 'Pedigree High Protein Beef 24lb', 'brand' => 'pedigree', 'price' => 49, 'desc' => 'High-protein kibble with real beef for active adults.'],
            ['name' => 'Whiskas Seafood Medley Adult Cat Food 6lb', 'brand' => 'whiskas', 'price' => 24, 'desc' => 'A sea of flavors in every bowl of complete food.'],
            ['name' => 'Pedigree Puppy Complete Nutrition 16lb', 'brand' => 'pedigree', 'price' => 39, 'desc' => 'Supports healthy growth with DHA and antioxidants.'],
            ['name' => 'Whiskas Indoor Cat Food 6lb', 'brand' => 'whiskas', 'price' => 26, 'desc' => 'Lower-calorie recipe for indoor cats with weight management.'],
            ['name' => 'Pedigree Small Breed Bites 12lb', 'brand' => 'pedigree', 'price' => 29, 'desc' => 'Small kibble tailored to small breeds\' energy needs.'],
            ['name' => 'Whiskas Tasty Rewards Chicken Cat Treats', 'brand' => 'whiskas', 'price' => 8, 'desc' => 'Oven-baked treats that make every moment a reward.'],
        ],
        'baby-essentials' => [
            ['name' => 'Pampers Swaddlers Diapers Size 1 (108ct)', 'brand' => 'pampers', 'price' => 45, 'discount' => 10, 'desc' => 'Softest-ever baby care with up to 12 hours of protection.'],
            ['name' => 'Pampers Baby Wipes (9 Packs)', 'brand' => 'pampers', 'price' => 19, 'desc' => 'Plant-based, 99% water wipes with a hypoallergenic formula.'],
            ['name' => 'Huggies Little Snugglers Diapers Size 1', 'brand' => 'huggies', 'price' => 42, 'desc' => 'Extra-soft baby diapers with a wetness indicator.'],
            ['name' => 'Graco Modes 3-in-1 Stroller', 'brand' => 'graco', 'price' => 289, 'discount' => 15, 'desc' => 'Travel system with infant car seat, bassinet and toddler seat.'],
            ['name' => 'Graco Pack \'n Play Playard', 'brand' => 'graco', 'price' => 169, 'desc' => 'Baby playard with changing station, bassinet and napper.'],
            ['name' => 'Graco SlimFold High Chair', 'brand' => 'graco', 'price' => 129, 'desc' => 'One-hand fold, dishwasher-safe tray and 3 reclining positions.'],
            ['name' => 'Huggies Natural Care Unscented Wipes', 'brand' => 'huggies', 'price' => 16, 'desc' => '99% water, hypoallergenic wipes for sensitive skin.'],
            ['name' => 'Graco EveryWay 4-in-1 Car Seat', 'brand' => 'graco', 'price' => 249, 'desc' => 'Rear-facing to booster with an adjustable harness.'],
            ['name' => 'Pampers Sensitive Wipes Refill (480ct)', 'brand' => 'pampers', 'price' => 22, 'desc' => 'Hypoallergenic wipes with 0% alcohol for delicate skin.'],
            ['name' => 'Huggies Little Movers Diapers Size 3', 'brand' => 'huggies', 'price' => 45, 'desc' => 'Movable waistband that fits a wiggly, growing toddler.'],
        ],
        'garden-patio' => [
            ['name' => 'Bosch EasyGrassCut Cordless Grass Trimmer', 'brand' => 'bosch', 'price' => 129, 'desc' => 'Lightweight 18V trimmer with spool-free bump feed.'],
            ['name' => 'Bosch EasyHedgeCut Hedge Trimmer', 'brand' => 'bosch', 'price' => 89, 'desc' => '20cm laser-cut blades for fast, clean cuts in tight spots.'],
            ['name' => 'Black+Decker 20V Drill/Driver Kit', 'brand' => 'blackdecker', 'price' => 149, 'desc' => 'Cordless drill with 11-position clutch and battery plus charger.'],
            ['name' => 'Black+Decker String Trimmer 13"', 'brand' => 'blackdecker', 'price' => 99, 'desc' => 'Corded 6.5A trimmer with tiltable cutting head.'],
            ['name' => 'Black+Decker Hedge Trimmer 22"', 'brand' => 'blackdecker', 'price' => 69, 'desc' => 'Dual-action 22-inch blades with a full-wrap front handle.'],
            ['name' => 'Bosch Compact Garden Hose Reel', 'brand' => 'bosch', 'price' => 49, 'desc' => 'Wall-mount reel with 20m kink-resistant garden hose.'],
            ['name' => 'Bosch Professional Pruning Shears', 'brand' => 'bosch', 'price' => 39, 'desc' => 'Ergonomic bypass pruners rated for branches up to 24mm.'],
            ['name' => 'Bosch Cordless Leaf Blower', 'brand' => 'bosch', 'price' => 119, 'desc' => '18V blower with a turbo switch for stubborn leaves.'],
            ['name' => 'Black+Decker Precision Screwdriver Set', 'brand' => 'blackdecker', 'price' => 29, 'desc' => '46-piece home toolset with a durable storage case.'],
            ['name' => 'Black+Decker Lawn Shears', 'brand' => 'blackdecker', 'price' => 45, 'desc' => 'Manual grass shears with a rotating cushioned handle.'],
        ],
    ];

    public function run(): void
    {
        ProductImage::query()->delete();
        Product::query()->delete();

        $brands = Brand::query()->pluck('id', 'slug');
        if ($brands->isEmpty()) {
            $this->command->warn('No brands found — run BrandSeeder first.');

            return;
        }

        $subcategories = Category::whereNotNull('parent_id')->orderBy('id')->get()->keyBy('slug');
        if ($subcategories->isEmpty()) {
            $subcategories = Category::whereNull('parent_id')->orderBy('id')->get()->keyBy('slug');
        }

        $counter = 0;
        $usedSkus = [];

        foreach ($subcategories as $slug => $category) {
            $templates = $this->products[$slug] ?? null;

            if (! $templates) {
                $this->command->warn("No product templates found for subcategory '{$slug}'. Skipping.");

                continue;
            }

            $images = $this->imagePool[$slug] ?? $this->fallbackImages;

            foreach ($templates as $i => $template) {
                $counter++;

                $brandId = $brands[$template['brand']] ?? null;
                if (! $brandId) {
                    $this->command->warn("Brand '{$template['brand']}' not found for {$template['name']}. Skipping.");

                    continue;
                }

                $price = $template['price'];
                $discountPercent = $template['discount'] ?? null;
                $discountPrice = $discountPercent
                    ? round($price * (1 - $discountPercent / 100), 2)
                    : null;

                $slugBase = Str::slug($template['name']);
                $productSlug = $slugBase;
                $suffix = 2;
                while (Product::where('slug', $productSlug)->exists()) {
                    $productSlug = $slugBase.'-'.$suffix++;
                }

                $sku = strtoupper(Str::substr($template['brand'], 0, 4).'-'.str_pad((string) $counter, 4, '0', STR_PAD_LEFT));
                while (isset($usedSkus[$sku]) || Product::where('sku', $sku)->exists()) {
                    $sku = strtoupper(Str::substr($template['brand'], 0, 4)).'-'.str_pad((string) ++$counter, 4, '0', STR_PAD_LEFT);
                }
                $usedSkus[$sku] = true;

                $isFlashSale = $discountPrice !== null && $i % 4 === 1;

                $product = Product::create([
                    'name' => $template['name'],
                    'slug' => $productSlug,
                    'description' => $template['desc'],
                    'price' => $price,
                    'discount_price' => $discountPrice,
                    'stock' => $counter % 31 === 0 ? 0 : rand(10, 120),
                    'sku' => $sku,
                    'average_rating' => round(mt_rand(350, 500) / 100, 2),
                    'reviews_count' => rand(5, 200),
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'status' => 'active',
                    'is_featured' => $i === 0 || $counter % 7 === 0,
                    'is_new_arrival' => $i < 2 || $counter % 4 === 0,
                    'is_trending' => $counter % 5 === 3,
                    'is_best_seller' => $counter % 5 === 0,
                    'is_flash_sale' => $isFlashSale,
                    'is_recommended' => $counter % 6 === 3,
                    'is_popular' => $counter % 4 === 2,
                    'is_limited_edition' => $counter % 11 === 0,
                    'sale_ends_at' => $isFlashSale ? now()->addDays(rand(1, 5))->endOfDay() : null,
                ]);

                $primary = $images[$i % count($images)];
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $primary,
                    'is_primary' => true,
                ]);

                if ($counter % 3 === 0 && count($images) > 1) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $images[($i + 1) % count($images)],
                        'is_primary' => false,
                    ]);
                }
            }
        }

        $categoriesCount = Category::whereNotNull('parent_id')->count();
        $this->command->info(sprintf(
            'Seeded %d products across %d subcategories (avg %d per subcategory).',
            Product::count(),
            $categoriesCount,
            $categoriesCount > 0 ? intdiv(Product::count(), $categoriesCount) : 0
        ));
    }
}