<?php

namespace Vncore\Core\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {

        if (function_exists('vncore_process_private_folder')) {
            return vncore_process_private_folder();
        }
        return;
    }
}
