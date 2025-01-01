<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminConfig;

class AdminPasswordPolicyController extends RootAdminController
{

    public function __construct()
    {
        parent::__construct();
        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
    }

    public function index()
    {
        $id = VNCORE_ID_GLOBAL;
        $data = [
            'title' => vncore_language_render('admin.menu_titles.password_policy'),
            'subTitle' => '',
        ];

        // Customer config
        $dataPassswordPolicy = [
            'code' => 'password_policy',
            'storeId' => $id,
            'keyBy' => 'key',
            'sort' => 'asc'
        ];
        $passwordPolicy = AdminConfig::getListConfigByCode($dataPassswordPolicy);
        //End email
        $data['passwordPolicy']                = $passwordPolicy;
        $data['storeId']                        = $id;
        $data['urlUpdateConfigGlobal']          = vncore_route_admin('admin_config_global.update');

        return view('vncore-admin::screen.password_policy')
        ->with($data);
    }

    /*
    Update value config store
    */
    public function update()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
        } else {
            $data = request()->all();
            $name = $data['name'];
            $value = $data['value'];
            $storeId = $data['storeId'] ?? '';
            if (!$storeId) {
                return response()->json(
                    [
                    'error' => 1,
                    'field' => 'storeId',
                    'value' => $storeId,
                    'msg'   => 'Store ID can not empty!',
                    ]
                );
            }

            try {
                AdminConfig::where('key', $name)
                    ->where('store_id', $storeId)
                    ->update(['value' => $value]);
                $error = 0;
                $msg = vncore_language_render('action.update_success');
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
            return response()->json(
                [
                'error' => $error,
                'field' => $name,
                'value' => $value,
                'msg'   => $msg,
                ]
            );
        }
    }
}
