<?php
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\Admin\Models\AdminHome;
use Illuminate\Support\Facades\Artisan;

if (!function_exists('vncore_extension_get_all_local') && !in_array('vncore_extension_get_all_local', config('vncore_functions_except', []))) {
    /**
     * Get all extension local
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function vncore_extension_get_all_local($type = 'Plugin')
    {
        if ($type == 'Template') {
            $typeTmp = 'Templates';
        } else {
            $typeTmp = 'Plugins';
        }
        $arrClass = [];
        $dirs = array_filter(glob(app_path() . '/Vncore/'.$typeTmp.'/*'), 'is_dir');
        if ($dirs) {
            foreach ($dirs as $dir) {
                $tmp = explode('/', $dir);
                $nameSpace = '\App\Vncore\\' . $typeTmp.'\\'.end($tmp);
                if (file_exists($dir . '/AppConfig.php')) {
                    $arrClass[end($tmp)] = $nameSpace;
                }
            }
        }
        return $arrClass;
    }
}

if (!function_exists('vncore_extension_get_installed') && !in_array('vncore_extension_get_installed', config('vncore_functions_except', []))) {
    /**
     * Get all class plugin
     *
     *
     */
    function vncore_extension_get_installed($type = "Plugin", $active = true)
    {
        switch ($type) {
            case 'Template':
                return \Vncore\Core\Admin\Models\AdminConfig::getTemplateCode($active);
                break;
            default:
            return \Vncore\Core\Admin\Models\AdminConfig::getPluginCode($active);
                break;
        }
    }
}


    /**
     * Get namespace extension config
     *
     *
     * @return  [array]
     */
    if (!function_exists('vncore_extension_get_class_config') && !in_array('vncore_extension_get_class_config', config('vncore_functions_except', []))) {
        function vncore_extension_get_class_config(string $type="Plugin", string $key = "")
        {
            $key = vncore_word_format_class($key);

            $nameSpace = vncore_extension_get_namespace(type: $type, key:$key);
            $nameSpace = $nameSpace . '\AppConfig';

            return $nameSpace;
        }
    }

    /**
     * Get namespace module
     *
     * @param   [string]  $code  Block, Cms, Payment, shipping..
     * @param   [string]  $key  Content,Paypal, Cash..
     *
     * @return  [array]
     */
    if (!function_exists('vncore_extension_get_namespace') && !in_array('vncore_extension_get_namespace', config('vncore_functions_except', []))) {
        function vncore_extension_get_namespace(string $type="Plugin", string $key = "")
        {
            $key = vncore_word_format_class($key);
            switch ($type) {
                case 'Template':
                    $nameSpace = '\App\Vncore\Templates\\' . $key;
                    break;
                default:
                    $nameSpace = '\App\Vncore\Plugins\\' . $key;
                    break;
            }
            return $nameSpace;
        }
    }

    /**
     * Check plugin and template compatibility with Vncore version
     *
     * @param   string  $versionsConfig  [$versionsConfig description]
     *
     * @return  [type]                   [return description]
     */
    if (!function_exists('vncore_extension_check_compatibility') && !in_array('vncore_extension_check_compatibility', config('vncore_functions_except', []))) {
        function vncore_extension_check_compatibility(string $versionsConfig) {
            $arrVersionVncore = explode('|', $versionsConfig);
            return in_array(config('vncore.core'), $arrVersionVncore);
        }
    }

    
if (!function_exists('vncore_extension_check_active') && !in_array('vncore_extension_check_active', config('vncore_functions_except', []))) {

    // Check extension is active
    function vncore_extension_check_active($group, $key)
    {
        $checkConfig = AdminConfig::where('store_id', VNCORE_ID_GLOBAL)
        ->where('key', $key)
        ->where('group', $group)
        ->where('value', 1)
        ->first();

        if ($checkConfig) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('vncore_extension_check_installed') && !in_array('vncore_extension_check_installed', config('vncore_functions_except', []))) {

    // Check extension is installed
    function vncore_extension_check_installed($group, $key)
    {
        $checkConfig = AdminConfig::where('store_id', VNCORE_ID_GLOBAL)
        ->where('key', $key)
        ->where('group', $group)
        ->first();

        if ($checkConfig) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('vncore_extension_update') && !in_array('vncore_extension_update', config('vncore_functions_except', []))) {

    // Process when extension update
    function vncore_extension_update()
    {
        try {
            // Check if file cache exist then clear cache and create new cache
            if(file_exists(base_path('bootstrap/cache/routes-v7.php'))) {
                Artisan::call('route:clear');
                Artisan::call('route:cache');
            }
    
            // Check if file cache exist then clear cache and create new cache
            if(file_exists(base_path('bootstrap/cache/config.php'))) {
                Artisan::call('config:clear');
                Artisan::call('config:cache');
            }
        } catch (\Throwable $e) {
            vncore_report($e->getMessage());
        }


    }
}