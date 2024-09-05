<?php
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\Admin\Models\AdminStore;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;

if (!function_exists('vncore_admin_can_config')) {
    /**
     * Check user can change config value
     *
     * @return  [type]          [return description]
     */
    function vncore_admin_can_config()
    {
        return auth('admin')->user()->checkPermissionConfig();
    }
}

if (!function_exists('vncore_config') && !in_array('vncore_config', config('vncore_functions_except', []))) {
    /**
     * Get value config from table vncore_config
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array]  $key      [$key description]
     * @param   [int|null]  $storeId  [$storeId description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]            [return description]
     */
    function vncore_config($key = "", $storeId = null, $default = null)
    {
        $storeId = ($storeId === null) ? config('app.storeId') : $storeId;
        if (!is_string($key)) {
            return;
        }

        $allConfig = AdminConfig::getAllConfigOfStore($storeId);

        if ($key === "") {
            return $allConfig;
        }
        return array_key_exists($key, $allConfig) ? $allConfig[$key] : 
            (array_key_exists($key, vncore_config_global()) ? vncore_config_global()[$key] : $default);
    }
}


if (!function_exists('vncore_config_admin') && !in_array('vncore_config_admin', config('vncore_functions_except', []))) {
    /**
     * Get config value in adin with session store id
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [type]$key  [$key description]
     * @param   null        [ description]
     *
     * @return  [type]      [return description]
     */
    function vncore_config_admin($key = "", $default = null)
    {
        return vncore_config($key, session('adminStoreId'), $default);
    }
}


if (!function_exists('vncore_config_global') && !in_array('vncore_config_global', config('vncore_functions_except', []))) {
    /**
     * Get value config from table vncore_config for store_id 0
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array] $key      [$key description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]          [return description]
     */
    function vncore_config_global($key = "", $default = null)
    {
        if (!is_string($key)) {
            return;
        }
        $allConfig = [];
        try {
            $allConfig = AdminConfig::getAllGlobal();
        } catch (\Throwable $e) {
            //
        }
        if ($key === "") {
            return $allConfig;
        }
        if (!array_key_exists($key, $allConfig)) {
            return $default;
        } else {
            return trim($allConfig[$key]);
        }
    }
}

if (!function_exists('vncore_config_group') && !in_array('vncore_config_group', config('vncore_functions_except', []))) {
    /*
    Group Config info
     */
    function vncore_config_group($group = null, $suffix = null)
    {
        $groupData = AdminConfig::getGroup($group, $suffix);
        return $groupData;
    }
}


if (!function_exists('vncore_store') && !in_array('vncore_store', config('vncore_functions_except', []))) {
    /**
     * Get info store_id, table admin_store
     *
     * @param   [string] $key      [$key description]
     * @param   [null|int]  $store_id    store id
     *
     * @return  [mix]
     */
    function vncore_store($key = null, $store_id = null, $default = null)
    {
        $store_id = ($store_id == null) ? config('app.storeId') : $store_id;

        //Update store info
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return AdminStore::where('id', $store_id)->update([$k => $v]);
                }
            } else {
                return false;
            }
        }
        //End update

        $allStoreInfo = [];
        try {
            $allStoreInfo = AdminStore::getListAll()[$store_id]->toArray() ?? [];
        } catch (\Throwable $e) {
            //
        }

        $lang = app()->getLocale();
        $descriptions = $allStoreInfo['descriptions'] ?? [];
        foreach ($descriptions as $row) {
            if ($lang == $row['lang']) {
                $allStoreInfo += $row;
            }
        }
        if ($key == null) {
            return $allStoreInfo;
        }
        return $allStoreInfo[$key] ?? $default;
    }
}

if (!function_exists('vncore_store_active') && !in_array('vncore_store_active', config('vncore_functions_except', []))) {
    function vncore_store_active($field = null)
    {
        switch ($field) {
            case 'code':
                return AdminStore::getCodeActive();
                break;

            case 'domain':
                return AdminStore::getStoreActive();
                break;

            default:
                return AdminStore::getListAllActive();
                break;
        }
    }
}



if (!function_exists('vncore_route') && !in_array('vncore_route', config('vncore_functions_except', []))) {
    /**
     * Render route
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function vncore_route($name, $param = [])
    {
        if (!config('vncore-config.route.VNCORE_SEO_LANG')) {
            $param = Arr::except($param, ['lang']);
        } else {
            $arrRouteExcludeLanguage = ['home','locale', 'banner.click'];
            if (!key_exists('lang', $param) && !in_array($name, $arrRouteExcludeLanguage)) {
                $param['lang'] = app()->getLocale();
            }
        }
        
        if (Route::has($name)) {
            try {
                $route = route($name, $param);
            } catch (\Throwable $th) {
                $route = url('#'.$name.'#'.implode(',', $param));
            }
            return $route;
        } else {
            if ($name == 'home') {
                return url('/');
            } else {
                return url('#'.$name);
            }
        }
    }
}


if (!function_exists('vncore_route_admin') && !in_array('vncore_route_admin', config('vncore_functions_except', []))) {
    /**
     * Render route admin
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function vncore_route_admin($name, $param = [])
    {
        if (Route::has($name)) {
            try {
                $route = route($name, $param);
            } catch (\Throwable $th) {
                $route = url('#'.$name.'#'.implode(',', $param));
            }
            return $route;
        } else {
            return url('#'.$name);
        }
    }
}

if (!function_exists('vncore_uuid') && !in_array('vncore_uuid', config('vncore_functions_except', []))) {
    /**
     * Generate UUID
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function vncore_uuid()
    {
        return (string)\Illuminate\Support\Str::orderedUuid();
    }
}

if (!function_exists('vncore_generate_id') && !in_array('vncore_generate_id', config('vncore_functions_except', []))) {
    /**
     * Generate ID
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_generate_id($type = null, $default = null)
    {
        switch ($type) {
            case 'shop_store':
                return 'ST-'.vncore_token(5);
                break;
            case 'admin_user':
                return 'AU-'.vncore_token(5);
                break;
            default:
                if ($default) {
                    return $default.'-'.vncore_token(8);
                } else {
                    return vncore_uuid();
                }
                break;
        }
    }
}


if (!function_exists('vncore_config_update') && !in_array('vncore_config_update', config('vncore_functions_except', []))) {

    /**
     * Update key config
     *
     * @param   array  $dataUpdate  [$dataUpdate description]
     * @param   [type] $storeId     [$storeId description]
     *
     * @return  [type]              [return description]
     */
    function vncore_config_update($dataUpdate = null, $storeId = null)
    {
        $storeId = ($storeId === null) ? config('app.storeId') : $storeId;
        //Update config
        if (is_array($dataUpdate)) {
            if (count($dataUpdate) == 1) {
                foreach ($dataUpdate as $k => $v) {
                    return AdminConfig::where('store_id', $storeId)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        }
        //End update
    }
}

if (!function_exists('vncore_config_exist') && !in_array('vncore_config_exist', config('vncore_functions_except', []))) {

    /**
     * Check key config exist
     *
     * @param   [type]  $key      [$key description]
     * @param   [type]  $storeId  [$storeId description]
     *
     * @return  [type]            [return description]
     */
    function vncore_config_exist($key = "", $storeId = null)
    {
        if(!is_string($key)) {
            return false;
        }
        $storeId = ($storeId === null) ? config('app.storeId') : $storeId;
        $checkConfig = AdminConfig::where('store_id', $storeId)->where('key', $key)->first();
        if ($checkConfig) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('vncore_config_global_update') && !in_array('vncore_config_global_update', config('vncore_functions_except', []))) {
    /**
     * [vncore_config_global_update description]
     *
     * @param   [type]  $arrayData  [$arrayData description]
     *
     * @return  []                  [return description]
     */
    function vncore_config_global_update($arrayData = [])
    {
        //Update config
        if (is_array($arrayData)) {
            if (count($arrayData) == 1) {
                foreach ($arrayData as $k => $v) {
                    return AdminConfig::where('store_id', VNCORE_ID_GLOBAL)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        } else {
            return;
        }
        //End update
    }
}


if (!function_exists('vncore_add_module') && !in_array('vncore_add_module', config('vncore_functions_except', []))) {

    
    function vncore_add_module(string $position, string $pathToView) {
        $positions = config('vncore-module.'.$position) ?? [];
        $positions[] = $pathToView;
        config(['vncore-module.'.$position => $positions]);
    }
}