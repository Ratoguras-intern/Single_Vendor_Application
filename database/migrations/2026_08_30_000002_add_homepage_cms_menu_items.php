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

        // The old "Store Settings" item pointed at content-settings (support,
        // returns, shipping, press). Rename it to "Content Settings" now that a
        // dedicated contact-info "Store Information" page exists.
        if ($stale = NavigationItem::where('menu_id', $menuId)
            ->where('name', 'Store Settings')
            ->where('url', 'admin.content-settings.edit')
            ->first()) {
            $stale->update(['name' => 'Content Settings']);
        }

        $helpers = [
            'store' => NavigationItem::where('menu_id', $menuId)->where('name', 'Store')->where('config->is_group_title', true)->first(),
            'settings' => NavigationItem::where('menu_id', $menuId)->where('name', 'Settings')->where('config->is_group_title', true)->first(),
        ];

        $known = [
            'store' => [
                ['name' => 'Why Choose Us', 'url' => 'admin.why-choose-us.index', 'icon' => 'featured'],
                ['name' => 'Testimonials', 'url' => 'admin.testimonials.index', 'icon' => 'reviews'],
            ],
            'settings' => [
                ['name' => 'Store Information', 'url' => 'admin.store-settings.edit', 'icon' => 'settings'],
            ],
        ];

        foreach (['store', 'settings'] as $groupKey) {
            $group = $helpers[$groupKey];
            if (! $group) {
                continue;
            }

            $nextOrder = (int) NavigationItem::where('menu_id', $menuId)
                ->where('parent_id', $group->id)
                ->max('sort_order') + 1;

            foreach ($known[$groupKey] as $item) {
                $exists = NavigationItem::where('menu_id', $menuId)
                    ->where('parent_id', $group->id)
                    ->where('name', $item['name'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                NavigationItem::create([
                    'menu_id' => $menuId,
                    'parent_id' => $group->id,
                    'name' => $item['name'],
                    'icon_key' => $item['icon'],
                    'url' => $item['url'],
                    'target' => '_self',
                    'is_enabled' => true,
                    'sort_order' => $nextOrder++,
                ]);
            }
        }

        Cache::forget('admin_sidebar_nav:admin');
        Cache::forget('admin_sidebar_nav:super_admin');
    }

    public function down(): void
    {
        Cache::forget('admin_sidebar_nav:admin');
        Cache::forget('admin_sidebar_nav:super_admin');
    }
};
