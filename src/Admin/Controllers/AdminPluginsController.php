<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Controllers\ExtensionController;

class AdminPluginsController extends RootAdminController
{
    use ExtensionController;

    public $type = 'Plugin';
    public $groupType = 'Plugins';

    public function __construct()
    {
        parent::__construct();
    }
    
}
