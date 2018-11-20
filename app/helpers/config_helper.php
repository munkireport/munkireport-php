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
