<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_homepage_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->string('display_style')->default('grid');
            $table->timestamps();

            $table->unique('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_homepage_categories');
    }
};
