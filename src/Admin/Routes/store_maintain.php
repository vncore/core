<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminStoreMaintainController.php'))) {
    $nameSpaceAdminStoreMaintain = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminStoreMaintain = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'store_maintain'], function () use ($nameSpaceAdminStoreMaintain) {
    Route::get('/', $nameSpaceAdminStoreMaintain.'\AdminStoreMaintainController@index')->name('admin_store_maintain.index');
    Route::post('/', $nameSpaceAdminStoreMaintain.'\AdminStoreMaintainController@postEdit');
});
