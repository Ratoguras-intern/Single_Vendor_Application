<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            BannerSeeder::class,
            SaleBannerSeeder::class,
            HomepageSeeder::class,
            FeaturedHomepageCategorySeeder::class,
            PageSeeder::class,
            NavigationSeeder::class,
        ]);
    }
}
