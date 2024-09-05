<?php
return [

    // Module display in left header admin
    'module_header_left' => [
        'vncore-admin::component.language', // path to view
        'vncore-admin::component.admin_theme', // path to view
    ],

    // Module display in right header admin
    'module_header_right' => [
        'vncore-admin::component.notice',
        'vncore-admin::component.admin_profile',
    ],

    //List block to homepage
    'homepage' => [
        'vncore-admin::component.home_default',
        'vncore-admin::component.home_footer',
    ],
];