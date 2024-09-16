<?php
return [
    'admin' => [
        //Enable, disable page libary online
        'api_plugin'      => env('VNCORE_ADMIN_API_PLUGIN', 1),
        'api_template'    => env('VNCORE_ADMIN_API_TEMPLATE', 1),

        //Prefix path view admin
        'path_view'           => 'vncore-admin',

        //Config log access admin
        'admin_log_except'    => env('VNCORE_ADMIN_LOG_EXCEPT', ['password','password_confirmation']), //Except log
        'admin_log'           => env('VNCORE_ADMIN_LOG', 1), //Log access admin

        'forgot_password'     => env('VNCORE_ADMIN_FORGOT_PASSWORD', 1), //Enable feature forgot password

        'schema_customize' => [
            // List of tables that can be customized add new fields
            // Format is table_name => table_label
            // Example: 'shop_product' => 'Product'
        ],

        //Config for extension
        'extension' => [
            'extension_protected' => [
                'Plugins' => explode(',', env('VNCORE_PROTECTED_PLUGINS', '')), // 'Plugin1','Plugin2'
                'Templates' => explode(',', env('VNCORE_PROTECTED_TEMPLATES', '')), // 'Template1','Template2'
            ],
        
        ],

        'middleware'  => [
            1        => 'admin.auth',
            2        => 'admin.permission',
            3        => 'admin.pjax',
            4        => 'admin.log',
            5        => 'admin.storeId',
            6        => 'localization',
            7        => 'admin.session',
        ],

    ],

    //Config for api
    'api' => [
        'auth' => [
            'api_remmember' => env('VNCORE_API_RECOMMEMBER', 30), //days - expires_at
            'api_token_expire_default' => env('VNCORE_API_TOKEN_EXPIRE_DEFAULT', 7), //days - expires_at default
            'api_remmember_admin' => env('VNCORE_API_RECOMMEMBER_ADMIN', 30), //days - expires_at
            'api_token_expire_admin_default' => env('VNCORE_API_TOKEN_EXPIRE_ADMIN_DEFAULT', 7), //days - expires_at default
            'api_scope_type' => env('VNCORE_API_SCOPE_TYPE', 'ability'), //ability|abilities
            'api_scope_type_admin' => env('VNCORE_API_SCOPE_TYPE_ADMIN', 'ability'), //ability|abilities
            'api_scope_user' => env('VNCORE_API_SCOPE_USER', 'user'), //string, separated by commas
            'api_scope_user_guest' => env('VNCORE_API_SCOPE_USER_GUEST', 'user-guest'), //string, separated by commas
            'api_scope_admin' => env('VNCORE_API_SCOPE_ADMIN', 'admin-supper'),//string, separated by commas
        ],
        'middleware' => [
            1        => 'json.response',
            2        => 'api.connection',
            3        => 'throttle: 1000',
        ],
    ],

    'env' => [
        'VNCORE_ACTIVE'        => env('VNCORE_ACTIVE', 1), // 1: active, 0: deactive - prevent load vencore package
        'VNCORE_LIBRARY_API'   => env('VNCORE_LIBRARY_API', 'https://api.vncore.net/v1'),
        'VNCORE_API_MODE'      => env('VNCORE_API_MODE', 1), // 1: active, 0: deactive - prevent provide api service, as your-domain/api/service...
        'VNCORE_DB_PREFIX'     => env('VNCORE_DB_PREFIX', 'vncore_'), //Cannot change after install vncore
        'VNCORE_DB_CONNECTION' => env('VNCORE_DB_CONNECTION', env('DB_CONNECTION', 'mysql')), 
        'VNCORE_ADMIN_PREFIX'  => env('VNCORE_ADMIN_PREFIX', 'vncore_admin'), //Prefix url admin, ex: domain.com/vncore_admin
    ]

];
