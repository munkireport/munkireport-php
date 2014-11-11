<?php 

/**
 * Machine module class
 *
 * @package munkireport
 * @author 
 **/
class Machine_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		// Store module path
		$this->module_path = dirname(__FILE__) .'/';
		$this->view_path = $this->module_path . 'views/';
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the hardware module!";
	}

	/**
	 * Return json array with memory configuration breakdown
	 *
	 * @author AvB
	 **/
	function memory()
	{
		$out = array();
		$machine = new Machine_model();
		$sql = "SELECT physical_memory, count(1) as count
			FROM machine 
			GROUP BY physical_memory
			ORDER BY physical_memory DESC";

		// Legacy loop to do sort in php
		$tmp = array();
		foreach ($machine->query($sql) as $obj)
		{
			// Convert string to intval
			$obj->physical_memory = intval($obj->physical_memory);

			// Take care of mixed entries
			if (isset($tmp[$obj->physical_memory]))
			{
				$tmp[$obj->physical_memory] += $obj->count;
			}
			else
			{
				$tmp[$obj->physical_memory] = $obj->count;
			}
		}
		krsort($tmp);

		$cnt = 0;
		foreach ($tmp as $mem => $memcnt)
		{
			$out[] = array('label' => $mem . ' GB', 'data' => array(array(intval($memcnt), $cnt++)));
		}

		$obj = new View();
		$obj->view('json', array('msg' => $out));
	}

	/**
	 * Return json array with hardware configuration breakdown
	 *
	 * @author AvB
	 **/
	function hw()
	{
		$out = array();
		$machine = new Machine_model();
		$sql = 'SELECT machine_name, count(1) as count 
			FROM machine 
			GROUP BY machine_name 
			ORDER BY count DESC';
		$cnt = 0;
		foreach ($machine->query($sql) as $obj)
		{
			$out[] = array('label' => $obj->machine_name, 'data' => array(array($cnt++, intval($obj->count))));
		}

		$obj = new View();
		$obj->view('json', array('msg' => $out));
	}

	/**
	 * Return json array with os breakdown
	 *
	 * @author AvB
	 **/	
	function os()
	{
		$out = array();
		$machine = new Machine_model();
		$sql = "SELECT count(1) as count, os_version 
				FROM machine
				GROUP BY os_version";

		$os_array = array();
		foreach ($machine->query($sql) as $obj)
		{
			$obj->os_version = $obj->os_version ? $obj->os_version : '0.0.0';
			$os_array[$obj->os_version] = $obj->count;
		}

		// Sort OS versions
		uksort($os_array, 'version_compare');

		// Convert to flotr array
		$cnt = 0;
		foreach ($os_array as $os => $count)
		{
			$os = $os == '0.0.0' ? 'Unknown' : $os;
			$out[] = array('label' => $os, 'data' => array(array(intval($count), $cnt++)));
		}

		$obj = new View();
		$obj->view('json', array('msg' => $out));
	}
	
} // END class Machine_controller