<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('source_hash', 32)->unique();
            $table->text('source_text');
            $table->string('source_locale', 5);
            $table->string('target_locale', 5);
            $table->text('translated_text')->nullable();
            $table->timestamps();

            $table->index(['source_locale', 'target_locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
