<?php

/**
 * Module controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Module_controller
{
	
	// Module, override in child object
	protected $module_path;

	function get_script($name='')
	{
		// Get scriptnames in module scripts dir (just to be safe)
		$scripts = array_diff(scandir($this->module_path . '/scripts/'), array('..', '.'));

		$script_path = $this->module_path . '/scripts/' . basename($name);

		if( ! in_array($name, $scripts) OR ! is_readable($script_path))
		{
			// Signal to curl that the load failed
			header("HTTP/1.0 404 Not Found");
			printf('Script %s is not available', $name);
			return;
		}

		// Dump the file
		header("Content-Type: text/plain");
		echo file_get_contents($script_path);
	}

}