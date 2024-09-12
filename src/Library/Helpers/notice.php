<?php
if (!function_exists('vncore_notice_add')) {
    /**
     * [vncore_notice_add description]
     *
     * @param   string  $type    [$type description]
     * @param   string  $typeId  [$typeId description]
     *
     * @return  [type]           [return description]
     */
    function vncore_notice_add(string $type, string $typeId = '', $content = '')
    {
        $modelNotice = new Vncore\Core\Admin\Models\AdminNotice;
        $listAdmin = vncore_notice_get_admin($type);
        switch ($type) {
            case 'Plugin':
            case 'Template':
            case 'Admin':
                $admin_created = admin()->user()->id;
                break;
            default:
                $admin_created = '';
                break;
        }
        if (count($listAdmin)) {
            foreach ($listAdmin as $key => $admin) {
                $modelNotice->create(
                    [
                        'type' => $type,
                        'type_id' => $typeId,
                        'admin_id' => $admin,
                        'admin_created' => $admin_created,
                        'content' => $content
                    ]
                );
            }
        }

    }

    /**
     * Get list id admin can get notice
     */
    if (!function_exists('vncore_notice_get_admin')) {
        function vncore_notice_get_admin(string $type = "")
        {
            if (function_exists('vncore_notice_pro_get_admin')) {
                return vncore_notice_pro_get_admin($type);
            }

            return (new \Vncore\Core\Admin\Models\AdminUser)
            ->selectRaw('distinct '. VNCORE_DB_PREFIX.'admin_user.id')
            ->join(VNCORE_DB_PREFIX . 'admin_role_user', VNCORE_DB_PREFIX . 'admin_role_user.user_id', VNCORE_DB_PREFIX . 'admin_user.id')
            ->join(VNCORE_DB_PREFIX . 'admin_role', VNCORE_DB_PREFIX . 'admin_role.id', VNCORE_DB_PREFIX . 'admin_role_user.role_id')
            ->whereIn(VNCORE_DB_PREFIX . 'admin_role.slug', ['administrator','view.all', 'manager'])
            ->pluck('id')
            ->toArray();
        }
    }

}
