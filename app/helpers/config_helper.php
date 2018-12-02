<?php

use \Dotenv\Dotenv, \Dotenv\Exception\InvalidPathException;

/**
 * Init Dotenv
 *
 * Initialize Dotenv environment variable support
 *
 */
function initDotEnv()
{
  try {
      $envfile = defined('MUNKIREPORT_SETTINGS') ? MUNKIREPORT_SETTINGS : '.env';
      $dotenv = new Dotenv(APP_ROOT, $envfile);
      $dotenv->load();
  } catch (InvalidPathException $e) {
      // .env is missing, but not really an issue since configuration is specified here anyway.
  }

}

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
 * Get config item
 * @param string config item
 * @param string default value (optional)
 * @author AvB
 **/
function conf($cf_item, $default = '')
{
	return array_key_exists($cf_item, $GLOBALS['conf']) ? $GLOBALS['conf'][$cf_item] : $default;
}

