<?php
use Illuminate\Support\Facades\Route;

$config = file_get_contents(__DIR__.'/vncore.json');
$config = json_decode($config, true);

if(vncore_extension_check_active($config['configGroup'], $config['configKey'])) {
    Route::group(
        [
            'prefix'    => 'plugin/ExtensionUrlKey',
            'namespace' => 'App\Vncore\Plugins\Extension_Key\Controllers',
        ],
        function () {
            Route::get('index', 'FrontController@index')
            ->name('ExtensionUrlKey.index');
        }
    );

    Route::group(
        [
            'prefix' => VNCORE_ADMIN_PREFIX.'/ExtensionUrlKey',
            'middleware' => VNCORE_ADMIN_MIDDLEWARE,
            'namespace' => 'Vncore\Plugins\Extension_Key\Admin',
        ], 
        function () {
            Route::get('/', 'AdminController@index')
            ->name('admin_ExtensionUrlKey.index');
        }
    );
}