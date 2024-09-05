<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminNoticeController.php'))) {
    $nameSpaceAdmin = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdmin = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'notice'], function () use ($nameSpaceAdmin) {
    Route::get('/', $nameSpaceAdmin.'\AdminNoticeController@index')->name('admin_notice.index');
    Route::get('mark_read', $nameSpaceAdmin.'\AdminNoticeController@markRead')->name('admin_notice.mark_read');
    Route::get('url/{type}/{typeId}', $nameSpaceAdmin.'\AdminNoticeController@url')->name('admin_notice.url');
    Route::post('/delete', $nameSpaceAdmin.'\AdminNoticeController@deleteList')->name('admin_notice.delete');
});