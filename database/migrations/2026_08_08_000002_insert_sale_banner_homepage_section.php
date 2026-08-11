<?php

use App\Models\HomepageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $hero = HomepageSection::where('slug', 'hero-carousel')->first();

        HomepageSection::updateOrCreate(
            ['slug' => 'sale-banner'],
            [
                'title' => 'Sale Banner',
                'subtitle' => 'Featured sale products with live countdown.',
                'is_enabled' => true,
                'sort_order' => ($hero?->sort_order ?? 0) + 1,
                'max_products' => 0,
                'layout' => 'carousel',
                'config' => [
                    'autoplay' => true,
                    'transition_speed' => 5000,
                    'pause_on_hover' => true,
                ],
            ]
        );

        $sale = HomepageSection::where('slug', 'sale-banner')->first();
        $afterFlash = HomepageSection::where('slug', 'flash-sale')->first();

        if ($sale && $afterFlash) {
            HomepageSection::where('sort_order', '>', $afterFlash->sort_order)
                ->where('slug', '!=', 'sale-banner')
                ->increment('sort_order');
            $sale->update(['sort_order' => $afterFlash->sort_order + 1]);
        }
    }

    public function down(): void
    {
        HomepageSection::where('slug', 'sale-banner')->delete();
    }
};
