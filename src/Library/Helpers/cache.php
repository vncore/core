<?php

use \Illuminate\Support\Facades\Cache;

if (!function_exists('vncore_cache_clear') && !in_array('vncore_cache_clear', config('vncore_functions_except', []))) {
    /**
     * Clear cache
     *
     * @param   [string]  $domain
     *
     * @return  [string]         [$domain]
     */
    function vncore_cache_clear($typeCache = 'cache_all', $storeId = null)
    {
        try {
            $storeI = $storeId ?? session('adminStoreId');
            if ($typeCache == 'cache_all') {
                Cache::flush();
            } else {
                Cache::forget($typeCache);
            }
            $response = ['error' => 0, 'msg' => 'Clear success!', 'action' => $typeCache];
        } catch (\Throwable $e) {
            $response = ['error' => 1, 'msg' => $e->getMessage(), 'action' => $typeCache];
        }
        return $response;
    }
}

if (!function_exists('vncore_cache_set') && !in_array('vncore_cache_set', config('vncore_functions_except', []))) {
    /**
     * [vncore_cache_set description]
     *
     * @param   [string]$cacheIndex  [$cacheIndex description]
     * @param   [type]$value       [$value description]
     * @param   [seconds]$time        [$time description]
     * @param   null               [ description]
     *
     * @return  [type]             [return description]
     */
    function vncore_cache_set($cacheIndex, $value, $time = null)
    {
        if (empty($cacheIndex)) {
            return ;
        }
        $seconds = $time ?? (vncore_config_global('cache_time') ?? 600);
        
        Cache::put($cacheIndex, $value, $seconds);
    }
}
