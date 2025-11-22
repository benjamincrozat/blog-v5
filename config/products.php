<?php

return [
    'sticky_carousel' => [
        'price_id' => env('STRIPE_PRODUCT_STICKY_CAROUSEL'),
        'subscription' => true,
    ],
    'sponsored_article' => [
        'price_id' => env('STRIPE_PRODUCT_SPONSOR_ARTICLE'),
        'subscription' => false,
    ],
];
