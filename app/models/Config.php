<?php
/**
 * A class that sits between the application and the settings that are
 * configurable by an admin.
 *
 * All settings are stored in app/db/settings.plist
 */
require_once(__DIR__ . "/Plist.php");
define("SETTINGS_FILE", dirname(__DIR__) . "/db/settings.plist");

class Config
{
	protected static $_settings;

	protected static function _loadSettings()
	{
		if (self::$_settings == NULL)
		{
			if (!is_file( SETTINGS_FILE ))
			{
				require_once(__DIR__ . "/ConfigDefaults.php");
				$defaults = new ConfigDefaults();
				$defaults->writeDefaultValues();
			}

			self::$_settings = Plist::readFile( SETTINGS_FILE );
		}
	}




	public static function getAllKeys()
	{
		self::_loadSettings();
		return array_keys(self::$_settings);
	}




	public static function get($aKey)
	{
		self::_loadSettings();
		$paths = explode(".", $aKey);
		$data = self::$_settings;
		foreach($paths as $path)
		{
			if (!isset($data[$path]))
				return NULL;
			
			$data = $data[$path];
		}
		return $data;
	}




	public static function set($aKey, $aValue)
	{
		self::$_settings[$aKey] = $aValue;
	}




	public static function flush()
	{
		Plist::writeToXMLFile(self::$_settings, SETTINGS_FILE);
	}
}