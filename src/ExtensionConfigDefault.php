<?php
namespace Vncore\Core;

abstract class  ExtensionConfigDefault
{       
    public $configGroup;
    public $configKey;
    public $title;
    public $version;
    public $vncoreVersion;
    public $auth;
    public $link;
    public $image;
    const ON = 1;
    const OFF = 0;
    const ALLOW = 1;
    const DENIED = 0;
    public $appPath;

    /**
     * Install app
     */
    abstract public function install();

    /**
     * Uninstall app
     */
    abstract public function uninstall();

    /**
     * Enable app
     */
    abstract public function enable();

    /**
     * Disable app
     */
    abstract public function disable();

    /**
     * Get info app
     */
    abstract public function getInfo();
        
    /**
     * Process when click button plugin in admin
     */
    public function clickApp()
    {
        return null;
    }

    /**
     * Config app
     */
    public function endApp()
    {
        return null;
    }
}
