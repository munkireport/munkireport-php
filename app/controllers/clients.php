<?php
class clients extends Controller
{

    function index() {
        
        $data['page'] = 'clients';

        $obj = new View();
        $obj->view('client_list', $data);
    
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
		$data['page'] = 'clients';
		$data['serial'] = $sn;

        $obj = new View();
        $obj->view('client_detail', $data);
	}

}