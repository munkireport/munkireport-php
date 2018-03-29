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

// Get a value from $_ENV with fallback to default
// typehint parameter processes env var as the suggested type.
function getenv_default($key, $default = null, $typehint = null) {
    if (getenv($key)) {
        $v = getenv($key);
        switch ($typehint) {
            case 'array':
                return explode(',', $v);
            case 'bool':
                return $v == 'TRUE' || $v == '1';
            case 'int':
                return (int)$v;
            default:
                return $v;
        }
    } else {
        return $default;
    }
}
