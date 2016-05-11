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
		// Get engine
		// Get permissions
		// Do a write
		// Do a read
		// Get tables
		// Get size
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