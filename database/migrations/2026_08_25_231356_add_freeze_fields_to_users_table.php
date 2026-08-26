<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_frozen')->default(false);
            $table->text('frozen_reason')->nullable();
            $table->timestamp('frozen_at')->nullable();
            $table->integer('failed_login_attempts')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_frozen', 'frozen_reason', 'frozen_at', 'failed_login_attempts']);
        });
    }
};
