<?php
if (file_exists(app_path('Vncore/Core/Admin/Controllers/Auth/PermissionController.php'))) {
    $nameSpaceAdminPermission = 'App\Vncore\Core\Admin\Controllers';
} else {
    $nameSpaceAdminPermission = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'permission'], function () use ($nameSpaceAdminPermission) {
    Route::get('/', $nameSpaceAdminPermission.'\Auth\PermissionController@index')->name('admin_permission.index');
    Route::get('create', $nameSpaceAdminPermission.'\Auth\PermissionController@create')->name('admin_permission.create');
    Route::post('/create', $nameSpaceAdminPermission.'\Auth\PermissionController@postCreate')->name('admin_permission.post_create');
    Route::get('/edit/{id}', $nameSpaceAdminPermission.'\Auth\PermissionController@edit')->name('admin_permission.edit');
    Route::post('/edit/{id}', $nameSpaceAdminPermission.'\Auth\PermissionController@postEdit')->name('admin_permission.post_edit');
    Route::post('/delete', $nameSpaceAdminPermission.'\Auth\PermissionController@deleteList')->name('admin_permission.delete');
});