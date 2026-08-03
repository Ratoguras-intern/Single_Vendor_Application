<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    private array $brands = [
        'AirFlex',
        'UrbanX',
        'VoltEdge',
        'NovaCore',
        'PulseLine',
    ];

    public function run(): void
    {
        foreach ($this->brands as $name) {
            Brand::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
                'status' => 'active',
            ]);
        }
    }
}
