<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FeaturedHomepageCategory;
use Illuminate\Database\Seeder;

class FeaturedHomepageCategorySeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            ['slug' => 'fashion', 'display_style' => 'full_width', 'sort_order' => 0],
            ['slug' => 'electronics', 'display_style' => 'half_width', 'sort_order' => 1],
            ['slug' => 'home-living', 'display_style' => 'third_width', 'sort_order' => 2],
            ['slug' => 'sports-fitness', 'display_style' => 'banner', 'sort_order' => 3],
        ];

        foreach ($collections as $data) {
            $category = Category::where('slug', $data['slug'])->first();

            if (! $category) {
                continue;
            }

            FeaturedHomepageCategory::updateOrCreate(
                ['category_id' => $category->id],
                [
                    'display_style' => $data['display_style'],
                    'sort_order' => $data['sort_order'],
                    'is_enabled' => true,
                ]
            );
        }

        FeaturedHomepageCategory::clearCache();
    }
}
