<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        // ── Header Nav ──
        $headerNav = NavigationMenu::updateOrCreate(
            ['slug' => 'header-nav'],
            ['name' => 'Header Navigation', 'sort_order' => 0]
        );

        $headerItems = [
            ['name' => 'Home', 'url' => '/', 'sort_order' => 0],
            ['name' => 'Shop', 'url' => '/shop', 'sort_order' => 1],
            ['name' => 'Categories', 'url' => null, 'sort_order' => 2, 'config' => ['type' => 'mega-menu']],
            ['name' => 'New Arrivals', 'url' => '/shop?new_arrivals=1', 'sort_order' => 3],
            ['name' => 'Sale', 'url' => '/shop?on_sale=1', 'sort_order' => 4, 'css_class' => 'text-red-500 hover:text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-500/10'],
        ];

        foreach ($headerItems as $item) {
            NavigationItem::updateOrCreate(
                ['menu_id' => $headerNav->id, 'name' => $item['name'], 'parent_id' => null],
                array_merge($item, ['is_enabled' => true])
            );
        }

        // ── Mobile Nav ──
        $mobileNav = NavigationMenu::updateOrCreate(
            ['slug' => 'mobile-nav'],
            ['name' => 'Mobile Navigation', 'sort_order' => 1]
        );

        $mobileItems = [
            ['name' => 'Home', 'url' => '/', 'icon_key' => 'home', 'sort_order' => 0],
            ['name' => 'Shop', 'url' => '/shop', 'icon_key' => 'shop', 'sort_order' => 1],
            ['name' => 'New Arrivals', 'url' => '/shop?new_arrivals=1', 'icon_key' => 'clock', 'sort_order' => 2],
            ['name' => 'Sale', 'url' => '/shop?on_sale=1', 'icon_key' => 'fire', 'sort_order' => 3, 'css_class' => 'text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10'],
            ['name' => 'Wishlist', 'url' => '/favorites', 'icon_key' => 'heart', 'sort_order' => 4],
            ['name' => 'Cart', 'url' => '/cart', 'icon_key' => 'cart', 'sort_order' => 5],
            ['name' => 'Contact', 'url' => '/contact', 'icon_key' => 'mail', 'sort_order' => 6],
            ['name' => 'About', 'url' => '/about', 'icon_key' => 'info', 'sort_order' => 7],
        ];

        foreach ($mobileItems as $item) {
            NavigationItem::updateOrCreate(
                ['menu_id' => $mobileNav->id, 'name' => $item['name'], 'parent_id' => null],
                array_merge($item, ['is_enabled' => true])
            );
        }

        // ── Mega Menu Promo ──
        $megaPromo = NavigationMenu::updateOrCreate(
            ['slug' => 'mega-menu-promo'],
            [
                'name' => 'Mega Menu Promo Banner',
                'sort_order' => 2,
                'config' => [
                    'badge' => 'Featured',
                    'heading' => 'Explore Our Collection',
                    'description' => 'Discover quality products across all categories.',
                    'cta_text' => 'Shop Now',
                    'url' => '/shop',
                ],
            ]
        );

        // ── Admin Sidebar ──
        $adminSidebar = NavigationMenu::updateOrCreate(
            ['slug' => 'admin-sidebar'],
            ['name' => 'Admin Sidebar', 'sort_order' => 3]
        );

        // Clear existing items for fresh seed
        NavigationItem::where('menu_id', $adminSidebar->id)->delete();

        $groups = [
            'Overview' => 0,
            'Catalog' => 1,
            'Orders' => 2,
            'Customers' => 3,
            'Store' => 4,
            'Settings' => 5,
        ];

        $groupIds = [];
        foreach ($groups as $label => $order) {
            $groupIds[$label] = NavigationItem::create([
                'menu_id' => $adminSidebar->id,
                'name' => $label,
                'sort_order' => $order,
                'config' => ['is_group_title' => true],
            ])->id;
        }

        $add = function (int $groupId, string $name, string $url, string $icon, int $order, ?string $permission = null) use ($adminSidebar) {
            NavigationItem::create([
                'menu_id' => $adminSidebar->id,
                'parent_id' => $groupId,
                'name' => $name,
                'icon_key' => $icon,
                'url' => $url,
                'sort_order' => $order,
                'permission' => $permission,
            ]);
        };

        // Overview
        $add($groupIds['Overview'], 'Dashboard', 'admin.dashboard', 'dashboard', 0);
        $add($groupIds['Overview'], 'Notifications', 'admin.notifications.index', 'bell', 1);

        // Catalog
        $add($groupIds['Catalog'], 'Products', 'admin.products.index', 'product', 0);
        $add($groupIds['Catalog'], 'Categories', 'admin.categories.index', 'category', 1);
        $add($groupIds['Catalog'], 'Brands', 'admin.brands.index', 'brand', 2);

        // Orders
        $add($groupIds['Orders'], 'Orders', 'admin.orders.index', 'order', 0);
        $add($groupIds['Orders'], 'Returns & Refunds', 'admin.returns.index', 'returns', 1);

        // Customers
        $add($groupIds['Customers'], 'Customers', 'admin.customers.index', 'customer', 0);
        $add($groupIds['Customers'], 'Reviews', 'admin.reviews.index', 'reviews', 1);
        $add($groupIds['Customers'], 'Live Chat', 'admin.chat.index', 'chat', 2);

        // Store & content
        $add($groupIds['Store'], 'Homepage', 'admin.homepage-sections.index', 'homepage', 0);
        $add($groupIds['Store'], 'Banners', 'admin.banners.index', 'banner', 1);
        $add($groupIds['Store'], 'Sale Banners', 'admin.sale-banners.index', 'sale', 2);
        $add($groupIds['Store'], 'Featured Categories', 'admin.featured-categories.index', 'featured', 3);
        $add($groupIds['Store'], 'Pages', 'admin.pages.index', 'media', 5);
        $add($groupIds['Store'], 'Footer', 'admin.footer.edit', 'homepage', 6);
        $add($groupIds['Store'], 'Media', 'admin.media.index', 'media', 7);
        $add($groupIds['Store'], 'Navigation', 'admin.navigations.index', 'navigation', 8, 'super_admin');
        $add($groupIds['Store'], 'Why Choose Us', 'admin.why-choose-us.index', 'featured', 9);
        $add($groupIds['Store'], 'Testimonials', 'admin.testimonials.index', 'reviews', 10);

        $productSections = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $groupIds['Store'],
            'name' => 'Product Sections',
            'icon_key' => 'product',
            'url' => null,
            'sort_order' => 4,
        ]);

        $productSectionItems = [
            ['name' => 'Featured', 'url' => 'admin.product-sections.index:featured-products', 'sort_order' => 0],
            ['name' => 'New Arrivals', 'url' => 'admin.product-sections.index:new-arrivals', 'sort_order' => 1],
            ['name' => 'Trending', 'url' => 'admin.product-sections.index:trending-products', 'sort_order' => 2],
            ['name' => 'Best Sellers', 'url' => 'admin.product-sections.index:best-sellers', 'sort_order' => 3],
            ['name' => 'Flash Sales', 'url' => 'admin.product-sections.index:flash-sale', 'sort_order' => 4],
            ['name' => 'Recommended', 'url' => 'admin.product-sections.index:recommended-products', 'sort_order' => 5],
            ['name' => 'Popular', 'url' => 'admin.product-sections.index:popular-products', 'sort_order' => 6],
        ];

        foreach ($productSectionItems as $item) {
            NavigationItem::create([
                'menu_id' => $adminSidebar->id,
                'parent_id' => $productSections->id,
                'name' => $item['name'],
                'url' => $item['url'],
                'sort_order' => $item['sort_order'],
            ]);
        }

        // Settings
        $add($groupIds['Settings'], 'Content Settings', 'admin.content-settings.edit', 'settings', 0);
        $add($groupIds['Settings'], 'Display Limits', 'admin.limit-settings.edit', 'sliders', 1);
        $add($groupIds['Settings'], 'Branding & Logo', 'admin.branding.edit', 'branding', 2, 'super_admin');
        $add($groupIds['Settings'], 'Team Members', 'superadmin.admins.index', 'team', 3, 'super_admin');
        $add($groupIds['Settings'], 'All Users', 'superadmin.users.index', 'users', 4, 'super_admin');
        $add($groupIds['Settings'], 'My Account', 'profile.edit', 'account', 5);
        $add($groupIds['Settings'], 'Store Information', 'admin.store-settings.edit', 'settings', 6);
    }
}
