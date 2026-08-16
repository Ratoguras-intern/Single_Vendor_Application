<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('comment')->nullable();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('order_id');
        });

        $orders = DB::table('orders')->select(['id', 'created_at', 'updated_at'])->get();

        foreach ($orders as $order) {
            DB::table('order_status_history')->insert([
                'order_id' => $order->id,
                'status' => 'pending',
                'comment' => 'Order placed.',
                'changed_by_user_id' => null,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
