<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('badge')->nullable()->after('button_text');
            $table->string('badge_color')->nullable()->after('badge');
        });

        DB::table('banners')->where('position', 'collection')->update(['position' => 'featured-section']);
    }

    public function down(): void
    {
        DB::table('banners')->where('position', 'featured-section')->update(['position' => 'collection']);

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['badge', 'badge_color']);
        });
    }
};
