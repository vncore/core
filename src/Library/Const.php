<?php
// list ID admin guard
define('VNCORE_GUARD_ADMIN', ['1']); // admin
// list ID language guard
define('VNCORE_GUARD_LANGUAGE', ['1', '2']); // vi, en
// list ID ROLES guard
define('VNCORE_GUARD_ROLES', ['1', '2']); // admin, only view

/**
 * Admin define
 */
define('VNCORE_ADMIN_MIDDLEWARE', ['web', 'admin']);
define('VNCORE_API_MIDDLEWARE', ['api', 'api.extend']);
define('VNCORE_DB_CONNECTION', config('vncore-config.env.VNCORE_DB_CONNECTION'));
//Prefix url admin
define('VNCORE_ADMIN_PREFIX', config('vncore-config.env.VNCORE_ADMIN_PREFIX'));
//Prefix database
define('VNCORE_DB_PREFIX', config('vncore-config.env.VNCORE_DB_PREFIX'));
//Vncore active
define('VNCORE_ACTIVE', config('vncore-config.env.VNCORE_ACTIVE'));
// Root ID store
define('VNCORE_ID_ROOT', 1);
define('VNCORE_ID_GLOBAL', 0);
define('VNCORE_SYSTEM', 'SYSTEM');
