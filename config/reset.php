<?php

return [

    'modules' => [

        'catalog' => [
            'categories',
            'attributes',
            'attribute_categories',
            'attribute_values',
            'attribute_value_categories',
            'category_product_additional_info_master',
        ],

        'products' => [
            'product_variant_attributes',
            'product_variants',
            'product_attribute_value_images',
            'product_attributes',
            'product_images',
            'product_categories',
            'product_additional_info',
            'products',
            'product_families',
            'tags',
            'product_tags',
            'product_reviews'
        ],

        'orders' => [
            'order_details',
            'orders',
            'payments',
            'invoices',
        ],

        'customers' => [
            'address_books',
            'customers',
            'wishlists',
            'users',
            'user_details',
            'order_details',
            'orders',
            'payments',
            'invoices',
            'product_reviews'
        ],

        // 'users' => [
        //     'user_details',
        //     'users',
        // ],

        'meta' => [
            'meta_management',
        ],

    ],

];
