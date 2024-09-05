<?php
return [
    'admin' => [
        'driver'   => 'session',
        'provider' => 'admin_provider',
    ],
    'api' => [
        'driver'   => 'sanctum',
        'provider' => 'users',
    ],
    'admin-api' => [
        'driver'   => 'sanctum',
        'provider' => 'admin_provider',
    ],
];
