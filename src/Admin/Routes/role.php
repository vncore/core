<?php
if (file_exists(app_path('Vncore/Admin/Controllers/Auth/RoleController.php'))) {
    $nameSpaceAdminRole = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminRole = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'role'], function () use ($nameSpaceAdminRole) {
    Route::get('/', $nameSpaceAdminRole.'\Auth\RoleController@index')->name('admin_role.index');
    Route::get('create', $nameSpaceAdminRole.'\Auth\RoleController@create')->name('admin_role.create');
    Route::post('/create', $nameSpaceAdminRole.'\Auth\RoleController@postCreate')->name('admin_role.post_create');
    Route::get('/edit/{id}', $nameSpaceAdminRole.'\Auth\RoleController@edit')->name('admin_role.edit');
    Route::post('/edit/{id}', $nameSpaceAdminRole.'\Auth\RoleController@postEdit')->name('admin_role.post_edit');
    Route::post('/delete', $nameSpaceAdminRole.'\Auth\RoleController@deleteList')->name('admin_role.delete');
});