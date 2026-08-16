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
    public function run(): void
    {
        $json = file_get_contents(resource_path('data/products.json'));
        $items = json_decode($json, true) ?? [];

        foreach ($items as $index => $item) {
            $category = Category::where('slug', $item['category'] ?? 'shoes')->first()
                ?? Category::first();
            $brand = Brand::where('slug', Str::slug($item['brand'] ?? 'AirFlex'))->first()
                ?? Brand::first();

            $slug = Str::slug($item['name']);
            $stock = $index === 9 ? 0 : (($index * 7) % 75 + 5);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'price' => $item['price'],
                    'discount_price' => $item['discount_price'] ?? null,
                    'stock' => $stock,
                    'sku' => strtoupper(Str::slug($item['name'], '-')).'-'.$item['id'],
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'status' => 'active',
                    'is_new_arrival' => $index % 3 === 0,
                    'is_flash_sale' => isset($item['discount_price']),
                    'is_best_seller' => $index % 5 === 0,
                    'is_featured' => $index % 5 === 1,
                    'is_trending' => $index % 5 === 2,
                    'is_recommended' => $index % 5 === 3,
                    'is_popular' => $index % 5 === 4,
                    'is_limited_edition' => $index % 10 === 7,
                ]
            );

            if (! empty($item['image'])) {
                $image = ProductImage::where('product_id', $product->id)
                    ->where('is_primary', true)
                    ->first();

                if ($image) {
                    $image->update(['image' => $item['image']]);
                } else {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $item['image'],
                        'is_primary' => true,
                    ]);
                }
            }
        }
    }
}
