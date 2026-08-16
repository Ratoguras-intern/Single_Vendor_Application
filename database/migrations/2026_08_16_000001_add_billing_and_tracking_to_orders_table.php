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
            $table->text('billing_address')->nullable()->after('shipping_address');
            $table->string('tracking_number')->nullable()->after('billing_address');
            $table->string('tracking_carrier')->nullable()->after('tracking_number');
        });

        DB::table('orders')
            ->whereNull('billing_address')
            ->update(['billing_address' => DB::raw('shipping_address')]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['billing_address', 'tracking_number', 'tracking_carrier']);
        });
    }
};
