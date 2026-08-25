<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\SaleBanner;
use Illuminate\Console\Command;

class SyncSaleEndsAt extends Command
{
    protected $signature = 'sale:sync';
    protected $description = 'Sync sale_ends_at on products from active sale banners';

    public function handle(): int
    {
        $banners = SaleBanner::where('is_enabled', true)
            ->whereNotNull('ends_at')
            ->where('ends_at', '>', now())
            ->whereNotNull('featured_product_id')
            ->get();

        $synced = 0;

        foreach ($banners as $b) {
            Product::where('id', $b->featured_product_id)->update(['sale_ends_at' => $b->ends_at]);
            $this->line("Product {$b->featured_product_id} synced to {$b->ends_at}");
            $synced++;
        }

        $this->info("Done. Synced {$synced} products.");
        return 0;
    }
}
