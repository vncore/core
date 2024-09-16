<?php

if (!function_exists('vncore_captcha_method') && !in_array('vncore_captcha_method', config('vncore_functions_except', []))) {
    function vncore_captcha_method()
    {
        //If function captcha disable or dont setup
        if (empty(vncore_config('captcha_mode'))) {
            return null;
        }

        // If method captcha selected
        if (!empty(vncore_config('captcha_method'))) {
            $moduleClass = vncore_config('captcha_method');
            //If class plugin captcha exist
            if (class_exists($moduleClass)) {
                //Check plugin captcha disable
                $key = (new $moduleClass)->configKey;
                if (vncore_config($key)) {
                    return (new $moduleClass);
                } else {
                    return null;
                }
            }
        }
        return null;
    }
}

if (!function_exists('vncore_captcha_page') && !in_array('vncore_captcha_page', config('vncore_functions_except', []))) {
    function vncore_captcha_page():array
    {
        if (empty(vncore_config('captcha_page'))) {
            return [];
        }

        if (!empty(vncore_config('captcha_page'))) {
            return json_decode(vncore_config('captcha_page'));
        }
    }
}

if (!function_exists('vncore_captcha_get_plugin_installed') && !in_array('vncore_captcha_get_plugin_installed', config('vncore_functions_except', []))) {
    /**
     * Get all class plugin captcha installed
     *
     * @param   [string]  $code  Payment, Shipping
     *
     */
    function vncore_captcha_get_plugin_installed($onlyActive = true)
    {
        $listPluginInstalled =  \Vncore\Core\Admin\Models\AdminConfig::getPluginCaptchaCode($onlyActive);
        $arrPlugin = [];
        if ($listPluginInstalled) {
            foreach ($listPluginInstalled as $key => $plugin) {
                $keyPlugin = vncore_word_format_class($plugin->key);
                $appPath = app_path() . '/Plugins/Other/'.$keyPlugin;
                $nameSpaceConfig = '\Vncore\Plugins\Other\\'.$keyPlugin.'\AppConfig';
                if (file_exists($appPath . '/AppConfig.php') && class_exists($nameSpaceConfig)) {
                    $arrPlugin[$nameSpaceConfig] = vncore_language_render($plugin->detail);
                }
            }
        }
        return $arrPlugin;
    }
}
