<?php

class ConfigDefaults
{
	protected $_settings = array();

	public function __construct()
	{
		$this->_settings['indexPage'] = "";
		$this->_settings['uriProtocol'] = "AUTO";
		$this->_settings['webHost'] = '//'.$_SERVER[ 'HTTP_HOST' ];

		$this->_settings['subdirectory']
			= str_replace("index.php", "", $_SERVER['PHP_SELF']);

		$this->_settings['siteName'] = "MunkiReport";
		$this->_settings['bundleidIgnoreList']
			= array("com.apple.print.PrinterProxy");
		$this->_settings['auth'] = array();
		$this->_settings['routes'] = array();
		$this->_settings['paths']
			= array(
				"system"      => APP_ROOT . "system/",
				"application" => APP_ROOT . "app/",
				"view"        => APP_ROOT . "app/views/",
				"controller"  => APP_ROOT . "app/controllers/"
			);
		$this->_settings['pdo']
			= array(
				"dsn" => 'sqlite:'.APP_ROOT.'app/db/db.sqlite',
				"user" => "",
				"pass" => "",
				"opts" => array()
			);
		$this->_settings['timezone'] = @date_default_timezone_get();
		$this->_settings['debugModeEnabled'] = FALSE;
	}


	public function writeDefaultValues()
	{
		if (is_writable(dirname(SETTINGS_FILE)) == FALSE)
			throw new Exception(SETTINGS_FILE . " is not writable by the webserver.");
		
		require_once(__DIR__ . "/Plist.php");
		Plist::writeToXMLFile( $this->_settings, SETTINGS_FILE );
	}
}