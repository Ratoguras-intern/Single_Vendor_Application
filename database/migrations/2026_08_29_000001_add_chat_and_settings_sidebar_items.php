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

        $groups = [
            'Menu' => 'Menu',
            'Content' => 'Content',
            'Sales' => 'Sales',
            'Administration' => 'Administration',
        ];

        foreach ($groups as $name => $label) {
            $groups[$name] = $this->findOrCreateGroup($menuId, $name);
        }

        $this->addItem($menuId, $groups['Menu'], 'Notifications', 'admin.notifications.index', 'bell', 1);
        $this->addItem($menuId, $groups['Sales'], 'Live Chat', 'admin.chat.index', 'mail', 3);
        $this->addItem($menuId, $groups['Content'], 'Content Settings', 'admin.content-settings.edit', 'info', 9);
        $this->addItem($menuId, $groups['Administration'], 'Display Limits', 'admin.limit-settings.edit', 'homepage', 2);

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
            ->whereIn('url', [
                'admin.notifications.index',
                'admin.chat.index',
                'admin.content-settings.edit',
                'admin.limit-settings.edit',
            ])
            ->delete();

        Cache::forget('admin_sidebar_nav:admin');
        Cache::forget('admin_sidebar_nav:super_admin');
    }

    private function findOrCreateGroup(int $menuId, string $name): int
    {
        $group = NavigationItem::where('menu_id', $menuId)
            ->whereNull('parent_id')
            ->get()
            ->first(function (NavigationItem $item) use ($name) {
                $config = is_array($item->config) ? $item->config : [];

                return ($config['is_group_title'] ?? false) && $item->name === $name;
            });

        if ($group) {
            return $group->id;
        }

        $created = NavigationItem::create([
            'menu_id' => $menuId,
            'name' => $name,
            'sort_order' => 99,
            'config' => ['is_group_title' => true],
        ]);

        return $created->id;
    }

    private function addItem(
        int $menuId,
        int $parentId,
        string $name,
        string $url,
        string $icon,
        int $sortOrder,
        ?string $permission = null
    ): void {
        NavigationItem::updateOrCreate(
            ['menu_id' => $menuId, 'url' => $url],
            [
                'parent_id' => $parentId,
                'name' => $name,
                'icon_key' => $icon,
                'target' => '_self',
                'is_enabled' => true,
                'sort_order' => $sortOrder,
                'permission' => $permission,
                'badge' => null,
                'css_class' => null,
                'config' => null,
            ]
        );
    }
};