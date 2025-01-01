<?php

namespace Vncore\Core;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

use Vncore\Core\Commands\Customize;
use Vncore\Core\Commands\Make;
use Vncore\Core\Commands\Infomation;
use Vncore\Core\Commands\Update;
use Vncore\Core\Commands\Install;
use Vncore\Core\Admin\Middleware\Localization;
use Vncore\Core\Api\Middleware\ApiConnection;
use Vncore\Core\Api\Middleware\ForceJsonResponse;
use Vncore\Core\Admin\Middleware\Authenticate;
use Vncore\Core\Admin\Middleware\LogOperation;
use Vncore\Core\Admin\Middleware\Session;
use Vncore\Core\Admin\Middleware\PermissionMiddleware;
use Vncore\Core\Admin\Middleware\AdminStoreId;
use Spatie\Pjax\Middleware\FilterIfPjax;

use Vncore\Core\Admin\Models\PersonalAccessToken;
use Vncore\Core\Admin\Models\AdminStore;

class VncoreServiceProvider extends ServiceProvider
{
    protected $listCommand = [
        Make::class,
        Infomation::class,
        Update::class,
        Customize::class,
    ];
    
    protected function initial()
    {
        $this->loadTranslationsFrom(__DIR__.'/Lang', 'vncore');

        //Create directory
        try {
            if (!is_dir($directory = app_path('Vncore/Plugins'))) {
                mkdir($directory, 0755, true);
            }

            if (!is_dir($directory = app_path('Vncore/Helpers'))) {
                mkdir($directory, 0755, true);
            }
            if (!is_dir($directory = app_path('Vncore/Templates'))) {
                mkdir($directory, 0755, true);
            }
            if (!is_dir($directory = app_path('Vncore/Blocks'))) {
                mkdir($directory, 0755, true);
            }
            if (!is_dir($directory = app_path('Vncore/Core'))) {
                mkdir($directory, 0755, true);
            }
        } catch (\Throwable $e) {
            $msg = '#VNCORE:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            echo $msg;
            exit;
        }

        //Load publish
        try {
            $this->registerPublishing();
        } catch (\Throwable $e) {
            $msg = '#VNCORE:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            echo $msg;
            exit;
        }

        //Load command initial
        try {
            $this->commands([
                Install::class,
            ]);
        } catch (\Throwable $e) {
            $msg = '#VNCORE:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            echo $msg;
            exit;
        }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->initial();

        if (VNCORE_ACTIVE == 1 && \Illuminate\Support\Facades\Storage::disk('local')->exists('vncore-installed.txt')) {

            //If env is production, then disable debug mode
            if (config('app.env') === 'production') {
                config(['app.debug' => false]);
            }
            
            Paginator::useBootstrap();
            Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

            //Load helper
            try {
                foreach (glob(__DIR__.'/Library/Helpers/*.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::core_helper_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Check connection
            try {
                DB::connection(VNCORE_DB_CONNECTION)->getPdo();
            } catch (\Throwable $e) {
                $msg = '#VNCORE::Pdo_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Boot process Vncore
            try {
                $this->bootDefault();
            } catch (\Throwable $e) {

                $msg = '#VNCORE::core_default_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile().PHP_EOL;
                if (\Illuminate\Support\Facades\Storage::disk('local')->exists('vncore-installed.txt')) {
                    $msg .= "--> Try delete the file vncore-installed.txt in the ".\Illuminate\Support\Facades\Storage::disk('local')->path('vncore-installed.txt').', then re-install vncore'.PHP_EOL;
                }
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Route Admin
            try {
                if (file_exists($routes = __DIR__.'/Admin/routes.php')) {
                    $this->loadRoutesFrom($routes);
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::core_route_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Route Api
            try {
                if (config('vncore-config.env.VNCORE_API_MODE')) {
                    if (file_exists($routes = __DIR__.'/Api/routes.php')) {
                        $this->loadRoutesFrom($routes);
                    }
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::core_api_route_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            try {
                $this->registerRouteMiddleware();
            } catch (\Throwable $e) {
                $msg = '#VNCORE::core_middeware_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            try {
                $this->commands($this->listCommand);
            } catch (\Throwable $e) {
                $msg = '#VNCORE::core_command_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            try {
                $this->validationExtend();
            } catch (\Throwable $e) {
                $msg = '#VNCORE::core_validate_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            $this->loadViewsFrom(__DIR__.'/Views/admin', config('vncore-config.admin.path_view'));
            $this->loadViewsFrom(app_path().'/Vncore/Blocks', 'VncoreBlock');
    
            //Load Plugin Provider
            try {
                foreach (glob(app_path().'/Vncore/Plugins/*/Provider.php') as $filename) {
                    require_once $filename;
                }
                foreach (glob(app_path().'/Vncore/Plugins/*/Route.php') as $filename) {
                    $this->loadRoutesFrom($filename);
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::plugin_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Load Template Provider
            try {
                foreach (glob(app_path().'/Vncore/Templates/*/Provider.php') as $filename) {
                    require_once $filename;
                }
                foreach (glob(app_path().'/Vncore/Templates/*/Route.php') as $filename) {
                    $this->loadRoutesFrom($filename);
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::template_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Load helper
            try {
                foreach (glob(app_path().'/Vncore/Helpers/*.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::helper_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            //Load block
            try {
                foreach (glob(app_path().'/Vncore/Blocks/*.blade.php') as $filename) {
                    $filename = str_replace('.blade.php', '', basename($filename));
                    vncore_add_module('homepage', 'VncoreBlock::'.$filename);
                }
            } catch (\Throwable $e) {
                $msg = '#VNCORE::block_load:: '.$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                vncore_report($msg);
                echo $msg;
                exit;
            }

            $this->eventRegister();

        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Note the order of precedence.
        //The previous config can be used in the following configg

        $this->mergeConfigFrom(__DIR__.'/Config/vncore.php', 'vncore');
        $this->mergeConfigFrom(__DIR__.'/Config/vncore-config.php', 'vncore-config');
        $this->mergeConfigFrom(__DIR__.'/Config/vncore-module.php', 'vncore-module');

        $this->mergeConfigFrom(__DIR__.'/Config/disks_vncore.php', 'filesystems.disks');
        $this->mergeConfigFrom(__DIR__.'/Config/auth_guards_vncore.php', 'auth.guards');
        $this->mergeConfigFrom(__DIR__.'/Config/auth_passwords_vncore.php', 'auth.passwords');
        $this->mergeConfigFrom(__DIR__.'/Config/auth_providers_vncore.php', 'auth.providers');
        $this->mergeConfigFrom(__DIR__.'/Config/lfm.php', 'lfm');

        if (file_exists(__DIR__.'/Library/Const.php')) {
            require_once(__DIR__.'/Library/Const.php');
        }

    }

    public function bootDefault()
    {
        // Set store id
        // Default is domain root
        $storeId = VNCORE_ID_ROOT;

        //Process for multi store
        if (vncore_store_check_multi_domain_installed()) {
            $domain = vncore_store_process_domain(url('/'));
            $arrDomain = AdminStore::getDomainStore();
            if (in_array($domain, $arrDomain)) {
                $storeId =  array_search($domain, $arrDomain);
            }
        }
        //End process multi store
        
        config(['app.storeId' => $storeId]);
        // end set store Id
        
        if (vncore_config_global('LOG_SLACK_WEBHOOK_URL')) {
            config(['logging.channels.slack.url' => vncore_config_global('LOG_SLACK_WEBHOOK_URL')]);
        }

        //Title app
        config(['app.name' => vncore_store_info('title')]);

        //Config for  email
        if (
            // Default use smtp mode for for supplier if use multi-store
            ($storeId != VNCORE_ID_ROOT && vncore_store_check_multi_domain_installed())
            ||
            // Use smtp config from admin if root domain have smtp_mode enable
            ($storeId == VNCORE_ID_ROOT && vncore_config_global('smtp_mode'))
        ) {
            $smtpHost     = vncore_config('smtp_host');
            $smtpPort     = vncore_config('smtp_port');
            $smtpSecurity = vncore_config('smtp_security');
            $smtpUser     = vncore_config('smtp_user');
            $smtpPassword = vncore_config('smtp_password');
            $smtpName     = vncore_config('smtp_name');
            $smtpFrom     = vncore_config('smtp_from');
            config(['mail.default'                 => 'smtp']);
            config(['mail.mailers.smtp.host'       => $smtpHost]);
            config(['mail.mailers.smtp.port'       => $smtpPort]);
            config(['mail.mailers.smtp.encryption' => $smtpSecurity]);
            config(['mail.mailers.smtp.username'   => $smtpUser]);
            config(['mail.mailers.smtp.password'   => $smtpPassword]);
            config(['mail.from.address'            => ($smtpFrom ?? vncore_store_info('email'))]);
            config(['mail.from.name'               => ($smtpName ?? vncore_store_info('title'))]);
        } else {
            //Set default
            config(['mail.from.address' => (config('mail.from.address')) ? config('mail.from.address') : vncore_store_info('email')]);
            config(['mail.from.name'    => (config('mail.from.name')) ? config('mail.from.name') : vncore_store_info('title')]);
        }
        //email

        //Share variable for view
        view()->share('vncore_languages', vncore_language_all());
        view()->share('vncore_templatePathAdmin', config('vncore-config.admin.path_view').'::');
    }

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'localization'     => Localization::class,
        'api.connection'   => ApiConnection::class,
        'json.response'    => ForceJsonResponse::class,
        //Admin
        'admin.auth'       => Authenticate::class,
        'admin.log'        => LogOperation::class,
        'admin.permission' => PermissionMiddleware::class,
        'admin.storeId'    => AdminStoreId::class,
        'admin.pjax'      => FilterIfPjax::class,
        'admin.session'    => Session::class,
        //Sanctum
        'abilities'        => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability'          => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected function middlewareGroups()
    {
        return [
            'admin'        => config('vncore-config.admin.middleware'),
            'api.extend'   => config('vncore-config.api.middleware'),
        ];
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups() as $key => $middleware) {
            app('router')->middlewareGroup($key, array_values($middleware));
        }
    }

    /**
     * Validattion extend
     *
     * @return  [type]  [return description]
     */
    protected function validationExtend()
    {
        //
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/public/Vncore' => public_path('Vncore')], 'vncore:public-static');
            $this->publishes([__DIR__.'/public/vendor' => public_path('vendor')], 'vncore:public-vendor');
            $this->publishes([__DIR__.'/Views/admin' => resource_path('views/vendor/'.config('vncore-config.admin.path_view'))], 'vncore:view-admin');
            $this->publishes([__DIR__.'/Config/vncore-config.php' => config_path('vncore-config.php')], 'vncore:config');
            $this->publishes([__DIR__.'/Config/lfm.php' => config_path('lfm.php')], 'vncore:config-lfm');
            $this->publishes([__DIR__.'/Config/vncore_functions_except.stub' => config_path('vncore_functions_except.php')], 'vncore:functions-except');
        }
    }

    //Event register
    protected function eventRegister()
    {
        //
    }
}
