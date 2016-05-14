<?php
class system extends Controller
{
	function __construct()
	{
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		if( ! $this->authorized('global'))
		{
			die('You need to be admin');
		}
	}
	
	//===============================================================
	
	/**
	 * DataBase
	 *
	 * Get Database info and status
	 *
	 */
	public function DataBaseInfo()
	{
		$out = array(
			'db.driver' => '',
			'db.connectable' => false,
			'db.writable' => false,
			'error' => '',
		);
		$config = array(
			'pdo_dsn' => conf('pdo_dsn'),
			'pdo_user' => conf('pdo_user'),
			'pdo_pass' => conf('pdo_pass'),
			'pdo_opts' => conf('pdo_opts'),
		);
		
		include_once (APP_PATH . '/lib/munkireport/Database.php');
		$db = new munkireport\Database($config);
		//echo '<pre>'; var_dump($db);
		if($db->connect()){
			$out['db.connectable'] = true;
			$out['db.driver'] = $db->get_driver();

			if($db->isWritable())
			{
				$out['db.writable'] = true;
			}
			else {
				$out['error'] = $db->getError();
			}
		}
		else{
			$out['error'] = $db->getError();
		}
		//echo '<pre>'; var_dump($db);
		// Get engine
		// Get permissions
		// Do a write
		// Do a read
		// Get tables
		// Get size
		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}
	
	//===============================================================
	
	/**
	 * Authentication and Authorization
	 *
	 * Get Authentication and Authorization data
	 *
	 */
	public function AuthenticationAndAuthorization()
	{
		# code...
	}
	//===============================================================
	//===============================================================
	//===============================================================
	//===============================================================
    
    //===============================================================

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    function show($which = '')
    {
        if($which)
        {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $view = 'system/'.$which;
        }
        else
        {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $data);
    }


}