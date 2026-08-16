<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('product_image')->nullable();
            $table->string('link')->nullable();
            $table->string('button_text')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            $table->foreignId('featured_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('background_color')->nullable();
            $table->string('gradient_from')->nullable();
            $table->string('gradient_to')->nullable();
            $table->string('text_alignment', 20)->default('left');
            $table->string('image_position', 30)->default('center');
            $table->integer('overlay_opacity')->nullable();
            $table->string('text_color', 50)->nullable();
            $table->boolean('show_countdown')->default(false);
            $table->date('countdown_end_date')->nullable();
            $table->time('countdown_end_time')->nullable();
            $table->string('countdown_timezone', 64)->nullable();
            $table->boolean('enable_badge')->default(true);
            $table->boolean('enable_product_image')->default(true);
            $table->boolean('enable_prices')->default(true);
            $table->boolean('enable_buttons')->default(true);
            $table->boolean('enable_overlay')->default(true);
            $table->json('style_settings')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_banners');
    }
};
