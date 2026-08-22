<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media Library Configuration
    |--------------------------------------------------------------------------
    |
    | Central configuration for the reusable media picker / media library.
    | All uploads flow through the MediaController and are tracked in the
    | `media` database table so files stay reusable across features.
    |
    */

    // Storage disk (must be defined in config/filesystems.php).
    'disk' => env('MEDIA_DISK', 'public'),

    // Base directory inside the disk for uploads that are not tied to a
    // specific feature folder (see `folders` below).
    'directory' => 'media',

    /*
    |----------------------------------------------------------------------
    | Library folders
    |----------------------------------------------------------------------
    |
    | Sidebar entries for the Media Library. Each key maps to a display
    | label, the storage path prefixes that belong to it (`match`) and the
    | directory new uploads are stored in (`path`). Folders reuse the
    | existing storage structure — no files are moved or duplicated.
    |
    */
    'folders' => [
        'products' => [
            'label' => 'Products',
            'match' => ['products'],
            'path' => 'products',
        ],
        'categories' => [
            'label' => 'Categories',
            'match' => ['categories'],
            'path' => 'categories',
        ],
        'brands' => [
            'label' => 'Brands',
            'match' => ['brands'],
            'path' => 'brands',
        ],
        'banners' => [
            'label' => 'Banners',
            'match' => ['banners', 'sale-banners', 'homepage'],
            'path' => 'banners',
        ],
        'logos' => [
            'label' => 'Logos',
            'match' => ['branding'],
            'path' => 'branding',
        ],
        'pages' => [
            'label' => 'Pages',
            'match' => ['pages'],
            'path' => 'pages',
        ],
    ],

    // Maximum upload size in kilobytes.
    'max_size' => env('MEDIA_MAX_SIZE_KB', 2048),

    // Allowed MIME types for validation.
    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/avif',
        'image/svg+xml',
        'image/gif',
    ],

    // Allowed file extensions (mirror the mimes above).
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'avif', 'svg', 'gif'],

    // SVG uploads can embed scripts (XSS when opened directly). When enabled,
    // uploaded SVGs are sanitised before being stored.
    'allow_svg' => true,

    // Generate an optimised WebP thumbnail for the library grid.
    'thumb_width' => 400,
    'thumb_height' => 400,
    'thumb_quality' => 78,

    // Items per page for the "Recent Media" grid.
    'per_page' => 24,
];
