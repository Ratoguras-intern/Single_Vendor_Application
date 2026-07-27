<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->string('banner_image')->nullable()->after('image');
            $table->string('thumbnail_image')->nullable()->after('banner_image');
            $table->string('icon')->nullable()->after('thumbnail_image');
            $table->integer('sort_order')->default(0)->after('status');
            $table->boolean('featured')->default(false)->after('sort_order');
            $table->string('seo_title')->nullable()->after('featured');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->foreignId('created_by')->nullable()->after('seo_description')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'parent_id', 'banner_image', 'thumbnail_image', 'icon',
                'sort_order', 'featured', 'seo_title', 'seo_description',
                'created_by', 'updated_by', 'deleted_at',
            ]);
        });
    }
};
