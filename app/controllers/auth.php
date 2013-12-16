<?php
class auth extends Controller
{
	// Authentication mechanisms we handle
	public $mechanisms = array('noauth', 'config', 'AD');

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

		// If no valid mechanisms found, bail
		if ( ! $this->auth_mechanisms)
		{
			redirect('auth/generate');
		}
		
		$login = isset($_POST['login']) ? $_POST['login'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		// Loop through authentication mechanisms
		// Break when we have a match
		foreach($this->auth_mechanisms as $mechanism => $auth_data)
		{
			// Local is just a username => hash array
			switch ($mechanism)
			{
				case 'noauth': // No authentication
					$check = TRUE;
					$login = 'noauth';
					break 2;

				case 'config': // Config authentication
					if(isset($auth_data[$login]))
					{
						$t_hasher = $this->load_phpass();
						$check = $t_hasher->CheckPassword($password, $auth_data[$login]);
						break 2;
					}
					$error_msg = lang('wrong_user_or_pass');
					break;
					
				case 'AD': // Active Directory authentication
					//prevent empty values
					if ($login != NULL && $password != NULL){
						//include the class and create a connection
						//TODO wrap this include somewhere else?
						include_once (APP_PATH . '/lib/adLDAP/adLDAP.php');
						try {
							$adldap = new adLDAP($auth_data);
						}
						catch (adLDAPException $e) {
							$error_msg = lang('error_contacting_AD');
							//helpful for troubleshooting connections
							//$error_msg = $e;
							break 2;   
						}
						//authenticate user
						if ($adldap->authenticate($login, $password)){
							//check user against users list
							if (in_array(strtolower($login),array_map('strtolower', $auth_data['mr_allowed_users']))) {
								$check = TRUE;
								break 2;
							}
							//check user against group list
                            if(isset($auth_data['mr_allowed_groups']))
                            { 
                                // Set mr_allowed_groups to array
                                $admin_groups = is_array($auth_data['mr_allowed_groups']) ? $auth_data['mr_allowed_groups'] : array($auth_data['mr_allowed_groups']);

                                // Get groups from AD
                                $groups = $adldap->user()->groups($login);

                                foreach ($groups as $group)
                                {
                                    if (in_array($group, $admin_groups)) 
                                    {
                                        $check = TRUE;
                                        break 3;
                                    }
                                }

                            }//end group list check
							$error_msg = lang('not_authorized');
							break;
						}
						$error_msg = lang('wrong_user_or_pass');
						break;
					}
					$error_msg = lang('empty_not_allowed');
					break;
				
				default:
					die( 'Unknown authentication mechanism: '.$mechanism);
					break;
			}
		}

		// If authentication succeeded, create session
		if($check)
		{
			$_SESSION['user'] = $login;
			$_SESSION['auth'] = $mechanism;
			session_regenerate_id();
			redirect($return);
		}
		
		$data = array('login' => $login, 'url' => url("auth/login/$return"));
		
		if($_POST)
		{
			$data['error'] = $error_msg;
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