<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('banner_mobile_image')->nullable()->after('banner_image');
            $table->string('banner_image_fit')->default('cover')->after('banner_mobile_image');
            $table->string('banner_image_position')->default('center')->after('banner_image_fit');
        });

        // The `icon` column now stores a Lucide icon name (e.g. "Laptop").
        // Null out legacy image paths so the frontend renders names as SVG icons.
        DB::table('categories')
            ->where('icon', 'like', '%/%')
            ->orWhere('icon', 'like', '%.%')
            ->update(['icon' => null]);

        Cache::forget('frontend_categories');
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['banner_mobile_image', 'banner_image_fit', 'banner_image_position']);
        });
    }
};
