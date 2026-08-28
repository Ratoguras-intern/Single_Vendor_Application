<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    private array $brands = [
        'Apple',
        'Samsung',
        'Google',
        'Xiaomi',
        'OnePlus',
        'Nothing',
        'Microsoft',
        'Dell',
        'HP',
        'Lenovo',
        'ASUS',
        'Acer',
        'LG',
        'Amazon',
        'Logitech',
        'Razer',
        'Corsair',
        'Anker',
        'Sony',
        'Bose',
        'JBL',
        'Beats',
        'Nintendo',
        'Nike',
        'Adidas',
        'Puma',
        'New Balance',
        'Skechers',
        'Levi\'s',
        'Zara',
        'H&M',
        'Tommy Hilfiger',
        'Lululemon',
        'Fossil',
        'Seiko',
        'Citizen',
        'Casio',
        'Samsonite',
        'Coach',
        'IKEA',
        'West Elm',
        'KitchenAid',
        'Ninja',
        'Philips',
        'Tefal',
        'OXO',
        'L\'Oréal Paris',
        'Neutrogena',
        'Olay',
        'Estée Lauder',
        'Bowflex',
        'NordicTrack',
        'Under Armour',
        'TRX',
        'Reebok',
        'Garmin',
        'ASICS',
        'LEGO',
        'Hasbro',
        'Mattel',
        'Pedigree',
        'Whiskas',
        'Pampers',
        'Huggies',
        'Graco',
        'Bosch',
        'Black+Decker',
    ];

    public function run(): void
    {
        $keptSlugs = [];

        foreach ($this->brands as $name) {
            $slug = Str::slug($name);
            $keptSlugs[] = $slug;

            Brand::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'status' => 'active',
                    // Logos are uploaded per-brand via the admin panel; keeping
                    // this null avoids broken /storage URLs on a fresh clone.
                    'logo' => null,
                ],
            );
        }

        // Remove legacy/demo brands that no longer belong to the catalog and
        // have no products attached (safe on fresh databases).
        Brand::whereNotIn('slug', $keptSlugs)
            ->whereDoesntHave('products')
            ->delete();

        $this->command->info('Seeded '.count($keptSlugs).' brands.');
    }
}