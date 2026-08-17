<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('navigation_menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->cascadeOnDelete();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('icon_key')->nullable();
            $table->string('target')->default('_self');
            $table->boolean('is_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('permission')->nullable();
            $table->string('badge')->nullable();
            $table->string('css_class')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
    }
};
