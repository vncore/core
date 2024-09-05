<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminStoreConfigController.php'))) {
    $nameSpaceAdminStoreConfig = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminStoreConfig = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'store_config'], function () use ($nameSpaceAdminStoreConfig) {
    Route::get('/', $nameSpaceAdminStoreConfig.'\AdminStoreConfigController@index')->name('admin_config.index');
    Route::post('/update', $nameSpaceAdminStoreConfig.'\AdminStoreConfigController@update')->name('admin_config.update');
    Route::post('/add_new', $nameSpaceAdminStoreConfig.'\AdminStoreConfigController@addNew')->name('admin_config.add_new');
    Route::post('/delete', $nameSpaceAdminStoreConfig.'\AdminStoreConfigController@delete')->name('admin_config.delete');
});