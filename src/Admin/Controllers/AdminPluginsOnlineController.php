<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Controllers\ExtensionOnlineController;
class AdminPluginsOnlineController extends RootAdminController
{
    use ExtensionOnlineController;

    public $type = 'Plugin';
    public $groupType = 'Plugins';
    public $urlOnline = '';

    public function __construct()
    {
        parent::__construct();
        $this->urlOnline = vncore_route_admin('admin_plugin_online.index');
    }
}
