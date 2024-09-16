<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminStore;
use Vncore\Core\Admin\Models\AdminLanguage;

class AdminStoreInfoController extends RootAdminController
{
    public $templates;
    public $languages;

    public function __construct()
    {
        parent::__construct();
        $this->templates = vncore_extension_get_installed(type: 'Template', active: true);
        $this->languages = AdminLanguage::getListActive();
    }

    /*
    Update value config
    */
    public function updateInfo()
    {
        $data      = request()->all();
        $data = vncore_clean($data, [], true);
        $storeId   = $data['storeId'];
        $fieldName = $data['name'];
        $value     = $data['value'];
        $parseName = explode('__', $fieldName);
        $name      = $parseName[0];
        $lang      = $parseName[1] ?? '';
        $msg       = 'Update success';
        // Check store
        $store     = AdminStore::find($storeId);
        if (!$store) {
            return response()->json(['error' => 1, 'msg' => 'Store not found!']);
        }

        if (!$lang) {
            try {
                if ($name == 'type') {
                    // Can not change type in here
                    $error = 1;
                    $msg = vncore_language_render('store.admin.value_cannot_change');
                } elseif ($name == 'domain') {
                    if (
                        $storeId == VNCORE_ID_ROOT 
                        || ((vncore_store_check_multi_partner_installed()) && vncore_store_is_partner($storeId)) 
                        || vncore_store_check_multi_store_installed()
                    ) {
                        // Only store root can edit domain
                        $domain = vncore_store_process_domain($value);
                        if (AdminStore::where('domain', $domain)->where('id', '<>', $storeId)->first()) {
                            $error = 1;
                            $msg = vncore_language_render('store.admin.domain_exist');
                        } else {
                            AdminStore::where('id', $storeId)->update([$name => $domain]);
                            $error = 0;
                        }
                    } else {
                        $error = 1;
                        $msg = vncore_language_render('store.admin.value_cannot_change');
                    }
                } elseif ($name == 'code') {
                    if (AdminStore::where('code', $value)->where('id', '<>', $storeId)->first()) {
                        $error = 1;
                        $msg = vncore_language_render('store.admin.code_exist');
                    } else {
                        AdminStore::where('id', $storeId)->update([$name => $value]);
                        $error = 0;
                    }
                } elseif ($name == 'template') {
                    
                    $store = AdminStore::where('id', $storeId)->first();
                    $templateKey = $value;
                    $oldTepmlateKey = $store->template;
                    
                    $classTemplate = vncore_extension_get_class_config(type:'Template', key:$templateKey);
                    $oldClassTemplate = vncore_extension_get_class_config(type:'Template', key:$oldTepmlateKey);

                    (new $oldClassTemplate)->removeStore($storeId);
                    (new $classTemplate)->setupStore($storeId);

                    AdminStore::where('id', $storeId)->update([$name => $templateKey]);

                    vncore_notice_add(type:'template', typeId: $templateKey, content:'admin_notice.vncore_template_change');
                    vncore_extension_update();

                    $error = 0;
                } else {
                    AdminStore::where('id', $storeId)->update([$name => $value]);
                    $error = 0;
                }
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
        } else {
            // Process description
            $dataUpdate = [
                'storeId' => $storeId,
                'lang' => $lang,
                'name' => $name,
                'value' => $value,
            ];
            $dataUpdate = vncore_clean($dataUpdate, [], true);
            try {
                AdminStore::updateDescription($dataUpdate);
                $error = 0;
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
        }
        return response()->json(['error' => $error, 'msg' => $msg]);
    }

    public function index()
    {
        $id = session('adminStoreId');
        $store = AdminStore::find($id);
        if (!$store) {
            $data = [
                'title' => vncore_language_render('store.admin.title'),
                'subTitle' => '',
                'dataNotFound' => 1
            ];
            return view($this->vncore_templatePathAdmin.'screen.store_info')
            ->with($data);
        }
        $data = [
            'title' => vncore_language_render('store.admin.title'),
            'subTitle' => '',
        ];
        $data['store'] = $store;
        $data['templates'] = $this->templates;
        $data['languages'] = $this->languages;
        $data['storeId'] = $id;

        return view($this->vncore_templatePathAdmin.'screen.store_info')
        ->with($data);
    }
}
