<?php
use Illuminate\Support\Str;
/**
 * Get list store
 */
if (!function_exists('vncore_get_list_code_store') && !in_array('vncore_get_list_code_store', config('vncore_functions_except', []))) {
    function vncore_get_list_code_store()
    {
        return \Vncore\Core\Admin\Models\AdminStore::getListStoreCode();
    }
}


/**
 * Get domain from code
 */
if (!function_exists('vncore_get_domain_from_code') && !in_array('vncore_get_domain_from_code', config('vncore_functions_except', []))) {
    function vncore_get_domain_from_code(string $code = ""):string
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
if (!function_exists('vncore_get_domain_root') && !in_array('vncore_get_domain_root', config('vncore_functions_except', []))) {
    function vncore_get_domain_root():string
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

if (!function_exists('vncore_process_domain_store') && !in_array('vncore_process_domain_store', config('vncore_functions_except', []))) {
    /**
     * Process domain store
     *
     * @param   [string]  $domain
     *
     * @return  [string]         [$domain]
     */
    function vncore_process_domain_store(string $domain = "")
    {
        $domain = str_replace(['http://', 'https://'], '', $domain);
        $domain = Str::lower($domain);
        $domain = rtrim($domain, '/');
        return $domain;
    }
}

if (!function_exists('vncore_check_multi_domain_installed') && !in_array('vncore_check_multi_domain_installed', config('vncore_functions_except', []))) {
/**
 * Check plugin multi domain installed
 *
 * @return
 */
    function vncore_check_multi_domain_installed()
    {
        return 
        vncore_config_global('MultiVendorPro') 
        || vncore_config_global('MultiVendor') 
        || vncore_config_global('B2B') 
        || vncore_config_global('MultiStorePro')
        || vncore_config_global('MultiStore')
        || vncore_config_global('PMO');
    }
}

if (!function_exists('vncore_check_multi_vendor_installed') && !in_array('vncore_check_multi_vendor_installed', config('vncore_functions_except', []))) {
    /**
     * Check plugin multi vendor installed
     *
     * @return
     */
        function vncore_check_multi_vendor_installed()
        {
            return vncore_config_global('MultiVendorPro') || vncore_config_global('B2B') || vncore_config_global('MultiVendor');
        }
}

if (!function_exists('vncore_check_multi_store_installed') && !in_array('vncore_check_multi_store_installed', config('vncore_functions_except', []))) {
    /**
     * Check plugin multi store installed
     *
     * @return
     */
        function vncore_check_multi_store_installed()
        {
            return vncore_config_global('MultiStorePro');
        }
}

if (!function_exists('vncore_link_vendor') && !in_array('vncore_link_vendor', config('vncore_functions_except', []))) {
    /**
     * Link vendor
     *
     * @return
     */
        function vncore_link_vendor(string $code = "")
        {
            $link = vncore_route('home');
            if (vncore_config_global('MultiVendorPro')) {
                $link = vncore_route('MultiVendorPro.detail', ['code' => $code]);
            }
            if (vncore_config_global('MultiVendor')) {
                $link = vncore_route('MultiVendor.detail', ['code' => $code]);
            }
            if (vncore_config_global('B2B')) {
                $link = vncore_route('B2B.detail', ['code' => $code]);
            }
            return $link;
        }
}


if (!function_exists('vncore_path_vendor') && !in_array('vncore_path_vendor', config('vncore_functions_except', []))) {
    /**
     * Path vendor
     *
     * @return
     */
        function vncore_path_vendor()
        {
            $path = 'vendor';
            if (vncore_config_global('MultiVendorPro')) {
                $path = config('MultiVendorPro.front_path');
            }
            if (vncore_config_global('MultiVendor')) {
                $path = config('MultiVendor.front_path');
            }
            if (vncore_config_global('B2B')) {
                $path = config('B2B.front_path');
            }
            return $path;
        }
}