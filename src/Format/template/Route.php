<?php
use Illuminate\Support\Facades\Route;

$config = file_get_contents(__DIR__.'/vncore.json');
$config = json_decode($config, true);

if(vncore_extension_check_active($config['configGroup'], $config['configKey'])) {
    // Route define
}