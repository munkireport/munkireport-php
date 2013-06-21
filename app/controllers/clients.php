<?php
class clients extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login/clients');
		}
	} 

    function index() {
        
        $data['page'] = 'clients';

        $obj = new View();
        $obj->view('client/client_list', $data);
    
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Detail page of a machine
	 *
	 * @param string serial
	 * @return void
	 * @author abn290
	 **/
	function detail($sn='')
	{
		$format = $ext = pathinfo($sn, PATHINFO_EXTENSION);
		$sn = basename($sn, "." . $format);

		$hash     = new Hash($sn, "Machine");
		$report   = new Reportdata($sn);
		$machine  = new Machine($sn);
		$warranty = new Warranty($sn);
		$installHistory = new InstallHistory($sn);
		$installArray = array();
		$warrantyArray = array();
		$machineArray = array( "formatted_available_disk_space"
			=> humanreadablesize($machine->available_disk_space * 1024)
		);

		foreach($machine->rs as $key => $val)
			$machineArray[$key] = $val;
		foreach($warranty->rs as $key => $val)
			$warrantyArray[$key] = $val;
		foreach($installHistory->itemsBySerialNumber($sn) as $key => $val)
			$installArray[$key] = $val;


		$data = array(
			"meta" => array(
				"iconURL" => "https://km.support.apple.com.edgekey.net"
								. "/kb/securedImage.jsp?configcode="
								. substr($sn, 8)
								. "&size=120x120",
				"hostname" => $machine->computer_name,
				"checkin-date-relative" => RelativeTime(time()-$hash->timestamp) . " ago",
				"checkin-date" => $hash->timestamp,
				"remote_ip" => $report->remote_ip,
				"console_user" => $report->console_user,
				"long_username" => $report->long_username
			),
			"machine" => $machineArray,
			"warranty" => $warrantyArray,
			"installHistory" => $installArray
		);

        $obj = new View();
        if ($format == "json")
	        $obj->view('client/client_detail.json', $data);
	    else
	    	$obj->view("client/client_detail", $data);
	}

	// ------------------------------------------------------------------------

	/**
	 * List of machines
	 *
	 * @param string name of view
	 * @return void
	 * @author abn290
	 **/
	function show($view='')
	{
		$data['page'] = 'clients';
		// TODO: Check if view exists
        $obj = new View();
        $obj->view('client/'.$view, $data);
	}

	/**
	 * Force recheck warranty TODO: maybe move somewhere else?
	 *
	 * @return void
	 * @author 
	 **/
	function recheck_warranty($serial='')
	{
		$warranty = new Warranty($serial);
		$warranty->check_status($force=TRUE);
		redirect("clients/detail/$serial");
	}


}