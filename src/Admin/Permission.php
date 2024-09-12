<?php

namespace Vncore\Core\Admin;

class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     *
     * @return true
     */
    public static function check($permission)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                call_user_func([Permission::class, 'check'], $permission);
            });

            return;
        }

        if (admin()->user()->cannot($permission)) {
            return static::error();
        }
    }

    /**
     * If current user is administrator.
     *
     * @return mixed
     */
    public static function isAdministrator()
    {
        return admin()->user()->isRole('administrator');
    }

    public static function error()
    {
        $uriCurrent = request()->fullUrl();
        $methodCurrent = request()->method();
        if (strtoupper($methodCurrent) === 'GET') {
            return redirect()->route('admin.deny')->with(['method' => $methodCurrent, 'url' => $uriCurrent]);
        } else {
            return response()->json([
                'error' => '1',
                'msg' => vncore_language_render('admin.access_denied'),
                'detail' => [
                    'method' => $methodCurrent,
                    'url' => $uriCurrent
                    ]
            ]);
        }
    }

    public static function listPathDefaultPassThrough() {
        $exceptsPAth = [
            VNCORE_ADMIN_PREFIX . '/auth/login',
            VNCORE_ADMIN_PREFIX . '/auth/logout',
        ];
        // Add partner pass through
        if (function_exists('partner_path_default_pass')) {
            $exceptsPAth = array_merge($exceptsPAth, partner_path_default_pass());
        }
        return $exceptsPAth;
    }

    public static function listRouteDefaultPassThrough() {
        $allowRoute = [
            //Page default
            'admin.deny', 
            'admin.deny_single', 
            'admin.locale', 
            'admin.data_not_found',
            'admin.default',
            // User update profile
            'admin.setting',
            'admin.post_setting',
            //Get list file
            'unisharp.lfm.show',
            'unisharp.lfm.getItems',
            'unisharp.lfm.getFolders',
        ];
        // Add partner pass through
        if (function_exists('partner_route_default_pass')) {
            $allowRoute = array_merge($allowRoute, partner_route_default_pass());
        }
        return $allowRoute;
    }
}
