<?php
/**
 * Template format 1.0
 */
#App\Vncore\Templates\Extension_Key\AppConfig.php
namespace App\Vncore\Templates\Extension_Key;

use App\Vncore\Templates\Extension_Key\Models\ExtensionModel;
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\ExtensionConfigDefault;
class AppConfig extends ExtensionConfigDefault
{
    public function __construct()
    {
        //Read config from vncore.json
        $config = file_get_contents(__DIR__.'/vncore.json');
        $config = json_decode($config, true);
    	$this->configGroup = $config['configGroup'];
        $this->configKey = $config['configKey'];
        $this->vncoreVersion = $config['vncoreVersion'];
        //Path
        $this->appPath = $this->configGroup . '/' . $this->configKey;
        //Language
        $this->title = trans($this->appPath.'::lang.title');
        //Image logo or thumb
        $this->image = $this->appPath.'/'.$config['image'];
        //
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install()
    {
        $return = ['error' => 0, 'msg' => ''];
        $check = AdminConfig::where('key', $this->configKey)
            ->where('group', $this->configGroup)
            ->first();
        if ($check) {
            //Check Plugin key exist
            $return = ['error' => 1, 'msg' =>  vncore_language_render('admin.template.template_exist')];
        } else {
            //Insert template to config
            $dataInsert = [
                [
                    'group'  => $this->configGroup,
                    'key'    => $this->configKey,
                    'code'    => $this->configKey,
                    'sort'   => 0,
                    'store_id' => VNCORE_ID_GLOBAL,
                    'value'  => self::ON, //Enable extension
                    'detail' => $this->appPath.'::lang.title',
                ],
            ];
            $process = AdminConfig::insert(
                $dataInsert
            );
            if (!$process) {
                $return = ['error' => 1, 'msg' => vncore_language_render('admin.template.install_faild')];
            } else {
                $return = (new ExtensionModel)->installExtension();
            }
        }

        return $return;
    }

    public function uninstall()
    {
        $return = ['error' => 0, 'msg' => ''];
        //Please delete all values inserted in the installation step
        $process = (new AdminConfig)
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey.'_config')
            ->delete();
        if (!$process) {
            $return = ['error' => 1, 'msg' => vncore_language_render('admin.template.action_error', ['action' => 'Uninstall'])];
        } else {
            (new ExtensionModel)->uninstallExtension();
        }
        return $return;
    }
    
    public function enable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('group', $this->configGroup)
            ->where('key', $this->configKey)
            ->update(['value' => self::ON]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error enable'];
        }
        return $return;
    }

    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('group', $this->configGroup)
            ->where('key', $this->configKey)
            ->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error disable'];
        }
        return $return;
    }

    /**
     * Get info template
     *
     * @return  [type]  [return description]
     */
    public function getInfo()
    {
        $arrData = [
            'title' => $this->title,
            'key' => $this->configKey,
            'image' => $this->image,
            'permission' => self::ALLOW,
            'version' => $this->version,
            'auth' => $this->auth,
            'link' => $this->link,
            'value' => 0, // this return need for template shipping
            'appPath' => $this->appPath
        ];

        return $arrData;
    }

    // Remove setup for store
    // Use when change template
    public function removeStore($storeId)
    {
        // code here
    }

    // Setup for store
    // Use when change template
    public function setupStore($storeId)
    {
       // code here
    }
}
