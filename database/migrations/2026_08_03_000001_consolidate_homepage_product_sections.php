<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('homepage_sections')
            ->whereIn('slug', [
                'featured-products',
                'trending-products',
                'recommended-products',
                'popular-products',
            ])
            ->update(['is_enabled' => false]);
    }

    public function down(): void
    {
        DB::table('homepage_sections')
            ->whereIn('slug', [
                'featured-products',
                'trending-products',
                'recommended-products',
                'popular-products',
            ])
            ->update(['is_enabled' => true]);
    }
};
