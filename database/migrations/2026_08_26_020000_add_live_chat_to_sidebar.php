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
            'name' => 'Live Chat',
            'url' => 'admin.chat.index',
            'icon_key' => 'mail',
            'target' => '_self',
            'is_enabled' => true,
            'sort_order' => ($maxSort ?? 0) + 1,
            'permission' => null,
            'badge' => null,
            'css_class' => null,
            'config' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $menuId = DB::table('navigation_menus')->where('slug', 'admin-sidebar')->value('id');

        if ($menuId) {
            DB::table('navigation_items')
                ->where('url', 'admin.chat.index')
                ->where('menu_id', $menuId)
                ->delete();
        }
    }
};
