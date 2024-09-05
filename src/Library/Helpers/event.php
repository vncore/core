<?php
use \Vncore\Core\Admin\Events\EventAdminLogin;
use \Vncore\Core\Admin\Events\EventAdminCreated;
use \Vncore\Core\Admin\Events\EventAdminDeleting;
if (!function_exists('vncore_event_admin_login') && !in_array('vncore_event_admin_login', config('vncore_functions_except', []))) {
    /**
     * [vncore_event_admin_login description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_event_admin_login(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        EventAdminLogin::dispatch($user);
    }
}
if (!function_exists('vncore_event_admin_created') && !in_array('vncore_event_admin_created', config('vncore_functions_except', []))) {
    /**
     * [vncore_event_admin_created description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_event_admin_created(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        EventAdminCreated::dispatch($user);
    }
}
if (!function_exists('vncore_event_admin_deleting') && !in_array('vncore_event_admin_deleting', config('vncore_functions_except', []))) {
    /**
     * [vncore_event_admin_deleting description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_event_admin_deleting(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        EventAdminDeleting::dispatch($user);
    }
}