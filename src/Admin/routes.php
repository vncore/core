<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => VNCORE_ADMIN_PREFIX,
        'middleware' => VNCORE_ADMIN_MIDDLEWARE,
    ],
    function () {
        foreach (glob(__DIR__ . '/Routes/*.php') as $filename) {
            $this->loadRoutesFrom($filename);
        }
        if (file_exists(app_path('Vncore/Admin/Controllers/HomeController.php'))) {
            $nameSpaceHome = 'App\Vncore\Admin\Controllers';
        } else {
            $nameSpaceHome = 'Vncore\Core\Admin\Controllers';
        }
        Route::get('/', $nameSpaceHome.'\HomeController@index')->name('admin.home');
        Route::get('/default', $nameSpaceHome.'\HomeController@default')->name('admin.default');
        Route::get('/deny', $nameSpaceHome.'\HomeController@deny')->name('admin.deny');
        Route::get('/data_not_found', $nameSpaceHome.'\HomeController@dataNotFound')->name('admin.data_not_found');
        Route::get('/deny_single', $nameSpaceHome.'\HomeController@denySingle')->name('admin.deny_single');

        //Language
        Route::get('locale/{code}', function ($code) {
            session(['locale' => $code]);
            return back();
        })->name('admin.locale');
    }
);