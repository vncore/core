<?php
#App\Vncore\Plugins\Extension_Key\Admin\AdminController.php

namespace App\Vncore\Plugins\Extension_Key\Admin;

use Vncore\Core\Admin\Controllers\RootAdminController;
use App\Vncore\Plugins\Extension_Key\AppConfig;

class AdminController extends RootAdminController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }
    public function index()
    {
        return view($this->plugin->appPath.'::Admin',
            [
                
            ]
        );
    }
}
