<?php
namespace Vncore\Core\Admin\Controllers;

use Illuminate\Support\Facades\File;
use Vncore\Core\Admin\Models\AdminStore;

trait  ExtensionController
{
    public function index()
    {
        $action = request('action');
        $key = request('key');
        if ($action == 'config' && $key != '') {
            $namespace = vncore_get_class_extension_config(type:$this->type, key:$key);
            $body = (new $namespace)->clickApp();
        } else {
            $body = $this->render();
        }
        return $body;
    }

    public function render()
    {
        $extensionProtected = config('vncore-config.extension.extension_protected')[$this->groupType] ?? [];
        $extensionsInstalled = vncore_get_extension_installed(type:$this->type, active: false);
        $extensions = vncore_get_all_extension_local(type: $this->type);

        switch ($this->type) {
            case 'Template':
                $urlAction = [
                    'install' => vncore_route_admin('admin_template.install'),
                    'uninstall' => vncore_route_admin('admin_template.uninstall'),
                    'enable' => vncore_route_admin('admin_template.enable'),
                    'disable' => vncore_route_admin('admin_template.disable'),
                    'urlOnline' => vncore_route_admin('admin_template_online.index'),
                    'urlImport' => vncore_route_admin('admin_template.import'),
                ];
                break;
            
            default:
                $urlAction = [
                    'install' => vncore_route_admin('admin_plugin.install'),
                    'uninstall' => vncore_route_admin('admin_plugin.uninstall'),
                    'enable' => vncore_route_admin('admin_plugin.enable'),
                    'disable' => vncore_route_admin('admin_plugin.disable'),
                    'urlOnline' => vncore_route_admin('admin_plugin_online.index'),
                    'urlImport' => vncore_route_admin('admin_plugin.import'),
                ];
                break;
        }
        return view($this->vncore_templatePathAdmin.'screen.extension')->with(
            [
                "title"               => vncore_language_render('admin.extension.management', ['extension' => $this->type]),
                "groupType"           => $this->groupType,
                "configExtension"     => config('vncore-config.admin.api_'.strtolower($this->type)),
                "extensionsInstalled" => $extensionsInstalled,
                "extensions"          => $extensions,
                "extensionProtected"  => $extensionProtected,
                "urlAction"           => $urlAction,
            ]
        );
    }

    /**
     * Install extension
     */
    public function install()
    {
        $key = request('key');
        $namespace = vncore_get_class_extension_config(type:$this->type, key:$key);
        $response = (new $namespace)->install();
        if (is_array($response) && $response['error'] == 0) {
            vncore_notice_add(type:$this->type, typeId: $key, content:'admin_notice.vncore_'.strtolower($this->type).'_install');
            vncore_extension_update();
        }
        return response()->json($response);
    }

    /**
     * Uninstall plugin
     *
     * @return  [type]  [return description]
     */
    public function uninstall()
    {
        $key = request('key');
        
        if ($this->type == 'Template') {
            $checkTemplateUse = (new AdminStore)->where('template', $key)->count();
            if ($checkTemplateUse) {
                return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.extension.error_template_use')]);
            }
        }

        $onlyRemoveData = request('onlyRemoveData');
        $namespace = vncore_get_class_extension_config(type:$this->type, key:$key);
        $response = (new $namespace)->uninstall();
        $appPath = 'Vncore/'.$this->groupType.'/'.$key;
        if (!$onlyRemoveData) {
            File::deleteDirectory(app_path($appPath));
            File::deleteDirectory(public_path($appPath));
        }
        if (is_array($response) && $response['error'] == 0) {
            vncore_notice_add(type:$this->type, typeId: $key, content:'admin_notice.vncore_'.strtolower($this->type).'_uninstall');
            vncore_extension_update();
        }
        return response()->json($response);
    }

    /**
     * Enable plugin
     *
     * @return  [type]  [return description]
     */
    public function enable()
    {
        $key = request('key');
        $namespace = vncore_get_class_extension_config(type:$this->type, key:$key);
        $response = (new $namespace)->enable();
        if (is_array($response) && $response['error'] == 0) {
            vncore_notice_add(type:$this->type, typeId: $key, content:'admin_notice.vncore_'.strtolower($this->type).'_enable');
            vncore_extension_update();
        }
        return response()->json($response);
    }

    /**
     * Disable plugin
     *
     * @return  [type]  [return description]
     */
    public function disable()
    {
        $key = request('key');

        if ($this->type == 'Template') {
            $checkTemplateUse = (new AdminStore)->where('template', $key)->count();
            if ($checkTemplateUse) {
                return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.extension.error_template_use')]);
            }
        }

        $namespace = vncore_get_class_extension_config(type:$this->type, key:$key);
        $response = (new $namespace)->disable();
        if (is_array($response) && $response['error'] == 0) {
            vncore_notice_add(type: $this->type, typeId: $key, content:'admin_notice.vncore_'.strtolower($this->type).'_disable');
            vncore_extension_update();
        }
        return response()->json($response);
    }

    /**
     * Import plugin
     */
    public function importExtension()
    {
        $data =  [
            'title' => vncore_language_render('admin.extension.import'),
            'urlAction' => vncore_route_admin('admin_'.strtolower($this->type).'.process_import')
        ];
        return view($this->vncore_templatePathAdmin.'screen.extension_upload')
        ->with($data);
    }

    /**
     * Process import
     *
     * @return  [type]  [return description]
     */
    public function processImport()
    {
        $data = request()->all();
        $validator = \Validator::make(
            $data,
            [
                'file'   => 'required|mimetypes:application/zip|max:'.min($maxSizeConfig = vncore_getMaximumFileUploadSize($unit = 'K'), 51200),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $pathTmp = time();
        $linkRedirect = '';
        $pathFile = vncore_file_upload($data['file'], $disk = 'tmp', $pathFolder = $pathTmp)['pathFile'] ?? '';

        if (!is_writable(storage_path('tmp'))) {
            return response()->json(['error' => 1, 'msg' => 'No write permission '.storage_path('tmp')]);
        }
        
        if ($pathFile) {
            $unzip = vncore_unzip(storage_path('tmp/'.$pathFile), storage_path('tmp/'.$pathTmp));
            if ($unzip) {
                $checkConfig = glob(storage_path('tmp/'.$pathTmp) . '/*/vncore.json');
                if ($checkConfig) {
                    $folderName = explode('/vncore.json', $checkConfig[0]);
                    $folderName = explode('/', $folderName[0]);
                    $folderName = end($folderName);
                    
                    //Check compatibility 
                    $config = json_decode(file_get_contents($checkConfig[0]), true);
                    $vncoreVersion = $config['vncoreVersion'] ?? '';
                    if (!vncore_extension_check_compatibility($vncoreVersion)) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', vncore_language_render('admin.extension.not_compatible', ['version' => $vncoreVersion, 'vncore_version' => config('vncore.core')]));
                    }

                    $configGroup = $config['configGroup'] ?? '';
                    $configKey = $config['configKey'] ?? '';

                    //Process if extention config incorect
                    if (!$configGroup || !$configKey) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', vncore_language_render('admin.extension.error_config_format'));
                    }
                    //Check extension exist
                    $arrPluginLocal = vncore_get_all_extension_local(type: $this->type);
                    if (array_key_exists($configKey, $arrPluginLocal)) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', vncore_language_render('admin.extension.error_exist'));
                    }

                    $appPath = 'Vncore/'.$configGroup.'/'.$configKey;

                    if (!is_writable($checkPubPath = public_path('Vncore/'.$configGroup))) {
                        return response()->json(['error' => 1, 'msg' => 'No write permission '.$checkPubPath]);
                    }
            
                    if (!is_writable($checkAppPath = app_path('Vncore/'.$configGroup))) {
                        return response()->json(['error' => 1, 'msg' => 'No write permission '.$checkAppPath]);
                    }

                    try {
                        File::copyDirectory(storage_path('tmp/'.$pathTmp.'/'.$folderName.'/public'), public_path($appPath));
                        File::copyDirectory(storage_path('tmp/'.$pathTmp.'/'.$folderName), app_path($appPath));
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        $namespace = vncore_get_class_extension_config(type:$this->type, key:$configKey);
                        $response = (new $namespace)->install();
                        if (!is_array($response) || $response['error'] == 1) {
                            return redirect()->back()->with('error', $response['msg']);
                        }
                        $linkRedirect = route('admin_plugin');
                    } catch (\Throwable $e) {
                        File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                        return redirect()->back()->with('error', $e->getMessage());
                    }
                } else {
                    File::deleteDirectory(storage_path('tmp/'.$pathTmp));
                    return redirect()->back()->with('error', vncore_language_render('admin.extension.error_check_config'));
                }
            } else {
                return redirect()->back()->with('error', vncore_language_render('admin.extension.error_unzip'));
            }
        } else {
            return redirect()->back()->with('error', vncore_language_render('admin.extension.error_upload'));
        }

        vncore_notice_add(type:$this->type, typeId: $configKey, content:'admin_notice.vncore_'.strtolower($this->type).'_import');
        vncore_extension_update();

        if ($linkRedirect) {
            return redirect($linkRedirect)->with('success', vncore_language_render('admin.extension.import_success'));
        } else {
            return redirect()->back()->with('success', vncore_language_render('admin.extension.import_success'));
        }
    }
}
