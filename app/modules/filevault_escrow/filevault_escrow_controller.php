<?php

/**
 * filevault_escrow_controller class
 *
 * @package filevault_escrow
 * @author gmarnin
 **/


class filevault_escrow_controller extends Module_controller
{
	function __construct()
	{
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author wardsparadox
	 **/
	function index()
	{
		echo "You've loaded the filevault_escrow module!";
	}
  /**
  * Trick Crypt's escrow. Responds with 200 when hit.
  * WARNING THIS MAY MAKE KEYS SHOW UP IN YOUR LOGS!
  **/
  public function checkin()
  {
      http_response_code(200);

  }
} // END class filevault_escrow_controller
