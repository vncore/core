<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminStoreInfoController.php'))) {
    $nameSpaceAdminStoreInfo = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminStoreInfo = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'store_info'], function () use ($nameSpaceAdminStoreInfo) {
    Route::get('/', $nameSpaceAdminStoreInfo.'\AdminStoreInfoController@index')->name('admin_store.index');
    Route::post('/update_info', $nameSpaceAdminStoreInfo.'\AdminStoreInfoController@updateInfo')->name('admin_store.update');
});