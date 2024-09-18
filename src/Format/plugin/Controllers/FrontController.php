<?php
#App\Vncore\Plugins\Extension_Key\Controllers\FrontController.php
namespace App\Vncore\Plugins\Extension_Key\Controllers;

use App\Vncore\Plugins\Extension_Key\AppConfig;
use Vncore\Front\Controllers\RootFrontController;

class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {
        return view($this->plugin->appPath.'::Front',
            [
                //
            ]
        );
    }
}
