<?php

// Route api admin
Route::group(
    [
        'middleware' => VNCORE_API_MIDDLEWARE,
        'prefix' => VNCORE_API_PREFIX,
    ],
    function () {

        if (file_exists(app_path('Vncore/Core/Api/Controllers/AdminAuthController.php'))) {
            $nameSpaceHome = 'App\Vncore\Core\Api\Controllers';
        } else {
            $nameSpaceHome = 'Vncore\Core\Api\Controllers';
        }
        Route::post('login', $nameSpaceHome.'\AdminAuthController@login');

        Route::group([
            'middleware' => [
                'auth:admin-api', 
                config('vncore-config.api.auth.api_scope_type_admin').':'.config('vncore-config.api.auth.api_scope_admin')
            ]
        ], function () {
            if (file_exists(app_path('Vncore/Core/Api/Controllers/AdminAuthController.php'))) {
                $nameSpaceHome = 'App\Vncore\Core\Api\Controllers';
            } else {
                $nameSpaceHome = 'Vncore\Core\Api\Controllers';
            }
            Route::get('logout', $nameSpaceHome.'\AdminAuthController@logout');


            if (file_exists(app_path('Vncore/Core/Api/Controllers/AdminController.php'))) {
                $nameSpaceHome = 'App\Vncore\Core\Api\Controllers';
            } else {
                $nameSpaceHome = 'Vncore\Core\Api\Controllers';
            }
            Route::get('info', $nameSpaceHome.'\AdminController@getInfo');
        });
    }
);
