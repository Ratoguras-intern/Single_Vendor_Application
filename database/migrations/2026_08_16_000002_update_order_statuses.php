<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 'processing', 'packed', 'shipped', 'completed', 'delivered', 'cancelled',
            ])->default('pending')->change();
        });

        DB::table('orders')->where('status', 'processing')->update(['status' => 'packed']);
        DB::table('orders')->where('status', 'completed')->update(['status' => 'delivered']);

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 'packed', 'shipped', 'delivered', 'cancelled',
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'completed', 'cancelled',
            ])->default('pending')->change();
        });
    }
};
