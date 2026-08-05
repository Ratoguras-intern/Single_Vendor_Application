<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | Each method maps to the value used in the checkout request validation
    | and the order's payment_method column. A method is active when its
    | 'enabled' flag is true.
    |
    */

    'methods' => [
        'cod' => [
            'label' => 'Cash on Delivery',
            'enabled' => true,
        ],
        'stripe' => [
            'label' => 'Stripe',
            'enabled' => (bool) env('STRIPE_ENABLED', false),
        ],
        'paypal' => [
            'label' => 'PayPal',
            'enabled' => (bool) env('PAYPAL_ENABLED', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Badges
    |--------------------------------------------------------------------------
    |
    | Badges shown in the storefront footer. Each badge has a slug used as a
    | class hook for the inline SVG rendered by the footer partial.
    |
    */

    'badges' => [
        'visa' => ['label' => 'Visa', 'enabled' => true],
        'mastercard' => ['label' => 'Mastercard', 'enabled' => true],
        'paypal' => ['label' => 'PayPal', 'enabled' => (bool) env('PAYPAL_ENABLED', false)],
        'stripe' => ['label' => 'Stripe', 'enabled' => (bool) env('STRIPE_ENABLED', false)],
        'cod' => ['label' => 'Cash on Delivery', 'enabled' => true],
    ],
];
