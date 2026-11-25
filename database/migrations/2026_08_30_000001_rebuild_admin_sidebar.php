<?php

use App\Models\NavigationItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Business-facing admin sidebar. Every feature is grouped under the
     * category a store owner would naturally look for it in.
     */
    private array $groups = [
        'Overview' => [
            'items' => [
                ['name' => 'Dashboard', 'url' => 'admin.dashboard', 'icon' => 'dashboard'],
                ['name' => 'Notifications', 'url' => 'admin.notifications.index', 'icon' => 'bell'],
            ],
        ],
        'Catalog' => [
            'items' => [
                ['name' => 'Products', 'url' => 'admin.products.index', 'icon' => 'product'],
                ['name' => 'Categories', 'url' => 'admin.categories.index', 'icon' => 'category'],
                ['name' => 'Brands', 'url' => 'admin.brands.index', 'icon' => 'brand'],
            ],
        ],
        'Orders' => [
            'items' => [
                ['name' => 'Orders', 'url' => 'admin.orders.index', 'icon' => 'order'],
                ['name' => 'Returns & Refunds', 'url' => 'admin.returns.index', 'icon' => 'returns'],
            ],
        ],
        'Customers' => [
            'items' => [
                ['name' => 'Customers', 'url' => 'admin.customers.index', 'icon' => 'customer'],
                ['name' => 'Reviews', 'url' => 'admin.reviews.index', 'icon' => 'reviews'],
                ['name' => 'Live Chat', 'url' => 'admin.chat.index', 'icon' => 'chat'],
            ],
        ],
        'Store' => [
            'items' => [
                ['name' => 'Homepage', 'url' => 'admin.homepage-sections.index', 'icon' => 'homepage'],
                ['name' => 'Banners', 'url' => 'admin.banners.index', 'icon' => 'banner'],
                ['name' => 'Sale Banners', 'url' => 'admin.sale-banners.index', 'icon' => 'sale'],
                ['name' => 'Featured Categories', 'url' => 'admin.featured-categories.index', 'icon' => 'featured'],
                ['name' => 'Product Sections', 'url' => null, 'icon' => 'product', 'children' => [
                    ['name' => 'Featured', 'url' => 'admin.product-sections.index:featured-products'],
                    ['name' => 'New Arrivals', 'url' => 'admin.product-sections.index:new-arrivals'],
                    ['name' => 'Trending', 'url' => 'admin.product-sections.index:trending-products'],
                    ['name' => 'Best Sellers', 'url' => 'admin.product-sections.index:best-sellers'],
                    ['name' => 'Flash Sales', 'url' => 'admin.product-sections.index:flash-sale'],
                    ['name' => 'Recommended', 'url' => 'admin.product-sections.index:recommended-products'],
                    ['name' => 'Popular', 'url' => 'admin.product-sections.index:popular-products'],
                ]],
                ['name' => 'Pages', 'url' => 'admin.pages.index', 'icon' => 'media'],
                ['name' => 'Footer', 'url' => 'admin.footer.edit', 'icon' => 'homepage'],
                ['name' => 'Media', 'url' => 'admin.media.index', 'icon' => 'media'],
                ['name' => 'Navigation', 'url' => 'admin.navigations.index', 'icon' => 'navigation', 'permission' => 'super_admin'],
            ],
        ],
        'Settings' => [
            'items' => [
                ['name' => 'Store Settings', 'url' => 'admin.content-settings.edit', 'icon' => 'settings'],
                ['name' => 'Display Limits', 'url' => 'admin.limit-settings.edit', 'icon' => 'sliders'],
                ['name' => 'Branding & Logo', 'url' => 'admin.branding.edit', 'icon' => 'branding', 'permission' => 'super_admin'],
                ['name' => 'Team Members', 'url' => 'superadmin.admins.index', 'icon' => 'team', 'permission' => 'super_admin'],
                ['name' => 'All Users', 'url' => 'superadmin.users.index', 'icon' => 'users', 'permission' => 'super_admin'],
                ['name' => 'My Account', 'url' => 'profile.edit', 'icon' => 'account'],
            ],
        ],
    ];

    public function up(): void
    {
        $menuId = DB::table('navigation_menus')->where('slug', 'admin-sidebar')->value('id');

        if (! $menuId) {
            return;
        }

        // Deterministic rebuild: drop every item under the admin sidebar, then
        // recreate the full structure. Navigation rows carry no business data,
        // so this is safe to reset.
        NavigationItem::where('menu_id', $menuId)->delete();

        $order = 0;
        foreach ($this->groups as $label => $group) {
            $groupId = NavigationItem::create([
                'menu_id' => $menuId,
                'name' => $label,
                'sort_order' => $order++,
                'config' => ['is_group_title' => true],
            ])->id;

            $itemOrder = 0;
            foreach ($group['items'] as $item) {
                $row = [
                    'menu_id' => $menuId,
                    'parent_id' => $groupId,
                    'name' => $item['name'],
                    'icon_key' => $item['icon'],
                    'url' => $item['url'],
                    'target' => '_self',
                    'is_enabled' => true,
                    'sort_order' => $itemOrder++,
                    'permission' => $item['permission'] ?? null,
                ];
                $created = NavigationItem::create($row);

                if (! empty($item['children'])) {
                    $childOrder = 0;
                    foreach ($item['children'] as $child) {
                        NavigationItem::create([
                            'menu_id' => $menuId,
                            'parent_id' => $created->id,
                            'name' => $child['name'],
                            'url' => $child['url'],
                            'target' => '_self',
                            'is_enabled' => true,
                            'sort_order' => $childOrder++,
                        ]);
                    }
                }
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
