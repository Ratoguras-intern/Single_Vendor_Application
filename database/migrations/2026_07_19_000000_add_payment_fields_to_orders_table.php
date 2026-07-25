<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('shipping_address');
            $table->string('payment_method', 50)->default('cod')->after('phone');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cod'])->default('pending')->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['phone', 'payment_method', 'payment_status']);
        });
    }
};
