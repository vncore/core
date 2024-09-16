<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminLanguage;
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\Admin\Models\AdminPage;

class AdminStoreConfigController extends RootAdminController
{
    public $templates;
    public $languages;
    public $timezones;

    public function __construct()
    {
        parent::__construct();
        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
        $this->templates = vncore_extension_get_installed(type: 'Template', active: true);
        $this->languages = AdminLanguage::getListActive();
        $this->timezones = $timezones;
    }

    public function index()
    {
        $id = session('adminStoreId');
        $data = [
            'title' => vncore_language_render('admin.menu_titles.config_store_default'),
            'subTitle' => '',
        ];

        // Customer config
        $dataCustomerConfig = [
            'code' => 'customer_config_attribute',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $customerConfigs = AdminConfig::getListConfigByCode($dataCustomerConfig);
        
        $dataCustomerConfigRequired = [
            'code' => 'customer_config_attribute_required',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $customerConfigsRequired = AdminConfig::getListConfigByCode($dataCustomerConfigRequired);
        //End customer

        $productConfigQuery = [
            'code' => 'product_config',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $productConfig = AdminConfig::getListConfigByCode($productConfigQuery);

        $productConfigAttributeQuery = [
            'code' => 'product_config_attribute',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $productConfigAttribute = AdminConfig::getListConfigByCode($productConfigAttributeQuery);

        $productConfigAttributeRequiredQuery = [
            'code' => 'product_config_attribute_required',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $productConfigAttributeRequired = AdminConfig::getListConfigByCode($productConfigAttributeRequiredQuery);

        $orderConfigQuery = [
            'code' => 'order_config',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $orderConfig = AdminConfig::getListConfigByCode($orderConfigQuery);

        $configDisplayQuery = [
            'code' => 'display_config',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $configDisplay = AdminConfig::getListConfigByCode($configDisplayQuery);

        $configCaptchaQuery = [
            'code' => 'captcha_config',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $configCaptcha = AdminConfig::getListConfigByCode($configCaptchaQuery);

        $configCustomizeQuery = [
            'code' => 'admin_custom_config',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $configCustomize = AdminConfig::getListConfigByCode($configCustomizeQuery);

        
        $configLayoutQuery = [
            'code' => 'config_layout',
            'storeId' => $id,
            'keyBy' => 'key',
        ];
        $configLayout = AdminConfig::getListConfigByCode($configLayoutQuery);

        $emailConfigQuery = [
            'code' => ['smtp_config', 'email_action'],
            'storeId' => $id,
            'groupBy' => 'code',
            'sort'    => 'asc',
        ];
        $emailConfig = AdminConfig::getListConfigByCode($emailConfigQuery);

        $data['emailConfig'] = $emailConfig;
        $data['smtp_method'] = ['' => 'None Secirity', 'TLS' => 'TLS', 'SSL' => 'SSL'];
        $data['captcha_page'] = [
            'register' => vncore_language_render('admin.captcha.captcha_page_register'),
            'forgot'   => vncore_language_render('admin.captcha.captcha_page_forgot_password'),
            'checkout' => vncore_language_render('admin.captcha.captcha_page_checkout'),
            'contact'  => vncore_language_render('admin.captcha.captcha_page_contact'),
            'review'   => vncore_language_render('admin.captcha.captcha_page_review'),
        ];
        //End email
        $data['customerConfigs']                = $customerConfigs;
        $data['customerConfigsRequired']        = $customerConfigsRequired;
        $data['productConfig']                  = $productConfig;
        $data['productConfigAttribute']         = $productConfigAttribute;
        $data['productConfigAttributeRequired'] = $productConfigAttributeRequired;
        $data['configLayout']                   = $configLayout;
        $data['pluginCaptchaInstalled']         = vncore_captcha_get_plugin_installed();
        $data['configDisplay']                  = $configDisplay;
        $data['orderConfig']                    = $orderConfig;
        $data['configCaptcha']                  = $configCaptcha;
        $data['configCustomize']                = $configCustomize;
        $data['templates']                      = $this->templates;
        $data['timezones']                      = $this->timezones;
        $data['languages']                      = $this->languages;
        $data['storeId']                        = $id;
        $data['urlUpdateConfig']                = vncore_route_admin('admin_config.update');
        $data['urlUpdateConfigGlobal']          = vncore_route_admin('admin_config_global.update');

        return view($this->vncore_templatePathAdmin.'screen.config_store_default')
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

    /**
     * Add new config admin
     *
     * @return  [type]  [return description]
     */
    public function addNew() {
        $data = request()->all();
        $key = $data['key'] ?? '';
        $value = $data['value'] ?? '';
        $detail = $data['detail'] ?? '';
        $storeId = $data['storeId'] ?? '';

        if (session('adminStoreId') != VNCORE_ID_ROOT && $storeId != session('adminStoreId')) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.remove_dont_permisison') . ': storeId#' . $storeId]);
        }

        if (!$key) {
            return redirect()->back()->with('error', 'Key: '.vncore_language_render('admin.not_empty'));
        }
        $group = $data['group'] ?? 'admin_custom_config';
        $dataUpdate = ['key' => $key, 'value' => $value, 'code' => $group, 'store_id' => $storeId, 'detail' => $detail];
        if (AdminConfig::where(['key' => $key, 'store_id' => $storeId])->first()) {
            return redirect()->back()->with('error', vncore_language_quickly('admin.admin_custom_config.key_exist', 'Key already exist'));
        }
        $dataUpdate = vncore_clean($dataUpdate, [], true);
        AdminConfig::insert($dataUpdate);
        return redirect()->back()->with('success', vncore_language_render('action.update_success'));
    }

    /**
     * Remove config
     *
     * @return  [type]  [return description]
     */
    public function delete() {
        $key = request('key');
        $storeId = request('storeId');

        if (session('adminStoreId') != VNCORE_ID_ROOT && $storeId != session('adminStoreId')) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.remove_dont_permisison') . ': storeId#' . $storeId]);
        }
        AdminConfig::where('key', $key)->where('store_id', $storeId)->delete();
        return response()->json(['error' => 0, 'msg' => vncore_language_render('action.update_success')]);
    }
}
