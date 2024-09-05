<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Controllers\ExtensionController;
use Vncore\Core\Admin\Models\AdminStore;

class AdminTemplateController extends RootAdminController
{
    use ExtensionController;

    public $type = 'Template';
    public $groupType = 'Templates';

    public function __construct()
    {
        parent::__construct();
        
    }
}
