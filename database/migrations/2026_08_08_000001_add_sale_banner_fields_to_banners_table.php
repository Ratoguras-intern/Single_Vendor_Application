<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->foreignId('featured_product_id')
                ->nullable()
                ->after('badge_color')
                ->constrained('products')
                ->nullOnDelete();

            $table->string('product_image')->nullable()->after('featured_product_id');

            $table->string('background_color')->nullable()->after('product_image');
            $table->string('gradient_from')->nullable()->after('background_color');
            $table->string('gradient_to')->nullable()->after('gradient_from');

            $table->date('countdown_end_date')->nullable()->after('show_countdown');
            $table->time('countdown_end_time')->nullable()->after('countdown_end_date');
            $table->string('countdown_timezone', 64)->nullable()->after('countdown_end_time');

            $table->boolean('enable_badge')->default(true)->after('countdown_timezone');
            $table->boolean('enable_product_image')->default(true)->after('enable_badge');
            $table->boolean('enable_prices')->default(true)->after('enable_product_image');
            $table->boolean('enable_buttons')->default(true)->after('enable_prices');
            $table->boolean('enable_overlay')->default(true)->after('enable_buttons');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropConstrainedForeignId('featured_product_id');

            $table->dropColumn([
                'product_image',
                'background_color',
                'gradient_from',
                'gradient_to',
                'countdown_end_date',
                'countdown_end_time',
                'countdown_timezone',
                'enable_badge',
                'enable_product_image',
                'enable_prices',
                'enable_buttons',
                'enable_overlay',
            ]);
        });
    }
};
