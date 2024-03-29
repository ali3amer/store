<?php

return [
    'roles_structure' => [
        'super_admin' => [
            'users' => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'products' => 'c,r,u,d',
            'product_details' => 'c,r,u,d',
            'orders' => 'c,r,u,d',
            'order_details' => 'c,r,u,d',
            'clients' => 'c,r,u,d',
            'expenses' => 'c,r,u,d',
        ],
        'admin' => []
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
