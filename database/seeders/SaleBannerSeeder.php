<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SaleBanner;
use Illuminate\Database\Seeder;

class SaleBannerSeeder extends Seeder
{
    public function run(): void
    {
        $flashSaleProduct = Product::where('is_flash_sale', true)->first();
        $flashSaleProductTwo = Product::where('is_flash_sale', true)->skip(1)->first();

        $sales = [
            [
                'title' => 'Flash Sale: Up to 40% Off',
                'subtitle' => 'Limited time only — grab the deal before it disappears!',
                'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1600&q=80',
                'featured_product_id' => $flashSaleProduct?->id,
                'enable_prices' => true,
                'enable_product_image' => true,
                'enable_buttons' => true,
                'enable_badge' => true,
                'badge' => 'FLASH SALE',
                'badge_color' => 'bg-red-500',
                'button_text' => 'Shop the Sale',
                'link' => '/shop',
                'show_countdown' => true,
                'countdown_end_date' => now()->addDays(3)->toDateString(),
                'countdown_end_time' => '23:59',
                'countdown_timezone' => config('app.timezone', 'UTC'),
                'text_alignment' => 'left',
                'text_color' => 'text-white',
                'overlay_opacity' => 40,
                'style_settings' => ['overlay_color' => '#000000', 'overlay_enabled' => true],
                'enable_overlay' => true,
            ],
            [
                'title' => 'Deal of the Day',
                'subtitle' => 'Handpicked favorites at unbeatable prices. Today only.',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600&q=80',
                'featured_product_id' => $flashSaleProductTwo?->id,
                'enable_prices' => true,
                'enable_product_image' => true,
                'enable_buttons' => true,
                'enable_badge' => false,
                'button_text' => 'Grab It Now',
                'link' => '/shop',
                'show_countdown' => false,
                'text_alignment' => 'left',
                'text_color' => 'text-white',
                'overlay_opacity' => 40,
                'style_settings' => ['overlay_color' => '#000000', 'overlay_enabled' => true],
                'enable_overlay' => true,
            ],
        ];

        foreach ($sales as $index => $data) {
            SaleBanner::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'is_enabled' => true,
                    'sort_order' => $index,
                ])
            );
        }
    }
}
