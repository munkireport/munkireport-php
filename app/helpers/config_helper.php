<?php

function initConfig()
{
    $GLOBALS['conf'] = [];
}

/**
 * Add config array to global config array
 *
 *
 * @param array $configArray
 */
function configAppendArray($configArray, $namespace = '')
{
	if($namespace){
    $GLOBALS['conf'] += [$namespace => $configArray];
  }
  else{
    $GLOBALS['conf'] += $configArray;
  }
}

/**
 * Add config file to global config array
 *
 *
 * @param array $configPath
 */
function configAppendFile($configPath, $namespace = '')
{
	$config = require $configPath;
	configAppendArray($config, $namespace);
}

/**
 * Get configuration item as if it were from the old app/config path instead of the Laravel standard config/ path.
 *
 * This used to refer to the $GLOBALS['conf'] variable.
 *
 * @param $cf_item
 * @param string $default
 * @return mixed|string
 */
function conf($cf_item, $default = '')
{
    // Dirty hack to track migration of config items from conf() to config() - mosen.
    if (in_array($cf_item, [
        'temperature_unit',
        'hide_inactive_modules',
        'module_search_paths',
        'default_theme',
        'roles',
        'groups',
        'enable_business_units',
        'vnc_link',
        'ssh_link',
        'curl_cmd',
        'mwa2_link',
        'modules',
        'custom_css',
        'custom_js',
        'show_help',
        'help_url',
        'detail_widget_list',
        'client_passphrases',
        'preflight_script',
        'postflight_script',
        'report_broken_client_script',
        'proxy',
        'guzzle_handler',
        'request_timeout',
        'apple_hardware_icon_url',
        'local',

        'system_path',
        'view_path',
        'controller_path',
        'module_path',
        'storage_path',
        'routes',
        'authorization',
        'subdirectory',
        'uri_protocol',
        'index_page',
    ])) {
        return config("munkireport.{$cf_item}", $default);
    }

    if (in_array($cf_item, [
        'dashboard', 'widget'
    ])) {
        return config($cf_item);
    }

    if ($cf_item === "application_path") {
        return app_path();
    }

    if (isset($GLOBALS['conf'])) {
        return array_key_exists($cf_item, $GLOBALS['conf']) ? $GLOBALS['conf'][$cf_item] : $default;
    } else {
        return $default;
    }
}


function local_conf($item)
{
    return rtrim(conf('local'), '/') . '/' . $item;
}

function module_conf($item)
{
  return local_conf('module_configs/' .$item);
}
