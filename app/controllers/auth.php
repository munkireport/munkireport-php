<?php
class auth extends Controller
{
	// Authentication mechanisms we handle
	public $mechanisms = array('config');

	// Authentication mechanisms available
	public $auth_mechanisms = array();
	
	function __construct()
	{
		// Check if there's a valid auth mechanism in config
		$auth_mechanisms = array();
		$authSettings = conf('auth');
		foreach($this->mechanisms as $mech)
		{
			if (isset($authSettings["auth_$mech"]) && is_array($authSettings["auth_$mech"]))
			{
				$this->auth_mechanisms[$mech] = $authSettings["auth_$mech"];
			}
		}
	} 
	
	
	//===============================================================
	
	function index()
	{
		redirect('auth/login');
	}
	
	function login($return = '')
	{
		$check = FALSE;
		
		$login = isset($_POST['login']) ? $_POST['login'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		
		$data = array('login' => $login, 'url' => url("auth/login/$return"));
		
		
		
		// No valid mechanisms found, bail
		if ( ! $this->auth_mechanisms)
		{
			redirect('auth/generate');
			//die('Error: No authentication mechanism set in config file');
			// TODO: make this nicer
			// EDIT (joe.wollard) - is redirecting nicer?
		}
		
		if ($login && $password)
		{
			
			// Get hasher object
			$t_hasher = $this->load_phpass();
			
			foreach($this->auth_mechanisms as $mechanism => $auth_data)
			{
				// Local is just a username => hash array
				if($mechanism == 'config')
				{
					if(isset($auth_data[$login]))
					{
						$check = $t_hasher->CheckPassword($password, $auth_data[$login]);
						break;
					}
				}
			}
			
			if($check)
			{
				$_SESSION['user'] = $login;
				$_SESSION['auth'] = $mechanism;
				redirect($return);
			}
			
		}
		
		if($_POST)
		{
			$data['error'] = "Your username and password didn't match. Please try again";
		}
				
		$obj = new View();
		$obj->view('auth/login', $data);
	}
	
	function logout()
	{
		session_destroy();
		redirect('');
	}
	
	function generate()
	{
		// Add a reason why generate is called
		$data = array('reason' => empty($this->auth_mechanisms) ? 'noauth' : 'none');

		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$data['login'] = isset($_POST['login']) ? $_POST['login'] : '';
		
		if ($password)
		{
			$t_hasher = $this->load_phpass();
			$data['generated_pwd'] = $t_hasher->HashPassword($password);
		}
		
		$obj = new View();
		$obj->view('auth/generate_password', $data);
	}
	
	function load_phpass()
	{
		require(APP_PATH . '/lib/phpass-0.3/PasswordHash.php');
		return new PasswordHash(8, TRUE);
	}
	
}