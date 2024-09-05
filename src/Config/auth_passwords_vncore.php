<?php
return [
    'admin_password' => [
        'provider' => 'admin_provider',
        'table'    => config('vncore-config.env.VNCORE_DB_PREFIX').'admin_password_resets',
        'expire'   => 60,
    ],
];
