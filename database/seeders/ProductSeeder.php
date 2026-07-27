<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(resource_path('data/products.json'));
        $products = json_decode($json, true) ?? [];

        $shoes = Category::where('slug', 'shoes')->first();
        $laptops = Category::where('slug', 'laptops')->first();
        $categoryId = $shoes?->id ?? 1;

        $slugs = ['shoes', 'shoes', 'shoes', 'shoes', 'shoes', 'laptops', 'laptops', 'laptops', 'laptops', 'laptops'];

        foreach ($products as $index => $item) {
            $slug = $slugs[$index] ?? 'shoes';
            $category = Category::where('slug', $slug)->first();
            if (!$category) {
                $category = $shoes ?? Category::first();
            }

            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'] ?? null,
                'price' => $item['price'],
                'stock' => 50,
                'sku' => strtoupper(Str::slug($item['name'], '-')).'-'.$item['id'],
                'category_id' => $category->id,
                'brand_id' => 1,
                'status' => true,
            ]);
        }
    }
}
