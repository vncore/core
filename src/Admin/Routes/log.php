<?php
if (file_exists(app_path('Vncore/Core/Admin/Controllers/AdminLogController.php'))) {
    $nameSpaceAdminLog = 'App\Vncore\Core\Admin\Controllers';
} else {
    $nameSpaceAdminLog = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'log'], function () use ($nameSpaceAdminLog) {
    Route::get('/', $nameSpaceAdminLog.'\AdminLogController@index')->name('admin_log.index');
    Route::post('/delete', $nameSpaceAdminLog.'\AdminLogController@deleteList')->name('admin_log.delete');
});