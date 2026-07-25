<?php

return [
    'default' => env('CURRENCY_DEFAULT', 'USD'),

    'supported' => [
        'USD' => ['symbol' => '$', 'name' => 'US Dollar', 'locale' => 'en-US'],
        'JPY' => ['symbol' => '¥', 'name' => 'Japanese Yen', 'locale' => 'ja-JP'],
        'NPR' => ['symbol' => 'रु', 'name' => 'Nepalese Rupee', 'locale' => 'ne-NP'],
    ],

    'api_url' => env('CURRENCY_API_URL', 'https://api.exchangerate-api.com/v4/latest/USD'),

    'cache_ttl' => 86400,
];
