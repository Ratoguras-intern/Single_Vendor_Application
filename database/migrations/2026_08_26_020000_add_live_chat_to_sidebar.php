<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // IDs below are environment-specific; skip on fresh databases (e.g. tests).
        // Later sidebar migrations add Live Chat by lookup instead of fixed IDs.
        $menuExists = DB::table('navigation_menus')->where('id', 4)->exists();
        $parentExists = DB::table('navigation_items')->where('id', 104)->exists();

        if (! $menuExists || ! $parentExists) {
            return;
        }

        DB::table('navigation_items')->insert([
            'menu_id' => 4,
            'parent_id' => 104,
            'name' => 'Live Chat',
            'url' => 'admin.chat.index',
            'icon_key' => 'mail',
            'target' => '_self',
            'is_enabled' => true,
            'sort_order' => 15,
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
        DB::table('navigation_items')
            ->where('url', 'admin.chat.index')
            ->where('menu_id', 4)
            ->delete();
    }
};
