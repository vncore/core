<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminConfig;

class AdminConfigGlobalController extends RootAdminController
{
    public $templates;
    public $languages;
    public $timezones;

    public function __construct()
    {
        parent::__construct();
    }

    public function webhook()
    {
        $data = [
            'title' => vncore_language_render('admin.config.webhook'),
            'subTitle' => '',
        ];
        return view('vncore-admin::screen.webhook')
            ->with($data);
    }

    /**
     * Update config global
     *
     * @return  [type]  [return description]
     */
    public function update()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
        } else {
            $data = request()->all();
            $name = $data['name'];
            $value = $data['value'];
            try {
                AdminConfig::where('key', $name)
                    ->where('store_id', VNCORE_ID_GLOBAL)
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
