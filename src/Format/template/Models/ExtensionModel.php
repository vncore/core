<?php
#App\Vncore\Templates\Extension_Key\Models\ExtensionModel.php
namespace App\Vncore\Templates\Extension_Key\Models;

class ExtensionModel
{
    public function uninstallExtension()
    {
        return ['error' => 0, 'msg' => 'uninstall success'];
    }

    public function installExtension()
    {
        return ['error' => 0, 'msg' => 'install success'];
    }
    
}
