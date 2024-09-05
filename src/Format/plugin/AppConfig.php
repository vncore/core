<?php
/**
 * Plugin format 1.0
 */
#App\Vncore\Plugins\Extension_Key\AppConfig.php
namespace App\Vncore\Plugins\Extension_Key;

use App\Vncore\Plugins\Extension_Key\Models\ExtensionModel;
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\Admin\Models\AdminHome;
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
            ->where('group', $this->configGroup)->first();
        if ($check) {
            //Check Plugin key exist
            $return = ['error' => 1, 'msg' =>  vncore_language_render('admin.extension.plugin_exist')];
        } else {
            //Insert plugin to config
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
                $return = ['error' => 1, 'msg' => vncore_language_render('admin.extension.install_faild')];
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
            $return = ['error' => 1, 'msg' => vncore_language_render('admin.extension.action_error', ['action' => 'Uninstall'])];
        } else {
            (new ExtensionModel)->uninstallExtension();
        }
        
        //Admin config home
        AdminHome::where('extension', $this->appPath)->delete();

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

        //Admin config home
        AdminHome::where('extension', $this->appPath)->update(['status' => 0]);

        return $return;
    }

    // Process when click button plugin in admin    
    
    public function clickApp()
    {
        //
    }

    /**
     * Get info plugin
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
            'value' => 0, // this return need for plugin shipping
            'appPath' => $this->appPath
        ];

        return $arrData;
    }
}
