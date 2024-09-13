<?php
if (!function_exists('vncore_notice_add')) {
    /**
     * [vncore_notice_add description]
     *
     * @param   string  $type     [$type description]
     * @param   string  $typeId   [$typeId description]
     * @param   string  $content  [$content description]
     * @param   [type]  $adminId  [$adminId description]
     * @param   [type]  $creator  [$creator description]
     *
     * @return  [type]            [return description]
     */
    function vncore_notice_add(string $type, string $typeId = '', string $content = '', $adminId = null, $creator = null)
    {
        $modelNotice = new Vncore\Core\Admin\Models\AdminNotice;
        if ($adminId) {
            $listAdmin = is_array($adminId)? $adminId: [$adminId];
        } else {
            $listAdmin = vncore_notice_get_admin($type);
        }
        if ($creator) {
            $admin_created = $creator;
        } else {
            $admin_created = admin()->user()->id;
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
            if (function_exists('vncore_notice_custom_get_admin')) {
                return vncore_notice_custom_get_admin($type);
            }

            return (new \Vncore\Core\Admin\Models\AdminUser)
            ->selectRaw('distinct '. VNCORE_DB_PREFIX.'admin_user.id')
            ->join(VNCORE_DB_PREFIX . 'admin_role_user', VNCORE_DB_PREFIX . 'admin_role_user.user_id', VNCORE_DB_PREFIX . 'admin_user.id')
            ->join(VNCORE_DB_PREFIX . 'admin_role', VNCORE_DB_PREFIX . 'admin_role.id', VNCORE_DB_PREFIX . 'admin_role_user.role_id')
            ->whereIn(VNCORE_DB_PREFIX . 'admin_role.slug', ['administrator','view.all'])
            ->pluck('id')
            ->toArray();
        }
    }

}
