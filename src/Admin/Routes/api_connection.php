<?php
if (file_exists(app_path('Vncore/Core/Admin/Controllers/AdminApiConnectionController.php'))) {
    $nameSpaceAdminApi = 'App\Vncore\Core\Admin\Controllers';
} else {
    $nameSpaceAdminApi = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'api_connection'], function () use ($nameSpaceAdminApi) {
    Route::get('/', $nameSpaceAdminApi.'\AdminApiConnectionController@index')->name('admin_api_connection.index');
    Route::get('create', function () {
        return redirect()->route('admin_api_connection.index');
    });
    Route::post('/create', $nameSpaceAdminApi.'\AdminApiConnectionController@postCreate')->name('admin_api_connection.create');
    Route::get('/edit/{id}', $nameSpaceAdminApi.'\AdminApiConnectionController@edit')->name('admin_api_connection.edit');
    Route::post('/edit/{id}', $nameSpaceAdminApi.'\AdminApiConnectionController@postEdit');
    Route::post('/delete', $nameSpaceAdminApi.'\AdminApiConnectionController@deleteList')->name('admin_api_connection.delete');
    Route::get('/generate_key', $nameSpaceAdminApi.'\AdminApiConnectionController@generateKey')->name('admin_api_connection.generate_key');
});
