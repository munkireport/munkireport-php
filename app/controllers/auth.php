<?php
class auth extends Controller
{
	// Authentication mechanisms we handle
	public $mechanisms = array('noauth', 'config', 'ldap', 'AD');

	// Authentication mechanisms available
	public $auth_mechanisms = array();
	
	function __construct()
	{
		if(conf('auth_secure') && empty($_SERVER['HTTPS']))
		{
			redirect('error/client_error/426'); // Switch protocol
		}

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
		
		if($this->authorized())
		{
			redirect($return);
		}

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

					if($_POST && isset($auth_data[$login]))
					{
						$t_hasher = $this->load_phpass();
						$check = $t_hasher->CheckPassword($password, $auth_data[$login]);
						break 2;
					}

					break;
				
				case 'ldap': // LDAP authentication

					if ($login && $password)
					{
						include_once (APP_PATH . '/lib/authLDAP/authLDAP.php');

						$ldap_auth_obj = new Auth_ldap($auth_data);

						if ($ldap_auth_obj->authenticate($login, $password))
						{
							//alert('Authenticated');
							// Check user against users list
							if(isset($auth_data['mr_allowed_users']))
							{
                                //
                                $admin_users = is_array($auth_data['mr_allowed_users']) ? $auth_data['mr_allowed_users'] : array($auth_data['mr_allowed_users']);

								if (in_array(strtolower($login),array_map('strtolower', $admin_users)))
								{
									$check = TRUE;
									break 2;
								}
							}

							// Check user against group list
                            if(isset($auth_data['mr_allowed_groups']))
                            { 
                                // Set mr_allowed_groups to array
                                $admin_groups = is_array($auth_data['mr_allowed_groups']) ? $auth_data['mr_allowed_groups'] : array($auth_data['mr_allowed_groups']);

                                // Get groups from AD
                                if( $user_data = $ldap_auth_obj->getUserData($login))
                                {
	                                foreach ($user_data['grps'] as $group)
	                                {
	                                    if (in_array($group, $admin_groups)) 
	                                    {
	                                        $check = TRUE;
	                                        break 3;
	                                    }
	                                }
                                }

                            }//end group list check

							// Not in users list or group list
							error('Not authorized', 'auth.not_authorized');

							break;

						}
						
					}
				
				case 'AD': // Active Directory authentication

					// Prevent empty values
					if ($_POST && $login && $password)
					{
						//include the class and create a connection
						//TODO wrap this include somewhere else?
						include_once (APP_PATH . '/lib/adLDAP/adLDAP.php');
						try
						{
							$adldap = new adLDAP($auth_data);
						}
						catch (adLDAPException $e)
						{

							error('An error ocurred while contacting AD', 'error_contacting_AD');

							// When in debug mode, show additional info
							if (conf('debug'))
							{
								error($e->getMessage());
							}

							break 2;   
						}

						// Authenticate user
						if ($adldap->authenticate($login, $password))
						{
							// Check user against userlist
							if(isset($auth_data['mr_allowed_users']))
							{
                                //
                                $admin_users = is_array($auth_data['mr_allowed_users']) ? $auth_data['mr_allowed_users'] : array($auth_data['mr_allowed_users']);

								if (in_array(strtolower($login),array_map('strtolower', $admin_users)))
								{
									$check = TRUE;
									break 2;
								}
							}

							// Check user against group list
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

							// Not in users list or group list
							error('Not authorized', 'auth.not_authorized');

							break;
						}
						break;
					}
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

		// If POST and no other alerts, auth has failed
		if($_POST && ! $GLOBALS['alerts'])
		{
			if( ! $login OR ! $password)
			{
				error('Empty values are not allowed', 'auth.empty_not_allowed');
			}
			else
			{
				error('Wrong username or password', 'auth.wrong_user_or_pass');
			}
		}
		
		$data = array('login' => $login, 'url' => url("auth/login/$return"));
				
		$obj = new View();
		$obj->view('auth/login', $data);
	}
	
	function logout()
	{
		// Initialize session
		$this->authorized();

		// Destroy session;
		session_destroy();
		redirect('');
	}
	
	function generate()
	{
		// Add a reason why generate is called
		$data = array('reason' => empty($this->auth_mechanisms) ? 'noauth' : 'none');

		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$data['login'] = isset($_POST['login']) ? $_POST['login'] : '';

		if( $_POST && (! $data['login'] OR ! $password))
		{
			error('Empty values are not allowed', 'auth.empty_not_allowed');
		}
		
		if ($data['login'] && $password)
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