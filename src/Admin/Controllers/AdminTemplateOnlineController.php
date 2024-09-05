<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Controllers\ExtensionOnlineController;
class AdminTemplateOnlineController extends RootAdminController
{
    use ExtensionOnlineController;
    public $type = 'Template';
    public $groupType = 'Templates';
    public $urlOnline = '';

    public function __construct()
    {
        parent::__construct();
        $this->urlOnline = vncore_route_admin('admin_template_online');
    }
}
