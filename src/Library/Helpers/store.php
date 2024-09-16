<?php
use Illuminate\Support\Str;
use Vncore\Core\Admin\Models\AdminStore;

/**
 * Get list store
 */
if (!function_exists('vncore_store_get_list_code') && !in_array('vncore_store_get_list_code', config('vncore_functions_except', []))) {
    function vncore_store_get_list_code()
    {
        return \Vncore\Core\Admin\Models\AdminStore::getListStoreCode();
    }
}


/**
 * Get domain from code
 */
if (!function_exists('vncore_store_get_domain_from_code') && !in_array('vncore_store_get_domain_from_code', config('vncore_functions_except', []))) {
    function vncore_store_get_domain_from_code(string $code = ""):string
    {
        $domainList = \Vncore\Core\Admin\Models\AdminStore::getStoreDomainByCode();
        if (!empty($domainList[$code])) {
            return 'http://'.$domainList[$code];
        } else {
            return url('/');
        }
    }
}

/**
 * Get domain root
 */
if (!function_exists('vncore_store_get_domain_root') && !in_array('vncore_store_get_domain_root', config('vncore_functions_except', []))) {
    function vncore_store_get_domain_root():string
    {
        $store = \Vncore\Core\Admin\Models\AdminStore::find(VNCORE_ID_ROOT);
        return $store->domain;
    }
}

/**
 * Check store is partner
 */
if (!function_exists('vncore_store_is_partner') && !in_array('vncore_store_is_partner', config('vncore_functions_except', []))) {
    function vncore_store_is_partner(string $storeId):bool
    {
        $store = \Vncore\Core\Admin\Models\AdminStore::find($storeId);
        if (!$store) {
            return false;
        }
        return $store->partner || $storeId == VNCORE_ID_ROOT;
    }
}

/**
 * Check store is root
 */
if (!function_exists('vncore_store_is_root') && !in_array('vncore_store_is_root', config('vncore_functions_except', []))) {
    function vncore_store_is_root(string $storeId):bool
    {
        return  $storeId == VNCORE_ID_ROOT;
    }
}

if (!function_exists('vncore_store_process_domain') && !in_array('vncore_store_process_domain', config('vncore_functions_except', []))) {
    /**
     * Process domain store
     *
     * @param   [string]  $domain
     *
     * @return  [string]         [$domain]
     */
    function vncore_store_process_domain(string $domain = "")
    {
        $domain = str_replace(['http://', 'https://'], '', $domain);
        $domain = Str::lower($domain);
        $domain = rtrim($domain, '/');
        return $domain;
    }
}

if (!function_exists('vncore_store_check_multi_domain_installed') && !in_array('vncore_store_check_multi_domain_installed', config('vncore_functions_except', []))) {
/**
 * Check plugin multi domain installed
 *
 * @return
 */
    function vncore_store_check_multi_domain_installed()
    {
        return 
        vncore_config_global('MultiVendorPro') 
        || vncore_config_global('MultiVendor') 
        || vncore_config_global('MultiStorePro')
        || vncore_config_global('Pmo');
    }
}

if (!function_exists('vncore_store_check_multi_partner_installed') && !in_array('vncore_store_check_multi_partner_installed', config('vncore_functions_except', []))) {
    /**
     * Check plugin multi vendor installed
     *
     * @return
     */
        function vncore_store_check_multi_partner_installed()
        {
            return 
            vncore_config_global('MultiVendorPro') 
            || vncore_config_global('MultiVendor')
            || vncore_config_global('Pmo');
        }
}

if (!function_exists('vncore_store_check_multi_store_installed') && !in_array('vncore_store_check_multi_store_installed', config('vncore_functions_except', []))) {
    /**
     * Check plugin multi store installed
     *
     * @return
     */
        function vncore_store_check_multi_store_installed()
        {
            return vncore_config_global('MultiStorePro');
        }
}

if (!function_exists('vncore_store_get_list_active') && !in_array('vncore_store_get_list_active', config('vncore_functions_except', []))) {
    function vncore_store_get_list_active($field = null)
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


if (!function_exists('vncore_store_info') && !in_array('vncore_store_info', config('vncore_functions_except', []))) {
    /**
     * Get info store_id, table admin_store
     *
     * @param   [string] $key      [$key description]
     * @param   [null|int]  $store_id    store id
     *
     * @return  [mix]
     */
    function vncore_store_info($key = null, $default = null, $store_id = null)
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

