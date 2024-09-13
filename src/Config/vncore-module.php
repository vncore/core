<?php
return [

    // Module display in left header admin
    'module_header_left' => [
        ['view' => 'vncore-admin::component.language', 'sort' => 100], // path to view
        ['view' => 'vncore-admin::component.admin_theme', 'sort' => 200], // path to view
    ],

    // Module display in right header admin
    'module_header_right' => [
        ['view' => 'vncore-admin::component.notice', 'sort' => 100], // path to view
        ['view' => 'vncore-admin::component.admin_profile', 'sort' => 200], // path to view
    ],

    //List block to homepage
    'homepage' => [
        ['view' => 'vncore-admin::component.home_default', 'sort' => 100], // path to view
        ['view' => 'vncore-admin::component.home_footer', 'sort' => 200], // path to view
    ],
];