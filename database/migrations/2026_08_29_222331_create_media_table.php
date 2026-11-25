<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Duplicate of 2026_08_21_094446_create_media_table; kept as a guarded no-op
     * so existing databases that recorded it stay consistent.
     */
    public function up(): void
    {
        if (Schema::hasTable('media')) {
            return;
        }

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Intentionally empty: the media table is owned by the earlier migration.
     */
    public function down(): void
    {
        //
    }
};
