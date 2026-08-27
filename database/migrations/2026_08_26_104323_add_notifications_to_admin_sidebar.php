<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $menuId = DB::table('navigation_menus')->where('slug', 'admin-sidebar')->value('id');

        if (! $menuId) {
            return;
        }

        $maxSort = DB::table('navigation_items')
            ->where('menu_id', $menuId)
            ->whereNull('parent_id')
            ->max('sort_order');

        DB::table('navigation_items')->insert([
            'menu_id' => $menuId,
            'parent_id' => null,
            'name' => 'Notifications',
            'url' => 'admin.notifications.index',
            'icon_key' => 'bell',
            'sort_order' => ($maxSort ?? 0) + 1,
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('navigation_items')
            ->where('url', 'admin.notifications.index')
            ->whereNull('parent_id')
            ->delete();
    }
};
