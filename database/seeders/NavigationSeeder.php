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

        // Menu group
        $menuGroup = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'name' => 'Menu',
            'sort_order' => 0,
            'config' => ['is_group_title' => true],
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $menuGroup->id,
            'name' => 'Dashboard',
            'icon_key' => 'dashboard',
            'url' => 'admin.dashboard',
            'sort_order' => 0,
        ]);

        // Catalog group
        $catalogGroup = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'name' => 'Catalog',
            'sort_order' => 1,
            'config' => ['is_group_title' => true],
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $catalogGroup->id,
            'name' => 'Categories',
            'icon_key' => 'category',
            'url' => 'admin.categories.index',
            'sort_order' => 0,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $catalogGroup->id,
            'name' => 'Brands',
            'icon_key' => 'brand',
            'url' => 'admin.brands.index',
            'sort_order' => 1,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $catalogGroup->id,
            'name' => 'Products',
            'icon_key' => 'product',
            'url' => 'admin.products.index',
            'sort_order' => 2,
        ]);

        // Content group
        $contentGroup = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'name' => 'Content',
            'sort_order' => 2,
            'config' => ['is_group_title' => true],
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Homepage Sections',
            'icon_key' => 'homepage',
            'url' => 'admin.homepage-sections.index',
            'sort_order' => 0,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Banners',
            'icon_key' => 'banner',
            'url' => 'admin.banners.index',
            'sort_order' => 1,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Sale Banners',
            'icon_key' => 'sale',
            'url' => 'admin.sale-banners.index',
            'sort_order' => 2,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Featured Categories',
            'icon_key' => 'featured',
            'url' => 'admin.featured-categories.index',
            'sort_order' => 3,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Navigation',
            'icon_key' => 'navigation',
            'url' => 'admin.navigations.index',
            'permission' => 'super_admin',
            'sort_order' => 4,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Pages',
            'icon_key' => 'info',
            'url' => 'admin.pages.index',
            'sort_order' => 5,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Footer',
            'icon_key' => 'homepage',
            'url' => 'admin.footer.edit',
            'sort_order' => 6,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Reviews',
            'icon_key' => 'homepage',
            'url' => 'admin.reviews.index',
            'sort_order' => 7,
        ]);

        $productSections = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $contentGroup->id,
            'name' => 'Product Sections',
            'icon_key' => 'product',
            'url' => null,
            'sort_order' => 8,
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

        // Sales group
        $salesGroup = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'name' => 'Sales',
            'sort_order' => 3,
            'config' => ['is_group_title' => true],
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $salesGroup->id,
            'name' => 'Orders',
            'icon_key' => 'order',
            'url' => 'admin.orders.index',
            'sort_order' => 0,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $salesGroup->id,
            'name' => 'Customers',
            'icon_key' => 'customer',
            'url' => 'admin.customers.index',
            'sort_order' => 1,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $salesGroup->id,
            'name' => 'Returns',
            'icon_key' => 'order',
            'url' => 'admin.returns.index',
            'sort_order' => 2,
        ]);

        // Administration group (super_admin only)
        $adminGroup = NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'name' => 'Administration',
            'sort_order' => 4,
            'config' => ['is_group_title' => true],
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $adminGroup->id,
            'name' => 'Admins',
            'icon_key' => 'admins',
            'url' => 'superadmin.admins.index',
            'permission' => 'super_admin',
            'sort_order' => 0,
        ]);

        NavigationItem::create([
            'menu_id' => $adminSidebar->id,
            'parent_id' => $adminGroup->id,
            'name' => 'Users',
            'icon_key' => 'users',
            'url' => 'superadmin.users.index',
            'permission' => 'super_admin',
            'sort_order' => 1,
        ]);
    }
}
