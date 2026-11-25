<?php

use App\Models\NavigationItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $menuId = DB::table('navigation_menus')->where('slug', 'admin-sidebar')->value('id');

        if (! $menuId) {
            return;
        }

        $adminGroup = NavigationItem::query()
            ->where('menu_id', $menuId)
            ->whereNull('parent_id')
            ->get()
            ->first(function (NavigationItem $item) {
                $config = is_array($item->config) ? $item->config : [];

                return ($config['is_group_title'] ?? false) && $item->name === 'Administration';
            });

        if (! $adminGroup) {
            return;
        }

        NavigationItem::updateOrCreate(
            ['menu_id' => $menuId, 'url' => 'admin.branding.edit'],
            [
                'parent_id' => $adminGroup->id,
                'name' => 'Branding & Logo',
                'icon_key' => 'branding',
                'target' => '_self',
                'is_enabled' => true,
                'sort_order' => 3,
                'permission' => 'super_admin',
                'badge' => null,
                'css_class' => null,
                'config' => null,
            ]
        );

        Cache::forget('admin_sidebar_nav:admin');
        Cache::forget('admin_sidebar_nav:super_admin');
    }

    public function down(): void
    {
        $menuId = DB::table('navigation_menus')->where('slug', 'admin-sidebar')->value('id');

        if (! $menuId) {
            return;
        }

        DB::table('navigation_items')
            ->where('menu_id', $menuId)
            ->where('url', 'admin.branding.edit')
            ->delete();

        Cache::forget('admin_sidebar_nav:admin');
        Cache::forget('admin_sidebar_nav:super_admin');
    }
};