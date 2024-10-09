<?php
use Illuminate\Support\Facades\Route;

$config = file_get_contents(__DIR__.'/vncore.json');
$config = json_decode($config, true);

if(vncore_extension_check_active($config['configGroup'], $config['configKey'])) {


    //$stub_front

    Route::group(
        [
            'prefix' => VNCORE_ADMIN_PREFIX.'/ExtensionUrlKey',
            'middleware' => VNCORE_ADMIN_MIDDLEWARE,
            'namespace' => '\App\Vncore\Plugins\Extension_Key\Admin',
        ], 
        function () {
            Route::get('/', 'AdminController@index')
            ->name('admin_ExtensionUrlKey.index');
        }
    );
}