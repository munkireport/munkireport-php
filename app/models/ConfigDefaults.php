<?php

class ConfigDefaults
{
	protected $_settings = array();

	public function __construct()
	{
		$this->_settings['indexPage'] = "index.php";
		$this->_settings['uriProtocol'] = "AUTO";
		$this->_settings['webHost'] = '//'.$_SERVER[ 'HTTP_HOST' ];
		$this->_settings['subdirectory']
			= substr(
					    $_SERVER['PHP_SELF'],
					    0,
					    strpos($_SERVER['PHP_SELF'], basename(FC))  
				    );
		//	= str_replace("index.php", "", $_SERVER['PHP_SELF']);

		$this->_settings['siteName'] = "MunkiReport";
		$this->_settings['vnc_link'] = "vnc://%s:59000";
		$this->_settings['bundleidIgnoreList']
			= array("com.apple.print.PrinterProxy");
		$this->_settings['auth'] = array(
			"auth_config" => array(
				'admin' => '$P$BrBM9FGh3.jOt4nEVRXfMBRuiRyJu01'
			)
		);
		$this->_settings['routes'] = array();
		$this->_settings['routes']['module(/.*)?']	= "module/load$1";
		$this->_settings['paths']
			= array(
				"system"      => APP_ROOT . "system/",
				"application" => APP_ROOT . "app/",
				"view"        => APP_ROOT . "app/views/",
				"controller"  => APP_ROOT . "app/controllers/",
				"module"      => APP_ROOT . "app/modules/"
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
