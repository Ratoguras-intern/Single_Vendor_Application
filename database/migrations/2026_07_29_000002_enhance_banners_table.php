<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('mobile_image')->nullable()->after('image');
            $table->text('description')->nullable()->after('subtitle');
            $table->string('secondary_button_text')->nullable()->after('button_text');
            $table->string('secondary_button_url')->nullable()->after('secondary_button_text');
            $table->string('text_alignment', 20)->default('left')->after('secondary_button_url');
            $table->integer('overlay_opacity')->nullable()->after('text_alignment');
            $table->string('text_color', 50)->nullable()->after('overlay_opacity');
            $table->boolean('show_countdown')->default(false)->after('text_color');
            $table->json('target_pages')->nullable()->after('show_countdown');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'mobile_image',
                'description',
                'secondary_button_text',
                'secondary_button_url',
                'text_alignment',
                'overlay_opacity',
                'text_color',
                'show_countdown',
                'target_pages',
            ]);
        });
    }
};
