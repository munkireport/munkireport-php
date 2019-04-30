<?php

use \Dotenv\Dotenv, \Dotenv\Exception\InvalidPathException, \Dotenv\Exception\InvalidFileException;

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
      $dotenv = Dotenv::create(APP_ROOT, $envfile);
      $dotenv->load();
  } catch (InvalidPathException $e) {
      // .env is missing, but not really an issue since configuration is specified here anyway.
  } catch (InvalidFileException $e) {
      die($e->getMessage());
  }

}

function loadAuthConfig()
{
    $auth_config = [];
    foreach (env('AUTH_METHODS', []) as $auth_method) {
        switch (strtoupper($auth_method)) {
            case 'NOAUTH':
                $auth_config['auth_noauth'] = require APP_ROOT . 'app/config/auth/noauth.php';
                break;
            case 'SAML':
                $auth_config['auth_saml'] = require APP_ROOT . 'app/config/auth/saml.php';
                break;
            case 'LOCAL':
                $auth_config['auth_local'] = require APP_ROOT . 'app/config/auth/local.php';
                break;
            case 'LDAP':
                $auth_config['auth_ldap'] = require APP_ROOT . 'app/config/auth/ldap.php';
                break;
            case 'AD':
                $auth_config['auth_AD'] = require APP_ROOT . 'app/config/auth/ad.php';
                break;
            case 'NETWORK':
                $auth_config['network'] = require APP_ROOT . 'app/config/auth/network.php';
                break;
        }
    }
    configAppendArray($auth_config, 'auth');
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

function local_conf($item)
{
    return rtrim(conf('local'), '/') . '/' . $item;
}

function module_conf($item)
{
  return local_conf('module_configs/' .$item);
}