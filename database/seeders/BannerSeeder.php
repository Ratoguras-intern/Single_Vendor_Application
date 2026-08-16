<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $heroes = [
            [
                'title' => 'Step Into Style',
                'subtitle' => 'Discover our latest collection of premium products — comfort, design, and quality in every piece.',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80',
                'badge' => 'NEW',
                'badge_color' => 'bg-primary-500',
                'button_text' => 'Shop Now',
                'secondary_button_text' => 'Learn More',
                'link' => '/shop',
                'secondary_button_url' => '/about',
                'enable_badge' => true,
                'enable_buttons' => true,
                'text_alignment' => 'left',
                'text_color' => 'text-white',
                'overlay_opacity' => 40,
                'style_settings' => ['overlay_color' => '#000000', 'overlay_enabled' => true],
                'enable_overlay' => true,
                'image_position' => 'center',
            ],
            [
                'title' => 'Tech That Moves You',
                'subtitle' => 'Laptops, phones and accessories engineered for people on the go.',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1920&q=80',
                'badge' => 'SALE',
                'badge_color' => 'bg-red-500',
                'button_text' => 'Shop Electronics',
                'secondary_button_text' => 'View Offers',
                'link' => '/shop',
                'secondary_button_url' => '/shop',
                'enable_badge' => true,
                'enable_buttons' => true,
                'text_alignment' => 'left',
                'text_color' => 'text-white',
                'overlay_opacity' => 45,
                'style_settings' => ['overlay_color' => '#000000', 'overlay_enabled' => true],
                'enable_overlay' => true,
                'image_position' => 'center',
            ],
            [
                'title' => 'Endless Athletic Energy',
                'subtitle' => 'Premium footwear and fitness gear built to outperform.',
                'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=1920&q=80',
                'badge' => 'TOP RATED',
                'badge_color' => 'bg-emerald-500',
                'button_text' => 'Shop Sports',
                'secondary_button_text' => null,
                'link' => '/shop',
                'secondary_button_url' => null,
                'enable_badge' => true,
                'enable_buttons' => true,
                'text_alignment' => 'left',
                'text_color' => 'text-white',
                'overlay_opacity' => 35,
                'style_settings' => ['overlay_color' => '#000000', 'overlay_enabled' => true],
                'enable_overlay' => true,
                'image_position' => 'center',
            ],
        ];

        foreach ($heroes as $index => $data) {
            Banner::updateOrCreate(
                ['position' => 'hero', 'title' => $data['title']],
                array_merge($data, [
                    'target_pages' => ['home'],
                    'is_enabled' => true,
                    'sort_order' => $index,
                ])
            );
        }

        Banner::clearCache();
    }
}
