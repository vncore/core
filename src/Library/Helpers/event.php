<?php
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
        if (function_exists('partner_event_admin_login')) {
            partner_event_admin_login($user);
        }
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
        if (function_exists('partner_event_admin_add')) {
            partner_event_admin_add($user);
        }
        vncore_notice_add(type: 'Admin', typeId: $user->id, content:'admin_notice.vncore_new_admin_add::name__'.$user->name);
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
        if (function_exists('partner_event_admin_delete')) {
            partner_event_admin_delete($user);
        }
        vncore_notice_add(type: 'Admin', typeId: $user->id, content:'admin_notice.vncore_new_admin_delete::name__'.$user->name);
    }
}