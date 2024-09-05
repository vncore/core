<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminCacheConfigController.php'))) {
    $nameSpaceAdminCacheConfig = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminCacheConfig = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'cache_config'], function () use ($nameSpaceAdminCacheConfig) {
    Route::get('/', $nameSpaceAdminCacheConfig.'\AdminCacheConfigController@index')->name('admin_cache_config.index');
    Route::post('/clear_cache', $nameSpaceAdminCacheConfig.'\AdminCacheConfigController@clearCache')->name('admin_cache_config.clear_cache');
});