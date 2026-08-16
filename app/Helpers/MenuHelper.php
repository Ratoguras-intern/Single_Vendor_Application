<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuGroups(): array
    {
        $role = auth()->user()->role ?? 'customer';

        $menu = [
            [
                'title' => 'Menu',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'icon' => 'dashboard',
                        'path' => $role === 'super_admin'
                            ? route('superadmin.dashboard')
                            : route('admin.dashboard'),
                    ],
                ],
            ],
            [
                'title' => 'Catalog',
                'items' => [
                    [
                        'name' => 'Categories',
                        'icon' => 'category',
                        'path' => route('admin.categories.index'),
                    ],
                    [
                        'name' => 'Brands',
                        'icon' => 'brand',
                        'path' => route('admin.brands.index'),
                    ],
                    [
                        'name' => 'Products',
                        'icon' => 'product',
                        'path' => route('admin.products.index'),
                    ],
                ],
            ],
            [
                'title' => 'Content',
                'items' => [
                    [
                        'name' => 'Homepage Sections',
                        'icon' => 'homepage',
                        'path' => route('admin.homepage-sections.index'),
                    ],
                    [
                        'name' => 'Banners',
                        'icon' => 'banner',
                        'path' => route('admin.banners.index'),
                    ],
                    [
                        'name' => 'Sale Banners',
                        'icon' => 'sale',
                        'path' => route('admin.sale-banners.index'),
                    ],
                    [
                        'name' => 'Featured Categories',
                        'icon' => 'featured',
                        'path' => route('admin.featured-categories.index'),
                    ],
                    [
                        'name' => 'Product Sections',
                        'icon' => 'product',
                        'subItems' => [
                            ['name' => 'Featured', 'path' => route('admin.product-sections.index', 'featured-products')],
                            ['name' => 'New Arrivals', 'path' => route('admin.product-sections.index', 'new-arrivals')],
                            ['name' => 'Trending', 'path' => route('admin.product-sections.index', 'trending-products')],
                            ['name' => 'Best Sellers', 'path' => route('admin.product-sections.index', 'best-sellers')],
                            ['name' => 'Flash Sales', 'path' => route('admin.product-sections.index', 'flash-sale')],
                            ['name' => 'Recommended', 'path' => route('admin.product-sections.index', 'recommended-products')],
                            ['name' => 'Popular', 'path' => route('admin.product-sections.index', 'popular-products')],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Sales',
                'items' => [
                    [
                        'name' => 'Orders',
                        'icon' => 'order',
                        'path' => route('admin.orders.index'),
                    ],
                    [
                        'name' => 'Customers',
                        'icon' => 'customer',
                        'path' => route('admin.customers.index'),
                    ],
                ],
            ],
        ];

        if ($role === 'super_admin') {
            $menu[] = [
                'title' => 'Administration',
                'items' => [
                    [
                        'name' => 'Admins',
                        'icon' => 'admins',
                        'path' => route('superadmin.admins.index'),
                    ],
                    [
                        'name' => 'Users',
                        'icon' => 'users',
                        'path' => route('superadmin.users.index'),
                    ],
                ],
            ];
        }

        return $menu;
    }

    public static function getIconSvg(string $icon): string
    {
        $icons = [
            'dashboard' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.10927 2.55078H5.09927C3.89927 2.55078 2.91927 3.53078 2.91927 4.73078V8.74078C2.91927 9.94078 3.89927 10.9208 5.09927 10.9208H9.10927C10.3093 10.9208 11.2893 9.94078 11.2893 8.74078V4.73078C11.2893 3.53078 10.3093 2.55078 9.10927 2.55078Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.10927 13.0808H5.09927C3.89927 13.0808 2.91927 14.0608 2.91927 15.2608V19.2708C2.91927 20.4708 3.89927 21.4508 5.09927 21.4508H9.10927C10.3093 21.4508 11.2893 20.4708 11.2893 19.2708V15.2608C11.2893 14.0608 10.3093 13.0808 9.10927 13.0808Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9007 2.55078H14.8907C13.6907 2.55078 12.7107 3.53078 12.7107 4.73078V8.74078C12.7107 9.94078 13.6907 10.9208 14.8907 10.9208H18.9007C20.1007 10.9208 21.0807 9.94078 21.0807 8.74078V4.73078C21.0807 3.53078 20.1007 2.55078 18.9007 2.55078Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9007 13.0808H14.8907C13.6907 13.0808 12.7107 14.0608 12.7107 15.2608V19.2708C12.7107 20.4708 13.6907 21.4508 14.8907 21.4508H18.9007C20.1007 21.4508 21.0807 20.4708 21.0807 19.2708V15.2608C21.0807 14.0608 20.1007 13.0808 18.9007 13.0808Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'category' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.10927 2.55078H5.09927C3.89927 2.55078 2.91927 3.53078 2.91927 4.73078V8.74078C2.91927 9.94078 3.89927 10.9208 5.09927 10.9208H9.10927C10.3093 10.9208 11.2893 9.94078 11.2893 8.74078V4.73078C11.2893 3.53078 10.3093 2.55078 9.10927 2.55078Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.10927 13.0808H5.09927C3.89927 13.0808 2.91927 14.0608 2.91927 15.2608V19.2708C2.91927 20.4708 3.89927 21.4508 5.09927 21.4508H9.10927C10.3093 21.4508 11.2893 20.4708 11.2893 19.2708V15.2608C11.2893 14.0608 10.3093 13.0808 9.10927 13.0808Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9007 2.55078H14.8907C13.6907 2.55078 12.7107 3.53078 12.7107 4.73078V8.74078C12.7107 9.94078 13.6907 10.9208 14.8907 10.9208H18.9007C20.1007 10.9208 21.0807 9.94078 21.0807 8.74078V4.73078C21.0807 3.53078 20.1007 2.55078 18.9007 2.55078Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9007 13.0808H14.8907C13.6907 13.0808 12.7107 14.0608 12.7107 15.2608V19.2708C12.7107 20.4708 13.6907 21.4508 14.8907 21.4508H18.9007C20.1007 21.4508 21.0807 20.4708 21.0807 19.2708V15.2608C21.0807 14.0608 20.1007 13.0808 18.9007 13.0808Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'brand' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'product' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 8V21H3V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 3H23V8H1V3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'order' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 2V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 2V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 10H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V12C3 13.1046 3.89543 14 5 14H19C20.1046 14 21 13.1046 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 17V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'customer' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'homepage' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 22V12H15V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'banner' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 3H3C1.89543 3 1 3.89543 1 5V19C1 20.1046 1.89543 21 3 21H21C22.1046 21 23 20.1046 23 19V5C23 3.89543 22.1046 3 21 3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M1 12H23" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'featured' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'admins' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 21V19C4 16.7909 5.79086 15 8 15H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'users' => '<svg class="menu-item-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 21V19C22 17.3132 20.6569 15.75 18.5 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 3.4C17.4906 3.4 18.7 4.60937 18.7 6.1C18.7 7.59063 17.4906 8.8 16 8.8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ];

        return $icons[$icon] ?? $icons['dashboard'];
    }
}
