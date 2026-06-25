<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permission groups (matches admin sidebar structure)
    |--------------------------------------------------------------------------
    */
    'groups' => [
        [
            'label' => 'Dashboard',
            'permissions' => ['dashboard_view'],
        ],
        [
            'label' => 'POS',
            'permissions' => ['sale_create'],
        ],
        [
            'label' => 'People',
            'sections' => [
                [
                    'label' => 'Customers',
                    'permissions' => [
                        'customer_create',
                        'customer_view',
                        'customer_update',
                        'customer_delete',
                        'customer_sales',
                    ],
                ],
                [
                    'label' => 'Suppliers',
                    'permissions' => [
                        'supplier_create',
                        'supplier_view',
                        'supplier_update',
                        'supplier_delete',
                    ],
                ],
            ],
        ],
        [
            'label' => 'Products',
            'permissions' => [
                'product_create',
                'product_view',
                'product_update',
                'product_delete',
                'product_import',
            ],
        ],
        [
            'label' => 'Brands',
            'permissions' => [
                'brand_create',
                'brand_view',
                'brand_update',
                'brand_delete',
            ],
        ],
        [
            'label' => 'Categories',
            'permissions' => [
                'category_create',
                'category_view',
                'category_update',
                'category_delete',
            ],
        ],
        [
            'label' => 'Units',
            'permissions' => [
                'unit_create',
                'unit_view',
                'unit_update',
                'unit_delete',
            ],
        ],
        [
            'label' => 'Sales',
            'permissions' => [
                'sale_view',
                'sale_update',
                'sale_delete',
            ],
        ],
        [
            'label' => 'Installments',
            'permissions' => [
                'installment_view',
                'installment_create',
                'installment_update',
                'installment_delete',
            ],
        ],
        [
            'label' => 'Cash Register',
            'permissions' => [
                'cash_register_view',
                'cash_register_open',
                'cash_register_close',
                'cash_register_edit',
            ],
        ],
        [
            'label' => 'Purchase',
            'permissions' => [
                'purchase_create',
                'purchase_view',
                'purchase_update',
                'purchase_delete',
            ],
        ],
        [
            'label' => 'Reports',
            'permissions' => [
                'reports_summary',
                'reports_sales',
                'reports_inventory',
                'reports_advanced',
            ],
        ],
        [
            'label' => 'Stock Movements',
            'permissions' => [
                'stock_movement_view',
                'stock_movement_adjust',
            ],
        ],
        [
            'label' => 'Notifications',
            'permissions' => ['notification_view'],
        ],
        [
            'label' => 'Settings',
            'sections' => [
                [
                    'label' => 'General Settings',
                    'permissions' => [
                        'website_settings',
                        'contact_settings',
                        'socials_settings',
                        'style_settings',
                        'custom_settings',
                        'notification_settings',
                        'website_status_settings',
                        'invoice_settings',
                    ],
                ],
                [
                    'label' => 'Currency',
                    'permissions' => [
                        'currency_create',
                        'currency_view',
                        'currency_update',
                        'currency_delete',
                        'currency_set_default',
                    ],
                ],
                [
                    'label' => 'Roles',
                    'permissions' => [
                        'role_create',
                        'role_view',
                        'role_update',
                        'role_delete',
                    ],
                ],
                [
                    'label' => 'Permissions',
                    'permissions' => ['permission_view'],
                ],
                [
                    'label' => 'User Management',
                    'permissions' => [
                        'user_create',
                        'user_view',
                        'user_update',
                        'user_delete',
                        'user_suspend',
                    ],
                ],
                [
                    'label' => 'Backup & Restore',
                    'permissions' => [
                        'backup_view',
                        'backup_create',
                        'backup_restore',
                        'backup_delete',
                    ],
                ],
                [
                    'label' => 'Audit Logs',
                    'permissions' => ['audit_log_view'],
                ],
                [
                    'label' => 'System Health',
                    'permissions' => ['system_health_view'],
                ],
                [
                    'label' => 'License',
                    'permissions' => [
                        'license_view',
                        'license_update',
                    ],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hidden on the Permissions index page (managed via Roles in sidebar)
    |--------------------------------------------------------------------------
    */
    'excluded_from_permissions_page' => [
        'role_create',
        'role_view',
        'role_update',
        'role_delete',
    ],

];
